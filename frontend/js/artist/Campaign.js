$( function() {
    $("#commence_campaign_btn").click(function(event){
        if(!event.detail || event.detail == 1)
        {
            
        }
        else
        {
            $("#commence_campaign_btn").prop('disabled', true);
        }
    })
})