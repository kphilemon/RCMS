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
        "render": function () {

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