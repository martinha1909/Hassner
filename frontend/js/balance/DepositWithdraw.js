function deposit()
{
    $.ajax({
        type: "POST",
        url: window.location.protocol + "//" + window.location.hostname + ":" + window.location.port + "/Hassner/backend/shared/FiatOptionsSwitcher.php",
        data: {
            options: $("#deposit_btn").val()
        },
        async: false,
        dataType: "json",
        success: function(data){
            var fiat_options = data.fiat_options;
            if(fiat_options == "DEPOSIT")
            {
                var currency = $('#dark').find(":selected").text();
                // var currency = $("#dark").val();
                console.log(currency);
                $("#deposit_btn").removeClass("btn btn-primary");
                $("#deposit_btn").addClass("btn btn-secondary");

                $("#balance_div").show();
                $("#deposit_or_withdraw_header").text("Enter Amount in " + currency);
            }
        },
        error: function(){

        }
    });
}

function withdraw()
{
    $.ajax({
        type: "POST",
        url: window.location.protocol + "//" + window.location.hostname + ":" + window.location.port + "/Hassner/backend/shared/FiatOptionsSwitcher.php",
        data: {
            options: $("#withdraw_btn").val(),
        },
        async: false,
        dataType: "json",
        success: function(data){
            var fiat_options = data.fiat_options;
            if(fiat_options == "WITHDRAW")
            {
                $("#withdraw_btn").removeClass("btn btn-primary");
                $("#withdraw_btn").addClass("btn btn-secondary");

                $("#balance_div").show();
                $("#deposit_or_withdraw_header").text("Enter Amount in USD");
            }
        },
        error: function(data){

        }
    });
}

function checkout()
{
    $.ajax({
        type: "POST",
        url: window.location.protocol + "//" + window.location.hostname + ":" + window.location.port + "/Hassner/backend/shared/FiatSendSwitcher.php",
        data: {
            amount: $("#deposit_withdraw_amount").val(),
        },
        async: false,
        dataType: "json",
        success: function(data){

        },
        error: function(data){
            
        }
    });
}

$( function() {
    $("#deposit_btn").click(deposit);
    $("#withdraw_btn").click(withdraw);
    $("#checkout_btn").click(checkout);
});

$(document).keypress(function (e) {
    var key = e.which;
    if(key == 13)  // the enter key code
     {
       $('#deposit_btn').click();
       $('#withdraw_btn').click();
       return false;  
     }
});  