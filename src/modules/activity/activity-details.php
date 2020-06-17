<?php
redirect_if_profile_incomplete();
include '../src/templates/header.php';
include '../src/templates/navbar.php';

$matches = [];
preg_match('/^\/activities\/(\w+)\/?$/i', $_SERVER['REQUEST_URI'], $matches);
$activity_id = $matches[1];

// get activity from database by $activity_id

$other_activities = [];

// if activity found, set to true
$found = true;

// if found, check if user has registered

// if registered, set to true
$registered = false;

// set to true if any of the db actions failed
$server_err = false;

$registration_err = false;
if ($found && isset($_POST['action'])) { //check if form was submitted
    if ($_POST['action'] == 'register' && !$registered) {

        // perform db operations

        // if success, set registered to true
        $registered = true;

        $registration_err = false;
    } elseif ($_POST['action'] == 'unregister' && $registered) {

        // perform db operations

        // if success, set registered to false
        $registered = false;

        $registration_err = false;
    }
}
?>

<main class="container">
    <?php if ($server_err) : include '../src/templates/server_err.php'; ?>
    <?php elseif ($found) : ?>

        <?php if (isset($_POST['action'])): ?>
            <?php if ($registration_err): ?>
                <div class="alert alert-warning alert-dismissible fade show" role="alert">
                    Registration failed. Please try again.
                    <button type="button" class="close" data-dismiss="alert">
                        <span>&times;</span>
                    </button>
                </div>
            <?php else: ?>
                <!-- a different success message if the action is cancel registration (unregister) -->
                <div class="alert alert-info alert-dismissible fade show" role="alert">
                    Registration successful. Check out your registered activities at <a
                            href="/my-activities" class="alert-link">My Activities</a>.
                    <button type="button" class="close" data-dismiss="alert">
                        <span>&times;</span>
                    </button>
                </div>
            <?php endif; ?>
        <?php endif; ?>

        <div class="row">
            <div class="col-lg-8">
                <div class="card sticky-top">
                    <div class="position-relative">
                        <img class="card-img-top card-img-300" src="/assets/img/activities/activity-0000.jpg">
                        <?php if ($registered): ?>
                            <button type="button" class="btn btn-primary overlay-top-right mt-4 mr-4 "
                                    data-toggle="tooltip"
                                    data-placement="left" title="You are registered for this activity.">Registered
                            </button>
                        <?php endif; ?>
                    </div>

                    <div class="card-body px-2">
                        <div class="container">
                            <div class="row pt-2 pb-3">
                                <div class="col-md-8 m-auto align-items-center ">
                                    <h4 class="text-primary card-title">Swimming Class</h4>
                                    <h5 class="text-primary-purple card-subtitle">Sun, 15 Jun 2020 , 4.00 PM</h5>
                                    <h5 class="text-primary mt-2">Random venue</h5>
                                </div>

                                <div class="col-md-4 pt-3 pt-md-0">
                                    <button type="button" class="btn btn-outline-primary w-100 mb-3">Add to Calendar
                                    </button>
                                    <?php if (isset($_SESSION['user_id'])) : ?>
                                        <form action="" method="post">
                                            <input type="hidden" name="action"
                                                   value="<?= ($registered) ? 'unregister' : 'register' ?>"/>
                                            <button type="submit"
                                                    class="btn <?= ($registered) ? 'btn-outline-primary' : 'btn-primary' ?> w-100">
                                                <?= ($registered) ? 'Cancel Registration' : 'Register' ?>
                                            </button>
                                        </form>
                                    <?php else: ?>
                                        <button type="button" class="btn btn-primary w-100" data-toggle="modal"
                                                data-target="#modal-acc-req">Register
                                        </button>
                                    <?php endif; ?>

                                </div>
                            </div>

                            <div class="row">
                                <div class="col">
                                    <p class="text-secondary">Learn How to Swim even If you Are Just a Beginner.
                                        Swimming Courses Designed For Students.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4 mt-5 mt-lg-2">
                <h6 class="text-secondary text-lg-left text-center">Other activities to check out</h6>

                <?php foreach ($other_activities as $activity): ?>
                    <div class="card raised-card flex-row mt-4">
                        <img class="card-img-left" src="<?= $activity['img'] ?>">
                        <div class="card-body details-sm">
                            <h6 class="card-title text-truncate text-primary-purple "><?= $activity['date'] ?></h6>
                            <h5 class="card-subtitle text-primary"><?= $activity['name'] ?></h5>
                        </div>
                        <a href="<?= '/activities/' . $activity['id'] ?>" class="stretched-link"></a>
                    </div>
                <?php endforeach; ?>

            </div>
        </div>
    <?php else : include '../src/templates/404.php'; endif; ?>
</main>

<?php include '../src/templates/modals.php' ?>

<script src="/assets/js/vendor/jquery-3.5.1.js"></script>
<script src="/assets/js/vendor/bootstrap.bundle.js"></script>
<script src="/assets/js/utility.js"></script>
<script src="/assets/js/core.js"></script>

<?php include '../src/templates/footer.php' ?>
