function IPO()
{
    $.ajax({
        type: "POST",
        url: window.location.protocol + "//" + window.location.hostname + ":" + window.location.port + "/Hassner/backend/artist/DistributeShareBackend.php",
        data: {
            distribute_share: $("#shares_dist").val(),
            amount_raising: $("#amount_raising").val()
        },
        async: false,
        dataType: "json",
        success: function(data) {
            var status = data.status;
            if(status != "SUCCESS")
            {
                $("#js_status_msg").show();
                $("#js_msg").addClass("error-msg");
                $("#js_msg").text(data.msg);
            }
            else
            {
                window.location.href = window.location.protocol + "//" + window.location.hostname + ":" + window.location.port + "/Hassner/frontend/artist/Artist.php";
            }
        },
        error: function(data) {

        }
    })
}

$( function() {
    $("#ipo_btn").click(IPO);
});

$(document).keypress(function (e) {
    var key = e.which;
    if(key == 13)  // the enter key code
     {
       $('#ipo_btn').click();
       return false;  
     }
});  