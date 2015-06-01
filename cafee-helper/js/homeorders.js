$(function (){
    
    
});

    
        if(typeof(EventSource)!=="undefined") 
        {
            //create an object, passing it the name and location of the server side script
            var eSource = new EventSource("pushorders");
            //detect message receipt
              //console.log("vvvvvvvvvvvvvvvv");
            eSource.onmessage = function(event) {
                    //write the received data to the page
                   
                    var stream = event.data.toString();
                    var data = stream.split('**');
                    orders = JSON.parse(data[0]);
                    details = JSON.parse(data[1]);
              
                    emptytable= '<table id="homeorder"></table>'
                    $("#homeorder").replaceWith(emptytable);
                     for (i = 0; i < orders.length; i++)
                    {
                   
                   row ='<tr class="title"><td>Order Date</td><td>Name</td><td>Room</td><td>Ext</td><td>Action</td></tr>'
                            +'<tr class="orderdata"><td>'+orders[i]["date"]+'</td><td>'+orders[i]["name"]+'</td>'
                            +'<td>'+orders[i]["room"]+'</td><td>'+orders[i]["ext"]+'</td>'
                            +'<td><button class="changestatus" id="'+orders[i]["orderid"]+'" >deliver</button> </td></tr>'
                            +'<tr ><td class="viewdetails" colspan="5">'
                            +'<div ><button class="getdetail" id="'+orders[i]["orderid"]+'" >&rArr;</button> </div><div class="detail" ></div>'
                            +'<div><span class="ordercomments">Comment:&nbsp'+orders[i]["notes"]+'</span>'
                            +'<span class="totaloforder">Total : &nbsp; EGP '+orders[i]["total_price"]+' </span></div></td></tr>';
                   $("#homeorder").append(row);
                     id =orders[i]["orderid"];
                     //product = details;
                    // $(".details").each(function () {$(this).replaceWith('<div class="details"> </div>');});
//                     console.log(details[id]);
//                        for (x =0; x < details[id].length;x++)
//                        {
//                            
//                            product='<div class="productinfo" >'
//                                +'<img  src="/cafee/public/img/product/'+details[id][x]["picture"]+'" alt="product"/>' 
//                                + '<p id =' +id + '>' +details[id][x]["name"] + '</p>' 
//                                +'<p>'+details[id][x]["amount"]+'</p>'
//                                +'<span class="price">'+details[id][x]["price"]+'</span><span >L.E &nbsp;</span></div>';
//                             
//                             if(orders[i]["orderid"] ==details[id][x]["orderid"] )
//                             {
//                                //$(".details").each(function () {$(this).append(product);});
//                                console.log(i);
//                             }
//                             else
//                             {
//                                 break;
//                             }
//                        }
                      //break;
               
                    }
              //    var arr = $.map(details, function(value, index) {return [value];});
                  
                  //document.getElementById("serverdata").innerHTML = data[1];
                   //console.log("vvvvvvvvvvvvvvvv ");
                   //alert(orders);
       $("#homeorder").delegate('.getdetail','click',function() {
          var id = $(this).attr('id'); 
          $(this).parent().next().replaceWith('<div class="productinfo" ></div>');
          for (x =0; x < details[id].length;x++)
                        {
                            
                            product='<div class="productinfo" >'
                                +'<img  src="/cafee/public/img/product/'+details[id][x]["picture"]+'" alt="product"/>' 
                                + '<p id =' +id + '>' +details[id][x]["name"] + '</p>' 
                                +'<p>'+details[id][x]["amount"]+'</p>'
                                +'<span class="price">'+details[id][x]["price"]+'</span><span >L.E &nbsp;</span></div>';
                             
                                $(this).parent().next().append(product);
                
                        }
                        $(this).text("⇓");
                   
       });
      $("#homeorder").delegate('.changestatus','click',function() {

      status = "r";
      id = $(this).attr('id');
      alert("loaded"+id);
      $(this).parents("tr.orderdata").prev().remove();
      $(this).parents("tr.orderdata").next().remove();
      $(this).parents("tr.orderdata").remove();
      $.ajax({
             type: "GET",
             url: "/cafee/public/Order/updateallorders?status="+status+"&id="+id,
            //dataType: 'json',
            //data: "status="+status ,
         
            success: function (msg) {
                alert("sucesss");
                console.log(msg);
            },
            error: function (xhr, ajaxOptions, thrownError) {
                alert(xhr.status + " " + thrownError);
            }
        });

    });
   
            };
        }
       else 
       {
            document.getElementById("serverdata").innerHTML="Whoops! Your browser doesn't receive server-sent events.";
       }
       
       
       