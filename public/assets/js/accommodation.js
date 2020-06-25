let table = $('#table').DataTable({
    "searching": false,
    "stateSave": true,
    "columnDefs": [{
        "targets": [0, 4, 5, 6],
        "orderable": false
    }, {
        "targets": 5,
        "render": data => {
            let badge = '';
            let word = '';

            switch (data) {
                case '0':
                    badge = 'warning';
                    word = 'Submitted';
                    break;
                case '1':
                    badge = 'success';
                    word = 'Approved';
                    break;
                case '2':
                    badge = 'danger';
                    word = 'Rejected';
                    break;
            }
            return '<span class="badge badge-' + badge + '">' + word + '</span>';
        }
    }, {
        "targets": 6,
        "render": function (data, type, row) {

            if (row[5] === '0') {
                return '<span><a class="delete-record" href="#" data-id="' + row[1] + '">Delete</a> | <a href="/accommodation/' + row[1] + '">Edit</a> </span>';
            } else {
                return '<span><a class="delete-record" href="#" data-id="' + row[1] + '">Delete</a></span>'
            }
        }
    }],
    "order": [[1, 'asc']],
    "language": {
        "emptyTable": "No application records found.<br>Click on \"Submit New\" to create a new application."
    }
});

$('#table tbody').on('click', 'tr', function () {
    let data = table.row(this).data();
    window.location.href = '/accommodation/' + data[1];
});

table.on('order.dt search.dt', function () {
    table.column(0, {search: 'applied', order: 'applied'}).nodes().each(function (cell, i) {
        cell.innerHTML = i + 1;
    });
}).draw();

$('.delete-record').click(function (event) {
    let id = $(this).data('id');
    $.ajax({
        url: '/api/accommodation/delete/' + id,
        success: function (data) {
            console.log(data);
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
        }
    });
    event.stopPropagation();
})