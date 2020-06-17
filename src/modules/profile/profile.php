<?php
include '../src/templates/header.php';
include '../src/templates/navbar.php';

if (isset($_SESSION['user_id'])) {
    // user is logged in, load profile from db
}

// set to true if any of the db actions failed
$server_err = false;

if (isset($_POST[''])) { //check if form was submitted

}

$update_err = false

?>

<main class="container">
    <?php if (isset($_SESSION['user_id'])) : ?>
        <?php if ($server_err) : include '../src/templates/server_err.php'; else: ?>

            <?php if (isset($_POST[''])): ?>
                <?php if ($update_err): ?>
                    <div class="alert alert-warning alert-dismissible fade show" role="alert">
                        Profile update failed. Please try again.
                        <button type="button" class="close" data-dismiss="alert">
                            <span>&times;</span>
                        </button>
                    </div>
                <?php else: ?>
                    <div class="alert alert-info alert-dismissible fade show" role="alert">
                        Profile updated successfully.
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
                        <h5 class="text-primary overflow-hidden"><?= $_SESSION['user_email'] ?></h5>

                        <button type="button" class="btn btn-outline-primary d-block mt-4 mb-2" data-toggle="modal"
                                data-target="#modal-delete-acc">Delete Account
                        </button>

                        <button type="button" class="btn btn-outline-primary mt-2 mb-4" data-toggle="modal"
                                data-target="#modal-pw-change">Change Password
                        </button>
                    </div>
                </div>

                <!--Form for student to enter their details information-->
                <div class="col-lg-9">
                    <div class="card">
                        <div class="card-body">
                            <div class="container my-2">
                                <form id="details" class="needs-validation" action="" method="post" novalidate>
                                    <h4 class="mb-3 text-primary">Student Details</h4>

                                    <div class="form-row">
                                        <!--Nationality Input-->
                                        <div class="col-md-3 mb-3">
                                            <label for="inputNationality">Nationality</label>
                                            <select class="form-control" id="inputNationality" required>
                                                <option value="" disabled selected hidden>Select your option</option>
                                                <option value="malaysian">Malaysian</option>
                                                <option value="nonMalaysian">Non Malaysian</option>
                                            </select>
                                            <div class="valid-feedback">
                                                Looks good!
                                            </div>
                                            <div class="invalid-feedback">
                                                Please select your nationality.
                                            </div>
                                        </div>

                                        <!--Telephone no Input-->
                                        <div class="col-md-3 mb-3">
                                            <label for="inputTel">Telephone No.</label>
                                            <input id="inputTel" type="text" class="form-control" placeholder="Tel No"
                                                   required>
                                            <div class="valid-feedback">
                                                Looks good!
                                            </div>
                                            <div class="invalid-feedback">
                                                Please enter your telephone no.
                                            </div>
                                        </div>

                                        <!--sex Input-->
                                        <div class="col-md-3 mb-3">
                                            <label for="sex">Sex</label>
                                            <select class="form-control" id="sex" required>
                                                <option value="" disabled selected hidden>Select your option</option>
                                                <option value="male">Male</option>
                                                <option value="female">Female</option>
                                            </select>
                                            <div class="valid-feedback">
                                                Looks good!
                                            </div>
                                            <div class="invalid-feedback">
                                                Please select your sex.
                                            </div>
                                        </div>

                                        <!--Date of Birth Input-->
                                        <div class="col-md-3 mb-3">
                                            <label for="inputDOB">Date of Birth</label>
                                            <input type="date" class="form-control" id="inputDOB" required>
                                            <div class="valid-feedback">
                                                Looks good!
                                            </div>
                                            <div class="invalid-feedback">
                                                Please enter your date of birth.
                                            </div>
                                        </div>
                                    </div>

                                    <!--Input their address line 1-->
                                    <div class="form-group">
                                        <label for="validationCustom01">Address</label>
                                        <input type="text" class="form-control" id="validationCustom01"
                                               placeholder="1234 Main St"
                                               required>
                                        <div class="valid-feedback">
                                            Looks good!
                                        </div>
                                        <div class="invalid-feedback">
                                            Please enter your address.
                                        </div>
                                    </div>

                                    <!--Input their address line 2-->
                                    <div class="form-group">
                                        <label for="validationCustom02">Address 2</label>
                                        <input type="text" class="form-control" id="validationCustom02"
                                               placeholder="Apartment, studio, or floor" required>
                                        <div class="valid-feedback">
                                            Looks good!
                                        </div>
                                        <div class="invalid-feedback">
                                            Please enter your address.
                                        </div>
                                    </div>

                                    <!--Input their city-->
                                    <div class="form-row">
                                        <div class="col-md-6 mb-3">
                                            <label for="validationDefault03">City</label>
                                            <input type="text" class="form-control" id="validationDefault03"
                                                   placeholder="City"
                                                   required>
                                            <div class="valid-feedback">
                                                Looks good!
                                            </div>
                                            <div class="invalid-feedback">
                                                Please enter your city.
                                            </div>
                                        </div>

                                        <!--Input their state-->
                                        <div class="col-md-3 mb-3">
                                            <label for="validationDefault04">State</label>
                                            <input type="text" class="form-control" id="validationDefault04"
                                                   placeholder="State"
                                                   required>
                                            <div class="valid-feedback">
                                                Looks good!
                                            </div>
                                            <div class="invalid-feedback">
                                                Please enter your state.
                                            </div>
                                        </div>

                                        <!--Input their zip code-->
                                        <div class="col-md-3 mb-3">
                                            <label for="validationDefault05">Zip</label>
                                            <input type="text" class="form-control" id="validationDefault05"
                                                   placeholder="Zip"
                                                   required>
                                            <div class="valid-feedback">
                                                Looks good!
                                            </div>
                                            <div class="invalid-feedback">
                                                Please enter your zip code.
                                            </div>
                                        </div>
                                    </div>

                                    <h4 class="mt-4 mb-3 text-primary">Bank Details</h4>

                                    <!--Input your bank branch-->
                                    <div class="form-row">
                                        <div class="form-group col-md-6">
                                            <label for="inputBank">Bank Code</label>
                                            <select class="form-control" id="inputBank" required>
                                                <option value="" disabled selected hidden>Select your option</option>
                                                <option value="cimb">CIMB Bank</option>
                                                <option value="bankIslam">Bank Islam</option>
                                            </select>
                                            <div class="valid-feedback">
                                                Looks good!
                                            </div>
                                            <div class="invalid-feedback">
                                                Please select a bank.
                                            </div>
                                        </div>

                                        <!--Input your bank account number-->
                                        <div class="form-group col-md-6">
                                            <label for="inputBankAcc">Bank Account</label>
                                            <input type="text" class="form-control" id="inputBankAcc"
                                                   placeholder="Bank Account Number"
                                                   required>
                                            <div class="valid-feedback">
                                                Looks good!
                                            </div>
                                            <div class="invalid-feedback">
                                                Please enter your bank account number.
                                            </div>
                                        </div>
                                    </div>

                                    <button type="submit" class="btn btn-primary ml-3 my-2 px-4 float-right"
                                            id="action">
                                        Save Details
                                    </button>

                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    <?php else : include '../src/templates/acc_req.php'; endif; ?>
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
                    <p>Are you sure want you want to delete your account? This action cannot be undone and
                        you will not be able to recover any data.</p>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-primary mr-2" data-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Delete</button>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="modal-discard-changes" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title text-primary">Discard all changes?</h5>
                    <button type="button" class="close" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>

                <div class="modal-body text-secondary">
                    <p>Do you really want to discard all recent changes? This action cannot be undone.</p>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-primary mr-2" data-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Discard</button>
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
                    <form id="passForm">

                        <div class="form-group">
                            <label for="currentPass">Current Password</label>
                            <input type="password" class="form-control" id="currentPass"
                                   placeholder="Current Password" required>
                        </div>

                        <div class="form-group">
                            <label for="newPass">New Password</label>
                            <input type="password" class="form-control" id="newPass"
                                   placeholder="New Password" required>
                        </div>

                        <div class="form-group">
                            <label for="retypePass">Current Password</label>
                            <input type="password" class="form-control" id="retypePass"
                                   placeholder="Retype New Password" required>
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-outline-primary" data-dismiss="modal">
                                Cancel
                            </button>
                            <button type="submit" class="btn btn-primary" data-dismiss="modal">Save Changes</button>
                        </div>
                    </form>

                </div>

            </div>
        </div>
    </div>
    <div class="modal fade" id="modal-user-details-saved" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title text-primary">Awesome!</h5>
                    <button type="button" class="close" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>

                <div class="modal-body text-secondary">
                    <p>Your student account had been set up. You are now able to enjoy the full features of
                        the system.</p>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-primary mr-2" data-dismiss="modal">Back to Home
                    </button>
                    <button type="button" class="btn btn-primary" data-dismiss="modal">Done</button>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="modal-user-details-updated" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title text-primary">Awesome!</h5>
                    <button type="button" class="close" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>

                <div class="modal-body text-secondary">
                    <p>Your student details has been updated.</p>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-primary mr-2" data-dismiss="modal">Back to Home
                    </button>
                    <button type="button" class="btn btn-primary" data-dismiss="modal">Done</button>
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

<?php include '../src/templates/footer.php' ?>

