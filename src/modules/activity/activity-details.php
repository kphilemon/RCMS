<?php
redirect_if_profile_incomplete();
include '../src/templates/header.php';
include '../src/templates/navbar.php';
include '../src/models/UserActivityModel.php';
include '../src/models/ActivityModel.php';

$db = new Database(DATABASE_NAME, DATABASE_USERNAME, DATABASE_PASSWORD);
$user_activity_model = new UserActivityModel($db->getConnection());


$matches = [];
preg_match('/^\/activities\/(\w+)\/?$/i', $_SERVER['REQUEST_URI'], $matches);
$activity_id = $matches[1];

// get activity from database by $activity_id
$curr_activities = $user_activity_model->getActivityById("$activity_id");
$other_activities = $user_activity_model->getActivityExceptId("$activity_id");

// if found, check if user has registered
$found = false;
// if registered, set to true
$registered = false;


// if activity found, set found to true
if (empty($curr_activities)==false){
    $found=true;

    //if logged in
    if (isset($_SESSION['user_id'])) {
        $studentid = $_SESSION['user_id'];

        //if logged in and registered , set register to true
        if ($user_activity_model->checkRegisterActivity("$studentid","$activity_id")==true){
            $registered = true;
        }
    }
}

// set to true if any of the db actions failed
$server_err = false;
$registration_err = false;

if ($found && isset($_POST['action'])) { //check if form was submitted
    if ($_POST['action'] == 'register' && !$registered) {

        if ($user_activity_model->registerActivity($studentid , $activity_id) == true){
            // if success, set registered to true
            $registered = true;
            $registration_err = false;
        }else{
            $registration_err = true;
        }

    } elseif ($_POST['action'] == 'unregister' && $registered) {

        // perform db operations
        if ($user_activity_model->unregisterActivity($studentid , $activity_id) == true){
            // if success, set registered to false
            $registered = false;
            $registration_err = false;
        }else{
            $registration_err = true;
        }
    }
}
?>

<main class="container">
    <?php if ($server_err) : include '../src/templates/server_err.php'; ?>
    <?php elseif ($found) : ?>

        <?php if (isset($_POST['action'])): ?>
            <?php if ($registration_err): ?>
                <div class="alert alert-warning alert-dismissible fade show" role="alert">
                    Register/Unregister failed. Please try again.
                    <button type="button" class="close" data-dismiss="alert">
                        <span>&times;</span>
                    </button>
                </div>
            <?php elseif ($_POST['action']=='register'): ?>
                <!-- a different success message if the action is cancel registration (unregister) -->
                <div class="alert alert-info alert-dismissible fade show" role="alert">
                    Registration successful. Check out your registered activities at <a
                            href="/my-activities" class="alert-link">My Activities</a>.
                    <button type="button" class="close" data-dismiss="alert">
                        <span>&times;</span>
                    </button>
                </div>

            <?php elseif ($_POST['action']=='unregister'): ?>
                <div class="alert alert-info alert-dismissible fade show" role="alert">
                    You have unregistered from this activity.
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
                        <img class="card-img-top card-img-300" src="<?= $curr_activities['img'] ?>">
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
                                    <h4 class="text-primary card-title"><?= $curr_activities['name'] ?></h4>
                                    <h5 class="text-primary-purple card-subtitle"><?=date("D , d F Y , h:i A ", strtotime($curr_activities['activity_date']))?></h5>
                                    <h5 class="text-primary mt-2"><?= $curr_activities['venue'] ?></h5>
                                </div>

                                <div class="col-md-4 pt-3 pt-md-0">
                                    <?php if (isset($_SESSION['user_id'])) : ?>
                                        <div title="Add to Calendar" class="btn btn-outline-primary w-100 mb-3 addeventatc "  >
                                            Add to Calendar
                                            <span class="start"><?= $curr_activities['activity_date'] ?></span>
                                            <span class="title"><?= $curr_activities['name'] ?></span>
                                            <span class="location"><?= $curr_activities['venue'] ?></span>
                                        </div>
                                    <?php else: ?>
                                        <button type="button" class="btn btn-outline-primary w-100 mb-3" data-toggle="modal"
                                                data-target="#modal-acc-req">Add to Calendar
                                        </button>
                                    <?php endif; ?>

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
                                    <p class="text-secondary"><?= $curr_activities['description'] ?></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4 mt-5 mt-lg-2">
                <h6 class="text-secondary text-lg-left text-center">Other activities to check out</h6>

                <?php foreach($other_activities as $activity):
                    ?>

                    <div class="card raised-card flex-row mt-4">
                        <img class="card-img-left" src="<?= $activity['img'] ?>">
                        <div class="card-body details-sm">
                            <h6 class="card-title text-truncate text-primary-purple "><?= date("d F Y ", strtotime($activity['activity_date']))?></h6>
                            <h5 class="card-subtitle text-truncate text-primary"><?= $activity['name'] ?></h5>
                        </div>
                        <a href="<?= '/activities/'.$activity['id']?>" class="stretched-link"></a>
                    </div>
                <?php endforeach; ?>

            </div>
        </div>
    <?php else : include '../src/templates/404.php'; endif; ?>
</main>

<?php include '../src/templates/modals.php' ?>

<script type="text/javascript" src="https://addevent.com/libs/atc/1.6.1/atc.min.js" async defer></script>
<!-- AddEvent Settings -->
<script type="text/javascript">
    window.addeventasync = function(){
        addeventatc.settings({
            appleical  : {show:true, text:"Apple Calendar"},
            google     : {show:true, text:"Google <em>(online)</em>"},
            office365  : {show:true, text:"Office 365 <em>(online)</em>"},
            outlook    : {show:true, text:"Outlook"},
            outlookcom : {show:true, text:"Outlook.com <em>(online)</em>"},
            yahoo      : {show:true, text:"Yahoo <em>(online)</em>"}
        });
    };
</script>
<script src="/assets/js/vendor/jquery-3.5.1.js"></script>
<script src="/assets/js/vendor/bootstrap.bundle.js"></script>
<script src="/assets/js/utility.js"></script>
<script src="/assets/js/core.js"></script>

<?php include '../src/templates/footer.php' ?>
