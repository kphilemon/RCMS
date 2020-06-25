<?php

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    // 405 - Method not allowed: Only POST is allowed
    http_response_code(405);
    exit();
}

if (!isset($_SESSION['user_id'])) {
    // 401 - Unauthorised: client not logged in
    http_response_code(401);
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

$uploaded_name = '';
if (isset($_FILES['img']) && is_uploaded_file($_FILES['img']['tmp_name'])) {
    $uploaded_name = process_file_upload();
    if ($uploaded_name === '') {
        // 500 - Server error: failed to upload docs
        http_response_code(500);
        exit();
    }
}

$type = $_POST['type'];
$location = $_POST['location'];
$details = trim($_POST['details']);
$inserted_id = '';

include '../src/models/IssueModel.php';
$db = new Database(DATABASE_NAME, DATABASE_USERNAME, DATABASE_PASSWORD);
try {
    $model = new IssueModel($db->getConnection());
    $inserted_id = $model->insert($_SESSION['user_id'], $type, $location, $details, ($uploaded_name === '') ? null : $uploaded_name);

} catch (PDOException $exception) {
    // 500 - Server error: failed to connect to database
    http_response_code(500);
    exit();
}

$db->closeConnection();

if ($inserted_id === '') {
    // 500 - Server error: failed to insert into database
    http_response_code(500);
    exit();
}

$_SESSION['added-issue'] = 1;
// 200 - Success
http_response_code(200);
echo json_encode(['id' => $inserted_id]);

//----------------------------------------------------------------------------------------------//


function validate_request()
{
    $validation = [];

    if (empty($_POST['type']) || strlen(trim($_POST['type'])) === 0) {
        $validation['type'] = 'Please select a problem type.';
    }

    if (empty($_POST['location']) || strlen(trim($_POST['location'])) === 0) {
        $validation['location'] = 'Please select a problem location.';
    }

    if (empty($_POST['details']) || strlen(trim($_POST['details'])) === 0) {
        $validation['details'] = 'Please provide details for your issue.';
    } elseif (strlen(trim($_POST['details'])) > 500) {
        $validation['details'] = 'You have exceeded the maximum character limit of 500. Please shorten it.';
    }

    if (isset($_FILES['img']) && is_uploaded_file($_FILES['img']['tmp_name'])) {
        if ($_FILES['img']['error'] !== 0) {
            $validation['img'] = 'Upload failed. Please try again';
        } elseif ($_FILES['img']['type'] !== 'image/jpg' && $_FILES['img']['type'] !== 'image/png' && $_FILES['img']['type'] !== 'image/jpeg') {
            $validation['img'] = 'Only file with .jpg , .jpeg and .png extension is acceptable.';
        } elseif ($_FILES['img']['size'] > 1048576) {
            $validation['img'] = 'File size exceeded limit of 1MB.';
        }
    }

    return $validation;
}

function process_file_upload(): string
{
    $file_name = time() . '_' . preg_replace('/[^A-Za-z0-9 \.\-_]/', '', $_FILES['img']['name']);

    $dest = ISSUES_UPLOAD_PATH . $file_name;
    if (!move_uploaded_file($_FILES['img']['tmp_name'], $dest)) {
        return '';
    }

    return $file_name;
}
