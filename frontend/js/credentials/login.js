function login(){
    $.ajax({
        type: "POST",
        url: "../../backend/credentials/LoginBackend.php",
        data: {
            username: $("#username").val(),
            password: $("#password").val()
        },
        async: false,
        dataType: "json",
        success: function(data){
            if(data.status != 1){
                $("#login-error").text(data.msg);
                $("#login-error").show();
            }
            else{
                window.location.href = "../shared/LandingPage.php";
            }
        },
        error: function(data){
    
        }
      });
}

$( function() {
    $("#login_btn").click(login);
});

$(document).keypress(function (e) {
    var key = e.which;
    if(key == 13)  // the enter key code
     {
       $('#login_btn').click();
       return false;  
     }
});   