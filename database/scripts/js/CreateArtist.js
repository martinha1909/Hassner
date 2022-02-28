function createArtist()
{
    $.ajax({
        type: "POST",
        url: window.location.protocol + "//" + window.location.hostname + ":" + window.location.port + "/Hassner/database/scripts/operation/CreateArtistOp.php",
        data: {
            username: $("#script_artist_username").val(),
            password: $("#script_artist_password").val(),
            market_tag: $("#script_artist_tag").val(),
            email: $("#script_artist_email").val()
        },
        async: false,
        dataType: "json",
        success: function(data){
            if(data.status != "SUCCESS")
            {
                $("#create_artist_status").addClass("error-msg");
                $("#create_artist_status").text(data.msg);
            }
            else
            {
                $("#create_artist_status").addClass("suc-msg");
                $("#create_artist_status").text(data.msg);
            }
        },
        error: function(data){

        }
    });
}

$( function() {
    $("#script_create_artist_btn").click(createArtist);
});

$(document).keypress(function (e) {
    var key = e.which;
    if(key == 13)  // the enter key code
     {
       $('#script_create_artist_btn').click();
       return false;  
     }
});