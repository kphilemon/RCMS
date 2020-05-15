if (isSignedIn()) {
    $('#navbar').load('common/navbar/signed-in.html', function () {
        $('#pages').find('.active').removeClass('active');
        $('#pages #home').addClass('active');
        $('#logout').click(function () {
            signOut();
            window.location.href = 'index.html';
        });
        $('#menu-logout').click(function () {
            signOut();
            window.location.href = 'index.html';
        })
    });
} else {
    $('#navbar').load('common/navbar/signed-out.html', function () {
        $('#pages').find('.active').removeClass('active');
        $('#pages #home').addClass('active');
    });
}

$('#modals #m1').load('common/modal/acc-required.html');
$('#modals #m4').load('common/modal/pw-reset-done.html');

$('#modals #m2').load('common/modal/sign-in.html', function () {
    $('#form-sign-in').submit(function () {
        $('#modal-sign-in').modal('hide');
        signIn();
        window.location.href = 'index.html';
    })
});
$('#modals #m3').load('common/modal/sign-up.html', function f() {
    $('#form-sign-up').submit(function () {
        $('#modal-sign-up').modal('hide');
        signIn()
        window.location.href = 'profile.html';
    })
});
$('#modals #m5').load('common/modal/pw-forget.html', function () {
    $('#form-pw-forget').submit(function () {
        $('#modal-pw-forget').modal('hide');
        $('#modal-pw-reset-done').modal('show');
    })
});

