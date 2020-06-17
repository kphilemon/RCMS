<?php
redirect_if_profile_incomplete();
include '../src/templates/header.php';
include '../src/templates/navbar.php';

if (isset($_SESSION['user_id'])) {
    // user is logged in, load issues from db
}

// set to true if any of the db actions failed
$server_err = false;
?>

<main class="container">
    <?php if (isset($_SESSION['user_id'])) : ?>
        <?php if ($server_err) : include '../src/templates/server_err.php'; else: ?>
            <div class="card p-4 mb-4">
                <h5 class="text-primary m-0">Report and track issues at KK<?= $_SESSION['user_college_id'] ?></h5>
                <p class="text-secondary m-0">Add, edit or delete issues found at your residential college.</p>
            </div>

            <div class="card p-4">
                <div class="table-responsive">
                    <button type="button" class="btn btn-primary" id="submit" style="float: right">Submit New</button>

                    <table class="table header-border table-responsive-sm" id="table">
                        <thead>
                        <tr>
                            <th>No.</th>
                            <th>Report ID</th>
                            <th>Problem Category</th>
                            <th>Problem Type</th>
                            <th>Status</th>
                            <th>Report Date-Time</th>
                            <th>Action</th>
                        </tr>
                        </thead>
                        <tbody id="myTable">
                        <tr>
                            <td></td>
                            <td>2005091127</td>
                            <td>Toilet</td>
                            <td>Piping</td>
                            <td><span class="badge badge-pending">Pending</span></td>
                            <td>2020-05-09 11:27</td>
                            <td></td>
                        </tr>
                        <tr>
                            <td></td>
                            <td>2005081456</td>
                            <td>Study Area</td>
                            <td>Internet Connection</td>
                            <td><span class="badge badge-success">Completed</span></td>
                            <td>2020-05-08 14:56</td>
                            <td></td>
                        </tr>
                        <tr>
                            <td></td>
                            <td>2003010922</td>
                            <td>Cafe</td>
                            <td>Cleanliness</td>
                            <td><span class="badge badge-success">Completed</span></td>
                            <td>2020-03-01 09:22</td>
                            <td></td>
                        </tr>
                        <tr>
                            <td></td>
                            <td>1912231732</td>
                            <td>Volleyball Court</td>
                            <td>Facility</td>
                            <td><span class="badge badge-failure">Incompleted</span></td>
                            <td>2019-12-23 17:32</td>
                            <td></td>
                        </tr>
                        <tr>
                            <td></td>
                            <td>1912221127</td>
                            <td>Toilet</td>
                            <td>Piping</td>
                            <td><span class="badge badge-success">Completed</span></td>
                            <td>2019-12-22 11:27</td>
                            <td></td>
                        </tr>
                        <tr>
                            <td></td>
                            <td>1910301456</td>
                            <td>Study Area</td>
                            <td>Internet Connection</td>
                            <td><span class="badge badge-success">Completed</span></td>
                            <td>2019-10-30 14:56</td>
                            <td></td>
                        </tr>
                        <tr>
                            <td></td>
                            <td>1910030922</td>
                            <td>Cafe</td>
                            <td>Cleanliness</td>
                            <td><span class="badge badge-success">Completed</span></td>
                            <td>2019-10-03 09:22</td>
                            <td></td>
                        </tr>
                        <tr>
                            <td></td>
                            <td>1909231732</td>
                            <td>Volleyball Court</td>
                            <td>Facility</td>
                            <td><span class="badge badge-success">Completed</span></td>
                            <td>2019-09-23 17:32</td>
                            <td></td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        <?php endif; ?>
    <?php else : include '../src/templates/acc_req.php'; endif; ?>
</main>

<?php include '../src/templates/modals.php' ?>

<script src="/assets/js/vendor/jquery-3.5.1.js"></script>
<script src="/assets/js/vendor/bootstrap.bundle.js"></script>
<script src="/assets/js/utility.js"></script>
<script src="/assets/js/core.js"></script>
<script src="/assets/js/issues.js"></script>

<?php include '../src/templates/footer.php' ?>

