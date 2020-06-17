// navbar
$('#nav-menu-logout, #nav-logout').click(function () {
    signOut();
    window.location.href = '/home';
})

$('#form-sign-in').submit(function () {
    $('#modal-sign-in').modal('hide');
    signIn();
})

$('#form-sign-up').submit(function () {
    $('#modal-sign-up').modal('hide');
    signIn();
})

$('#form-pw-forget').submit(function () {
    $('#modal-pw-forget').modal('hide');
    $('#modal-pw-reset-done').modal('show');
})

$('[data-toggle="tooltip"]').tooltip();

$('#tabs').click(function () {
    $('html, body').animate({
        scrollTop: $("#tabs").offset().top - 80
    }, 500);
});