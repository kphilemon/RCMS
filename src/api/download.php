<?php

if (!isset($_SESSION['user_id'])) {
    // 401 - Unauthorised: client not logged in
    http_response_code(401);
    exit();
}

//----------------------------------------------------------------------------------------------//

$matches = [];
preg_match('/^\/api\/download\/(\w+)\/(\d+)\/?$/i', $_SERVER['REQUEST_URI'], $matches);
$file_type = $matches[1];
$file_id = intval($matches[2]);

if (($file_type != 'accommodation' && $file_type != 'issue') || $file_id == 0) {
    // 400 - Bad request: invalid request URL
    http_response_code(400);
    echo json_encode(['error' => 'Invalid request URL.']);
    exit();
}

$db = new Database(DATABASE_NAME, DATABASE_USERNAME, DATABASE_PASSWORD);
try {

    if ($file_type == 'accommodation') {
        include '../src/models/AccommodationModel.php';
        $model = new AccommodationModel($db->getConnection());
    } else {
        include '../src/models/IssueModel.php';
        $model = new IssueModel($db->getConnection());
    }
    $data = $model->getDocsNameByIdUserId($file_id, $_SESSION['user_id']);

} catch (PDOException $exception) {
    // 500 - Server error: failed to connect to database
    http_response_code(500);
    exit();
}
$db->closeConnection();

if (empty($data)) {
    // 404 - File not found.
    http_response_code(404);
    echo json_encode(['error' => 'File not found']);
    exit();
}

if ($file_type == 'accommodation') {
    $file_path = ACCOMMODATION_UPLOAD_PATH . $data['supporting_docs'];
} else {
    $file_path = ISSUES_UPLOAD_PATH . $data['img'];
}

if (!file_exists($file_path)) {
    // 404 - File not found.
    http_response_code(404);
    echo json_encode(['error' => 'File not found']);
    exit();
}

header('Pragma: public');
header('Expires: 0');
header('Connection: Keep-Alive');
header('Cache-Control: must-revalidate, post-check=0, pre-check=0');

header('Content-Description: File Transfer');
header('Content-Type: application/octet-stream');
header('Content-Disposition: attachment; filename=' . substr(basename($file_path), 11));
header('Content-Transfer-Encoding: binary');
header('Content-Length: ' . filesize($file_path));

ob_clean();
flush();
readfile($file_path);
http_response_code(200);
