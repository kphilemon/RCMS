<?php
redirect_if_profile_incomplete();
include '../src/templates/header.php';
include '../src/templates/navbar.php';
include '../src/models/UserActivityModel.php';


if (isset($_SESSION['user_id'])) {
    // user is logged in, load user's activities from db
    $db = new Database(DATABASE_NAME, DATABASE_USERNAME, DATABASE_PASSWORD);
    $server_err = false;
    $no_activities_registered = false;

    try {
        $user_activity_model = new UserActivityModel($db->getConnection());
        $all_activities = $user_activity_model->getUserAllActivity($_SESSION['user_id']);

    } catch (PDOException $exception) {
        $server_err = true;
    }

    if (!empty($all_activities)) {
        $this_week = [];
        $past = [];
        $next_monday = date('Y-m-d', strtotime('next Monday'));
        $today = date('Y-m-d');

        foreach ($all_activities as $key => $value) {
            if ($value['activity_date'] < $next_monday) {
                $this_week[$key] = $value;
                unset($all_activities[$key]);
            }

            if ($value['activity_date'] < $today) {
                $today[$key] = $value;
                unset($all_activities[$key]);
            }
        }

    } else {
        $no_activities_registered = true;
    }
}

?>

<main class="container">
    <?php if (isset($_SESSION['user_id'])) : ?>
        <?php if (!$server_err) : ?>
            <?php if (!$no_activities_registered): ?>
                <h5 class="mt-4">This week's registered activities</h5>
                <?php if (!empty($this_week)): ?>
                    <div class="row">
                        <?php foreach ($this_week as $activity) : ?>
                            <div class="col-lg-4">
                                <div class="card raised-card flex-row mt-4">
                                    <img class="card-img-left" src="<?= $activity['img'] ?>">
                                    <div class="card-body details-sm">
                                        <h6 class="card-title text-truncate text-primary-purple "><?= date("d F Y ", strtotime($activity['activity_date'])) ?></h6>
                                        <h5 class="card-subtitle text-truncate text-primary"><?= $activity['name'] ?></h5>
                                    </div>
                                    <a href="<?= '/activities/' . $activity['activity_id'] ?>"
                                       class="stretched-link"></a>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <div>
                        <p class="my-5">You don't have upcoming activities for this week.</p>
                    </div>
                <?php endif; ?>

                <?php if (!empty($all_activities)): ?>

                    <hr class="mt-5 mb-4">
                    <h5>Other registered activities</h5>
                    <?php foreach ($all_activities as $activity) : ?>
                        <div class="row">
                            <div class="col-lg-4">
                                <div class="card raised-card flex-row mt-4">
                                    <img class="card-img-left" src="<?= $activity['img'] ?>">
                                    <div class="card-body details-sm">
                                        <h6 class="card-title text-truncate text-primary-purple "><?= date("d F Y ", strtotime($activity['activity_date'])) ?></h6>
                                        <h5 class="card-subtitle text-truncate text-primary"><?= $activity['name'] ?></h5>
                                    </div>
                                    <a href="<?= '/activities/' . $activity['activity_id'] ?>"
                                       class="stretched-link"></a>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>

                <?php if (!empty($past)): ?>

                    <hr class="mt-5 mb-4">
                    <h5>Past activities</h5>
                    <?php foreach ($past as $activity) : ?>
                        <div class="row">
                            <div class="col-lg-4">
                                <div class="card raised-card flex-row mt-4">
                                    <img class="card-img-left" src="<?= $activity['img'] ?>">
                                    <div class="card-body details-sm">
                                        <h6 class="card-title text-truncate text-primary-purple "><?= date("d F Y ", strtotime($activity['activity_date'])) ?></h6>
                                        <h5 class="card-subtitle text-truncate text-primary"><?= $activity['name'] ?></h5>
                                    </div>
                                    <a href="<?= '/activities/' . $activity['activity_id'] ?>"
                                       class="stretched-link"></a>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            <?php else: ?>
                <div class="row">
                    <div class="col pt-5 text-center">
                        <img class="card-img-150 mt-5" src="/assets/img/no_activities.svg"
                             alt="No activities registered">
                        <h6 class="text-secondary my-5 mx-2">It seems like you have no registered activities yet.<br>Stay
                            active and join the campus community now!</h6>
                        <a href="/home" class="btn btn-outline-primary px-3" type="button">Show me the activities!</a>
                    </div>
                </div>

            <?php endif; ?>
        <?php else: include '../src/templates/server_err.php'; endif; ?>
    <?php else : include '../src/templates/acc_req.php'; endif; ?>
</main>

<?php include '../src/templates/modals.php' ?>

<script src="/assets/js/vendor/jquery-3.5.1.js"></script>
<script src="/assets/js/vendor/bootstrap.bundle.js"></script>
<script src="/assets/js/core.js"></script>

<?php include '../src/templates/footer.php' ?>

