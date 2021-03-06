<?php

date_default_timezone_set('Asia/Kuala_Lumpur');

####################################################### PROD ENV #######################################################


// Comment out for development to enable error display
ini_set('log_errors','1');
ini_set('error_reporting', E_ALL );
ini_set('display_errors', '0');
ini_set('display_startup_errors', '1');


####################################################### DATABASE #######################################################


// Change according to your needs, RCMS is the name of the database created in setup.sql
define('DATABASE_NAME', 'RCMS');
define('DATABASE_USERNAME', 'root');
define('DATABASE_PASSWORD', '');


######################################################### SMTP #########################################################


// Use your own email with password if this is not working. Will disable soon :)
define('SMTP_SERVER_HOST', 'smtp.gmail.com');
define('SMTP_SERVER_PORT', 587);
define('EMAIL_ADDRESS', 'kphilemon0529@gmail.com');
define('EMAIL_PASSWORD', 'wmghwmsfjmrwaewt');


###################################################### FILE PATHS ######################################################


// User private uploads to be stored behind web root
define('ACCOMMODATION_UPLOAD_PATH', '../uploads/accommodation/');
define('ISSUES_UPLOAD_PATH', '../uploads/issues/');

if (!is_dir(ACCOMMODATION_UPLOAD_PATH)) {
    mkdir(ACCOMMODATION_UPLOAD_PATH, 0755, true);
}
if (!is_dir(ISSUES_UPLOAD_PATH)) {
    mkdir(ISSUES_UPLOAD_PATH, 0755, true);
}

define('CAROUSEL_IMAGES_PATH', realpath($_SERVER['DOCUMENT_ROOT'] . '/assets/img/carousel/'));


##################################################### APP CONSTANTS ####################################################


define('STATUS_PENDING', 0);
define('STATUS_COMPLETED', 1);
define('STATUS_IN_PROGRESS', 2);
define('STATUS_SUBMITTED', 0);
define('STATUS_APPROVED', 1);
define('STATUS_REJECTED', 2);

define('TITLE_RCMS', 'RCMS');
define('TITLE_ACTIVITY', 'Activities');
define('TITLE_MY_ACTIVITIES', 'My Activities');
define('TITLE_ISSUES', 'Issues');
define('TITLE_FOOD', 'Food');
define('TITLE_ACCOMMODATION', 'Accommodation');
define('TITLE_PROFILE', 'User Profile');
define('TITLE_NOT_FOUND', 'Not found');
define('TITLE_ACTIVATE', 'Activate account');
