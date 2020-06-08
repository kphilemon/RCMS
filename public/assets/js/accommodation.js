if (isSignedIn()) {
    $('#navbar').load('common/navbar/signed-in.html', function () {
        $('#pages').find('.active').removeClass('active');
        $('#pages #accommodation').addClass('active');

        $('#logout').click(function () {
            signOut();
            window.location.href = 'index.html';
        });
        $('#menu-logout').click(function () {
            signOut();
            window.location.href = 'index.html';
        })
    });
} else {
    alert('You are not signed in!');
    window.location.href = 'index.html';
}
$(function () {
    $("#myInput").on("keyup", function () {
        var value = $(this).val().toLowerCase();
        $("#myTable tr").filter(function () {
            $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
        });
    });

    editable();

    $('#submit').click(function () {
        window.location.href = 'new-accommodation.html';
    })

});

function editable() {
    var row = document.getElementsByTagName("TR").length;
    var i;
    for (i = 1; i < row; i++) {
        if (document.getElementsByTagName("TR")[i].getElementsByTagName("TD")[4].getAttribute("class") === "badge badge-pending") {
            document.getElementsByTagName("TR")[i].getElementsByTagName("TD")[5].innerHTML = "Edit";
            document.getElementsByTagName("TR")[i].getElementsByTagName("TD")[5].setAttribute("onclick", "location.href='editReport.html';");
        } else {
            document.getElementsByTagName("TR")[i].getElementsByTagName("TD")[5].innerHTML = "Delete";
            document.getElementsByTagName("TR")[i].getElementsByTagName("TD")[5].setAttribute("onclick", "deleteRow(this)");
        }
    }
}

function deleteRow(r) {
    var i = r.parentNode.rowIndex;
    document.getElementById("table").deleteRow(i);
}

$('#table').DataTable({
    searching: false,
    ordering: false,
    lengthMenu: [[-1, 5, 10, 25, 50], ["All", 5, 10, 25, 50]],
    createdRow: function (row, data, index) {
        if (data[5].replace(/[\$,]/g, '') * 1 > 150000) {
            $('td', row).eq(5).addClass('text-success');
        }
    }
});