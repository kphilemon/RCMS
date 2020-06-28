<?php
redirect_if_profile_incomplete();
include '../src/templates/header.php';
include '../src/templates/navbar.php';
include '../src/models/ActivityModel.php';
include '../src/models/UserActivityModel.php';

$matches = [];
preg_match('/^\/activities\/(\w+)\/?$/i', $_SERVER['REQUEST_URI'], $matches);
$activity_id = intval($matches[1]);
$not_found = false;

if ($activity_id != 0) {
    $db = new Database(DATABASE_NAME, DATABASE_USERNAME, DATABASE_PASSWORD);
    $server_err = false;
    $record = [];

    // get the activity first
    try {
        $activity_model = new ActivityModel($db->getConnection());
        $record = $activity_model->getById($activity_id);
        if (empty($record)) {
            $not_found = true;
        }
    } catch (PDOException $exception) {
        $server_err = true;
    }

    // record for activity_id found, proceed with other operations
    if (!$not_found && !$server_err) {

        // get other available activities for display
        $other_activities = [];
        try {
            $other_activities = $activity_model->getAllExceptId($activity_id);
        } catch (PDOException $exception) {
            // ignore
        }

        // check if user registered if logged in
        if (isset($_SESSION['user_id'])) {
            $registered = false;
            try {
                $user_activity_model = new UserActivityModel($db->getConnection());
                $registered = $user_activity_model->checkIfUserRegistered($_SESSION['user_id'], $activity_id);
            } catch (PDOException $exception) {
                // ignore
            }

            // check if form was submitted
            if (isset($_POST['action'])) {
                $alert_type = 'info';
                $alert_message = '';

                // check registration deadline
                if (date("Y-m-d H:i:s") < $record['registration_deadline']) {
                    if ($_POST['action'] == 'register') {
                        if (!$registered) {
                            try {
                                $success = $user_activity_model->registerActivity($_SESSION['user_id'], $activity_id);
                                if ($success) {
                                    $registered = true;
                                    $alert_message = 'Registration successful. Check out your registered activities at <a
                            href="/my-activities" class="alert-link">My Activities</a>.';
                                } else {
                                    $alert_type = 'warning';
                                    $alert_message = 'Registration failed. Please try again.';
                                }
                            } catch (PDOException $exception) {
                                $alert_type = 'warning';
                                $alert_message = 'Registration failed. Please try again.';
                            }
                        } else {
                            $alert_message = 'You have registered for this activity';
                        }
                    } elseif ($_POST['action'] == 'unregister') {
                        if ($registered) {
                            try {
                                $success = $user_activity_model->unregisterActivity($_SESSION['user_id'], $activity_id);
                                if ($success) {
                                    $registered = false;
                                    $alert_message = 'You have successfully unregistered from this activity';
                                } else {
                                    $alert_type = 'warning';
                                    $alert_message = 'Registration cancellation failed. Please try again.';
                                }
                            } catch (PDOException $exception) {
                                $alert_type = 'warning';
                                $alert_message = 'Registration cancellation failed. Please try again.';
                            }
                        } else {
                            $alert_message = 'You have unregistered from this activity';
                        }
                    }
                } else {
                    $alert_type = 'warning';
                    $alert_message = 'Registration for this activity is closed. You can no longer register nor cancel your registration';
                }
            }
        }
    }
} else {
    $not_found = true;
}
?>

<main class="container">
    <?php if (!$not_found) : ?>
        <?php if (!$server_err) : ?>

            <?php if (date("Y-m-d H:i:s") >= $record['activity_date']): ?>
                <div class="alert alert-info alert-dismissible fade show" role="alert">
                    This activity has ended.
                    <button type="button" class="close" data-dismiss="alert">
                        <span>&times;</span>
                    </button>
                </div>
            <?php elseif (date("Y-m-d H:i:s") >= $record['registration_deadline']): ?>
                <div class="alert alert-info alert-dismissible fade show" role="alert">
                    Registration for this activity is closed.
                    <button type="button" class="close" data-dismiss="alert">
                        <span>&times;</span>
                    </button>
                </div>
            <?php endif; ?>

            <?php if (isset($_SESSION['user_id']) && isset($_POST['action'])): ?>
                <div class="alert alert-<?= $alert_type ?> alert-dismissible fade show" role="alert">
                    <?= $alert_message ?>
                    <button type="button" class="close" data-dismiss="alert">
                        <span>&times;</span>
                    </button>
                </div>
            <?php endif; ?>

            <div class="row">
                <div class="col-lg-8">
                    <div class="card sticky-top">
                        <div class="position-relative">
                            <img class="card-img-top card-img-300" src="<?= $record['img'] ?>">
                            <?php if (isset($_SESSION['user_id']) && $registered): ?>
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
                                        <h4 class="text-primary card-title"><?= $record['name'] ?></h4>
                                        <h5 class="text-primary-purple card-subtitle"><?= date('D, d F Y, h:i A ', strtotime($record['activity_date'])) ?></h5>
                                        <h5 class="text-primary mt-2"><?= $record['venue'] ?></h5>
                                        </p>
                                    </div>

                                    <div class="col-md-4 pt-3 pt-md-0">

                                        <?php //activity not ended yet ?>
                                        <?php if (date("Y-m-d H:i:s") < $record['activity_date']): ?>
                                            <div title="Add to Calendar"
                                                 class="btn btn-outline-primary w-100 mb-3 addeventatc">
                                                Add to Calendar
                                                <span class="start d-none"><?= $record['activity_date'] ?></span>
                                                <span class="title d-none"><?= $record['name'] ?></span>
                                                <span class="location d-none"><?= $record['venue'] ?></span>
                                            </div>

                                            <?php // registration not ended ?>
                                            <?php if (date("Y-m-d H:i:s") < $record['registration_deadline']): ?>
                                                <?php if (isset($_SESSION['user_id'])) : ?>

                                                    <?php if ($registered) : ?>
                                                        <button type="button" class="btn btn-outline-primary w-100"
                                                                data-toggle="modal" data-target="#modal-unregister">
                                                            Cancel Registration
                                                        </button>
                                                    <?php else: ?>
                                                        <form action="" method="post">
                                                            <input type="hidden" name="action" value="register"/>
                                                            <button type="submit" class="btn btn-primary w-100">
                                                                Register
                                                            </button>
                                                        </form>
                                                    <?php endif; ?>

                                                <?php else: ?>

                                                    <button type="button" class="btn btn-primary w-100"
                                                            data-toggle="modal" data-target="#modal-acc-req">
                                                        Register
                                                    </button>

                                                <?php endif; ?>
                                            <?php endif; ?>
                                        <?php endif; ?>

                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col">
                                        <p class="text-secondary"><?= $record['description'] ?></p>
                                        <h6 class="text-primary mt-3 mb-4">Registration
                                            Deadline: <?= date('d F Y, h:i A ', strtotime($record['registration_deadline'])) ?></h6>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4 mt-5 mt-lg-2">
                    <h6 class="text-secondary text-lg-left text-center">Other activities to check out</h6>

                    <?php if (!empty($other_activities)): ?>
                        <?php foreach ($other_activities as $activity): ?>
                            <div class="card raised-card flex-row mt-4">
                                <img class="card-img-left" src="<?= $activity['img'] ?>">
                                <div class="card-body details-sm">
                                    <h6 class="card-title text-truncate text-primary-purple "><?= date('d F Y, h:i A ', strtotime($activity['activity_date'])) ?></h6>
                                    <h5 class="card-subtitle text-truncate text-primary"><?= $activity['name'] ?></h5>
                                </div>
                                <a href="<?= '/activities/' . $activity['id'] ?>" class="stretched-link"></a>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p class="text-center text-secondary my-5">No other activities found.</p>
                    <?php endif; ?>

                </div>
            </div>

            <?php if (isset($_SESSION['user_id']) && $registered): ?>
                <div class="modal fade" id="modal-unregister" tabindex="-1">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">

                            <div class="modal-header">
                                <h5 class="modal-title text-primary">Confirmation</h5>
                                <button type="button" class="close" data-dismiss="modal">
                                    <span>&times;</span>
                                </button>
                            </div>

                            <div class="modal-body text-secondary">
                                <p>Warning: You are cancelling your registration for <b><?= $record['name'] ?></b>. Are you sure?</p>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-outline-primary mr-2" data-dismiss="modal">No, do not
                                    cancel
                                </button>
                                <form action="" method="post">
                                    <input type="hidden" name="action" value="unregister"/>
                                    <button type="submit" class="btn btn-danger">
                                        Yes, cancel registration
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        <?php else: include '../src/templates/server_err.php'; endif; ?>
    <?php else : include '../src/templates/404.php'; endif; ?>
</main>

<?php include '../src/templates/modals.php' ?>

<script src="/assets/js/vendor/jquery-3.5.1.js"></script>
<script src="/assets/js/vendor/bootstrap.bundle.js"></script>
<script src="https://addevent.com/libs/atc/1.6.1/atc.min.js"></script>
<script src="/assets/js/core.js"></script>
<script src="/assets/js/activity-details.js"></script>

<?php include '../src/templates/footer.php' ?>
