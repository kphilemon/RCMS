<?php
redirect_if_profile_incomplete();
include '../src/templates/header.php';
include '../src/templates/navbar.php';
?>

<main class="container">
    <?php if (isset($_SESSION['user_id'])) : ?>
        <div class="alert alert-warning fade show" id="error-alert" role="alert">
            <span></span>
            <button type="button" class="close"><span>&times;</span></button>
        </div>

        <div class="card p-4 mb-4">
            <div class="row">
                <div class="col-lg-6">
                    <h5 class="text-primary m-0">New Accommodation Application</h5>
                    <p class="text-secondary m-0">Please fill in all required fields. <span
                                class="text-danger">*</span></p>
                </div>

                <div class="col-lg-6 align-self-center">
                    <p class="text-secondary mb-0 mt-2 mt-lg-0 text-left text-lg-right">
                        <a href="/accommodation" class="text-primary-purple">Accommodation</a>
                        <i class="fas fa-chevron-right fa-xs mx-2"></i> Submit New</p>
                </div>

            </div>
        </div>

        <div class="card p-4">
            <form id="details" action="/api/accommodation/create" method="post" enctype="multipart/form-data"
                  class="needs-validation"
                  novalidate>
                <div class="row">
                    <div class="col-lg-6">
                        <div class="mb-3">
                            <label for="college">College <span class="text-danger">*</span></label>
                            <select class="form-control custom-select" id="college" name="college" required>
                                <option value="" disabled selected hidden>Select the college you wish to apply for
                                </option>
                                <option value="1">1st Residential College</option>
                                <option value="2">2nd Residential College</option>
                                <option value="3">3rd Residential College</option>
                                <option value="4">4th Residential College</option>
                                <option value="5">5th Residential College</option>
                                <option value="6">6th Residential College</option>
                                <option value="7">7th Residential College</option>
                                <option value="8">8th Residential College</option>
                                <option value="9">9th Residential College</option>
                                <option value="10">10th Residential College</option>
                                <option value="11">11th Residential College</option>
                                <option value="12">12th Residential College</option>
                            </select>
                            <div class="invalid-feedback"></div>
                        </div>

                        <div class="form-row">
                            <div class="col-md-6 mb-3">
                                <label for="check-in">Check-in date <span class="text-danger">*</span></label>
                                <input type="date" class="form-control" id="check-in" name="check-in"
                                       min="<?= date('Y-m-d', strtotime("+30 days")) ?>" required>
                                <div class="invalid-feedback"></div>
                                <small class="form-text text-muted">At least 30 days from today</small>

                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="check-out">Check-out date <span class="text-danger">*</span></label>
                                <input type="date" class="form-control" id="check-out" name="check-out"
                                       min="<?= date('Y-m-d', strtotime("+31 days")) ?>" required>
                                <div class="invalid-feedback"></div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="supporting-docs">Supporting document (optional)</label>
                            <div class="custom-file ">
                                <label class="custom-file-label" for="supporting-docs">Select file</label>
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
                                      maxlength="5000" rows="9"></textarea>
                            <div class="invalid-feedback"></div>
                            <small id="remaining" class="form-text text-muted text-right">5000/5000</small>
                        </div>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary ml-3 my-2 px-4 float-right"
                        id="submit">
                    Submit
                </button>
                <a href="/accommodation" type="button" class="btn btn-outline-primary ml-3 my-2 px-4 float-right"
                        id="cancel">
                    Cancel
                </a>

            </form>
        </div>
    <?php else : include '../src/templates/acc_req.php'; endif; ?>
</main>

<script src="/assets/js/vendor/jquery-3.5.1.js"></script>
<script src="/assets/js/vendor/bootstrap.bundle.js"></script>
<script src="/assets/js/core.js"></script>
<script src="/assets/js/accommodation-new.js"></script>
<script>$('.custom-file-label').attr('text', 'Browse');</script>

<?php include '../src/templates/footer.php' ?>
