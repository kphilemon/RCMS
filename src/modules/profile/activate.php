<?php
include '../src/templates/header.php';
include '../src/templates/navbar.php';
include '../src/models/UserModel.php';

var_dump($_POST);

$matches = [];
preg_match('/^\/activate\/([a-f0-9]{32})\/?$/i', $_SERVER['REQUEST_URI'], $matches);
$hash = trim($matches[1]);

$server_err = false;
$not_found = false;
$activated = false;
$password_ok = true;
$activate_ok = true;

$db = new Database(DATABASE_NAME, DATABASE_USERNAME, DATABASE_PASSWORD);
try {
    $student_model = new UserModel($db->getConnection());
    $data = $student_model->getByHash($hash);
} catch (PDOException $exception) {
    $server_err = true;
}

if (!$server_err) {
    if (empty($data)) {
        $not_found = true;
    } else {
        if ($data['activated'] == 1) {
            $activated = true;
        }
    }
}

if (!$server_err && !$activated && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $pattern = '/^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d]{8,}$/';
    if (!empty($_POST['password']) && preg_match($pattern, $_POST['password'])) {
        try {
            $success = $student_model->updatePassword($data['email'], md5($_POST['password']));
        } catch (PDOException $exception) {
            $server_err = true;
        }

        if ($success) {
            $activated = true;
        } else {
            $activate_ok = false;
        }
    } else {
        echo 'haha';
        $password_ok = false;
    }
}


?>

<main class="container">
    <?php if (!$server_err) : ?>
        <?php if (!$not_found) : ?>

            <?php if ($activated) : ?>
                <div class="row">
                    <div class="col pt-5 text-center">
                        <img class="card-img-150 mt-5" src="/assets/img/activated.svg" alt="404 Not Found">
                        <h6 class="text-secondary my-5 mx-2">Your account has been activated<br>Click
                            the button below to sign in</h6>
                        <button type="button" class="btn btn-outline-primary px-3" data-toggle="modal"
                                data-target="#modal-sign-in"
                                data-dismiss="modal">Sign In
                        </button>
                    </div>
                </div>
            <?php else: ?>


                <?php if (!$password_ok): ?>
                    <div class="alert alert-warning alert-dismissible fade show" role="alert">
                        Your password does not fulfil the requirement (must consist of uppercase, lowercase, letter and
                        longer than 8). Please try again.
                        <button type="button" class="close" data-dismiss="alert">
                            <span>&times;</span>
                        </button>
                    </div>
                <?php endif; ?>

                <?php if (!$activate_ok): ?>
                    <div class="alert alert-warning alert-dismissible fade show" role="alert">
                        It seems like we are having some issues at the server side. Please try again.
                        <button type="button" class="close" data-dismiss="alert">
                            <span>&times;</span>
                        </button>
                    </div>

                <?php endif; ?>
                <div class="text-center">
                    <form class="form-activate" id="activate-form" action="" method="post" novalidate>
                        <h4 class="mt-5 font-weight-normal">Set your password<br>to activate your account
                            for<br></h4>
                        <h5 class="mb-3"><?= $data['email'] ?></h5>

                        <div class="form-group mt-5">
                            <input type="password" class="form-control" name="password" id="ac-password"
                                   placeholder="Password" required>
                            <div class="invalid-feedback"></div>
                        </div>

                        <div class="form-group mb-5">
                            <input type="password" class="form-control" id="ac-retype"
                                   placeholder="Re-type your password" required>
                            <div class="invalid-feedback"></div>
                        </div>

                        <button class="btn btn-primary btn-block" type="submit">Confirm password</button>
                    </form>
                </div>
            <?php endif; ?>


        <?php else : include '../src/templates/404.php'; endif; ?>
    <?php else : include '../src/templates/server_err.php'; endif; ?>
</main>

<?php include '../src/templates/modals.php' ?>

<script src="/assets/js/vendor/jquery-3.5.1.js"></script>
<script src="/assets/js/vendor/bootstrap.bundle.js"></script>
<script src="/assets/js/core.js"></script>
<script src="/assets/js/activate.js"></script>
<script src="https://unpkg.com/pure-md5@latest/lib/index.js"></script>

<?php include '../src/templates/footer.php' ?>

