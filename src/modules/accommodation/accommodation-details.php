<?php
include '../src/templates/header.php';
include '../src/templates/navbar.php';
include '../src/models/AccommodationModel.php';


//<input type="hidden" name="del-prev" value="0"/>

if (isset($_SESSION['user_id'])) {
    $db = new Database(DATABASE_NAME, DATABASE_USERNAME, DATABASE_PASSWORD);
    $server_err = false;
    $record_found = true;
    $record = [];
    $matches = [];
    preg_match('/^\/accommodation\/(\d+|new)\/?$/i', $_SERVER['REQUEST_URI'], $matches);

    if (strtolower($matches[1]) === 'new'){

    } else {

    }


    $accommodation_id = intval($matches[1]);


    try {
        // try to get the record with user id and accommodation id
        $model = new AccommodationModel($db->getConnection());
        $record = $model->getByIdAndUserId($_SESSION['user_id'], $accommodation_id);
        if (!empty($record)) {
            $record_found = true;
        }
    } catch (PDOException $exception) {
        $server_err = true;
    }

    $db->closeConnection();
}
?>

<main class="container">
    <?php if (isset($_SESSION['user_id'])) : ?>
        <?php if (!$server_err) : ?>
            <?php if ($record_found) : ?>
                <div class="card p-4 mb-4">
                    <h5 class="text-primary m-0">Accommodation Application #<?= sprintf('%04d', $accommodation_id) ?></h5>
                    <p class="text-secondary m-0">Submitted at </p>
                </div>
                <div class="row">
                    <div class="col-lg-9 mx-auto">
                        <div class="card">
                            <div class="card-body">
                                <div class="container my-2">
                                    <form id="details" action="#">
                                        <h4 class="mb-3 text-primary">Student Details</h4>

                                        <div class="form-group">
                                            <label for="name">Name</label>
                                            <input type="text" class="form-control" id="name" placeholder="Name" required>
                                        </div>

                                        <div class="form-row">
                                            <div class="col-md-6 mb-3">
                                                <label for="email">Email</label>
                                                <input type="text" class="form-control" id="email"
                                                       placeholder="abc@gmail.com"
                                                       required>
                                            </div>

                                            <div class="col-md-6 mb-3">
                                                <label for="contact">Contact Number</label>
                                                <input type="text" class="form-control" id="contact"
                                                       placeholder="+6012-3456789"
                                                       required>
                                            </div>

                                            <div class="col-md-6 mb-3">
                                                <label for="student-id">Student ID</label>
                                                <input type="text" class="form-control" id="student-id"
                                                       placeholder="17171234/1"
                                                       required>
                                            </div>
                                        </div>


                                        <h4 class="mt-4 mb-3 text-primary">Accommodation Details</h4>

                                        <div class="form-row">
                                            <div class="col-md-6 mb-3">
                                                <label for="start">Start date</label>
                                                <input type="date" class="form-control" id="start" required>
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label for="end">End date</label>
                                                <input type="date" class="form-control" id="end" required>
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label for="college">College</label>
                                                <select class="form-control" id="college" required>
                                                    <option value="" disabled selected hidden>Select your collge</option>
                                                    <option value="kk1">KK1</option>
                                                    <option value="kk2">KK2</option>
                                                    <option value="kk3">KK3</option>
                                                    <option value="kk4">KK4</option>
                                                    <option value="kk5">KK5</option>
                                                    <option value="kk6">KK6</option>
                                                    <option value="kk7">KK7</option>
                                                    <option value="kk8">KK8</option>
                                                    <option value="kk9">KK9</option>
                                                    <option value="kk10">KK10</option>
                                                    <option value="kk11">KK11</option>
                                                    <option value="kk12">KK12</option>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label for="reason">Reason to apply</label>
                                            <textarea type="text" class="form-control" id="reason" required>
                                    </textarea>
                                        </div>

                                        <button type="submit" class="btn btn-primary ml-3 my-2 px-4 float-right"
                                                id="submit">
                                            Submit
                                        </button>
                                        <button type="button" class="btn btn-outline-primary ml-3 my-2 px-4 float-right"
                                                id="cancel">
                                            Cancel
                                        </button>

                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php else : include '../src/templates/404.php'; endif; ?>
        <?php else: include '../src/templates/server_err.php'; endif; ?>
    <?php else : include '../src/templates/acc_req.php'; endif; ?>
</main>

<script src="/assets/js/vendor/jquery-3.5.1.js"></script>
<script src="/assets/js/vendor/bootstrap.bundle.js"></script>
<script src="/assets/js/utility.js"></script>
<script src="/assets/js/core.js"></script>
<script src="/assets/js/accommodation-form.js"></script>

<?php include '../src/templates/footer.php' ?>
