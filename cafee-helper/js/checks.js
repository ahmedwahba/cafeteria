$(function () {
    $("#startdatepicker").datepicker({appendText: "(yyyy-mm-dd)"
   ,   dateFormat: "yy-mm-dd"
 });

    $("#startdatepicker").on('change', function () {
        mystartdate = $('#startdatepicker').val();
        $('#startdatepicker').val(mystartdate + " 00:00:00");
    })

    $("#enddatepicker").datepicker({appendText: "(yyyy-mm-dd)",   dateFormat: "yy-mm-dd"

    });

    $("#enddatepicker").on('change', function () {
        myenddate = $('#enddatepicker').val();
        $('#enddatepicker').val(myenddate + " 00:00:00");
    })


    $('#checkorders').click(function (e) {
        e.preventDefault();
        mystartdate = $('#startdatepicker').val();
        myenddate = $('#enddatepicker').val();
        $.ajax({
            // type: "POST",
            type: "GET",
            //  url: "/cafee/public/Order/confirmorder",
            url: "/cafee/public/Order/checksajax?mystartdate=" + mystartdate + "&enddate=" + myenddate,
                        dataType: 'html',

            success: function (msg) {
                 usersss = $($.parseHTML(msg)).find("#checks");
                userscurr = $("#checks");
                userscurr.html(usersss);

            },
            error: function () {
                alert("errrooooooooorrrrr");
            }

        })


    });
    
    
     $('#myorders').click(function (e) {
        e.preventDefault();
        mystartdate = $('#mystartdatepicker').val();
        myenddate = $('#myenddatepicker').val();
        $.ajax({
            // type: "POST",
            type: "GET",
            //  url: "/cafee/public/Order/confirmorder",
            url: "/cafee/public/Order/myordersajax?mystartdate=" + mystartdate + "&enddate=" + myenddate,
                        dataType: 'html',

            success: function (msg) {
                 usersss = $($.parseHTML(msg)).find("#checks");
                userscurr = $("#checks");
                userscurr.html(usersss);

            },
            error: function () {
                alert("errrooooooooorrrrr");
            }

        })


    })
});