<?php
include '../src/templates/header.php';
include '../src/templates/navbar.php';
include '../src/models/UMStudentModel.php';


//update the details when the information is passed in
if (isset($_POST['nationality']) && isset($_POST['room_id']) && isset($_POST['telephone']) && isset($_POST['gender']) && isset($_POST['dob'])) {
    $edit = true;
    //check if the form was submitted
    try {
        $db = new Database(DATABASE_NAME, DATABASE_USERNAME, DATABASE_PASSWORD);
        // set to true if any of the db actions failed
        $server_err = false;
        $update_err = false;
        $profile = new UMStudentModel($db->getConnection());
        //check the file before submit it
        $err_msg = "";
        if (empty($_POST['nationality'])) {
            $err_msg .= "Nationality should not be empty.<br>";
        }
        if (empty($_POST['room_id'])) {
            $err_msg .= "Room Number should not be empty.<br>";
        } else {
            if (!preg_match("/^[A-F]{1}\d{3}$/", $_POST['room_id'])) {
                $err_msg .= "Room Number be start with blok number and follow by room number.<br>";
            }
        }

        if (empty($_POST['telephone'])) {
            $err_msg .= "Telephone should not be empty.<br>";
        } else {
            if (!preg_match("/^01\d{8}$/", $_POST['telephone']) && !preg_match("/^01\d{9}$/", $_POST['telephone'])) {
                $err_msg .= "The telephone should be start with 01 and contains numbers only.<br>";
            }
        }

        if (empty($_POST['gender'])) {
            $err_msg .= "Gender should not be empty. <br>";
        }
        $date_now = date("m/d/Y");
        if (strtotime($_POST['dob']) > strtotime($date_now)) {
            $err_msg .= "Date of Birth should not greater than today. <br>";
        }
        if (empty($_POST['dob'])) {
            $err_msg .= "Date of Birth should not be empty. <br>";
        }
        if (empty($_POST['address'])) {
            $err_msg .= "Address should not be empty. <br>";
        }
        if (empty($_POST['city'])) {
            $err_msg .= "City should not be empty. <br>";
        }
        if (empty($_POST['state'])) {
            $err_msg .= "State should not be empty. <br>";
        }
        if (empty($_POST['zip'])) {
            $err_msg .= "Zip should not be empty. <br>";
        }

        if ($err_msg != null) {
            $update_err = true;

        } else {
            $update_err = $profile->updateDetailsById($_SESSION['user_id'], $_POST['room_id'], $_POST['nationality'], $_POST['telephone'], $_POST['gender'], $_POST['dob'], $_POST['address'], $_POST['city'], $_POST['state'], $_POST['zip']);
        }

    } catch (PDOException $exception) {
        error_log('Profile: updateDetailsById: ' . $exception->getMessage() . 'id: ' . $_SESSION['user_id']);
        $server_err = true;
    }
    $db->closeConnection();
}


// for the user who has completed the profile
if (isset($_SESSION['user_id'])) {
    // user is logged in, load profile from db
    try {
        $db = new Database(DATABASE_NAME, DATABASE_USERNAME, DATABASE_PASSWORD);
        // set to true if any of the db actions failed
        $server_err = false;
        $profile = new UMStudentModel($db->getConnection());
        $data = $profile->getDetailsById($_SESSION['user_id']);

    } catch (PDOException $exception) {
        error_log('Profile: getDetailsById: ' . $exception->getMessage() . 'id: ' . $_SESSION['user_id']);
        $server_err = true;
    }
    $db->closeConnection();
}

// to delete account
if (isset($_SESSION['user_id'])) {
    // check password to delete account
    if (isset($_POST['pass_delete_acc']) && !empty($_POST['pass_delete_acc'])) {
        if (md5($_POST['pass_delete_acc']) == $data['password']) {
            try {
                $delete_acc = false;
                $db = new Database(DATABASE_NAME, DATABASE_USERNAME, DATABASE_PASSWORD);
                // set to true if any of the db actions failed
                $server_err = false;
                $profile = new UMStudentModel($db->getConnection());
                $delete_acc = $profile->deleteAccountById($_SESSION['user_id'], md5($_POST['pass_delete_acc']));
                if ($delete_acc) {
                    //prompt to the url (localhost/logout.php)
                    header('Location: /api/logout POST');
                } else {
                    $server_err = true;
                }

            } catch (PDOException $exception) {
                error_log('Profile: deleteAccountById: ' . $exception->getMessage() . 'id: ' . $_SESSION['user_id']);
                $server_err = true;
            }
            $db->closeConnection();
        } else {
            $delete_acc = false;
        }
    }
}

//change the password
if (isset($_SESSION['user_id'])) {
    // check password to delete account
    if (isset($_POST['currentPass']) && isset($_POST['newPass']) && isset($_POST['retypePass'])) {
        if (!empty($_POST['currentPass']) && !empty($_POST['retypePass']) && !empty($_POST['newPass'])) {
            if ((md5($_POST['currentPass']) == $data['password']) && ($_POST['newPass'] == $_POST['retypePass'])) {
                try {
                    $update_pass = false;
                    $db = new Database(DATABASE_NAME, DATABASE_USERNAME, DATABASE_PASSWORD);
                    // set to true if any of the db actions failed
                    $server_err = false;
                    $profile = new UMStudentModel($db->getConnection());
                    $update_pass = $profile->updatePasswordById($_SESSION['user_id'], md5($_POST['newPass']));

                } catch (PDOException $exception) {
                    error_log('Profile: updatePasswordById: ' . $exception->getMessage() . 'id: ' . $_SESSION('user_id'));
                    $server_err = true;
                }
                $db->closeConnection();
            } else {
                $update_pass = false;
            }
        }
    }
}


?>

    <main class="container">
        <?php if (isset($_SESSION['user_id'])) : ?>
        <?php if ($server_err) : include '../src/templates/server_err.php'; else: ?>
        <!--Put the things u want to test it if the user had entered data-->
        <?php if (isset($_POST['nationality']) && isset($_POST['room_id']) && isset($_POST['telephone']) && isset($_POST['gender']) && isset($_POST['dob'])): ?>
            <?php if ($update_err): ?>
                <div class="alert alert-warning alert-dismissible fade show" role="alert">

                    Profile update failed. Please try again.
                    <button type="button" class="close" data-dismiss="alert">
                        <span>&times;</span>
                    </button>
                </div>
            <?php else: ?>
                <?php $_SESSION['user_profile_complete'] = true; ?>
                <div class="alert alert-info alert-dismissible fade show" role="alert">
                    Profile updated successfully.
                    <button type="button" class="close" data-dismiss="alert">
                        <span>&times;</span>
                    </button>
                </div>
            <?php endif; ?>
        <?php endif; ?>

        <!--For notification of the password change-->
        <?php if (isset($_POST['currentPass']) && isset($_POST['newPass']) && isset($_POST['retypePass']) && !empty($_POST['currentPass']) && !empty($_POST['newPass']) && !empty($_POST['retypePass'])): ?>
            <?php if (!$update_pass): ?>
                <div class="alert alert-warning alert-dismissible fade show" role="alert">
                    Password update failed. Please try again.
                    <button type="button" class="close" data-dismiss="alert">
                        <span>&times;</span>
                    </button>
                </div>
            <?php else: ?>
                <div class="alert alert-info alert-dismissible fade show" role="alert">
                    Password updated successfully.
                    <button type="button" class="close" data-dismiss="alert">
                        <span>&times;</span>
                    </button>
                </div>
            <?php endif; ?>
        <?php endif; ?>

        <!--For notification of the password change-->
        <?php if (isset($_POST['pass_delete_acc']) && !empty($_POST['newPass'])): ?>
            <?php if (!$delete_acc): ?>
                <div class="alert alert-warning alert-dismissible fade show" role="alert">
                    You have entered the wrong password.
                    <button type="button" class="close" data-dismiss="alert">
                        <span>&times;</span>
                    </button>
                </div>
            <?php endif; ?>
        <?php endif; ?>


        <div class="row">
            <div class="col-lg-3">
                <div class="sticky-top">
                    <button class="btn btn-secondary btn-profile-bg mb-4 " type="button">
                        <h3><?= $_SESSION['username'][0] ?? 'R' ?></h3>
                    </button>
                    <h4 class="text-primary overflow-hidden"><?= $_SESSION['username'] ?></h4>
                    <h6 class="text-primary overflow-hidden"><?= $_SESSION['user_email'] ?></h6>

                    <button type="button" class="btn btn-outline-primary d-block mt-4 mb-2" data-toggle="modal"
                            data-target="#modal-delete-acc">Delete Account
                    </button>

                    <button type="button" class="btn btn-outline-primary mt-2 mb-4" data-toggle="modal"
                            data-target="#modal-pw-change">Change Password
                    </button>
                </div>
            </div>

            <input id="password" name="password" value="<?= $data['password'] ?>" hidden>

            <?php if ($_SESSION['user_profile_complete'] == false): ?>

            <!--Form for student to enter their details information-->
            <div class="col-lg-9">
                <div class="card">
                    <div class="card-body">
                        <div class="container my-2">
                            <form id="details" class="needs-validation" action="" method="post" novalidate>
                                <div class="invalid-feedback"></div>
                                <h4 class="mb-3 text-primary">Student Profile</h4>

                                <!--Matrix Number Here-->
                                <div class="form-row">
                                    <div class="col-md-4 mb-3">
                                        <label for="matrix_no">Student ID</label>
                                        <input type="text" class="form-control" id="matrix_no"
                                               name="matrix_no" value="<?= $data['matrix_no'] ?>" disabled>
                                        <div class="invalid-feedback"></div>
                                    </div>

                                    <!--College ID here-->
                                    <div class="col-md-4 mb-3">
                                        <label for="college">College</label>
                                        <input type="text" class="form-control" id="college" name="college"
                                               value=" <?= "KK" . $_SESSION['user_college_id'] ?>" disabled>
                                        <div class="invalid-feedback"></div>
                                    </div>

                                    <!--Input their room number here-->
                                    <div class="col-md-4 mb-3">
                                        <label for="room">Room Number</label>
                                        <input type="text" class="form-control" id="room" name="room_id"
                                               placeholder="F409"
                                               value="<?= ($data['room_no']) ? $data['room_no'] : "" ?>" required>
                                        <div class="invalid-feedback"></div>
                                    </div>
                                </div>

                                <div class="form-row">
                                    <!--Nationality Input-->
                                    <div class="col-md-3 mb-3">
                                        <label for="inputNationality">Nationality</label>
                                        <select class="form-control" id="inputNationality" name="nationality"
                                                required>
                                            <option value="" disabled selected hidden>Select your option</option>
                                            <option value="malaysian" <?= ($data['nationality'] == "malaysian") ? 'selected' : "" ?> >
                                                Malaysian
                                            </option>
                                            <option value="nonMalaysian" <?= ($data['nationality'] == "nonMalaysian") ? 'selected' : "" ?>>
                                                Non Malaysian
                                            </option>
                                        </select>
                                        <div class="invalid-feedback"></div>
                                    </div>

                                    <!--Telephone no Input-->
                                    <div class="col-md-3 mb-3">
                                        <label for="inputTel">Telephone No.</label>
                                        <input id="inputTel" name="telephone" type="text" class="form-control"
                                               placeholder="Tel No"
                                               value="<?= ($data['phone']) ? $data['phone'] : "" ?>" required>
                                        <div class="invalid-feedback"></div>
                                    </div>

                                    <!--sex Input-->
                                    <div class="col-md-3 mb-3">
                                        <label for="sex">Gender</label>
                                        <select class="form-control" id="sex" name="gender" required>
                                            <option value="" disabled selected hidden>Select your option</option>
                                            <option value="male" <?= ($data['gender'] == "male") ? 'selected' : "" ?>>
                                                Male
                                            </option>
                                            <option value="female" <?= ($data['gender'] == "female") ? 'selected' : "" ?>>
                                                Female
                                            </option>
                                        </select>
                                        <div class="invalid-feedback"></div>
                                    </div>

                                    <!--Date of Birth Input-->
                                    <div class="col-md-3 mb-3">
                                        <label for="inputDOB">Date of Birth</label>
                                        <input type="date" name="dob" class="form-control" id="inputDOB"
                                               value="<?= ($data['date_of_birth']) ? $data['date_of_birth'] : "" ?>"
                                               required>
                                        <div class="invalid-feedback"></div>
                                    </div>
                                </div>

                                <!--Input their address line-->
                                <div class="form-group">
                                    <label for="address">Address</label>
                                    <input type="text" class="form-control" id="address" name="address"
                                           placeholder="1234 Main St"
                                           value="<?= ($data['address']) ? $data['address'] : "" ?>" required>
                                    <div class="invalid-feedback"></div>
                                </div>


                                <!--Input their city-->
                                <div class="form-row">
                                    <div class="col-md-6 mb-3">
                                        <label for="city">City</label>
                                        <input type="text" class="form-control" id="city" name="city" placeholder="City"
                                               value="<?= ($data['city']) ? $data['city'] : "" ?>" required>
                                        <div class="invalid-feedback"></div>
                                    </div>

                                    <!--Input their state-->
                                    <div class="col-md-3 mb-3">
                                        <label for="state">State</label>
                                        <input type="text" class="form-control" id="state" name="state"
                                               placeholder="State"
                                               value="<?= ($data['state']) ? $data['state'] : "" ?>" required>
                                        <div class="invalid-feedback"></div>
                                    </div>

                                    <!--Input their zip code-->
                                    <div class="col-md-3 mb-3">
                                        <label for="zip">Zip</label>
                                        <input type="text" class="form-control" id="zip" name="zip" placeholder="Zip"
                                               value="<?= ($data['zip']) ? $data['zip'] : "" ?>" required>
                                        <div class="invalid-feedback"></div>
                                    </div>
                                </div>

                                <!--Academic Details Here-->
                                <h4 class="mt-4 mb-3 text-primary">Academic Details</h4>
                                <!--Input their address line 1-->
                                <div class="form-group">
                                    <label for="faculty">Faculty</label>
                                    <input type="text" class="form-control" id="faculty" name="faculty"
                                           value="<?= $data['faculty']; ?>" disabled>
                                    <div class="invalid-feedback"></div>
                                </div>

                                <div class="form-group">
                                    <label for="course">Course</label>
                                    <input type="text" class="form-control" id="course" name="course"
                                           value="<?= $data['course'] ?>" disabled>
                                    <div class="invalid-feedback"></div>
                                </div>

                                <button type="submit" class="btn btn-primary ml-3 my-2 px-4 float-right"
                                        id="new_user_save">
                                    Save Details
                                </button>

                                <?php else: ?>
                                <!--Form for student to enter their details information-->
                                <div class="col-lg-9">
                                    <div class="card">
                                        <div class="card-body">
                                            <div class="container my-2">
                                                <form id="details" class="needs-validation" action="" method="post"
                                                      novalidate>
                                                    <div class="invalid-feedback"></div>
                                                    <h4 class="mb-3 text-primary">Student Profile</h4>

                                                    <!--Matrix Number Here-->
                                                    <div class="form-row">
                                                        <div class="col-md-4 mb-3">
                                                            <label for="matrix_no">Student ID</label>
                                                            <input type="text" class="form-control" id="matrix_no"
                                                                   name="matrix_no" value="<?= $data['matrix_no'] ?>"
                                                                   disabled>
                                                            <div class="invalid-feedback"></div>
                                                        </div>

                                                        <!--College ID here-->
                                                        <div class="col-md-4 mb-3">
                                                            <label for="college">College</label>
                                                            <input type="text" class="form-control" id="college"
                                                                   name="college"
                                                                   value=" <?= "KK" . $_SESSION['user_college_id'] ?>"
                                                                   disabled>
                                                            <div class="invalid-feedback"></div>
                                                        </div>

                                                        <!--Input their room number here-->
                                                        <div class="col-md-4 mb-3">
                                                            <label for="room">Room Number</label>
                                                            <input type="text" class="form-control" id="room"
                                                                   name="room_id"
                                                                   value="<?= $data['room_no'] ?>"
                                                                <?= ($_SESSION['user_profile_complete'] == true) ? 'disabled' : "" ?>
                                                                   required>
                                                            <div class="invalid-feedback"></div>
                                                        </div>
                                                    </div>

                                                    <div class="form-row">
                                                        <div class="col-md-3 mb-3">
                                                            <label for="inputNationality">Nationality</label>
                                                            <select class="form-control" id="inputNationality"
                                                                    name="nationality"
                                                                <?= ($_SESSION['user_profile_complete'] == true) ? 'disabled' : "" ?>
                                                                    required>
                                                                <option value="" disabled hidden>Select your option
                                                                </option>
                                                                <option value="malaysian" <?= ($data['nationality'] == "malaysian") ? 'selected' : "" ?> >
                                                                    Malaysian
                                                                </option>
                                                                <option value="nonMalaysian" <?= ($data['nationality'] == "nonMalaysian") ? 'selected' : "" ?>>
                                                                    Non Malaysian
                                                                </option>
                                                            </select>
                                                            <div class="invalid-feedback"></div>
                                                        </div>

                                                        <!--Telephone no Input-->
                                                        <div class="col-md-3 mb-3">
                                                            <label for="inputTel">Telephone No.</label>
                                                            <input id="inputTel" name="telephone" type="text"
                                                                   class="form-control"
                                                                   value="<?= $data['phone'] ?>"
                                                                <?= ($_SESSION['user_profile_complete'] == true) ? 'disabled' : "" ?>
                                                                   required>
                                                            <div class="invalid-feedback"></div>
                                                        </div>

                                                        <!--sex Input-->
                                                        <div class="col-md-3 mb-3">
                                                            <label for="sex">Gender</label>
                                                            <select class="form-control" id="sex" name="gender"
                                                                <?= ($_SESSION['user_profile_complete'] == true) ? 'disabled' : "" ?>
                                                                    required>
                                                                <option value="" disabled hidden>Select your option
                                                                </option>
                                                                <option value="male" <?= ($data['gender'] == "male") ? 'selected' : "" ?>>
                                                                    Male
                                                                </option>
                                                                <option value="female" <?= ($data['gender'] == "female") ? 'selected' : "" ?>>
                                                                    Female
                                                                </option>
                                                            </select>
                                                            <div class="invalid-feedback"></div>
                                                        </div>

                                                        <!--Date of Birth Input-->
                                                        <div class="col-md-3 mb-3">
                                                            <label for="inputDOB">Date of Birth</label>
                                                            <input type="date" name="dob" class="form-control"
                                                                   id="inputDOB"
                                                                   value="<?= $data['date_of_birth'] ?>"
                                                                <?= ($_SESSION['user_profile_complete'] == true) ? 'disabled' : "" ?>
                                                                   required>
                                                            <div class="invalid-feedback"></div>
                                                        </div>
                                                    </div>

                                                    <!--Input their address line-->
                                                    <div class="form-group">
                                                        <label for="address">Address</label>
                                                        <input type="text" class="form-control" id="address"
                                                               name="address"
                                                               value="<?= $data['address'] ?>"
                                                            <?= ($_SESSION['user_profile_complete'] == true) ? 'disabled' : "" ?>
                                                               required>
                                                        <div class="invalid-feedback"></div>
                                                    </div>


                                                    <!--Input their city-->
                                                    <div class="form-row">
                                                        <div class="col-md-6 mb-3">
                                                            <label for="city">City</label>
                                                            <input type="text" class="form-control" id="city"
                                                                   name="city"
                                                                   value="<?= $data['city'] ?>"
                                                                <?= ($_SESSION['user_profile_complete'] == true) ? 'disabled' : "" ?>
                                                                   required>
                                                            <div class="invalid-feedback"></div>
                                                        </div>

                                                        <!--Input their state-->
                                                        <div class="col-md-3 mb-3">
                                                            <label for="state">State</label>
                                                            <input type="text" class="form-control" id="state"
                                                                   name="state"
                                                                   value="<?= $data['state'] ?>"
                                                                <?= ($_SESSION['user_profile_complete'] == true) ? 'disabled' : "" ?>
                                                                   required>
                                                            <div class="invalid-feedback"></div>
                                                        </div>

                                                        <!--Input their zip code-->
                                                        <div class="col-md-3 mb-3">
                                                            <label for="zip">Zip</label>
                                                            <input type="text" class="form-control" id="zip" name="zip"
                                                                   value="<?= $data['zip'] ?>"
                                                                <?= ($_SESSION['user_profile_complete'] == true) ? 'disabled' : "" ?>
                                                                   required>
                                                            <div class="invalid-feedback"></div>
                                                        </div>
                                                    </div>

                                                    <!--Academic Details Here-->
                                                    <h4 class="mt-4 mb-3 text-primary">Academic Details</h4>
                                                    <!--Input their address line 1-->
                                                    <div class="form-group">
                                                        <label for="faculty">Faculty</label>
                                                        <input type="text" class="form-control" id="faculty"
                                                               name="faculty"
                                                               value="<?= $data['faculty']; ?>" disabled>
                                                        <div class="invalid-feedback"></div>
                                                    </div>

                                                    <div class="form-group">
                                                        <label for="course">Course</label>
                                                        <input type="text" class="form-control" id="course"
                                                               name="course"
                                                               value="<?= $data['course'] ?>" disabled>
                                                        <div class="invalid-feedback"></div>
                                                    </div>

                                                    <button type="button"
                                                            class="btn btn-primary ml-3 my-2 px-4 float-right"
                                                            id="action">
                                                        Update Details
                                                    </button>

                                                    <button type="submit"
                                                            class="btn btn-primary ml-3 my-2 px-4 float-right"
                                                            id="old_user_save" hidden>
                                                        Save Details
                                                    </button>

                                                    <button type="button"
                                                            class="btn btn-danger ml-3 my-2 px-4 float-right"
                                                            id="cancel" hidden>
                                                        Cancel
                                                    </button>
                                                    <?php endif; ?>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                        </div>
                        <?php endif; ?>
                        <?php else : include '../src/templates/acc_req.php';
                        endif; ?>
    </main>


    <div id="modals">
        <div class="modal fade" id="modal-delete-acc" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">

                    <div class="modal-header">
                        <h5 class="modal-title text-primary">Confirmation</h5>
                        <button type="button" class="close" data-dismiss="modal">
                            <span>&times;</span>
                        </button>
                    </div>

                    <div class="modal-body text-secondary">
                        <p>Warning: Deleting your account cannot be undone.</p>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-primary mr-2" data-dismiss="modal">Cancel</button>
                        <button id="delete_acc_button" type="button" class="btn btn-danger">Delete</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="modal-confirm-delete-acc" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLongTitle4">Enter your password</h5>
                        <button type="button" class="close" data-dismiss="modal">
                            <span>&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form id="passDeleteAccForm" method="POST" action="" class="needs-validation" novalidate>

                            <div class="form-group">
                                <label for="pw_delete_acc">Password</label>
                                <input type="password" class="form-control" id="pw_delete_acc" name="pass_delete_acc"
                                       required>
                                <div class="invalid-feedback"></div>
                            </div>


                            <div class="modal-footer">
                                <button type="button" class="btn btn-outline-primary mr-2" data-dismiss="modal">Cancel
                                </button>
                                <button type="submit" class="btn btn-danger">Confirm</button>
                            </div>
                        </form>

                    </div>

                </div>
            </div>
        </div>


        <div class="modal fade" id="modal-pw-change" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLongTitle4">Change Password</h5>
                        <button type="button" class="close" data-dismiss="modal">
                            <span>&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form id="passForm" method="POST" action="" class="needs-validation" novalidate>
                            <div class="invalid-feedback"></div>
                            <div class="form-group">
                                <label for="currentPass">Current Password</label>
                                <input type="password" class="form-control" id="currentPass" name="currentPass"
                                       placeholder="Current Password" required>
                                <div class="invalid-feedback"></div>
                            </div>

                            <div class="form-group">
                                <label for="newPass">New Password</label>
                                <input type="password" class="form-control" id="newPass" name="newPass"
                                       placeholder="New Password" required>
                                <div class="invalid-feedback"></div>
                            </div>

                            <div class="form-group">
                                <label for="retypePass">Current Password</label>
                                <input type="password" class="form-control" id="retypePass" name="retypePass"
                                       placeholder="Retype New Password" required>
                                <div class="invalid-feedback"></div>
                            </div>

                            <div class="modal-footer">
                                <button type="button" class="btn btn-outline-primary" data-dismiss="modal">
                                    Cancel
                                </button>
                                <button type="submit" id="save_pass" class="btn btn-primary">Save Changes</button>
                            </div>
                        </form>

                    </div>

                </div>
            </div>
        </div>

        <div class="modal fade" id="modal-welcome-new-user" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">

                    <div class="modal-header">
                        <h5 class="modal-title text-primary">Welcome!</h5>
                        <button type="button" class="close" data-dismiss="modal">
                            <span>&times;</span>
                        </button>
                    </div>

                    <div class="modal-body text-secondary">
                        <p>Your account has been successfully created. You are now required to fill up all the student
                            details to enjoy our features.</p>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary" data-dismiss="modal">Let's do this!</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

<?php include '../src/templates/modals.php' ?>

    <script src="/assets/js/vendor/jquery-3.5.1.js"></script>
    <script src="/assets/js/vendor/bootstrap.bundle.js"></script>
    <script src="/assets/js/utility.js"></script>
    <script src="/assets/js/core.js"></script>
    <script src="/assets/js/profile.js"></script>
    <script src="https://unpkg.com/pure-md5@latest/lib/index.js"></script>

<?php include '../src/templates/footer.php' ?>