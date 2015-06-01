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
        user_id=$('#selectuser').val();
        $.ajax({
            // type: "POST",
            type: "GET",
            //  url: "/cafee/public/Order/confirmorder",
            url: "/cafee/public/Order/mychecksajax?mystartdate=" + mystartdate + "&enddate=" + myenddate + "&user_id=" + user_id,
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


    $("#checks").delegate(".myname", "click", function (e) {
        e.preventDefault();
        alert("hahaaa");

          mystartdate = $('#startdatepicker').val();
        myenddate = $('#enddatepicker').val();
        user_id = $('.myname').attr("id");
        alert(user_id);
        //alert(date + " "+ user_id);
        //<img src="'.$this->baseUrl().'/upload/'.$this->products[$i]['prod_image'].'" height="75" width="75"/>
        $.ajax({
            type: "GET",
            url: "/cafee/public/Order/myuserschecks?mystartdate=" + mystartdate + "&enddate=" + myenddate +"&user_id=" + user_id,
            dataType: 'html',
            success: function (msg) {
                
                myprods = $($.parseHTML(msg)).find("#myuserdates");
                myprodscur = $("#myuserdates");
                myprodscur.html(myprods);

            },
            error: function () {
                alert("errrooooooooorrrrr");
            }


        });
    });
    
    
    
     $("#myuserdates").delegate(".mydate", "click", function (e) {
        e.preventDefault();

        
        order_id = $(this).attr("id");
        alert(order_id);
        //alert(date + " "+ user_id);
        //<img src="'.$this->baseUrl().'/upload/'.$this->products[$i]['prod_image'].'" height="75" width="75"/>
        $.ajax({
            type: "GET",
            url: "/cafee/public/Order/mydetails?order_id=" + order_id ,
            dataType: 'html',
            success: function (msg) {
                
                myprods = $($.parseHTML(msg)).find("#alldetails");
                myprodscur = $("#alldetails");
                myprodscur.html(myprods);

            },
            error: function () {
                alert("errrooooooooorrrrr");
            }


        });
    });
});