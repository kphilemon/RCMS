//welcome the user
var detail = document.getElementById("details");
var room_no = detail.room_id.value;
var nationality = detail.nationality.value;
var telephone = detail.telephone.value;
var gender = detail.gender.value;
var dob  = detail.dob.value;
var address = detail.address.value;
var city = detail.city.value;
var state = detail.state.value;
var zip = detail.zip.value;

if (room_no=="" && nationality=="" && telephone=="" && gender=="" && dob=="" && address=="" && city=="" && state=="" && zip==""){
    $('#modal-welcome-new-user').modal("show");
    $('#details').submit(function (event) {
        hideError('#room, #inputNationality, #inputTel, #sex, #inputDOB, #address, #city, #state, #zip')
        let roomOK = true, nationalityOK = true, phoneOK = true, genderOK = true, dobOK = true, addressOK = true, cityOK = true, stateOK = true, zipOK = true;
        let room = $('#room').val(),
            nationality = $('#inputNationality').val(),
            phone = $('#inputTel').val(),
            gender = $('#sex').val(),
            dob = $('#inputDOB').val(),
            address = $('#address').val(),
            city = $('#city').val(),
            state = $('#state').val(),
            zip = $('#zip').val();

        if (!room.match(/^[A-Z]{1}\d{3}$/)) {
            showError('#room', 'Please enter a valid room number.');
            roomOK = false;
        }

        if (!nationality) {
            showError('#inputNationality', 'Please select a nationality.');
            nationalityOK = false;
        }

        if (!(phone.match(/^01\d{8}$/) || phone.match(/^01\d{9}$/))) {
            showError('#inputTel', 'Please enter a valid phone number.');
            phoneOK = false;
        }

        if (!gender) {
            showError('#sex', 'Please select your gender.');
            genderOK = false;
        }

        if (!dob) {
            showError('#inputDOB', 'Please enter your Date of Birth.');
            dobOK = false;
        }

        let today = new Date();
        today.setHours(0,0,0,0);
        today.setDate(today.getDate());
        if (Date.parse(dob) >= today){
            showError('#inputDOB', 'Please enter the valid Date of Birth.');
            dobOK = false;
        }

        if (!address) {
            showError('#address', 'Please enter a valid address.');
            addressOK = false;
        }

        if (!city) {
            showError('#city', 'Please enter a valid city.');
            cityOK = false;
        }

        if (!state) {
            showError('#state', 'Please enter a valid state.');
            stateOK = false;
        }

        if (!zip) {
            showError('#zip', 'Please enter a valid zip.');
            zipOK = false;
        }

        if (roomOK && nationalityOK && phoneOK && genderOK && dobOK && addressOK && cityOK && stateOK && zipOK) {
            return true;
        }
        event.preventDefault();
        event.stopPropagation();
        return false;

    })

}

//edit the file when update details button is pressed
document.getElementById('action').addEventListener('click',function(e){
    e.preventDefault();
    document.getElementById('old_user_save').hidden = false;
    document.getElementById('cancel').hidden = false;
    document.getElementById('action').hidden = true;
    //disable = false
    document.getElementById('inputNationality').disabled = false;
    document.getElementById('room').disabled = false;
    document.getElementById('inputTel').disabled = false;
    document.getElementById('sex').disabled = false;
    document.getElementById('inputDOB').disabled = false;
    document.getElementById('address').disabled = false;
    document.getElementById('city').disabled = false;
    document.getElementById('state').disabled = false;
    document.getElementById('zip').disabled = false;
});

//when cancel button is pressed, redirect to profile
document.getElementById('cancel').addEventListener('click', function () {
    window.location.href = 'profile';
})


$('#details').submit(function (event) {
    hideError('#room, #inputNationality, #inputTel, #sex, #inputDOB, #address, #city, #state, #zip')
    let roomOK = true, nationalityOK = true, phoneOK = true, genderOK = true, dobOK = true, addressOK = true, cityOK = true, stateOK = true, zipOK = true;
    let room = $('#room').val(),
        nationality = $('#inputNationality').val(),
        phone = $('#inputTel').val(),
        gender = $('#sex').val(),
        dob = $('#inputDOB').val(),
        address = $('#address').val(),
        city = $('#city').val(),
        state = $('#state').val(),
        zip = $('#zip').val();

    if (!room.match(/^[A-Z]{1}\d{3}$/)) {
        showError('#room', 'Please enter a valid room number.');
        roomOK = false;
    }

    if (!nationality) {
        showError('#inputNationality', 'Please select a nationality.');
        nationalityOK = false;
    }

    if (!(phone.match(/^01\d{8}$/) || phone.match(/^01\d{9}$/))) {
        showError('#inputTel', 'Please enter a valid phone number.');
        phoneOK = false;
    }

    if (!gender) {
        showError('#sex', 'Please select your gender.');
        genderOK = false;
    }

    if (!dob) {
        showError('#inputDOB', 'Please enter your Date of Birth.');
        dobOK = false;
    }

    let today = new Date();
    today.setHours(0,0,0,0);
    today.setDate(today.getDate());
    if (Date.parse(dob) >= today){
        showError('#inputDOB', 'Please enter the valid Date of Birth.');
        dobOK = false;
    }

    if (!address) {
        showError('#address', 'Please enter a valid address.');
        addressOK = false;
    }

    if (!city) {
        showError('#city', 'Please enter a valid city.');
        cityOK = false;
    }

    if (!state) {
        showError('#state', 'Please enter a valid state.');
        stateOK = false;
    }

    if (!zip) {
        showError('#zip', 'Please enter a valid zip.');
        zipOK = false;
    }

    if (roomOK && nationalityOK && phoneOK && genderOK && dobOK && addressOK && cityOK && stateOK && zipOK) {
        return true;
    }
    event.preventDefault();
    event.stopPropagation();
    return false;

})


//prompt modal to type password before deleting account
document.getElementById('delete_acc_button').addEventListener('click', function(){
    $('#modal-confirm-delete-acc').modal('show');
})


//check for the password first before submit(form validation on update password)
$('#passForm').submit(function (event) {
    hideError('#currentPass, #newPass, #retypePass')
    let currentPassOK = true, newPassOK = true, retypePassOK = true;
    let currentPass = $('#currentPass').val(),
        newPass = $('#newPass').val(),
        retypePass = $('#retypePass').val();

    if (currentPass === null){
        showError('#currentPass', 'Your password should not be empty.');
        currentPassOK = false;
    }

    if (md5(currentPass) != document.getElementById('password').value){
        showError('#currentPass', 'Your password is incorrect.');
        currentPassOK = false;
    }

    if (!newPass.match(/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)[a-zA-Z\d]{8,}$/)) {
        showError('#newPass', 'Your password should consists of uppercase, lowercase, letter and longer than 8.');
        newPassOK = false;
    }

    if (newPass === null){
        showError('#newPass', 'Your password should consists of uppercase, lowercase, letter and longer than 8.');
        newPassOK = false;
    }

    if (!retypePass.match(/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)[a-zA-Z\d]{8,}$/)) {
        showError('#retypePass', 'Your password should consists of uppercase, lowercase, letter and longer than 8.');
        retypePassOK = false;
    }

    if (retypePass === null){
        showError('#retypePass', 'Your password should consists of uppercase, lowercase, letter and longer than 8.');
        retypePassOK = false;
    }

    if (retypePass!=newPass) {
        showError('#retypePass', 'Your retype password does not match with your new password.');
        retypePassOK = false;
    }

    if (currentPassOK && newPassOK && retypePassOK) {
        return true;
    }
    event.preventDefault();
    event.stopPropagation();
    return false;

})



//form validation on delete account
$('#passDeleteAccForm').submit(function (event) {
    hideError('#pw_delete_acc')
    let passOK = true;
    let pass = $('#pw_delete_acc').val();

    if (md5(pass) != document.getElementById('password').value){
        showError('#pw_delete_acc', 'Your password is incorrect.');
        passOK = false;
    }

    if (passOK) {
        return true;
    }

    event.preventDefault();
    event.stopPropagation();
    return false;
})