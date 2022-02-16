$( function() {
    $("#ipo_btn").click(function(event){
        if(!event.detail || event.detail == 1)
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
        else
        {
            $("#ipo_btn").prop('disabled', true);
        }
    });
});

//Disable for now since multiple submission on enter pressed is not working and requires further investigation
//Uncomment to investigate
$(document).keypress(function (e) {
    var key = e.which;
    if(key == 13)  // the enter key code
    {
        $('#ipo_btn').click();
        return false;  
    }
});  