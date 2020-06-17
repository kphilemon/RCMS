<?php
include '../config/config.php';
include '../src/utilities/core.php';
include '../src/models/Database.php';
session_start();



if (preg_match('/^(\/?|\/(home|activities)\/?)$/i', $_SERVER['REQUEST_URI'])) {
    $GLOBALS['title'] = TITLE_RCMS;
    include '../src/modules/home/home.php';

} elseif (preg_match('/^\/activities\/\w+\/?$/i', $_SERVER['REQUEST_URI'])) {
    $GLOBALS['title'] = TITLE_ACTIVITY;
    include '../src/modules/activity/activity-details.php';

} elseif (preg_match('/^\/my-activities\/?$/i', $_SERVER['REQUEST_URI'])) {
    $GLOBALS['title'] = TITLE_MY_ACTIVITIES;
    include '../src/modules/activity/my-activities.php';

} elseif (preg_match('/^\/issues\/?$/i', $_SERVER['REQUEST_URI'])) {
    $GLOBALS['title'] = TITLE_ISSUES;
    include '../src/modules/issues/issues.php';

} elseif (preg_match('/^\/issues\/\w+\/?$/i', $_SERVER['REQUEST_URI'])) {
    $GLOBALS['title'] = TITLE_ISSUES;
    include '../src/modules/issues/issues-details.php';

} elseif (preg_match('/^\/food\/?$/i', $_SERVER['REQUEST_URI'])) {
    $GLOBALS['title'] = TITLE_FOOD;
    include '../src/modules/food/food.php';

} elseif (preg_match('/^\/accommodation\/?$/i', $_SERVER['REQUEST_URI'])) {
    $GLOBALS['title'] = TITLE_ACCOMMODATION;
    include '../src/modules/accommodation/accommodation.php';

} elseif (preg_match('/^\/accommodation\/\w+\/?$/i', $_SERVER['REQUEST_URI'])) {
    $GLOBALS['title'] = TITLE_ACCOMMODATION;
    include '../src/modules/accommodation/accommodation-details.php';

} elseif (preg_match('/^\/profile\/?$/i', $_SERVER['REQUEST_URI'])) {
    $GLOBALS['title'] = TITLE_PROFILE;
    include '../src/modules/profile/profile.php';

} elseif (preg_match('/^\/api(\/[^\s\/]+)+/i', $_SERVER['REQUEST_URI'])) {
    include '../src/api/handler.php';

} else {
    $GLOBALS['title'] = TITLE_NOT_FOUND;
    include '../src/modules/page-not-found.php';
}
