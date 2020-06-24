<?php

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
$password = trim($_POST['password']);

//connect database
include '../src/models/UserModel.php';
$db = new Database(DATABASE_NAME, DATABASE_USERNAME, DATABASE_PASSWORD);
try {
    $login = new UserModel($db->getConnection());
    $data = $login->getByEmail($siswamail);
} catch (PDOException $exception) {
    //server error
    http_response_code(500);
    echo json_encode(['error' => 'There\'s some issue with the server. Please try again.']);
    exit();
}
$db->closeConnection();


//check if user records exists
if (empty($data)) {
    // 412 - Precondition failed: user not registered
    http_response_code(412);
    echo json_encode(['error' => 'Your email is not registered. Please register first.']);
    exit();
}

//check if the account has been activated
if ($data['activated'] == 0) {
    //No , please activate it.
    http_response_code(412);
    echo json_encode(['error' => 'Please activate your account before login.']);
    exit();
}

//Yes, it is activated, let me check your password
if (md5($password) !== $data['password']) {
    //No, incorrect password
    http_response_code(412);
    echo json_encode(['error' => 'Password incorrect. Please try again.']);
    exit();
}

//Yes, match perfectly, let me start the session
session_start();
$_SESSION['user_id'] = $data['id'];
$_SESSION['username'] = $data['name'];
$_SESSION['user_email'] = $data['email'];
$_SESSION['user_college_id'] = $data['college_id'];

//check whether the user has completed the profile
//by checking if the data in the database got any null value
if (empty($data['room_no']) || empty($data['nationality']) || empty($data['phone']) || empty($data['gender']) || empty($data['date_of_birth']) || empty($data['address']) || empty($data['city']) || empty($data['state']) || empty($data['zip'])) {
    //profile not complete
    $_SESSION['user_profile_complete'] = false;
} else {
    //Yes, good to go
    $_SESSION['user_profile_complete'] = true;
}
http_response_code(200);

//----------------------------------------------------------------------------------------------//

function validate_request()
{
    $validation = [];
    $siswamail_pattern = "/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@siswa.um.edu.my$/";

    if (empty($_POST['email'] || strlen(trim($_POST['email'])) === 0)) {
        $validation['si-email'] = 'Please enter your siswamail.';
    } elseif (!preg_match($siswamail_pattern, $_POST['email'])) {
        $validation['si-email'] = 'The email you have entered is invalid. Please enter your siswamail again.';
    }

    if (empty($_POST['password'] || strlen(trim($_POST['password'])) === 0)) {
        $validation['si-password'] = 'Please enter your password.';
    }

    return $validation;
}