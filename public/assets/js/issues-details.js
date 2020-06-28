$("#type, #location, #img, #details").attr("disabled", "disabled");
const MAX_LENGTH = 500;
let problemtype = $('#type'), problemlocation = $('#location'), problemdetail = $('#details'), problemimage = $('#img'),
    alertError = $('#error-alert');

alertError.hide();
$('#cancel').hide();

$('#error-alert.close').click(function () {
    alertError.hide();
});

problemtype.change(function () {
    hideError('#type');
});

problemlocation.change(function () {
    hideError('#location');
});

problemdetail.on('keyup keydown', function () {
    if (this.value.trim().length > MAX_LENGTH) {
        return false;
    }
    $("#remaining").html((MAX_LENGTH - this.value.trim().length) + "/" + MAX_LENGTH);
});

problemdetail.keyup(function () {
    hideError('#details')
})

problemimage.change(function () {
    console.log(this.value);
    hideError('#img');
    let filename = 'Select image ';
    let buttontext = 'Browse';

    if (this.value) {
        filename = this.value.replace("C:\\fakepath\\", "");
        buttontext = 'Remove image';
    }

    $('.custom-file-label').attr('text', buttontext);
    $(this).prev('.custom-file-label').html(filename);
})

problemimage.click(function () {
    if (this.value) {
        this.value = null;
        $(this).change();
        return false;
    }
    return true;
})

$('#new-issue').submit(function (event) {
    event.preventDefault();
    event.stopPropagation();

    if (problemtype.prop('disabled') || problemlocation.prop('disabled') || problemimage.prop('disabled') || problemdetail.prop('disabled')) {
        $("#type, #location, #img, #details").removeAttr('disabled');
        $('#submit').text('Save changes');
        $('#cancel').show();
        $('#delete').hide();
        return;
    }

    hideError('#type, #location, #details, #img')
    let problemtypeOK = true, problemlocationOK = true, problemdetailOK = true, problemimageOK = true;
    let problem_type = problemtype.val(),
        problem_location = problemlocation.val(),
        problem_detail = problemdetail.val().trim(),
        problem_image = problemimage[0].files[0];

    if (!problem_type) {
        showError('#type', 'Please select a problem type.');
        problemtypeOK = false;
    }

    if (!problem_location) {
        showError('#location', 'Please select a problem location.');
        problemtypeOK = false;
    }

    if (problem_detail === '') {
        showError('#details', 'Please provide the details on the issue.');
        problemdetailOK = false;
    }

    if (problem_detail.length > MAX_LENGTH) {
        showError('#details', 'You have exceeded the maximum character limit of 500. Please shorten it.')
        problemdetailOK = false;
    }

    if (problem_image && (problem_image.size / 1024 / 1024) > 1) {
        showError('#img', 'File size exceeded limit of 1MB.');
        problemimageOK = false
    }

    if (problem_image && !problem_image.name.match(/\.(jpe?g|png)$/i)) {
        showError('#img', 'Only image with .jpg , .jpeg and .png extension is acceptable.');
        problemimageOK = false;
    }

    if (problemtypeOK && problemlocationOK && problemdetailOK && problemimageOK) {
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
                    $('#error-alert > span').text('Please sign in to submit your report.');
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
        url: '/api/issues/delete/' + id,
        success: function (data) {
            console.log(data);
            $('#modal-delete-issue').modal('hide');
            window.location.href = '/issues';
        },
        error: function (xhr) {
            console.log(xhr.responseText);
            $('#modal-delete-issue').modal('hide');
            $('#error-alert > span').text('Opps, your deletion has failed due to some server issues. Please try again.');
            alertError.show();
        }
    });
})

$('#cancel').click(function () {
    $("#type, #location, #img, #details").attr("disabled", "disabled");
    $('#submit').text('Edit');
    $(this).hide();
    $('#delete').show();
})