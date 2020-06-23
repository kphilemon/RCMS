<?php
redirect_if_profile_incomplete();
include '../src/templates/header.php';
include '../src/templates/navbar.php';
include '../src/models/AccommodationModel.php';

if (isset($_SESSION['user_id'])) {

    $db = new Database(DATABASE_NAME, DATABASE_USERNAME, DATABASE_PASSWORD);
    $server_err = false;

    try {
        $model = new AccommodationModel($db->getConnection());
        $records = $model->getAllByUserId($_SESSION['user_id']);
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
                <h5 class="text-primary m-0">Apply for accommodation at University of Malaya</h5>
                <p class="text-secondary m-0">Submit, view, update or cancel your accommodation application all in one
                    place.</p>
            </div>

            <div class="card p-4">
                <div>
                    <a href="accommodation/new" type="button" class="btn btn-primary float-none d-block
                   float-md-right px-4 mb-4 mb-md-0">Submit New</a>

                    <table class="table table-responsive-md table-hover w-100 py-3" id="table">
                        <thead>
                        <tr>
                            <th>No.</th>
                            <th>Application ID</th>
                            <th>Check-in</th>
                            <th>Check-out</th>
                            <th>College No.</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($records as $record): ?>
                            <tr class="clickable">
                                <td></td>
                                <td><?= sprintf( '%04d', $record['id'] ) ?></td>
                                <td><?= $record['check_in_date'] ?></td>
                                <td><?= $record['check_out_date'] ?></td>
                                <td><?= $record['college_id'] ?></td>
                                <td><?= $record['status'] ?></td>
                                <td></td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        <?php else: include '../src/templates/server_err.php'; endif; ?>
    <?php else : include '../src/templates/acc_req.php'; endif; ?>
</main>

<?php include '../src/templates/modals.php' ?>

<script src="/assets/js/vendor/jquery-3.5.1.js"></script>
<script src="/assets/js/vendor/bootstrap.bundle.js"></script>
<script src="/assets/js/vendor/datatables.js"></script>
<script src="/assets/js/utility.js"></script>
<script src="/assets/js/core.js"></script>
<script src="/assets/js/accommodation.js"></script>

<?php include '../src/templates/footer.php' ?>

