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

$matches = [];
preg_match('/^\/api\/issues\/update\/(\d+)\/?$/i', $_SERVER['REQUEST_URI'], $matches);
$report_id = intval($matches[1]);
if ($report_id === 0) {
    // 400 - Bad request: invalid accommodation id
    http_response_code(400);
    echo json_encode(['error' => 'Invalid issue id.']);
    exit();
}

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

$problemtype = $_POST['type'];
$problemlocation = $_POST['location'];
$problemdetail = trim($_POST['details']);
$success = false;

include '../src/models/IssueModel.php';
$db = new Database(DATABASE_NAME, DATABASE_USERNAME, DATABASE_PASSWORD);
try {
    $model = new IssueModel($db->getConnection());
    $success = $model->update($report_id, $_SESSION['user_id'], $problemtype, $problemlocation, $problemdetail, ($uploaded_name === '') ? null : $uploaded_name);

} catch (PDOException $exception) {
// 500 - Server error: failed to connect to database
    http_response_code(500);
    exit();
}

$db->closeConnection();

if (!$success) {
    // 500 - Server error: failed to update record
    http_response_code(500);
    exit();
}

// 200 - Success
http_response_code(200);

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
