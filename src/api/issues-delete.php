<?php

if (!isset($_SESSION['user_id'])) {
    // 401 - Unauthorised: client not logged in
    http_response_code(401);
    exit();
}

//----------------------------------------------------------------------------------------------//

$matches = [];
preg_match('/^\/api\/issues\/delete\/(\d+)\/?$/i', $_SERVER['REQUEST_URI'], $matches);
$issue_id = intval($matches[1]);
if ($issue_id === 0) {
    // 400 - Bad request: invalid accommodation id
    http_response_code(400);
    echo json_encode(['error' => 'Invalid issue id.']);
    exit();
}

include '../src/models/IssueModel.php';
$db = new Database(DATABASE_NAME, DATABASE_USERNAME, DATABASE_PASSWORD);
try {
    $model = new IssueModel($db->getConnection());
    $success = $model->delete($issue_id, $_SESSION['user_id']);

} catch (PDOException $exception) {
// 500 - Server error: failed to connect to database
    http_response_code(500);
    exit();
}

$db->closeConnection();

if (!$success){
    // 500 - Server error: failed to update record
    http_response_code(500);
    exit();
}

// 200 - Success
http_response_code(200);
