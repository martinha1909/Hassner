function tradeHistory()
{
    $("#trade_history_table_body").empty(); 
    $.ajax({
        type: "POST",
        url: window.location.protocol + "//" + window.location.hostname + ":" + window.location.port + "/Hassner/backend/artist/GetTradeHistory.php",
        data: {
            trade_history_from: $("#artist_trade_history_from").val(),
            trade_history_to: $("#artist_trade_history_to").val(),
            trade_history_type: $("#artist_trade_history_type").val()
        },
        async: false,
        dataType: "json",
        success: function(data){
            console.log(data);

            if(data.status != "SUCCESS")
            {
                $('#trade_history_found').hide();
                $("#artist_trade_history_status").text(data.msg);
                $("#artist_trade_history_status").show();
            }
            else
            {
                $("#artist_trade_history_status").hide();
                if(data.trade_history.size > 0)
                {
                    $('#trade_history_not_found').hide();
                    $('#trade_history_found').show();
                    var content = ""
                    for(i=0; i<data.trade_history.size; i++)
                    {
                        content += '<tr>\
                            <td>'+ data.trade_history.date[i] +'</td>\
                            <td>'+ data.trade_history.price_high[i] + '/' + data.trade_history.price_low[i] + '</td>\
                            <td>'+ data.trade_history.volume[i] +'</td>\
                            <td>'+ data.trade_history.value[i] +'</td>\
                            <td>'+ data.trade_history.trade[i] +'</td>\
                        </tr>';
                    }

                    $('#trade_history_table').append(content);
                }
                else if(data.trade_history.size === 0)
                {
                    $('#trade_history_found').hide();
                    $('#trade_history_not_found').text("No trades found");
                }
            }
        },
        error: function(data){

        }
    });
}

$( function() {
    $("#artist_trade_history_btn").click(tradeHistory);
    $("#artist_trade_history_type_selected").on("change", function() {
        $("#artist_trade_history_type").val();
    });
});