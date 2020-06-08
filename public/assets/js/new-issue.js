if (isSignedIn()) {
    $('#navbar').load('common/navbar/signed-in.html', function () {
        $('#pages').find('.active').removeClass('active');
        $('#pages #issues').addClass('active');

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
    alert('You are not signed in!');
    window.location.href = 'index.html';
}

$(function () {
    $('#cancel').click(function () {
        window.location.href = 'issues.html';
    })
    $('#submit').click(function () {
        alert('To be implemented');
    })
})