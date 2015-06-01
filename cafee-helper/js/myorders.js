$(function () {
    $("#startdatepicker").datepicker({appendText: "(yyyy-mm-dd)"
        , dateFormat: "yy-mm-dd"
    });

    $("#startdatepicker").on('change', function () {
        mystartdate = $('#startdatepicker').val();
        $('#startdatepicker').val(mystartdate + " 00:00:00");
    });

    $("#enddatepicker").datepicker({appendText: "(yyyy-mm-dd)", dateFormat: "yy-mm-dd"

    });

    $("#enddatepicker").on('change', function () {
        myenddate = $('#enddatepicker').val();
        $('#enddatepicker').val(myenddate + " 00:00:00");
    });

    $('#myorders').click(function (e) {
        e.preventDefault();
        mystartdate = $('#startdatepicker').val();
        myenddate = $('#enddatepicker').val();
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

        });


    });



    $("#checks").delegate(".mydate", "click", function (e) {
        e.preventDefault();
        alert("hahaaa");

        var date = $(this).text();
        var user_id = $(this).attr("id");
        //alert(date + " "+ user_id);
        //<img src="'.$this->baseUrl().'/upload/'.$this->products[$i]['prod_image'].'" height="75" width="75"/>
        $.ajax({
            type: "GET",
            url: "/cafee/public/Order/ylmyordersdateajax?mydate=" + date + "&user_id=" + user_id,
            dataType: 'html',
            success: function (msg) {
                myprods = $($.parseHTML(msg)).find("#myprods");
                myprodscur = $("#myprods");
                myprodscur.html(myprods);

            },
            error: function () {
                alert("errrooooooooorrrrr");
            }


        });
    });
});