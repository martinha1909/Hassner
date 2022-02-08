function signup()
{
    $.ajax({
        type: "POST",
        url: window.location.protocol + "//" + window.location.hostname + ":" + window.location.port + "/Hassner/backend/credentials/SignUpBackEnd.php",
        data: {
            username: $("#signup_username").val(),
            password: $("#signup_pwd").val(),
            email: $("#signup_email").val(),
            ticker: $("#signupTicker").val()
        },
        async: false,
        dataType: "json",
        success: function(data){
            console.log(data);
            if(data.status != "SUCCESS")
            {
                $("#signup_suc").hide();
                $("#signup_error").text(data.msg);
                // $("#signup_error").show();
            }
            else
            {
                window.location.href = window.location.protocol + "//" + window.location.hostname + ":" + window.location.port + "/Hassner/frontend/credentials/login.php";
                $("#sign_up_success").text(data.msg);
                $("#sign_up_success").show();
            }
        },
        error: function(data){

        }
    });
}

$( function() {
    $("#signup_btn").click(signup);
});

$(document).keypress(function (e) {
    var key = e.which;
    if(key == 13)  // the enter key code
     {
       $('#signup_btn').click();
       return false;  
     }
});

$(document).ready(function() {
    $(".artistRadio").hide();
    $('input[type="radio"]').click(function(){
        var inputValue = $(this).attr("value");
        if(inputValue == "artist"){
            $(".artistRadio").show();
        }
        else{
            $(".artistRadio").hide();
        }
    });
});