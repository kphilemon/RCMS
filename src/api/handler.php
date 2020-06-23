<?php


if (preg_match('/^\/api\/login\/?$/i', $_SERVER['REQUEST_URI'])) {
    include '../src/api/login.php';

} elseif (preg_match('/^\/api\/logout\/?$/i', $_SERVER['REQUEST_URI'])) {
    include '../src/api/logout.php';

} elseif (preg_match('/^\/api\/register\/?$/i', $_SERVER['REQUEST_URI'])) {
    include '../src/api/register.php';

} elseif (preg_match('/^\/api\/accommodation\/create\/?$/i', $_SERVER['REQUEST_URI'])) {
    include '../src/api/accommodation-create.php';

} elseif (preg_match('/^\/api\/accommodation\/update\/\d+\/?$/i', $_SERVER['REQUEST_URI'])) {
    include '../src/api/accommodation-update.php';

}elseif (preg_match('/^\/api\/accommodation\/delete\/\d+\/?$/i', $_SERVER['REQUEST_URI'])) {
    include '../src/api/accommodation-delete.php';

}else {
    http_response_code(404);
}