// showError sets error message on the general sibling of the selector that is passed in and show error
function showError(elem, msg = '') {
    if (msg !== '') {
        $(elem + ' ~ .invalid-feedback').html(msg);
    }

    $(elem).addClass('is-invalid');
}

function hideError(elem) {
    $(elem).removeClass('is-invalid');
}

// padId pads an id with leading zeros
function padId(num, size) {
    return ('0000000000' + num).substr(-size);
}

$('#si-alert, #su-alert, #fp-alert').hide();

$('#si-alert .close').click(function () {
    $('#si-alert').hide();
});

$('#su-alert .close').click(function () {
    $('#su-alert').hide();
});

$('#fp-alert .close').click(function () {
    $('#fp-alert').hide();
});

$('#si-email').change(function () {
    hideError('#si-email');
});
$('#si-password').change(function () {
    hideError('#si-password');
});
$('#su-email').change(function () {
    hideError('#su-email');
});
$('#fp-email').change(function () {
    hideError('#fp-email');
});

// sign-in form
$('#si-form').submit(function (event) {
    event.preventDefault();
    event.stopPropagation();

    hideError('#si-email, #si-password');
    let emailOK = true, passwordOK = true;
    let email = $('#si-email').val(),
        password = $('#si-password').val();

    if (!email || !email.match(/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@siswa.um.edu.my$/)) {
        showError('#si-email', 'Please enter a valid siswamail.');
        emailOK = false;
    }

    if (!password) {
        showError('#si-password', 'Please enter your password.');
        passwordOK = false;
    }

    if (emailOK && passwordOK) {
        $("#si-button").attr("disabled", "disabled");
        $.ajax({
            type: 'POST',
            url: $(this).attr('action'),
            enctype: 'multipart/form-data',
            data: new FormData(this),
            cache: false,
            contentType: false,
            processData: false,
            success: function (data) {
                $("#si-button").removeAttr("disabled");
                console.log(data)
                if (window.location.href.includes('activate')) {
                    window.location.href = '/profile';
                } else {
                    window.location.reload(true);
                }
            },
            error: function (xhr) {
                $("#si-button").removeAttr("disabled");
                console.log(xhr)
                if (xhr.status === 400) {
                    // Bad request
                    if (xhr.responseText) {
                        let response = JSON.parse(xhr.responseText);
                        console.log(response);
                        for (let key in response.error) {
                            if (response.error.hasOwnProperty(key)) {
                                showError('#' + key, response.error[key]);
                            }
                        }
                    }
                } else {
                    if (xhr.responseText) {
                        let response = JSON.parse(xhr.responseText);
                        console.log(response);
                        $('#si-alert > span').text(response.error);
                        $('#si-alert').show();
                    }
                }
            },
        });
    }
});

// sign-up form
$('#su-form').submit(function (event) {
    event.preventDefault();
    event.stopPropagation();

    hideError('#su-email');
    let emailOK = true;
    let email = $('#su-email').val();

    if (!email || !email.match(/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@siswa.um.edu.my$/)) {
        showError('#su-email', 'Please enter a valid siswamail.');
        emailOK = false;
    }

    if (emailOK) {
        $("#su-button").attr("disabled", "disabled");
        $.ajax({
            type: 'POST',
            url: $(this).attr('action'),
            enctype: 'multipart/form-data',
            data: new FormData(this),
            cache: false,
            contentType: false,
            processData: false,
            success: function (data) {
                console.log(data)
                $("#su-button").removeAttr("disabled");
                $('#modal-sign-up').modal('hide');
                $('#modal-sign-up-done').modal('show');
            },
            error: function (xhr) {
                $("#su-button").removeAttr("disabled");
                if (xhr.status === 400) {
                    // Bad request
                    if (xhr.responseText) {
                        let response = JSON.parse(xhr.responseText);
                        console.log(response);
                        for (let key in response.error) {
                            if (response.error.hasOwnProperty(key)) {
                                showError('#' + key, response.error[key]);
                            }
                        }
                    }
                } else {
                    if (xhr.responseText) {
                        let response = JSON.parse(xhr.responseText);
                        console.log(response);
                        $('#su-alert > span').text(response.error);
                        $('#su-alert').show();
                    }
                }
            },
        });
    }


});

// forget password form
$('#fp-form').submit(function (event) {
    event.preventDefault();
    event.stopPropagation();

    hideError('#fp-email');
    let emailOK = true;
    let email = $('#fp-email').val();

    if (!email || !email.match(/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@siswa.um.edu.my$/)) {
        showError('#fp-email', 'Please enter a valid siswamail.');
        emailOK = false;
    }

    if (emailOK) {
        $("#fp-button").attr("disabled", "disabled");
        $.ajax({
            type: 'POST',
            url: $(this).attr('action'),
            enctype: 'multipart/form-data',
            data: new FormData(this),
            cache: false,
            contentType: false,
            processData: false,
            success: function (data) {
                console.log(data)
                $("#fp-button").removeAttr("disabled");
                $('#modal-pw-forget').modal('hide');
                $('#modal-pw-reset-done').modal('show');
            },
            error: function (xhr) {
                $("#fp-button").removeAttr("disabled");
                if (xhr.status === 400) {
                    // Bad request
                    if (xhr.responseText) {
                        let response = JSON.parse(xhr.responseText);
                        console.log(response);
                        for (let key in response.error) {
                            if (response.error.hasOwnProperty(key)) {
                                showError('#' + key, response.error[key]);
                            }
                        }
                    }
                } else {
                    if (xhr.responseText) {
                        let response = JSON.parse(xhr.responseText);
                        console.log(response);
                        $('#fp-alert > span').text(response.error);
                        $('#fp-alert').show();
                    }
                }
            },
        });
    }


});

$('[data-toggle="tooltip"]').tooltip();

$('#tabs').click(function () {
    $('html, body').animate({
        scrollTop: $("#tabs").offset().top - 80
    }, 500);
});