function listenerPersonalPage()
{
    $.ajax({
        type: "POST",
        url: window.location.protocol + "//" + window.location.hostname + ":" + window.location.port + "/Hassner/backend/listener/PersonalPageBackend.php",
        data: {
            verify_password: $("#listener_personal_pwd").val(),
        },
        async: false,
        dataType: "json",
        success: function(data){
            if(data.status != "SUCCESS")
            {
                $("#listener_personal_status").show();
                $("#listener_personal_status").addClass("error-msg");
                $("#listener_personal_status").text(data.msg);
            }
            else
            {
                window.location.href = window.location.protocol + "//" + window.location.hostname + ":" + window.location.port + "/Hassner/frontend/listener/PersonalPage.php";
            }
        },
        error: function(data){

        }
    });
}

function artistPersonalPage()
{
    $.ajax({
        type: "POST",
        url: window.location.protocol + "//" + window.location.hostname + ":" + window.location.port + "/Hassner/backend/artist/PersonalPageBackend.php",
        data: {
            verify_password: $("#artist_personal_pwd").val(),
        },
        async: false,
        dataType: "json",
        success: function(data){
            if(data.status != "SUCCESS")
            {
                $("#artist_personal_status").show();
                $("#artist_personal_status").addClass("error-msg");
                $("#artist_personal_status").text(data.msg);
            }
            else
            {
                window.location.href = window.location.protocol + "//" + window.location.hostname + ":" + window.location.port + "/Hassner/frontend/artist/PersonalPage.php";
            }
        },
        error: function(data){

        }
    });
}

$( function() {
    $("#artist_personal_btn").click(artistPersonalPage);
    $("#listener_personal_btn").click(listenerPersonalPage);
});

$(document).keypress(function (e) {
    var key = e.which;
    if(key == 13)  // the enter key code
     {
       $('#artist_personal_btn').click();
       $('#listener_personal_btn').click();
       return false;  
     }
}); 