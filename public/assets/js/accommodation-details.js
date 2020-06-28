$("#college, #check-in, #check-out, #supporting-docs, #purpose").attr("disabled", "disabled");
const MAX_LENGTH = 5000;
let college = $('#college'), checkIn = $('#check-in'), checkOut = $('#check-out'),
    supportingDocs = $('#supporting-docs'), purpose = $('#purpose'), alertError = $('#error-alert');

alertError.hide();
$('#cancel').hide();

$('#error-alert .close').click(function () {
    alertError.hide();
});

college.change(function () {
    hideError('#college');
});

checkIn.change(function () {
    hideError('#check-in');
});

checkOut.change(function () {
    hideError('#check-out');
});

purpose.on('keyup keydown', function () {
    if (this.value.trim().length > MAX_LENGTH) {
        return false;
    }

    $("#remaining").html((MAX_LENGTH - this.value.trim().length) + "/" + MAX_LENGTH);
});

purpose.keyup(function () {
    hideError('#purpose');
});

supportingDocs.change(function () {
    hideError('#supporting-docs');
    let fileName = 'Select file ';
    let buttonText = 'Browse';

    if (this.value) {
        fileName = this.value.replace("C:\\fakepath\\", "");
        buttonText = 'Remove file';
    }

    $('.custom-file-label').attr('text', buttonText);
    $(this).prev('.custom-file-label').html(fileName);
});

supportingDocs.click(function () {
    if (this.value) {
        this.value = null;
        $(this).change();
        return false;
    }
    return true;
});


$('#details').submit(function (event) {
    event.preventDefault();
    event.stopPropagation();

    if (college.prop('disabled') || checkIn.prop('disabled') || checkOut.prop('disabled') || purpose.prop('disabled') || supportingDocs.prop('disabled')) {
        $("#college, #check-in, #check-out, #supporting-docs, #purpose").removeAttr('disabled');
        $('#submit').text('Save changes');
        $('#cancel').show();
        $('#delete').hide();
        return;
    }

    hideError('#college, #check-in, #check-out, #supporting-docs, #purpose')
    let collegeOK = true, checkInOK = true, checkOutOK = true, docsOK = true, purposeOK = true;
    let collegeNum = college.val(),
        checkInDate = Date.parse(checkIn.val()),
        checkOutDate = Date.parse(checkOut.val()),
        supportingDocsFile = supportingDocs[0].files[0],
        purposeText = purpose.val().trim();

    if (collegeNum < 1 || collegeNum > 12) {
        showError('#college', 'Please select a college.');
        collegeOK = false;
    }

    if (!checkInDate) {
        showError('#check-in', 'Please select a date.');
        checkInOK = false;
    }

    if (!checkOutDate) {
        showError('#check-out', 'Please select a date.');
        checkOutOK = false;
    }

    let thirtyDaysFromNow = new Date();
    thirtyDaysFromNow.setHours(0, 0, 0, 0);
    thirtyDaysFromNow.setDate(thirtyDaysFromNow.getDate() + 30);
    if (checkInDate < thirtyDaysFromNow) {
        showError('#check-in', 'Check-in date must be at least 30 days from today.');
        checkInOK = false;
    }

    if (checkOutDate <= thirtyDaysFromNow || checkInDate >= checkOutDate) {
        showError('#check-out', 'Check-out date must be greater than the valid check-in date.');
        checkOutOK = false;
    }

    if (supportingDocsFile && (supportingDocsFile.size / 1024 / 1024) > 1) {
        showError('#supporting-docs', 'File size exceeded limit of 1MB.');
        docsOK = false
    }

    if (supportingDocsFile && !supportingDocsFile.name.match(/\.(pdf)$/i)) {
        showError('#supporting-docs', 'Only files with .pdf extension is acceptable.');
        docsOK = false;
    }

    if (purposeText === '') {
        showError('#purpose', 'Please provide a reason for your application.');
        purposeOK = false;
    }

    if (purposeText.length > MAX_LENGTH) {
        showError('#purpose', 'You have exceeded the maximum character limit of 5000. Please shorten it.');
        purposeOK = false;
    }

    if (collegeOK && checkInOK && checkOutOK && docsOK && purposeOK) {
        $.ajax({
            type: 'POST',
            url: $(this).attr('action'),
            enctype: 'multipart/form-data',
            data: new FormData(this),
            cache: false,
            contentType: false,
            processData: false,
            success: function (data) {
                console.log(data);
                window.location.reload(true);
            },
            error: function (xhr) {
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
                } else if (xhr.status === 401) {
                    // User not logged in
                    $('#error-alert > span').text('Please sign in to submit your application.');
                    alertError.show();

                } else {
                    // Server errors
                    $('#error-alert > span').text('Opps, your submission has failed due to some server issues. Please try again later.');
                    alertError.show();
                }
            },
        });
    }

})

$('#confirm-delete').click(function (event) {
    let id = $(this).data('id');
    $.ajax({
        url: '/api/accommodation/delete/' + id,
        success: function (data) {
            console.log(data);
            $('#modal-delete-accommodation').modal('hide');
            window.location.href = '/accommodation';
        },
        error: function (xhr) {
            console.log(xhr.responseText);
            $('#modal-delete-accommodation').modal('hide');
            $('#error-alert > span').text('Opps, your deletion has failed due to some server issues. Please try again.');
            alertError.show();
        }
    });
})

$('#cancel').click(function () {
    $("#college, #check-in, #check-out, #supporting-docs, #purpose").attr("disabled", "disabled");
    $('#submit').text('Edit');
    $(this).hide();
    $('#delete').show();
})