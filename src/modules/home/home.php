<?php
redirect_if_profile_incomplete();
include '../src/templates/header.php';
include '../src/templates/navbar.php';

$carousel_images = array_diff(scandir(CAROUSEL_IMAGES_PATH, SCANDIR_SORT_DESCENDING), array('.', '..'));

// read from db and populate these arrays
$all_activities = [
    ['name' => 'Swimming',
        'img' => '/assets/img/activities/activity-0000.jpg',
        'date' => '20 May, 2020',
        'id' => '0000'],
    ['name' => 'Dancing',
        'img' => '/assets/img/activities/activity-0001.jpg',
        'date' => '20 May, 2020',
        'id' => '0001'],
    ['name' => 'Cheer leading',
        'img' => '/assets/img/activities/activity-0002.jpg',
        'date' => '20 May, 2020',
        'id' => '0002'],
    ['name' => 'Basketball',
        'img' => '/assets/img/activities/activity-0003.jpg',
        'date' => '20 May, 2020',
        'id' => '0003'],

];
$this_weekend_activities = [
    ['name' => 'Swimming',
        'img' => '/assets/img/activities/activity-0000.jpg',
        'date' => '20 May, 2020',
        'id' => '0000'],
    ['name' => 'Dancing',
        'img' => '/assets/img/activities/activity-0001.jpg',
        'date' => '20 May, 2020',
        'id' => '0001'],
    ['name' => 'Cheer leading',
        'img' => '/assets/img/activities/activity-0002.jpg',
        'date' => '20 May, 2020',
        'id' => '0002'],
];
$compulsory_activities = [
    ['name' => 'Swimming',
        'img' => '/assets/img/activities/activity-0000.jpg',
        'date' => '20 May, 2020',
        'id' => '0000'],
    ['name' => 'Basketball',
        'img' => '/assets/img/activities/activity-0003.jpg',
        'date' => '20 May, 2020',
        'id' => '0003']
];
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
        <a class="nav-item nav-link" href="#this-weekend" data-toggle="tab">This Weekend</a>
        <a class="nav-item nav-link" href="#compulsory" data-toggle="tab">Compulsory</a>
    </nav>

    <div class="tab-content">
        <div class="tab-pane fade show active" id="all">
            <div class="row">

                <?php foreach ($all_activities as $activity): ?>
                    <div class="col-xl-3 col-lg-4 col-sm-6 mb-4">
                        <div class="card raised-card">
                            <img class="card-img-top card-img-150" src="<?= $activity['img'] ?>">
                            <div class="card-body">
                                <h6 class="card-subtitle text-primary-purple mt-1"><?= $activity['date'] ?></h6>
                                <div class="details-big text-primary my-2">
                                    <h5 class="card-title"><?= $activity['name'] ?></h5>
                                </div>
                            </div>
                            <a href="<?= '/activities/' . $activity['id'] ?>" class="stretched-link"></a>
                        </div>
                    </div>
                <?php endforeach; ?>

            </div>
        </div>

        <div class="tab-pane fade" id="this-weekend">
            <div class="row">

                <?php foreach ($this_weekend_activities as $activity): ?>
                    <div class="col-xl-3 col-lg-4 col-sm-6 mb-4">
                        <div class="card raised-card">
                            <img class="card-img-top card-img-150" src="<?= $activity['img'] ?>">
                            <div class="card-body">
                                <h6 class="card-subtitle text-primary-purple mt-1"><?= $activity['date'] ?></h6>
                                <div class="details-big text-primary my-2">
                                    <h5 class="card-title"><?= $activity['name'] ?></h5>
                                </div>
                            </div>
                            <a href="<?= '/activities/' . $activity['id'] ?>" class="stretched-link"></a>
                        </div>
                    </div>
                <?php endforeach; ?>

            </div>
        </div>

        <div class="tab-pane fade " id="compulsory">
            <div class="row">

                <?php foreach ($compulsory_activities as $activity): ?>
                    <div class="col-xl-3 col-lg-4 col-sm-6 mb-4">
                        <div class="card raised-card">
                            <img class="card-img-top card-img-150" src="<?= $activity['img'] ?>">
                            <div class="card-body">
                                <h6 class="card-subtitle text-primary-purple mt-1"><?= $activity['date'] ?></h6>
                                <div class="details-big text-primary my-2">
                                    <h5 class="card-title"><?= $activity['name'] ?></h5>
                                </div>
                            </div>
                            <a href="<?= '/activities/' . $activity['id'] ?>" class="stretched-link"></a>
                        </div>
                    </div>
                <?php endforeach; ?>

            </div>
        </div>
    </div>
</main>

<?php include '../src/templates/modals.php' ?>

<script src="/assets/js/vendor/jquery-3.5.1.js"></script>
<script src="/assets/js/vendor/bootstrap.bundle.js"></script>
<script src="/assets/js/utility.js"></script>
<script src="/assets/js/core.js"></script>

<?php include '../src/templates/footer.php'?>

