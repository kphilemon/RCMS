<?php
redirect_if_profile_incomplete();
include '../src/templates/header.php';
include '../src/templates/navbar.php';
include '../src/models/AccommodationModel.php';


if (isset($_SESSION['user_id'])) {
    $matches = [];
    preg_match('/^\/accommodation\/(\d+)\/?$/i', $_SERVER['REQUEST_URI'], $matches);
    $accommodation_id = intval($matches[1]);
    $not_found = false;

    if ($accommodation_id !== 0) {
        $server_err = false;
        $record = [];

        $db = new Database(DATABASE_NAME, DATABASE_USERNAME, DATABASE_PASSWORD);
        try {
            // try to get the record with user id and accommodation id
            $model = new AccommodationModel($db->getConnection());
            $record = $model->getByIdAndUserId($accommodation_id, $_SESSION['user_id']);
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
        case STATUS_SUBMITTED:
            return '<span class="badge badge-warning">Submitted</span>';
        case STATUS_APPROVED:
            return '<span class="badge badge-success">Approved</span>';
        case STATUS_REJECTED:
            return '<span class="badge badge-danger">Rejected</span>';
        default:
            return '';
    }
}

?>

<main class="container">
    <?php if (isset($_SESSION['user_id'])) : ?>
        <?php if (!$not_found) : ?>
            <?php if (!$server_err) : ?>
                <div class="alert alert-warning fade show" id="error-alert" role="alert">
                    <span></span>
                    <button type="button" class="close"><span>&times;</span></button>
                </div>

                <?php if (isset($_SESSION['updated-accommodation'])): ?>
                    <div class="alert alert-info alert-dismissible fade show" role="alert">
                        Application updated successfully.
                        <button type="button" class="close" data-dismiss="alert">
                            <span>&times;</span>
                        </button>
                    </div>
                    <?php unset($_SESSION['updated-accommodation']); ?>
                <?php endif; ?>

                <?php if (isset($_SESSION['added-accommodation'])): ?>
                    <div class="alert alert-info alert-dismissible fade show" role="alert">
                        Application submitted. You can still edit the application before it is being approved.
                        <button type="button" class="close" data-dismiss="alert">
                            <span>&times;</span>
                        </button>
                    </div>
                    <?php unset($_SESSION['added-accommodation']); ?>
                <?php endif; ?>

                <div class="card p-4 mb-4">
                    <div class="row">
                        <div class="col-lg-6">
                            <h5 class="text-primary m-0">Accommodation Application
                                #<?= sprintf('%04d', $accommodation_id) ?> <?= createStatusBadge($record['status']) ?></h5>
                            <p class="text-secondary m-0">Submitted on <?php
                                $datetime = date_create_from_format('Y-m-d H:i:s', $record['created_at']);
                                echo $datetime->format('j F Y') ?></p>
                        </div>

                        <div class="col-lg-6 align-self-center">
                            <p class="text-secondary mb-0 mt-2 mt-lg-0 text-left text-lg-right">
                                <a href="/accommodation" class="text-primary-purple">Accommodation</a>
                                <i class="fas fa-chevron-right fa-xs mx-2"></i>
                                #<?= sprintf('%04d', $accommodation_id) ?></p>
                        </div>

                    </div>
                </div>

                <div class="card p-4">
                    <form id="details" action="/api/accommodation/update/<?= $record['id'] ?>" method="post"
                          enctype="multipart/form-data"
                          class="needs-validation"
                          novalidate>
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label for="college">College <span class="text-danger">*</span></label>
                                    <select class="form-control custom-select" id="college" name="college" required>
                                        <option value="1" <?= ($record['college_id'] == 1) ? 'selected' : '' ?>>1st
                                            Residential College
                                        </option>
                                        <option value="2" <?= ($record['college_id'] == 2) ? 'selected' : '' ?>>2nd
                                            Residential College
                                        </option>
                                        <option value="3" <?= ($record['college_id'] == 3) ? 'selected' : '' ?>>3rd
                                            Residential College
                                        </option>
                                        <option value="4" <?= ($record['college_id'] == 4) ? 'selected' : '' ?>>4th
                                            Residential College
                                        </option>
                                        <option value="5" <?= ($record['college_id'] == 5) ? 'selected' : '' ?>>5th
                                            Residential College
                                        </option>
                                        <option value="6"<?= ($record['college_id'] == 6) ? 'selected' : '' ?>>6th
                                            Residential College
                                        </option>
                                        <option value="7" <?= ($record['college_id'] == 7) ? 'selected' : '' ?>>7th
                                            Residential College
                                        </option>
                                        <option value="8" <?= ($record['college_id'] == 8) ? 'selected' : '' ?>>8th
                                            Residential College
                                        </option>
                                        <option value="9" <?= ($record['college_id'] == 9) ? 'selected' : '' ?>>9th
                                            Residential College
                                        </option>
                                        <option value="10" <?= ($record['college_id'] == 10) ? 'selected' : '' ?>>
                                            10th
                                            Residential College
                                        </option>
                                        <option value="11" <?= ($record['college_id'] == 11) ? 'selected' : '' ?>>
                                            11th
                                            Residential College
                                        </option>
                                        <option value="12" <?= ($record['college_id'] == 12) ? 'selected' : '' ?>>
                                            12th
                                            Residential College
                                        </option>
                                    </select>
                                    <div class="invalid-feedback"></div>
                                </div>

                                <div class="form-row">
                                    <div class="col-md-6 mb-3">
                                        <label for="check-in">Check-in date <span
                                                    class="text-danger">*</span></label>
                                        <input type="date" class="form-control" id="check-in" name="check-in"
                                               value="<?= $record['check_in_date'] ?>"
                                               min="<?= date('Y-m-d', strtotime("+30 days")) ?>" required>
                                        <div class="invalid-feedback"></div>
                                        <small class="form-text text-muted">At least 30 days from today</small>

                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="check-out">Check-out date <span
                                                    class="text-danger">*</span></label>
                                        <input type="date" class="form-control" id="check-out" name="check-out"
                                               value="<?= $record['check_out_date'] ?>"
                                               min="<?= date('Y-m-d', strtotime("+31 days")) ?>" required>
                                        <div class="invalid-feedback"></div>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label for="supporting-docs">Supporting document (optional)</label>
                                    <div class="custom-file ">
                                        <label class="custom-file-label"
                                               for="supporting-docs"><?= (empty($record['supporting_docs'])) ? 'Select file' : substr($record['supporting_docs'], 11) ?></label>
                                        <input type="file" class="custom-file-input" id="supporting-docs"
                                               name="supporting-docs" accept="application/pdf">
                                        <div class="invalid-feedback"></div>
                                        <small class="form-text text-muted">Document such as formal letters/medical
                                            certificate (PDF, 1MB Max)</small>
                                    </div>
                                </div>

                            </div>
                            <div class="col-lg-6 mt-3 mt-lg-0">
                                <div class="mb-3">
                                    <label for="purpose">Reason to apply <span class="text-danger">*</span></label>
                                    <textarea class="form-control" id="purpose" name="purpose" required
                                              maxlength="5000" rows="9"><?= $record['purpose'] ?></textarea>
                                    <div class="invalid-feedback"></div>
                                    <small id="remaining" class="form-text text-muted text-right">5000/5000</small>
                                </div>
                            </div>
                        </div>

                        <?php if ($record['status'] == STATUS_SUBMITTED): ?>
                            <button type="submit" class="btn btn-primary ml-3 my-2 px-4 float-right"
                                    id="submit">
                                Edit
                            </button>
                        <?php endif; ?>

                        <button type="button" class="btn btn-danger ml-3 my-2 px-4 float-right"
                                id="delete" data-toggle="modal" data-target="#modal-delete-accommodation">
                            Delete
                        </button>

                        <button type="button" class="btn btn-danger ml-3 my-2 px-4 float-right"
                                id="cancel">
                            Cancel
                        </button>
                    </form>
                </div>

                <div class="modal fade" id="modal-delete-accommodation" tabindex="-1">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">

                            <div class="modal-header">
                                <h5 class="modal-title text-primary">Confirmation</h5>
                                <button type="button" class="close" data-dismiss="modal">
                                    <span>&times;</span>
                                </button>
                            </div>

                            <div class="modal-body text-secondary">
                                <p>Warning: You are deleting your accommodation application
                                    <b>#<?= sprintf('%04d', $accommodation_id) ?></b>. Are you sure?</p>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-outline-primary mr-2" data-dismiss="modal">
                                    Cancel
                                </button>
                                <button type="button" id="confirm-delete" data-id="<?= $record['id'] ?>" class="btn btn-danger">
                                    Delete
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            <?php else: include '../src/templates/server_err.php'; endif; ?>
        <?php else : include '../src/templates/404.php'; endif; ?>
    <?php else : include '../src/templates/acc_req.php'; endif; ?>
</main>

<script src="/assets/js/vendor/jquery-3.5.1.js"></script>
<script src="/assets/js/vendor/bootstrap.bundle.js"></script>
<script src="/assets/js/core.js"></script>
<script src="/assets/js/accommodation-details.js"></script>
<script>$('.custom-file-label').attr('text', '<?= (empty($record['supporting_docs'])) ? 'Browse' : 'Change file'?>');</script>


<?php include '../src/templates/footer.php' ?>
