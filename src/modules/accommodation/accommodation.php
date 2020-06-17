<?php
redirect_if_profile_incomplete();
include '../src/templates/header.php';
include '../src/templates/navbar.php';

if (isset($_SESSION['user_id'])) {
    // user is logged in, load accommodation from db
}
?>

<main class="container">
    <?php if (isset($_SESSION['user_id'])) : ?>
        <div class="card p-4 mb-4">
            <h5 class="text-primary m-0">Apply for accommodation at KK<?= $_SESSION['user_college_id'] ?></h5>
            <p class="text-secondary m-0">Submit, view, update or cancel your accommodation application all in one
                place.</p>
        </div>

        <div class="card p-4">
            <div class="table-responsive">
                <button type="button" class="btn btn-primary" id="submit" style="float: right">Submit New</button>

                <table class="table header-border table-responsive-sm" id="table">
                    <thead>
                    <tr>
                        <th>No.</th>
                        <th>Application ID</th>
                        <th>Start date</th>
                        <th>End date</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                    </thead>
                    <tbody id="myTable">
                    <tr>
                        <td></td>
                        <td>2005081456</td>
                        <td>2 FEB 2020</td>
                        <td>27 FEB 2020</td>
                        <td><span class="badge badge-failure">Rejected</span></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td></td>
                        <td>2005091127</td>
                        <td>11 MARCH 2020</td>
                        <td>31 MARCH 2020</td>
                        <td><span class="badge badge-success">Approved</span></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td></td>
                        <td>2003010922</td>
                        <td>11 MARCH 2020</td>
                        <td>31 MARCH 2020</td>
                        <td><span class="badge badge-pending">Submitted</span></td>
                        <td></td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>

    <?php else : include '../src/templates/acc_req.php'; endif; ?>
</main>

<?php include '../src/templates/modals.php' ?>

<script src="/assets/js/vendor/jquery-3.5.1.js"></script>
<script src="/assets/js/vendor/bootstrap.bundle.js"></script>
<script src="/assets/js/utility.js"></script>
<script src="/assets/js/core.js"></script>
<script src="/assets/js/accommodation.js"></script>

<?php include '../src/templates/footer.php'?>

