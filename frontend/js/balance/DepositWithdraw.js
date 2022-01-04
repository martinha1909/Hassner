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
        success: function(){
            window.location.href = window.location.protocol + "//" + window.location.hostname + ":" + window.location.port + "/Hassner/frontend/listener/listener.php";
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
            window.location.href = window.location.protocol + "//" + window.location.hostname + ":" + window.location.port + "/Hassner/frontend/listener/listener.php";
        },
        error: function(data){

        }
    });
}

$( function() {
    $("#deposit_btn").click(deposit);
    $("#withdraw_btn").click(withdraw);
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