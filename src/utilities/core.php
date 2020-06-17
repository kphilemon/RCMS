<?php

function redirect_if_profile_incomplete()
{
    // user profile is not complete, redirect user to profile page to complete it
    if (isset($_SESSION['user_profile_complete']) && $_SESSION['user_profile_complete'] == false) {
        header("Location: /profile"); /* Redirect browser */
        exit();
    }
}
