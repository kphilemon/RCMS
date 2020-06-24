<?php
include '../src/utilities/email.php';


if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    // 405 - Method not allowed: Only POST is allowed
    http_response_code(405);
    exit();
}

//----------------------------------------------------------------------------------------------//

$validation = validate_request();
if (!empty($validation)) {
    // 400 - Bad request: error in the parameters
    http_response_code(400);
    echo json_encode(['error' => $validation]);
    exit();
}

$siswamail = trim($_POST['email']);

//connect to database
include '../src/models/UserModel.php';
$db = new Database(DATABASE_NAME, DATABASE_USERNAME, DATABASE_PASSWORD);
try {
    $forgetPass = new UserModel($db->getConnection());
    $data = $forgetPass->getByEmail($siswamail);

} catch (PDOException $exception) {
    //server error
    http_response_code(500);
    echo json_encode(['error' => 'There\'s some issue with the server. Please try again.']);
    exit();
}

//check if the student has registered
if (empty($data)) {
    http_response_code(412);
    echo json_encode(['error' => 'Your email is not registered.']);
    exit();
}

//send hash and set activated to 0
//generate hash and store it in the student table
$hash = md5(rand(0, 1000));
try {
    $success = $forgetPass->updateHash($siswamail, $hash);
} catch (PDOException $exception) {
    //server error
    http_response_code(500);
    echo json_encode(['error' => 'There\'s some issue with the server. Please try again.']);
    exit();
}

if (!$success) {
    //server err: insert err
    http_response_code(500);
    echo json_encode(['error' => 'There\'s some issue with the server. Please try again.']);
    exit();
}

$success = sendForgetPasswordEmail($siswamail, getHostAddress() . '/activate/' . $hash);
if (!$success){
    http_response_code(500);
    echo json_encode(['error' => 'There\'s some issue with the server. Please try again.']);
    exit();
}


http_response_code(200);
$db->closeConnection();


//----------------------------------------------------------------------------------------------//

function validate_request()
{
    $validation = [];
    $siswamail_pattern = "/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@siswa.um.edu.my$/";

    if (empty($_POST['email'] || strlen(trim($_POST['email'])) === 0)) {
        $validation['fp-email'] = 'Please enter your siswamail.';
    } elseif (!preg_match($siswamail_pattern, $_POST['email'])) {
        $validation['fp-email'] = 'The email you have entered is invalid. Please enter your siswamail again.';
    }

    return $validation;
}
