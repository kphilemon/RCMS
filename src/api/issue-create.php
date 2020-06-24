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
if (isset($_FILES['supporting-docs']) && is_uploaded_file($_FILES['supporting-docs']['tmp_name'])) {
    $uploaded_name = process_file_upload();
    if ($uploaded_name === '') {
        // 500 - Server error: failed to upload docs
        http_response_code(500);
        exit();
    }
}

$college = intval($_POST['college']);
$check_in_date = date_create_from_format('Y-m-d', $_POST['check-in'])->format('Y-m-d');
$check_out_date = date_create_from_format('Y-m-d', $_POST['check-out'])->format('Y-m-d');
$purpose = trim($_POST['purpose']);
$inserted_id = '';

include '../src/models/AccommodationModel.php';
$db = new Database(DATABASE_NAME, DATABASE_USERNAME, DATABASE_PASSWORD);
try {
    $model = new AccommodationModel($db->getConnection());
    $inserted_id = $model->insert($_SESSION['user_id'], $check_in_date, $check_out_date, $college, $purpose, ($uploaded_name === '') ? null : $uploaded_name);

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

// 200 - Success
http_response_code(200);
echo json_encode(['id' => $inserted_id]);

//----------------------------------------------------------------------------------------------//


function validate_request()
{
    $validation = [];

    if (empty($_POST['college']) || strlen(trim($_POST['college'])) === 0) {
        $validation['college'] = 'Please select a college.';
    } elseif (intval($_POST['college']) < 1 || intval($_POST['college']) > 12) {
        $validation['college'] = 'Invalid college number';
    }

    // false if date cannot be created from string
    $check_in_date = date_create_from_format('Y-m-d', $_POST['check-in'] ?? null);
    $check_out_date = date_create_from_format('Y-m-d', $_POST['check-out'] ?? null);

    if (!$check_in_date) {
        $validation['check-in'] = 'Please select a date.';
    } elseif ($check_in_date->format('Y-m-d') < date('Y-m-d', strtotime("+30 days"))) {
        $validation['check-in'] = 'Check-in date must be at least 30 days from today.';
    }

    if (!$check_out_date) {
        $validation['check-out'] = 'Please select a date.';
    } elseif ($check_out_date->format('Y-m-d') < date('Y-m-d', strtotime("+31 days")) ||
        $check_out_date <= $check_in_date) {
        $validation['check-out'] = 'Check-out date must be greater than the valid check-in date.';
    }

    if (empty($_POST['purpose']) || strlen(trim($_POST['purpose'])) === 0) {
        $validation['purpose'] = 'Please provide a reason for your application.';
    } elseif (strlen(trim($_POST['purpose'])) > 5000) {
        $validation['purpose'] = 'You have exceeded the maximum character limit of 5000. Please shorten it.';
    }

    if (isset($_FILES['supporting-docs']) && is_uploaded_file($_FILES['supporting-docs']['tmp_name'])) {
        if ($_FILES['supporting-docs']['error'] !== 0) {
            $validation['supporting-docs'] = 'Upload failed. Please try again';
        } elseif ($_FILES['supporting-docs']['type'] !== 'application/pdf') {
            $validation['supporting-docs'] = 'Only files with .pdf extension is acceptable.';
        } elseif ($_FILES['supporting-docs']['size'] > 1048576) {
            $validation['supporting-docs'] = 'File size exceeded limit of 1MB.';
        }
    }

    return $validation;
}

function process_file_upload(): string
{
    $file_name = substr(preg_replace('/[^A-Za-z0-9 \.\-_]/', '', $_FILES['supporting-docs']['name']), 0, -4)
        . '_' . time() . '.pdf';

    $dest = ACCOMMODATION_UPLOAD_PATH . $file_name;
    if (!move_uploaded_file($_FILES['supporting-docs']['tmp_name'], $dest)) {
        return '';
    }

    return $file_name;
}
