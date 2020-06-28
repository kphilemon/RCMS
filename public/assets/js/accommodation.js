let alertError = $('#error-alert');
alertError.hide();

let table = $('#table').DataTable({
    "searching": false,
    "stateSave": true,
    "columnDefs": [{
        "targets": [0, 4, 5, 6],
        "orderable": false
    }, {
        "targets": 5,
        "render": data => {
            switch (data) {
                case '0':
                    return '<span class="badge badge-warning">Submitted</span>';
                case '1':
                    return '<span class="badge badge-success">Approved</span>';
                case '2':
                    return '<span class="badge badge-danger">Rejected</span>';
                default:
                    return '';
            }
        }
    }, {
        "targets": 6,
        "render": function (data, type, row) {
            if (row[5] === '0') {
                return '<span><a class="delete-row" href="#" data-id="' + row[1] + '">Delete</a> | <a href="/accommodation/' + row[1] + '">Edit</a> </span>';
            } else {
                return '<span><a class="delete-row" href="#" data-id="' + row[1] + '">Delete</a></span>'
            }
        }
    }],
    "order": [[1, 'asc']],
    "language": {
        "emptyTable": "No application records found.<br>Click on \"Submit New\" to create a new application."
    }
});

table.on('order.dt search.dt', function () {
    table.column(0, {search: 'applied', order: 'applied'}).nodes().each(function (cell, i) {
        cell.innerHTML = i + 1;
    });
}).draw();

$('#table tbody').on('click', 'tr', function () {
    let data = table.row(this).data();
    window.location.href = '/accommodation/' + data[1];
});

$('.delete-row').click(function (event) {
    event.stopPropagation();
    let id = $(this).data('id');
    $('#modal-delete-accommodation #accommodation-id').text('#' + padId(id, 4));
    $('#modal-delete-accommodation #confirm-delete').data('id', id);
    $('#modal-delete-accommodation').modal('show');
})

$('#confirm-delete').click(function () {
    let id = $(this).data('id');
    $.ajax({
        url: '/api/accommodation/delete/' + id,
        success: function (data) {
            console.log(data);
            $('#modal-delete-accommodation').modal('hide');
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
            $('#modal-delete-accommodation').modal('hide');
            $('#error-alert > span').text('Opps, your deletion has failed due to some server issues. Please try again.');
            alertError.show();
        }
    });
})