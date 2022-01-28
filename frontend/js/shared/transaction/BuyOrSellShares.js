let artist_is_selling_shares = true;

class buy_back_btn_id_t 
{
    constructor(id, is_active)
    {
        this.id = id;
        this.is_active = is_active;
    }
}

class Buttons 
{
    constructor() 
    {
        // this.buttons = new Array(parseInt($("#sell_orders_size").text()));
        // if(Object.seal)
        // {
        //     this.buttons.fill(undefined);
        //     Object.seal(this.buttons);
        // }
        this.buttons = [];
    }

    newButton(id)
    {
        let button = new buy_back_btn_id_t(id, true);
        this.buttons.push(button)
    }
    
    getAllButtons()
    {
        return this.buttons;
    }

    getNoOfButtons()
    {
        return this.buttons.length;
    }
}

let sell_order_buttons = new Buttons();

function artistPostSellOrder()
{
    $.ajax({
        type: "POST",
        url: window.location.protocol + "//" + window.location.hostname + ":" + window.location.port + "/Hassner/backend/artist/SellOrderBackend.php",
        data: {
            asked_price: $("#artist_pps_selling").val(),
            purchase_quantity: $("#myRange").val()
        },
        async: false,
        dataType: "json",
        success: function(data){
            if(data.status != "SUCCESS")
            {
                $("#artist_sell_share_status").text(data.msg);
                $("#artist_sell_share_status").addClass("error-msg");
                $("#artist_sell_share_status").show();
            }
            else
            {
                artist_is_selling_shares = false;
                $("#artist_sell_share_content").hide();
                $("#artist_sell_share_success").text(data.msg);
                $("#artist_sell_share_success").addClass("suc-msg");
            }
        },
        error: function(data){

        }
    });
}

function artistSellShare()
{
    if(artist_is_selling_shares)
    {
        $("#artist_sell_share_content").show();
        artist_is_selling_shares = false;
    }
    else
    {
        $("#artist_sell_share_content").hide();
        artist_is_selling_shares = true;
    }
}

function buyBackShareClick(i)
{
    console.log(sell_order_buttons.getNoOfButtons());
    if(sell_order_buttons.getNoOfButtons() <= i)
    {
        sell_order_buttons.newButton(i);
    }
    if(sell_order_buttons.getAllButtons()[i].is_active)
    {
        var slider_max = 1;
        // $("#artist_buy_back_shares_btn_"+i).hide();
        $("#artist_buy_back_shares_btn_"+i).val("-");
        $("#artist_buy_back_content_"+i).show();

        $.ajax({
            type: "POST",
            url: window.location.protocol + "//" + window.location.hostname + ":" + window.location.port + "/Hassner/backend/artist/MaxNumOfShares.php",
            data: {
                price: $("#sell_order_price").text(),
                quantity: $("#sell_order_quantity").text()
            },
            async: false,
            dataType: "json",
            success: function(data){
                slider_max = data;
            },
            error: function(data){
    
            }
        });
        $("#buy_num_"+i).slider({
            range: "min",
            min: 1,
            max: slider_max,
            value: 1,
            slide: function( event, ui ) {
                $("#buy_num_shares_"+i).val(ui.value);
            }
        })


        // Accoridon init
        $( "#buy_accordion").accordion({
            heightStyle: "content",
            collapsible: true,
            active: false
        });

        sell_order_buttons.getAllButtons()[i].is_active = false;
    }
    else
    {
        $("#artist_buy_back_shares_btn_"+i).val("buy");
        $("#artist_buy_back_content_"+i).hide();
        sell_order_buttons.getAllButtons()[i].is_active = true;
    }
}

function buyBackShare(sell_order_id, index)
{
    console.log($("#buy_num_shares_"+index).val());
}

$( function() {
    
    $("#artist_sell_share_btn").click(artistSellShare);
    $("#artist_post_sell_order_btn").click(artistPostSellOrder);
});

$(document).keypress(function (e) {
    var key = e.which;
    if(key == 13)  // the enter key code
     {
       $('#artist_post_sell_order_btn').click();
       return false;  
     }
});  