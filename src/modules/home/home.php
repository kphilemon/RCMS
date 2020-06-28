<?php
redirect_if_profile_incomplete();
include '../src/templates/header.php';
include '../src/templates/navbar.php';
include '../src/models/ActivityModel.php';

$carousel_images = array_diff(scandir(CAROUSEL_IMAGES_PATH, SCANDIR_SORT_DESCENDING), array('.', '..'));

$db = new Database(DATABASE_NAME, DATABASE_USERNAME, DATABASE_PASSWORD);
$server_err = false;

try {
    $activity_model = new ActivityModel($db->getConnection());
    $all_activities = $activity_model->getAll();

} catch (PDOException $exception) {
    $server_err = true;
}

if (!empty($all_activities)) {

    $this_week_activities = [];
    $this_month_activities = [];

    $next_monday = date('Y-m-d', strtotime('monday next week'));
    $next_month_first_day = date('Y-m-d', strtotime('first day of next month'));

    foreach ($all_activities as $activity) {
        if ($activity['activity_date'] < $next_monday){
            array_push($this_week_activities, $activity);
        }

        if ($activity['activity_date'] < $next_month_first_day){
            array_push($this_month_activities, $activity);
        }
    }
}
?>

<main class="container">
    <div id="carousel" class="carousel slide mb-4" data-ride="carousel">
        <!-- The slideshow -->
        <div class="carousel-inner">
            <?php if (!empty($carousel_images)): ?>
                <div class="carousel-item active">
                    <img class="rounded" src="<?= '/assets/img/carousel/' . $carousel_images[0] ?>">
                </div>

                <?php array_shift($carousel_images) ?>
                <?php foreach ($carousel_images as $image): ?>
                    <div class="carousel-item ">
                        <img class="rounded" src="<?= '/assets/img/carousel/' . $image ?>">
                    </div>
                <?php endforeach; ?>

            <?php endif; ?>
        </div>

        <!-- Left and right controls -->
        <a class="carousel-control-prev" href="#carousel" data-slide="prev">
            <span class="carousel-control-prev-icon"></span>
        </a>
        <a class="carousel-control-next" href="#carousel" data-slide="next">
            <span class="carousel-control-next-icon"></span>
        </a>
    </div>

    <nav class="nav nav-tabs mb-4" id="tabs">
        <a class="nav-item nav-link px-4 active" href="#all" data-toggle="tab">All</a>
        <a class="nav-item nav-link" href="#this-week" data-toggle="tab">This Week</a>
        <a class="nav-item nav-link" href="#this-month" data-toggle="tab">This Month</a>
    </nav>

    <div class="tab-content">
        <div class="tab-pane fade show active" id="all">
            <div class="row">

                <?php if (!$server_err): ?>
                    <?php if (!empty($all_activities)): ?>
                        <?php foreach ($all_activities as $activity): ?>
                            <div class="col-xl-3 col-lg-4 col-sm-6 mb-4">
                                <div class="card raised-card">
                                    <img class="card-img-top card-img-150" src="<?= $activity['img'] ?>">
                                    <div class="card-body">
                                        <h6 class="card-subtitle text-primary-purple mt-1"><?= date('D, d F Y', strtotime($activity['activity_date'])) ?></h6>
                                        <h6 class="card-subtitle text-primary-purple mt-1"><?= date('h:i A', strtotime($activity['activity_date'])) ?></h6>
                                        <div class="details-big text-primary my-2">
                                            <h5 class="card-title"><?= $activity['name'] ?></h5>
                                        </div>
                                    </div>
                                    <a href="<?= '/activities/' . $activity['id'] ?>" class="stretched-link"></a>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="col my-5">
                            <p class="text-center">No upcoming activities found.</p>
                        </div>
                    <?php endif; ?>
                <?php else: ?>
                    <div class="col my-5">
                        <p class="text-center">Opps! We are having difficulties loading the activities.<br>Please try
                            again later.</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <div class="tab-pane fade" id="this-week">
            <div class="row">

                <?php if (!$server_err): ?>
                    <?php if (!empty($this_week_activities)): ?>
                        <?php foreach ($this_week_activities as $activity): ?>
                            <div class="col-xl-3 col-lg-4 col-sm-6 mb-4">
                                <div class="card raised-card">
                                    <img class="card-img-top card-img-150" src="<?= $activity['img'] ?>">
                                    <div class="card-body">
                                        <h6 class="card-subtitle text-primary-purple mt-1"><?= date('D, d F Y', strtotime($activity['activity_date'])) ?></h6>
                                        <h6 class="card-subtitle text-primary-purple mt-1"><?= date('h:i A', strtotime($activity['activity_date'])) ?></h6>
                                        <div class="details-big text-primary my-2">
                                            <h5 class="card-title"><?= $activity['name'] ?></h5>
                                        </div>
                                    </div>
                                    <a href="<?= '/activities/' . $activity['id'] ?>" class="stretched-link"></a>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="col my-5">
                            <p class="text-center">No upcoming activities found for this week.</p>
                        </div>
                    <?php endif; ?>
                <?php else: ?>
                    <div class="col my-5">
                        <p class="text-center">Opps! We are having difficulties loading the activities.<br>Please try
                            again later.</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <div class="tab-pane fade " id="this-month">
            <div class="row">
                <?php if (!$server_err): ?>
                    <?php if (!empty($this_month_activities)): ?>
                        <?php foreach ($this_month_activities as $activity): ?>
                            <div class="col-xl-3 col-lg-4 col-sm-6 mb-4">
                                <div class="card raised-card">
                                    <img class="card-img-top card-img-150" src="<?= $activity['img'] ?>">
                                    <div class="card-body">
                                        <h6 class="card-subtitle text-primary-purple mt-1"><?= date('D, d F Y', strtotime($activity['activity_date'])) ?></h6>
                                        <h6 class="card-subtitle text-primary-purple mt-1"><?= date('h:i A', strtotime($activity['activity_date'])) ?></h6>
                                        <div class="details-big text-primary my-2">
                                            <h5 class="card-title"><?= $activity['name'] ?></h5>
                                        </div>
                                    </div>
                                    <a href="<?= '/activities/' . $activity['id'] ?>" class="stretched-link"></a>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="col my-5">
                            <p class="text-center">No upcoming activities found for this month.</p>
                        </div>
                    <?php endif; ?>
                <?php else: ?>
                    <div class="col my-5">
                        <p class="text-center">Opps! We are having difficulties loading the activities.<br>Please try
                            again later.</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</main>

<?php include '../src/templates/modals.php' ?>

<script src="/assets/js/vendor/jquery-3.5.1.js"></script>
<script src="/assets/js/vendor/bootstrap.bundle.js"></script>
<script src="/assets/js/core.js"></script>

<?php include '../src/templates/footer.php' ?>

