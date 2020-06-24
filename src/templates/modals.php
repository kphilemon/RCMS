<?php
if (!isset($_SESSION['user_id'])) {
    // only include if user is not signed in
    include '../src/templates/modal/acc-required.html';
    include '../src/templates/modal/pw-forget.html';
    include '../src/templates/modal/pw-reset-done.html';
    include '../src/templates/modal/sign-in.html';
    include '../src/templates/modal/sign-up.html';
    include '../src/templates/modal/sign-up-done.html';
}

