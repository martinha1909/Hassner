function tradeHistory()
{
    $("#listener_trade_history_table_body").empty(); 
    $.ajax({
        type: "POST",
        url: window.location.protocol + "//" + window.location.hostname + ":" + window.location.port + "/Hassner/backend/listener/GetTradeHistory.php",
        data: {
            trade_history_from: $("#listener_trade_history_from").val(),
            trade_history_to: $("#listener_trade_history_to").val()
        },
        async: false,
        dataType: "json",
        success: function(data){
            console.log(data);

            if(data.status != "SUCCESS")
            {
                $('#listener_trade_history_found').hide();
                $("#listener_trade_history_status").text(data.msg);
                $("#listener_trade_history_status").show();
            }
            else
            {
                $("#listener_trade_history_status").hide();
                if(data.trade_history.size > 0)
                {
                    $('#listener_trade_history_not_found').hide();
                    $('#listener_trade_history_found').show();
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

                    $('#listener_trade_history_table').append(content);
                }
                else if(data.trade_history.size === 0)
                {
                    $('#listener_trade_history_found').hide();
                    $('#listener_trade_history_not_found').text("No trades found");
                }
            }
        },
        error: function(data){

        }
    });
}

$( function() {
    $("#listener_trade_history_btn").click(tradeHistory);
});