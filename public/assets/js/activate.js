$('#activate-form').submit(function (event) {


    hideError('#ac-password, #ac-retype');
    let passwordOK = true, retypeOK = true;
    let password = $('#ac-password').val(),
        retype = $('#ac-retype').val();

    if (!password) {
        showError('#ac-password', 'Please enter your password.');
        passwordOK = false;
    } else if (!password.match(/^(?=.*\d)(?=.*[A-Za-z])[0-9A-Za-z!@#$%]{8,}$/)){
        showError('#ac-password', 'Your password must consist of uppercase, lowercase, letter and longer than 8.');
        passwordOK = false;
    }



    if (!retype) {
        showError('#ac-retype', 'Please re-type your password.');
        retypeOK = false;
    } else if (password !== retype){
        showError('#ac-retype', 'Your retype password does not match with your password.');
        retypeOK = false;
    }

    if (retypeOK && passwordOK) {
        return true;
    }

    event.preventDefault();
    event.stopPropagation();
});