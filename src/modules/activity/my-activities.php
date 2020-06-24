<?php
redirect_if_profile_incomplete();
include '../src/templates/header.php';
include '../src/templates/navbar.php';
include '../src/models/UserActivityModel.php';


if (isset($_SESSION['user_id'])) {
    // user is logged in, load user's activities from db
    $db = new Database(DATABASE_NAME, DATABASE_USERNAME, DATABASE_PASSWORD);
    $server_err = false;

    try {
        $user_activity_model = new UserActivityModel($db->getConnection());
        $data = $user_activity_model->getUserAllActivity($_SESSION['user_id']);

    } catch (PDOException $exception) {
        $server_err = true;
    }

    var_dump($data);
}
?>

<main class="container">
    <?php if (isset($_SESSION['user_id'])) : ?>
        <?php if (!$server_err) : ?>
            <h2>My registered activities</h2>
            <div class="row">


            </div>
        <?php else: include '../src/templates/server_err.php'; endif; ?>
    <?php else : include '../src/templates/acc_req.php'; endif; ?>
</main>

<?php include '../src/templates/modals.php' ?>

<script src="/assets/js/vendor/jquery-3.5.1.js"></script>
<script src="/assets/js/vendor/bootstrap.bundle.js"></script>
<script src="/assets/js/core.js"></script>

<?php include '../src/templates/footer.php' ?>

