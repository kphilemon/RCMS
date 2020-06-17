<?php
// Navbar IDs
// set to empty string if you do not want the link in navbar to be highlighted
define('NAVBAR_ID_BY_TITLE', [TITLE_RCMS => 'home', TITLE_ACTIVITY => '', TITLE_MY_ACTIVITIES => 'my-activities',
        TITLE_ISSUES => 'issues', TITLE_FOOD => 'food', TITLE_ACCOMMODATION => 'accommodation', TITLE_PROFILE => 'profile',
        TITLE_NOT_FOUND => '']
);
?>

<nav class="navbar navbar-expand-lg fixed-top shadow navbar-dark bg-nav"
     id=<?= NAVBAR_ID_BY_TITLE[$GLOBALS['title']] ?>>
    <div class="container py-1">

        <!-- Brand name -->
        <a class="navbar-brand h1 mb-0" href="/">RCMS</a>

        <!-- Menu toggler button-->
        <button class="navbar-toggler border-0" type="button" data-toggle="collapse" data-target="#nav-links">
            <span class="navbar-toggler-icon"></span>
        </button>

        <!-- Nav links -->
        <div class="collapse navbar-collapse" id="nav-links">
            <div class="navbar-nav mr-auto">
                <a class="nav-item nav-link" id="nav-home" href="/home">Home</a>
                <a class="nav-item nav-link" id="nav-issues" href="/issues">Issues</a>
                <a class="nav-item nav-link" id="nav-food" href="/food">Food</a>
                <a class="nav-item nav-link" id="nav-accommodation" href="/accommodation">Accommodation</a>
                <a class="nav-item nav-link" id="nav-my-activities" href="/my-activities">My Activities</a>
                <?php if (isset($_SESSION['user_id'])) : ?>
                    <div class="dropdown-divider d-lg-none"></div>
                    <a class="nav-item nav-link d-lg-none" id="nav-profile" href="/profile">Manage Profile</a>
                    <a class="nav-item nav-link d-lg-none" id="nav-support" href="#">Support</a>
                    <a class="nav-item nav-link d-lg-none" id="nav-logout" href="">Log Out</a>
                <?php endif; ?>
            </div>

            <?php if (isset($_SESSION['user_id'])) : ?>
                <div class="dropdown d-lg-block d-none">
                    <button class="btn btn-secondary btn-profile-sm d-lg-block d-none" type="button"
                            data-toggle="dropdown">
                        <?= $_SESSION['username'][0] ?? 'R' ?>
                    </button>
                    <div class="dropdown-menu dropdown-menu-right">
                        <a class="dropdown-item" id="nav-menu-profile" href="/profile">Manage Profile</a>
                        <a class="dropdown-item" id="nav-menu-support" href="#">Support</a>
                        <a class="dropdown-item" id="nav-menu-logout" href="">Log Out</a>
                    </div>
                </div>
            <?php else : ?>
                <div class="navbar-nav">
                    <a class="nav-item nav-link pr-3" data-toggle="modal" data-target="#modal-sign-up">Sign Up</a>
                    <button class="btn btn-secondary my-2 my-lg-0" data-toggle="modal" data-target="#modal-sign-in">Sign
                        In
                    </button>
                </div>
            <?php endif; ?>
        </div>
    </div>
</nav>
