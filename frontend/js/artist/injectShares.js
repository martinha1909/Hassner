function confirmInject()
{
    $.ajax({
        type: "POST",
        url: window.location.protocol + "//" + window.location.hostname + ":" + window.location.port + "/Hassner/backend/artist/UpdateShareDistributedBackend.php",
        data: {
            share_distributing: $("#shares_injecting").val(),
            inject_comment: $("#comment").val()
        },
        async: false,
        dataType: "json",
        success: function(data){
            if(data.status != "SUCCESS")
            {
                $("#inject_error").text(data.msg);
                $("#inject_error").show();
            }
            else
            {
                $("#inject_success").show();
                $("#inject_success").addClass("suc-msg");
                $("#inject_success").text(data.msg);

                $("#inject_shares_content").hide();

                $("#inject_shares_btn").removeClass("btn-warning");
                $("#inject_shares_btn").addClass("btn-primary");                
            }
        },
        error: function(data){
    
        }
      });
}

function injectShares()
{
    $("#inject_shares_content").show();
    $("#inject_shares_btn").removeClass("btn-primary");
    $("#inject_shares_btn").addClass("btn-warning");

    $("#inject_error").hide();
    $("#inject_success").hide();
}

$( function () {
    $("#inject_shares_btn").click(injectShares);
    $("#confirm_inject_btn").click(confirmInject);
});

$(document).keypress(function (e) {
    var key = e.which;
    if(key == 13)  // the enter key code
    {
        $('#inject_shares_btn').click();
        $('#confirm_inject_btn').click();
        return false;  
    }
});