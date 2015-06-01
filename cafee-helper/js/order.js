
$(function () {


    $("#products").delegate(".productinfo img", 'click', function () {

        id = $(this).siblings("p").attr('id');
        name = $(this).siblings("p").text();
        price = $(this).siblings(".price").text();
        _price = price;
        console.log("gggggggggggggggg" + id);
        if (itemFound(name))
        {
            updateItem(name, price);
        }
        else
        {
            addItem(id, name, price);

        }
    });

    $("#selecteditems").delegate(".remove", 'click', function () {

        $(this).parents("tr.item").remove();
        updateTotal();
    });

    $("#selecteditems").delegate(".more", 'click', function () {

        var itemval = document.getElementsByClassName("amount");
        var newval = Number($(this).parent("td").prevUntil(itemval).first().children("input").attr('value')) + 1;
        if (newval > 0)
        {
            $(this).parent("td").prevUntil(itemval).first().children("input").attr('value', newval);
            var _name = $(this).parent("td").prevUntil(itemval).eq(1).text();
            updateAmount(newval, _name);
        }
    });

    $("#selecteditems").delegate(".less", 'click', function () {

        var itemval = document.getElementsByClassName("amount");
        var newval = Number($(this).parent("td").prevUntil(itemval).first().children("input").attr('value')) - 1;
        if (newval > 0)
        {
            $(this).parent("td").prevUntil(itemval).first().children("input").attr('value', newval);
            var _name = $(this).parent("td").prevUntil(itemval).last().text();
            updateAmount(newval, _name);
        }

    });


    // confirm order AS AN ADMIN
    $('#addorder').click(function (e) {
        e.preventDefault();
        products = []; //array of product shayel objectsss prodid,amount
        orders = {};  // object shayel el array + ba2y el infooo
        notes = $("#detail textarea").val();
        user_id = $("#selectuser").val();
        room_id = $("#selectroom").val();
        totalpricee = $("#totalprice").text();

        $("#selecteditems").find($(".amount")).each(function () {

            prod_id = $(this).attr("id");
            prod_amount = $(this).val();
            product = {prod_id: prod_id, prod_amount: prod_amount};
            products.push(product)


//            console.log("product id is :  " + prod_id);
//            console.log("product quantityy is :  " + prod_amount);
//            console.log("order notes is :  " + notes);
//            console.log("order user_id is :  " + user_id);
//            console.log("order room_id is :  " + room_id);
//            console.log("order total price is :  " + totalpricee);
        });

        console.log(products);
        orders.products = products;
        orders.notes = notes;
        orders.userid = user_id;
        orders.roomid = room_id;
        orders.totalprice = totalpricee;
        console.log(products);
        console.log(orders);
        myorder = JSON.stringify(orders);
//alert(orders);

        $.ajax({
            // type: "POST",
            type: "GET",
            //  url: "/cafee/public/Order/confirmorder",
            url: "/cafee/public/Order/confirmadminorder?myorder=" + myorder,
            dataType: 'html',
            //data: JSON.stringify(orders),

            success: function (msg) {
                console.log("sucesss");
                //console.log(msg);
                usersss = $($.parseHTML(msg)).find("#users");
                userscurr = $("#users");
                userscurr.html(usersss);

                selected = $($.parseHTML(msg)).find("#selecteditems");
                selectedcurr = $("#selecteditems");
                selectedcurr.html(selected);



                room = $($.parseHTML(msg)).find("#myrooms");
                selectedroom = $("#myrooms");
                selectedroom.html(room);

                $("#detail textarea").val(" ");
                $("#totalprice").text("00");





                //document.innerHTML=msg;

            },
            error: function () {
                alert("errrooooooooorrrrr");
            }
        });
    });


});

function itemFound(_name)
{
    items = $("#selecteditems").find($(".name"));
    found = false;
    for (var i = 0; i < items.length; i++)
    {
        if (items[i].innerHTML == _name)
        {
            found = true;
        }
    }
    return found;
}

function updateItem(_name, _price)
{
    items = $("#selecteditems").find($(".name"));
    amounts = $("#selecteditems").find($(".amount"));
    prices = $("#selecteditems").find($(".price"));

    for (var i = 0; i < items.length; i++)
    {
        if (items[i].innerHTML === _name)
        {
            newamount = Number(amounts[i].getAttribute('value')) + 1;
            amounts[i].setAttribute('value', newamount);
            totalprice = _price * newamount;
            prices[i].innerHTML = totalprice;
            updateTotal();
            break;
        }
    }

}

function updateTotal()
{
    total = 0;

    prices = $("#selecteditems").find($(".price"));

    for (var i = 0; i < prices.length; i++)
    {
        total += Number(prices[i].innerHTML);
    }
    $("#totalprice").text(total);
    $("#egp").text("EGP");

}

function addItem(_id, _name, _price)
{
    row = '<tr class="item">'
            + '<td  class="name">' + _name + '</td>'
            + '<td  ><input type="number" id="' + _id + '" class="amount" name="amount" value="1" readonly /></td>'
            + '<td><button class="more">+</button><button class="less">-</button></td>'
            + '<td><p>EGP&nbsp;</p><span class="price">' + _price + '</span></td>'
            + '<td><button class="remove">X</button></td>'
            + '</tr>';
    $("#selecteditems table").append(row);
    updateTotal();
}

function updateAmount(_amount, itemname)
{
    if (_amount > 0)
    {
        var names = $("#products").find($("p"));
        var items = $("#selecteditems").find($(".name"));
        var productsprice = $("#products").find($(".price"));
        var itemsprice = $("#selecteditems").find($(".price"));
        var _price;
        for (var i = 0; i < names.length; i++)
        {
            if (names[i].innerHTML === itemname)
            {
                _price = Number(productsprice[i].innerHTML);
                break;
            }
        }

        for (var i = 0; i < items.length; i++)
        {
            if (items[i].innerHTML === itemname)
            {
                var total = _price * _amount;
                itemsprice[i].innerHTML = total;
                updateTotal();
                break;
            }
        }
    }
}