<?php
redirect_if_profile_incomplete();
include '../src/templates/header.php';
include '../src/templates/navbar.php';

if (isset($_SESSION['user_id'])) {
    // user is logged in, load user's activities from db
}
?>

<main class="container">
    <?php if (isset($_SESSION['user_id'])) : ?>

    <?php else : include '../src/templates/acc_req.php'; endif; ?>
</main>

<?php include '../src/templates/modals.php' ?>

<script src="/assets/js/vendor/jquery-3.5.1.js"></script>
<script src="/assets/js/vendor/bootstrap.bundle.js"></script>
<script src="/assets/js/utility.js"></script>
<script src="/assets/js/core.js"></script>

<?php include '../src/templates/footer.php'?>

