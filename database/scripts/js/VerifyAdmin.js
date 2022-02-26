function adminVerify()
{
    $.ajax({
        type: "POST",
        url: window.location.protocol + "//" + window.location.hostname + ":" + window.location.port + "/Hassner/database/scripts/operation/VerifyAdmin.php",
        data: {
            verify_password: $("#scripts_admin_pwd").val(),
        },
        async: false,
        dataType: "json",
        success: function(data){
            if(data.status != "SUCCESS")
            {
                $("#admin_verify_err").show();
                $("#admin_verify_err").text(data.msg);
            }
            else
            {
                $("#hidden_after_verified").hide();
                $("#verified_content").show();
            }
        },
        error: function(data){

        }
    });
}

$( function() {
    $("#admin_verify_btn").click(adminVerify);
});

$(document).keypress(function (e) {
    var key = e.which;
    if(key == 13)  // the enter key code
     {
       $('#admin_verify_btn').click();
       return false;  
     }
}); 