let alertError = $('#error-alert');
alertError.hide();

let table = $('#table').DataTable({
    "searching": false,
    "stateSave": true,
    "columnDefs": [{
        "targets": [0, 2, 3, 5, 6],
        "orderable": false
    }, {
        "targets": 5,
        "render": data => {
            switch (data) {
                case '0':
                    return '<span class="badge badge-warning">Pending</span>';
                case '1':
                    return '<span class="badge badge-success">Completed</span>';
                case '2':
                    return '<span class="badge badge-danger">In-progress</span>';
                default:
                    return '';
            }
        }
    }, {
        "targets": 6,
        "render": function (data, type, row) {
            if (row[5] === '0') {
                return '<span><a class="delete-row" href="#" data-id="' + row[1] + '">Delete</a> | <a href="/issues/' + row[1] + '">Edit</a> </span>';
            } else if (row[5] === '1') {
                return '<span><a class="delete-row" href="#" data-id="' + row[1] + '">Delete</a></span>'
            } else {
                return '';
            }
        }
    }],
    "order": [[1, 'asc']],
    "language": {
        "emptyTable": "No issues found.<br>Click on \"Report New\" to create a new issue."
    }
});

table.on('order.dt search.dt', function () {
    table.column(0, {search: 'applied', order: 'applied'}).nodes().each(function (cell, i) {
        cell.innerHTML = i + 1;
    });
}).draw();

$('#table tbody').on('click', 'tr', function () {
    let data = table.row(this).data();
    window.location.href = '/issues/' + data[1];
});

$('.delete-row').click(function (event) {
    event.stopPropagation();
    let id = $(this).data('id');
    $('#modal-delete-issue #issue-id').text('#' + padId(id, 4));
    $('#modal-delete-issue #confirm-delete').data('id', id);
    $('#modal-delete-issue').modal('show');
})

$('#confirm-delete').click(function (event) {
    let id = $(this).data('id');
    $.ajax({
        url: '/api/issues/delete/' + id,
        success: function (data) {
            console.log(data);
            $('#modal-delete-issue').modal('hide');
            let indexes = table
                .rows()
                .indexes()
                .filter(function (value, index) {
                    return id === table.row(value).data()[1];
                });
            table.rows(indexes).remove().draw();
        },
        error: function (xhr) {
            console.log(xhr.responseText);
            $('#modal-delete-issue').modal('hide');
            $('#error-alert > span').text('Opps, your deletion has failed due to some server issues. Please try again.');
            alertError.show();
        }
    });
})