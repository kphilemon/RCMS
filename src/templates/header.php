<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="/assets/css/vendor/bootstrap.css">

    <?php if ($GLOBALS['title'] === TITLE_ACCOMMODATION || $GLOBALS['title'] === TITLE_ISSUES): ?>
    <link rel="stylesheet" href="/assets/css/vendor/datatables.css">
        <script src="https://kit.fontawesome.com/da5279c78c.js" crossorigin="anonymous"></script>
    <?php elseif ($GLOBALS['title'] === TITLE_FOOD): ?>
    <link rel="stylesheet" href="/assets/css/vendor/fullcalendar-core.css" >

    <?php endif; ?>

    <link rel="stylesheet" href="/assets/css/custom.css">

    <title><?= $GLOBALS['title']; ?></title>
</head>
<body>