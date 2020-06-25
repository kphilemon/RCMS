<?php
redirect_if_profile_incomplete();
include '../src/templates/header.php';
include '../src/templates/navbar.php';
include '../src/models/IssueModel.php';

if (isset($_SESSION['user_id'])) {

    $db = new Database(DATABASE_NAME, DATABASE_USERNAME, DATABASE_PASSWORD);
    $server_err = false;

    try {
        $model = new IssueModel($db->getConnection());
        $records = $model->getReportsByUserId($_SESSION['user_id']);
    } catch (PDOException $exception) {
        $server_err = true;
    }

    $db->closeConnection();
}
?>

<main class="container">
    <?php if (isset($_SESSION['user_id'])) : ?>
        <?php if (!$server_err) : ?>
            <div class="card p-4 mb-4">
                <h5 class="text-primary m-0">Report and track issues at KK<?= $_SESSION['user_college_id'] ?></h5>
                <p class="text-secondary m-0">Add, edit or delete issues found at your residential college.</p>
            </div>

            <div class="card p-4">
                <div class="table-responsive">
                    <a href="issues/new" type="button" class="btn btn-primary float-none d-block float-md-right px-4 mb-4 mb-md-0">Report New</a>

                    <table class="table header-border table-hover w-100 py-3 table-responsive-sm" id="table">
                        <thead>
                        <tr>
                            <th>No.</th>
                            <th>Report ID</th>
                            <th>Problem Type</th>
                            <th>Problem Location</th>
                            <th>Report Date-Time</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                        </thead>
                        <tbody id="myTable" onload="editable()">
                        <?php foreach ($records as $record): ?>
                            <tr>
                                <td></td>
                                <td><?= sprintf( '%04d', $record['id'] ) ?></td>
                                <td><?= $record['type'] ?></td>
                                <td><?= $record['location'] ?></td>
                                <td><?= $record['created_at'] ?></td>
                                <td><?= $record['status'] ?></td>
                                <td></td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        <?php else : include '../src/templates/server_err.php'; endif; ?>
    <?php else : include '../src/templates/acc_req.php'; endif; ?>
</main>

<?php include '../src/templates/modals.php' ?>

<script src="/assets/js/vendor/jquery-3.5.1.js"></script>
<script src="/assets/js/vendor/bootstrap.bundle.js"></script>
<script src="/assets/js/vendor/datatables.js"></script>
<script src="/assets/js/core.js"></script>
<script src="/assets/js/issues.js"></script>

<?php include '../src/templates/footer.php' ?>

