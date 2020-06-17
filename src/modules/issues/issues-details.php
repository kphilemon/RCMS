<?php
include '../src/templates/header.php';
include '../src/templates/navbar.php';

$matches = [];
preg_match('/^\/activities\/(\w+)\/?$/i', $_SERVER['REQUEST_URI'], $matches);
$issue_id = $matches[1];

if (isset($_SESSION['user_id'])) {
    // user is logged in, load issue by user_id and issue_id from db
}

// if issue found, set to true
$found = true;

// set to true if any of the db actions failed
$server_err = false;

?>

<main class="container">
    <div class="row">
        <div class="col-lg-9 mx-auto">
            <div class="card">
                <div class="card-body">
                    <div class="container my-2">
                        <form method="get" action="">

                            <div class="form-group">
                                <label for="problemcategory">Problem Category</label><br>
                                <select id="problemcategory" class="form-control" required>
                                    <option>Room</option>
                                    <option>Office</option>
                                    <option>Hall</option>
                                    <option>Study Area</option>
                                    <option>Toilet</option>
                                    <option>Sport Area</option>
                                    <option>Cafe</option>
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="problemtype">Problem Type</label><br>
                                <select id="problemtype" class="form-control" required>
                                    <option>Electricity</option>
                                    <option>Piping</option>
                                    <option>Internet Connection</option>
                                    <option>Facility</option>
                                    <option>Cleanliness</option>
                                    <option>Furniture</option>
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="problemdetail">Problem Detail</label><br>
                                <input id="problemdetail" type="text" class="form-control">
                            </div>

                            <div class="form-group">
                                <label for="problemlocatioin">Problem Location</label><br>
                                <input id="problemlocatioin" type="text" class="form-control">
                            </div>

                            <div class="form-group">
                                <label for="problemimg">Image Attachment</label><br>
                                <input type="file" id="problemimg" accept="image/*">
                            </div>

                            <button type="submit" class="btn btn-primary ml-3 my-2 px-4 float-right" id="submit">
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
</main>

<script src="/assets/js/vendor/jquery-3.5.1.js"></script>
<script src="/assets/js/vendor/bootstrap.bundle.js"></script>
<script src="/assets/js/utility.js"></script>
<script src="/assets/js/core.js"></script>
<script src="/assets/js/new-issues.js"></script>

<?php include '../src/templates/footer.php'?>

