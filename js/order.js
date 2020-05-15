//select date
$('.myCalendar').calendar({
    date: new Date(),
    autoSelect: false, // false by default
    select: function(date) {
        document.getElementById("order-date").textContent = date.toLocaleDateString("en-US");

    },
})

//open, close modal
var modal = document.querySelector('.bg-modal');

document.querySelector('.btn-order').addEventListener('click', function () {
    modal.style.display = 'flex';
});

window.addEventListener('click', function (e) {
    if (e.target == modal) {
        modal.style.display = 'none';
    }
});

//add or minus
function incrementValue(e) {
    e.preventDefault();
    var fieldName = $(e.target).data('field');
    var parent = $(e.target).closest('div');
    var currentVal = parseInt(parent.find('input[name=' + fieldName + ']').val(), 10);

    if (!isNaN(currentVal)) {
        parent.find('input[name=' + fieldName + ']').val(currentVal + 1);
    } else {
        parent.find('input[name=' + fieldName + ']').val(0);
    }
}

function decrementValue(e) {
    e.preventDefault();
    var fieldName = $(e.target).data('field');
    var parent = $(e.target).closest('div');
    var currentVal = parseInt(parent.find('input[name=' + fieldName + ']').val(), 10);

    if (!isNaN(currentVal) && currentVal > 0) {
        parent.find('input[name=' + fieldName + ']').val(currentVal - 1);
    } else {
        parent.find('input[name=' + fieldName + ']').val(0);
    }
}

$('.input-group').on('click', '.button-plus', function(e) {
    incrementValue(e);
});

$('.input-group').on('click', '.button-minus', function(e) {
    decrementValue(e);
});

//get order
function getDetails(){
    document.getElementById("confirmBut").onclick=function(){
        var fr = document.getElementById("friedRice").value;
        var sa = document.getElementById("satay").value;
        var ns = document.getElementById("nasiAyam").value;
        var sp = document.getElementById("spaghetti").value;
        var ty = document.getElementById("tomyam").value;
        var sy = document.getElementById("syrup").value;
        var mg = document.getElementById("mango").value;
        var mi = document.getElementById("milo").value;

        if (parseInt(fr)>0){
            var food1 = "Fried Rice x" + fr;
            document.getElementById("food1").textContent = food1;


        }
        if (parseInt(sa)>0){
            var food2 = "Satay x" + sa;
            document.getElementById("food2").textContent = food2;

        }
        if (parseInt(ns)>0){
            var food3 = "Nasi Ayam x" + ns;
            document.getElementById("food3").textContent = food3;

        }
        if (parseInt(sp)>0){
            var food4 = "Spaghetti x" + sp;
            document.getElementById("food4").textContent = food4;

        }
        if (parseInt(ty)>0){
            var food5 = "Tom Yam x" + ty;
            document.getElementById("food5").textContent = food5;

        }
        if (parseInt(sy)>0){
            var food6 = "Syrup x" + sy ;
            document.getElementById("food6").textContent = food6;

        }
        if (parseInt(mg)>0){
            var food7 = "Mango x" + mg;
            document.getElementById("food7").textContent = food7;

        }
        if (parseInt(mi)>0){
            var food8 = "Milo x" + mi;
            document.getElementById("food8").textContent = food8;

        }
        modal.style.display = "none";
        document.querySelector(".button1").style.display = 'block';
    }
}

//cancel order
document.querySelector('.button1').addEventListener('click', function () {
    document.getElementById("date-food").textContent = '';
});