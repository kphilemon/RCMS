$('#modal-welcome-new-user').modal("show");

$('#modal-delete-acc .btn-danger').click(function () {
    signOut();
    localStorage.clear();
    window.location.href = '/';
});

$('#modal-user-details-saved .btn-outline-primary').click(function () {
    window.location.href = '/';
});

$('#modal-user-details-updated .btn-outline-primary').click(function () {
    window.location.href = '/';
});


//check for form validation
'use strict';
window.addEventListener('load', function () {
    // Fetch all the forms we want to apply custom Bootstrap validation styles to
    var forms = document.getElementsByClassName('needs-validation');
    // Loop over them and prevent submission
    var validation = Array.prototype.filter.call(forms, function (form) {
        form.addEventListener('submit', function (event) {
            if (form.checkValidity() === false) {
                event.preventDefault();
                event.stopPropagation();
            }
            form.classList.add('was-validated');
        }, false);
    });
}, false);


//if form is validated and button is pressed, will prompt a modal
// check for form validity
document.getElementById("details").onsubmit = function (e) {
    var x1 = this.validationCustom01.value;
    var x2 = this.validationCustom02.value;
    var x3 = this.validationDefault03.value;
    var x4 = this.validationDefault04.value;
    var x5 = this.validationDefault05.value;
    var x7 = this.inputBank.value;
    var x8 = this.inputBankAcc.value;
    var x9 = this.inputNationality.value;
    var x10 = this.inputTel.value;
    var x11 = this.sex.value;
    var x12 = this.inputDOB.value;


    if (x1 && x2 && x3 && x4 && x5 && x7 && x8 && x9 && x10 && x11 && x12) {
        e.preventDefault();

        return true;
    }
    return false;
};
