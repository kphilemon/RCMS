if (isSignedIn()) {
    $('#navbar').load('common/navbar/signed-in.html');
} else {
    $('#navbar').load('common/navbar/signed-out.html');
}