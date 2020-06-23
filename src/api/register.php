<?php

$db = new Database(DATABASE_NAME, DATABASE_USERNAME, DATABASE_PASSWORD);
$signup = new UserModel($db->getConnection());
$server_err = false;
$msg = '';

// Both fields are being posted and there not empty
if (isset($_POST['email']) && !empty($_POST['email']) && isset($_POST['name']) && !empty($_POST['name'])) {
    $email = ($_POST['email']);
    $name = ($_POST['name']);

    //is siswamail?
    $pattern = '/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@siswa.um.edu.my$/';
    if (preg_match($pattern, $email) === 1) {
        //check if exists in student table
        try {
            $data = $signup->getByEmail($email);
        } catch (PDOException $exception) {
            $server_err = true;
            error_log('signup: getByEmail: ' . $exception->getMessage() . 'email: ' . $email);
        }
        //data is exists in student table
        if ($data != null) {
            //registered
            if ($data['activated'] == 0) {
                //not yet activate. prompt activation link
                $msg .= 'You have registered your account. Please activate it through your email.<br>';
            } else {
                //activated. prompt signin form
                header("Location: #");
            }
        } else {
            try {
                $registerData = $signup->getByEmailReg($email);
            } catch (PDOException $exception) {
                $server_err = true;
                error_log('signup: getByEmailReg: ' . $exception->getMessage() . 'email: ' . $email);
            }
            //student of UM
            if ($registerData != null) {
                //continue register
                try {
                    $insert_err = $signup->insertByEmailReg($registerData['email'], $registerData['name'], $registerData['matrix_no'], $registerData['college_id'], $registerData['faculty'], $registerData['course']);
                } catch (PDOException $exception) {
                    $server_err = true;
                    error_log('signup: insertByEmailReg: ' . $exception->getMessage() . 'email: ' . $email);
                }

                try {
                    $data = $signup->getByEmail($email);
                } catch (PDOException $exception) {
                    error_log('UserModel: getByEmail: ' . $exception->getMessage() . 'email: ' . $email);
                    $server_err = true;
                    error_log('signup: getByEmail: ' . $exception->getMessage() . 'email: ' . $email);
                }

                if ($insert_err == false) {
                    //register successful then send email
                    $to = $email; // Send email to our user
                    $subject = 'Signup | Verification for RCMS account'; // Give the email a subject
                    $message = '
                
                Hi! ' . $name . '. Thanks for signing up!
                Your account has been created, you can login with the following credentials after you have activated your account by pressing the url below.
                
                Please click this link to activate your account:
                http://localhost/activate/hash=' . $data['hash'] . '
                
                '; // Our message above including the link

                    $headers = 'From:noreply@yourwebsite.com' . "\r\n"; // Set from headers
                    mail($to, $subject, $message, $headers); // Send our email
                    echo json_encode(array('success' => 1));
                }
            } else {
                //register not successful
                $msg .= 'Please use the valid siswamail.';
            }
        }
    }else{
        $msg .= 'The email you entered is invalid. Please enter again your siswamail.';
    }
}else{
    if(empty($_POST['email'])){
        $msg .= 'Please enter your siswamail.';
    }
    if(empty($_POST['name'])){
        $msg .= 'Please enter your password.';
    }
}

// Check if $msg is not empty
if (!empty($msg)) {
    echo '<script type="text/javascript">alert("' . $msg . '")</script>';
}
