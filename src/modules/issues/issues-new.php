<?php
redirect_if_profile_incomplete();
include '../src/templates/header.php';
include '../src/templates/navbar.php';
?>

<main class="container">
    <?php if (isset($_SESSION['user_id'])): ?>
        <div class="alert alert-warning fade show" role="alert" id="error-alert">
            <span></span>
            <button type="button" class="close" data-dismiss="alert">
                <span>&times;</span>
            </button>
        </div>

        <div class="card p-4 mb-4">
            <div class="row">
                <div class="col-lg-6">
                    <h5 class="text-primary m-0">Submit a New Report to the Management</h5>
                    <p class="text-secondary m-0">Please fill in all required fields.
                        <span class="text-danger">*</span>
                    </p>
                </div>

                <div class="col-lg-6 align-self-center">
                    <p class="text-secondary mb-0 mt-2 mt-lg-0 text-left text-lg-right">
                        <a href="/issues" class="text-primary-purple">Issues</a>
                        <i class="fas fa-chevron-right fa-xs mx-2"></i> Submit New</p>
                </div>
            </div>
        </div>

        <div class="card p-4">
            <form id="new-issue" method="post" action="/api/issues/create" enctype="multipart/form-data"
                  class="needs-validation" novalidate>
                <div class="row">
                    <div class="col-lg-6">
                        <div class="mb-3">
                            <label for="type">Problem Type<span class="text-danger">*</span></label></label><br>
                            <select id="type" class="form-control custom-select" name="type" required>
                                <option value="" disabled selected hidden>Select the type of problem</option>
                                <option value="Acrylic Plate">Acrylic Plate</option>
                                <option value="Air Conditioner">Air Conditioner</option>
                                <option value="Auto Gate Barrier">Auto Gate Barrier</option>
                                <option value="Bed">Bed</option>
                                <option value="Book Shelf">Book Shelf</option>
                                <option value="Cabinet">Cabinet</option>
                                <option value="Ceiling">Ceiling</option>
                                <option value="Chair">Chair</option>
                                <option value="Cistern">Cistern</option>
                                <option value="Cleanliness">Cleanliness</option>
                                <option value="Clogged">Clogged</option>
                                <option value="Clothes Hanger">Clothes Hanger</option>
                                <option value="Counter Service">Counter Service</option>
                                <option value="Curtain">Curtain</option>
                                <option value="Door">Door</option>
                                <option value="Door Lock">Door Lock</option>
                                <option value="Cupboard">Cupboard</option>
                                <option value="Dryer">Dryer</option>
                                <option value="Electrical Trip">Electrical Trip</option>
                                <option value="Exhaust Fan">Exhaust Fan</option>
                                <option value="Fan">Fan</option>
                                <option value="Fire Alarm Panel">Fire Alarm Panel</option>
                                <option value="Fridge">Fridge</option>
                                <option value="Gas Stove">Gas Stove</option>
                                <option value="Grill">Grill</option>
                                <option value="Gutter">Gutter</option>
                                <option value="Induction Cooker">Induction Cooker</option>
                                <option value="Kettle">Kettle</option>
                                <option value="Lamp">Lamp</option>
                                <option value="Landscape">Landscape</option>
                                <option value="Leaked">Leaked</option>
                                <option value="Lift">Lift</option>
                                <option value="Main Hole">Main Hole</option>
                                <option value="Mattress">Mattress</option>
                                <option value="Microwave">Microwave</option>
                                <option value="PA System">PA System</option>
                                <option value="Pest Control">Pest Control</option>
                                <option value="Piping">Piping</option>
                                <option value="Road">Road</option>
                                <option value="Roof">Roof</option>
                                <option value="Shower">Shower</option>
                                <option value="Signage">Signage</option>
                                <option value="Sink">Sink</option>
                                <option value="Socket">Socket</option>
                                <option value="Table">Table</option>
                                <option value="Television">Television</option>
                                <option value="Tiles">Tiles</option>
                                <option value="Toilet Bowl">Toilet Bowl</option>
                                <option value="Towel hanger">Towel hanger</option>
                                <option value="Washing Machine">Washing Machine</option>
                                <option value="Water Boiler">Water Boiler</option>
                                <option value="Water Dispenser">Water Dispenser</option>
                                <option value="Water Heater">Water Heater</option>
                                <option value="Water Pressure">Water Pressure</option>
                                <option value="Water Proofing">Water Proofing</option>
                                <option value="Water Supply">Water Supply</option>
                                <option value="Window">Window</option>
                            </select>
                            <div class="invalid-feedback"></div>
                        </div>

                        <div class="mb-3">
                            <label for="location">Problem Location<span class="text-danger">*</span></label><br>
                            <select id="location" class="form-control custom-select" name="location" required>
                                <option value="" disabled selected hidden>Select the location of problem</option>
                                <option value="Room">Room</option>
                                <option value="Office">Office</option>
                                <option value="Hall">Hall</option>
                                <option value="Study Area">Study Area</option>
                                <option value="Toilet">Toilet</option>
                                <option value="Sport Area">Sport Area</option>
                                <option value="Cafe">Cafe</option>
                                <option value="Others">Others</option>
                            </select>
                            <div class="invalid-feedback"></div>
                        </div>

                        <div class="mb-3">
                            <label for="img">Image Attachment (optional)</label>
                            <div class="custom-file">
                                <label for="img" class="custom-file-label">Select image</label>
                                <input id="img" type="file" accept="image/*" class="custom-file-input" name="img">
                                <div class="invalid-feedback"></div>
                                <small class="form-text text-muted">Note: only jpg , jpeg , png and max size 1
                                    MB.</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6 mt-3 mt-lg-0">
                        <div class="mb-3">
                            <label for="details">Problem Detail<span class="text-danger">*</span></label><br>
                            <textarea id="details" class="form-control" name="details" maxlength="500" rows="5"
                                      required></textarea>
                            <div class="invalid-feedback"></div>
                            <small id="remaining" class="form-text text-muted text-right">500/500</small>
                        </div>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary ml-3 my-2 px-4 float-right" id="submit">
                    Submit
                </button>
                <a href="/issues" type="button" class="btn btn-outline-primary ml-3 my-2 px-4 float-right"
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
<script src="/assets/js/issues-new.js"></script>
<script>$('.custom-file-label').attr('text', 'Browse');</script>

<?php include '../src/templates/footer.php' ?>

