let artist_is_selling_shares = true;

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