<?php
redirect_if_profile_incomplete();
include '../src/templates/header.php';
include '../src/templates/navbar.php';
include '../src/models/IssueModel.php';


if (isset($_SESSION['user_id'])) {
    $matches = [];
    preg_match('/^\/issues\/(\d+)\/?$/i', $_SERVER['REQUEST_URI'], $matches);
    $issue_id = intval($matches[1]);
    $not_found = false;

    if ($issue_id !== 0) {
        $server_err = false;
        $record = [];

        $db = new Database(DATABASE_NAME, DATABASE_USERNAME, DATABASE_PASSWORD);
        try {
            // try to get the record with user id and issue id
            $model = new IssueModel($db->getConnection());
            $record = $model->getByIdAndUserId($issue_id, $_SESSION['user_id']);
            if (empty($record)) {
                $not_found = true;
            }
        } catch (PDOException $exception) {
            $server_err = true;
        }

        $db->closeConnection();
    } else {
        $not_found = true;
    }
}

function createStatusBadge(int $status): string
{
    switch ($status) {
        case STATUS_PENDING:
            return '<span class="badge badge-warning">Pending</span>';
        case STATUS_COMPLETED:
            return '<span class="badge badge-success">Completed</span>';
        case STATUS_IN_PROGRESS:
            return '<span class="badge badge-danger">In-progress</span>';
        default:
            return '';
    }
}

?>

    <main class="container">
        <?php if (isset($_SESSION['user_id'])) : ?>
            <?php if (!$not_found) : ?>
                <?php if (!$server_err) : ?>
                    <div class="alert alert-warning fade show" role="alert" id="error-alert">
                        <span></span>
                        <button type="button" class="close" data-dismiss="alert">
                            <span>&times;</span>
                        </button>
                    </div>

                    <?php if (isset($_SESSION['updated-issue'])): ?>
                        <div class="alert alert-info alert-dismissible fade show" role="alert">
                            Issue report updated successfully.
                            <button type="button" class="close" data-dismiss="alert">
                                <span>&times;</span>
                            </button>
                        </div>
                        <?php unset($_SESSION['updated-issue']); ?>
                    <?php endif; ?>

                    <?php if (isset($_SESSION['added-issue'])): ?>
                        <div class="alert alert-info alert-dismissible fade show" role="alert">
                            Issue submitted. You can still edit the issue before it is being processed.
                            <button type="button" class="close" data-dismiss="alert">
                                <span>&times;</span>
                            </button>
                        </div>
                        <?php unset($_SESSION['added-issue']); ?>
                    <?php endif; ?>

                    <div class="card p-4 mb-4">
                        <div class="row">
                            <div class="col-lg-6">
                                <h5 class="text-primary m-0">Issue Report
                                    #<?= sprintf('%04d', $issue_id) ?> <?= createStatusBadge($record['status']) ?></h5>
                                <p class="text-secondary m-0">Submitted on <?php
                                    $datetime = date_create_from_format('Y-m-d H:i:s', $record['created_at']);
                                    echo $datetime->format('j F Y') ?></p>
                            </div>

                            <div class="col-lg-6 align-self-center">
                                <p class="text-secondary mb-0 mt-2 mt-lg-0 text-left text-lg-right">
                                    <a href="/issues" class="text-primary-purple">Issues</a>
                                    <i class="fas fa-chevron-right fa-xs mx-2"></i>
                                    #<?= sprintf('%04d', $issue_id) ?></p>
                            </div>
                        </div>
                    </div>

                    <div class="card p-4">
                        <form id="new-issue" action="/api/issues/update/<?= $record['id'] ?>" method="post"
                              enctype="multipart/form-data"
                              class="needs-validation" novalidate>
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="mb-3">
                                        <label for="type">Problem Type<span class="text-danger">*</span></label></label>
                                        <br>
                                        <select id="type" class="form-control custom-select" name="type" required>
                                            <option value="Acrylic Plate" <?= ($record['type'] == 'Acrylic Plate') ? 'selected' : '' ?>>
                                                Acrylic Plate
                                            </option>
                                            <option value="Air Conditioner" <?= ($record['type'] == 'Air Conditioner') ? 'selected' : '' ?>>
                                                Air Conditioner
                                            </option>
                                            <option value="Auto Gate Barrier" <?= ($record['type'] == 'Auto Gate Barrier') ? 'selected' : '' ?>>
                                                Auto Gate Barrier
                                            </option>
                                            <option value="Bed" <?= ($record['type'] == 'Bed') ? 'selected' : '' ?>>
                                                Bed
                                            </option>
                                            <option value="Book Shelf" <?= ($record['type'] == 'Book Shelf') ? 'selected' : '' ?>>
                                                Book Shelf
                                            </option>
                                            <option value="Cabinet" <?= ($record['type'] == 'Cabinet') ? 'selected' : '' ?>>
                                                Cabinet
                                            </option>
                                            <option value="Ceiling" <?= ($record['type'] == 'Ceiling') ? 'selected' : '' ?>>
                                                Ceiling
                                            </option>
                                            <option value="Chair" <?= ($record['type'] == 'Chair') ? 'selected' : '' ?>>
                                                Chair
                                            </option>
                                            <option value="Cistern" <?= ($record['type'] == 'Cistern') ? 'selected' : '' ?>>
                                                Cistern
                                            </option>
                                            <option value="Cleanliness" <?= ($record['type'] == 'Cleanliness') ? 'selected' : '' ?>>
                                                Cleanliness
                                            </option>
                                            <option value="Clogged" <?= ($record['type'] == 'Clogged') ? 'selected' : '' ?>>
                                                Clogged
                                            </option>
                                            <option value="Clothes Hanger" <?= ($record['type'] == 'Clothes Hanger') ? 'selected' : '' ?>>
                                                Clothes Hanger
                                            </option>
                                            <option value="Counter Service" <?= ($record['type'] == 'Counter Service') ? 'selected' : '' ?>>
                                                Counter Service
                                            </option>
                                            <option value="Curtain" <?= ($record['type'] == 'Curtain') ? 'selected' : '' ?>>
                                                Curtain
                                            </option>
                                            <option value="Door" <?= ($record['type'] == 'Door') ? 'selected' : '' ?>>
                                                Door
                                            </option>
                                            <option value="Door Lock" <?= ($record['type'] == 'Door Lock') ? 'selected' : '' ?>>
                                                Door Lock
                                            </option>
                                            <option value="Cupboard" <?= ($record['type'] == 'Cupboard') ? 'selected' : '' ?>>
                                                Cupboard
                                            </option>
                                            <option value="Dryer" <?= ($record['type'] == 'Dryer') ? 'selected' : '' ?>>
                                                Dryer
                                            </option>
                                            <option value="Electrical Trip" <?= ($record['type'] == 'Electrical Trip') ? 'selected' : '' ?>>
                                                Electrical Trip
                                            </option>
                                            <option value="Exhaust Fan" <?= ($record['type'] == 'Exhaust Fan') ? 'selected' : '' ?>>
                                                Exhaust Fan
                                            </option>
                                            <option value="Fan" <?= ($record['type'] == 'Fan') ? 'selected' : '' ?>>
                                                Fan
                                            </option>
                                            <option value="Fire Alarm Panel" <?= ($record['type'] == 'Fire Alarm Panel') ? 'selected' : '' ?>>
                                                Fire Alarm Panel
                                            </option>
                                            <option value="Fridge" <?= ($record['type'] == 'Fridge') ? 'selected' : '' ?>>
                                                Fridge
                                            </option>
                                            <option value="Gas Stove" <?= ($record['type'] == 'Gas Stove') ? 'selected' : '' ?>>
                                                Gas Stove
                                            </option>
                                            <option value="Grill" <?= ($record['type'] == 'Grill') ? 'selected' : '' ?>>
                                                Grill
                                            </option>
                                            <option value="Gutter" <?= ($record['type'] == 'Gutter') ? 'selected' : '' ?>>
                                                Gutter
                                            </option>
                                            <option value="Induction Cooker" <?= ($record['type'] == 'Induction Cooker') ? 'selected' : '' ?>>
                                                Induction Cooker
                                            </option>
                                            <option value="Kettle" <?= ($record['type'] == 'Kettle') ? 'selected' : '' ?>>
                                                Kettle
                                            </option>
                                            <option value="Lamp" <?= ($record['type'] == 'Lamp') ? 'selected' : '' ?>>
                                                Lamp
                                            </option>
                                            <option value="Landscape" <?= ($record['type'] == 'Landscape') ? 'selected' : '' ?>>
                                                Landscape
                                            </option>
                                            <option value="Leaked" <?= ($record['type'] == 'Leaked') ? 'selected' : '' ?>>
                                                Leaked
                                            </option>
                                            <option value="Lift" <?= ($record['type'] == 'Lift') ? 'selected' : '' ?>>
                                                Lift
                                            </option>
                                            <option value="Main Hole" <?= ($record['type'] == 'Main Hole') ? 'selected' : '' ?>>
                                                Main Hole
                                            </option>
                                            <option value="Mattress" <?= ($record['type'] == 'Mattress') ? 'selected' : '' ?>>
                                                Mattress
                                            </option>
                                            <option value="Microwave" <?= ($record['type'] == 'Microwave') ? 'selected' : '' ?>>
                                                Microwave
                                            </option>
                                            <option value="PA System" <?= ($record['type'] == 'PA System') ? 'selected' : '' ?>>
                                                PA System
                                            </option>
                                            <option value="Pest Control" <?= ($record['type'] == 'Pest Control') ? 'selected' : '' ?>>
                                                Pest Control
                                            </option>
                                            <option value="Piping" <?= ($record['type'] == 'Piping') ? 'selected' : '' ?>>
                                                Piping
                                            </option>
                                            <option value="Road" <?= ($record['type'] == 'Road') ? 'selected' : '' ?>>
                                                Road
                                            </option>
                                            <option value="Roof" <?= ($record['type'] == 'Roof') ? 'selected' : '' ?>>
                                                Roof
                                            </option>
                                            <option value="Shower" <?= ($record['type'] == 'Shower') ? 'selected' : '' ?>>
                                                Shower
                                            </option>
                                            <option value="Signage" <?= ($record['type'] == 'Signage') ? 'selected' : '' ?>>
                                                Signage
                                            </option>
                                            <option value="Sink" <?= ($record['type'] == 'Sink') ? 'selected' : '' ?>>
                                                Sink
                                            </option>
                                            <option value="Socket" <?= ($record['type'] == 'Socket') ? 'selected' : '' ?>>
                                                Socket
                                            </option>
                                            <option value="Table" <?= ($record['type'] == 'Table') ? 'selected' : '' ?>>
                                                Table
                                            </option>
                                            <option value="Television" <?= ($record['type'] == 'Television') ? 'selected' : '' ?>>
                                                Television
                                            </option>
                                            <option value="Tiles" <?= ($record['type'] == 'Tiles') ? 'selected' : '' ?>>
                                                Tiles
                                            </option>
                                            <option value="Toilet Bowl" <?= ($record['type'] == 'Toilet Bowl') ? 'selected' : '' ?>>
                                                Toilet Bowl
                                            </option>
                                            <option value="Towel Hanger" <?= ($record['type'] == 'Towel Hanger') ? 'selected' : '' ?>>
                                                Towel Hanger
                                            </option>
                                            <option value="Washing Machine" <?= ($record['type'] == 'Washing Machine') ? 'selected' : '' ?>>
                                                Washing Machine
                                            </option>
                                            <option value="Water Boiler" <?= ($record['type'] == 'Water Boiler') ? 'selected' : '' ?>>
                                                Water Boiler
                                            </option>
                                            <option value="Water Dispenser" <?= ($record['type'] == 'Water Dispenser') ? 'selected' : '' ?>>
                                                Water Dispenser
                                            </option>
                                            <option value="Water Heater" <?= ($record['type'] == 'Water Heater') ? 'selected' : '' ?>>
                                                Water Heater
                                            </option>
                                            <option value="Water Pressure" <?= ($record['type'] == 'Water Pressure') ? 'selected' : '' ?>>
                                                Water Pressure
                                            </option>
                                            <option value="Water Proofing" <?= ($record['type'] == 'Water Proofing') ? 'selected' : '' ?>>
                                                Water Proofing
                                            </option>
                                            <option value="Water Supply" <?= ($record['type'] == 'Water Supply') ? 'selected' : '' ?>>
                                                Water Supply
                                            </option>
                                            <option value="Window" <?= ($record['type'] == 'Window') ? 'selected' : '' ?>>
                                                Window
                                            </option>
                                        </select>
                                        <div class="invalid-feedback"></div>
                                    </div>

                                    <div class="mb-3">
                                        <label for="location">Problem Location<span class="text-danger">*</span></label><br>
                                        <select id="location" class="form-control custom-select" name="location"
                                                required>
                                            <option value="Room" <?= ($record['location'] == 'Room') ? 'selected' : '' ?>>
                                                Room
                                            </option>
                                            <option value="Office" <?= ($record['location'] == 'Office') ? 'selected' : '' ?>>
                                                Office
                                            </option>
                                            <option value="Hall" <?= ($record['location'] == 'Hall') ? 'selected' : '' ?>>
                                                Hall
                                            </option>
                                            <option value="Study Area" <?= ($record['location'] == 'Study Area') ? 'selected' : '' ?>>
                                                Study Area
                                            </option>
                                            <option value="Toilet" <?= ($record['location'] == 'Toilet') ? 'selected' : '' ?>>
                                                Toilet
                                            </option>
                                            <option value="Sport Area" <?= ($record['location'] == 'Sport Area') ? 'selected' : '' ?>>
                                                Sport Area
                                            </option>
                                            <option value="Cafe" <?= ($record['location'] == 'Cafe') ? 'selected' : '' ?>>
                                                Cafe
                                            </option>
                                            <option value="Others" <?= ($record['location'] == 'Others') ? 'selected' : '' ?>>
                                                Others
                                            </option>
                                        </select>
                                        <div class="invalid-feedback"></div>
                                    </div>

                                    <div class="mb-3">
                                        <label for="img">Image Attachment (optional)</label>
                                        <div class="custom-file">
                                            <label for="img"
                                                   class="custom-file-label"><?= (empty($record['img'])) ? 'Select file' : substr($record['img'], 11) ?></label>
                                            <input id="img" type="file" accept="image/*" class="custom-file-input"
                                                   name="img">
                                            <div class="invalid-feedback"></div>
                                            <small class="form-text text-muted">Note: only jpg , jpeg , png and max size
                                                1
                                                MB.</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-6 mt-3 mt-lg-0">
                                    <div class="mb-3">
                                        <label for="details">Problem Detail<span
                                                    class="text-danger">*</span></label><br>
                                        <textarea id="details" class="form-control" name="details" maxlength="500"
                                                  rows="5" required><?= $record['details'] ?></textarea>
                                        <div class="invalid-feedback"></div>
                                        <small id="remaining" class="form-text text-muted text-right">500/500</small>
                                    </div>
                                </div>
                            </div>

                            <?php if ($record['status'] == STATUS_PENDING): ?>
                                <button type="submit" class="btn btn-primary ml-3 my-2 px-4 float-right"
                                        id="submit">
                                    Edit
                                </button>
                            <?php endif; ?>

                            <?php if ($record['status'] != STATUS_IN_PROGRESS): ?>
                                <button type="button" class="btn btn-danger ml-3 my-2 px-4 float-right"
                                        id="delete" data-toggle="modal" data-target="#modal-delete-issue">
                                    Delete
                                </button>
                            <?php endif; ?>

                            <button type="button" class="btn btn-danger ml-3 my-2 px-4 float-right"
                                    id="cancel">
                                Cancel
                            </button>
                        </form>
                    </div>

                    <?php if ($record['status'] != STATUS_IN_PROGRESS): ?>
                        <div class="modal fade" id="modal-delete-issue" tabindex="-1">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content">

                                    <div class="modal-header">
                                        <h5 class="modal-title text-primary">Confirmation</h5>
                                        <button type="button" class="close" data-dismiss="modal">
                                            <span>&times;</span>
                                        </button>
                                    </div>

                                    <div class="modal-body text-secondary">
                                        <p>Warning: You are deleting your issue report
                                            <b>#<?= sprintf('%04d', $issue_id) ?></b>. Are you sure?</p>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-outline-primary mr-2" data-dismiss="modal">
                                            Cancel
                                        </button>
                                        <button type="button" id="confirm-delete" data-id="<?= $record['id'] ?>"
                                                class="btn btn-danger">
                                            Delete
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>
                <?php else: include '../src/templates/server_err.php'; endif; ?>
            <?php else : include '../src/templates/404.php'; endif; ?>
        <?php else : include '../src/templates/acc_req.php'; endif; ?></main>

    <script src="/assets/js/vendor/jquery-3.5.1.js"></script>
    <script src="/assets/js/vendor/bootstrap.bundle.js"></script>
    <script src="/assets/js/core.js"></script>
    <script src="/assets/js/issues-details.js"></script>
    <script>$('.custom-file-label').attr('text', '<?= (empty($record['img'])) ? 'Browse' : 'Change image'?>');</script>

<?php include '../src/templates/footer.php' ?>