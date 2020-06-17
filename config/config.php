<?php

//ini_set('log_errors','1');
//ini_set('error_reporting', E_ALL );
//ini_set('display_errors', '0');
//ini_set('display_startup_errors', '1');

define('TITLE_RCMS', 'RCMS');
define('TITLE_ACTIVITY', 'Activities');
define('TITLE_MY_ACTIVITIES', 'My Activities');
define('TITLE_ISSUES', 'Issues');
define('TITLE_FOOD', 'Food');
define('TITLE_ACCOMMODATION', 'Accommodation');
define('TITLE_PROFILE', 'User Profile');
define('TITLE_NOT_FOUND', 'Not found');

define('CAROUSEL_IMAGES_PATH', realpath($_SERVER['DOCUMENT_ROOT'] . '/assets/img/carousel/'));

define('DATABASE', 'RCMS');
define('STATUS_PENDING', 0);
define('STATUS_APPROVED', 1);
define('STATUS_REJECTED', 2);