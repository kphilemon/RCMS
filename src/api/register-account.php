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

//connect database
include '../src/models/UserModel.php';
$db = new Database(DATABASE_NAME, DATABASE_USERNAME, DATABASE_PASSWORD);
try {
    $signup = new UserModel($db->getConnection());
    $data = $signup->getByEmail($siswamail);
} catch (PDOException $exception) {
    //server error
    http_response_code(500);
    echo json_encode(['error' => 'There\'s some issue with the server. Please try again.']);
    exit();
}

//check if data exists in our own db
if (!empty($data)) {
    //Yes, means registered with us, but is he/she activated
    if ($data['activated'] == 0) {
        //not yet activate, ask user check email
        http_response_code(412);
        echo json_encode(['error' => 'Your siswamail has been registered. Please find the activation link in your siswamail.']);
    } else {
        //activated, ask user sign in instead
        http_response_code(412);
        echo json_encode(['error' => 'Your siswamail has been registered and activated. Please sign in.']);
    }
    exit();
}

//siswamail was not registered before
//Let start register process
//getting all value from um database
try {
    $UMStudentData = $signup->getByEmailReg($siswamail);
} catch (PDOException $exception) {
    http_response_code(500);
    echo json_encode(['error' => 'There\'s some issue with the server. Please try again.']);
    exit();
}

//check if he/she exists in UM database
if (empty($UMStudentData)) {
    //No, not a student
    http_response_code(412);
    echo json_encode(['error' => 'We couldn\'t find your student record. Please make sure your siswamail is correct.']);
    exit();
}


//Yes, I am a student
//I want to register
//generate hash and store it in the student table
$hash = md5(rand(0, 1000));
try {
    $success = $signup->insertByEmailReg($UMStudentData['email'], $UMStudentData['name'], $UMStudentData['matrix_no'], $UMStudentData['college_id'], $UMStudentData['faculty'], $UMStudentData['course'], $hash);
} catch (PDOException $exception) {
    http_response_code(500);
    echo json_encode(['error' => 'There\'s some issue with the server. Please try again.']);
    exit();
}

//any insert error
if (!$success) {
    //server err: insert err
    http_response_code(500);
    echo json_encode(['error' => 'There\'s some issue with the server. Please try again.']);
    exit();
}

//register successful then send email
$success = sendAccountActivationEmail($siswamail, getHostAddress() . '/activate/' . $hash);
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
        $validation['su-email'] = 'Please enter your siswamail.';
    } elseif (!preg_match($siswamail_pattern, $_POST['email'])) {
        $validation['su-email'] = 'The email you have entered is invalid. Please enter your siswamail again.';
    }

    return $validation;
}
