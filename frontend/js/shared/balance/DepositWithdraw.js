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
            console.log(fiat_options);
            if(fiat_options == "DEPOSIT")
            {
                var currency = $('#dark').find(":selected").text();
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
            var status = data.status;
            var logging_mode = data.logging_mode;

            if(logging_mode == "DEPOSIT")
            {
                if(status != "SUCCESS")
                {
                    $("#js_status_msg").show();
                    $("#js_msg").addClass("error-msg");
                    $("#js_msg").text(data.msg);
                }
                else
                {
                    window.location.href = window.location.protocol + "//" + window.location.hostname + ":" + window.location.port + "/Hassner/frontend/shared/Checkout.php";
                }
            }
            else if(logging_mode == "WITHDRAW")
            {
                if(status != "SUCCESS")
                {
                    $("#js_status_msg").show();
                    $("#js_msg").addClass("error-msg");
                    $("#js_msg").text(data.msg);
                }
                else
                {
                    window.location.href = window.location.protocol + "//" + window.location.hostname + ":" + window.location.port + "/Hassner/frontend/shared/Sellout.php";
                }
            }
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