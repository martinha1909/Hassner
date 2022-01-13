// function tradeHistory()
// {
    // $.ajax({
    //     type: "POST",
    //     url: window.location.protocol + "//" + window.location.hostname + ":" + window.location.port + "/Hassner/backend/shared/TradeHistoryRangeSwitcher.php",
    //     data: {
    //         trade_history_from: $("#artist_trade_history_from").val(),
    //         trade_history_to: $("#artist_trade_history_to").val(),
    //         trade_history_type: $("#artist_trade_history_type").val()
    //     },
    //     async: false,
    //     dataType: "json",
    //     success: function(data){
    //         console.log(data);
    //         if(data.status != "SUCCESS")
    //         {
    //             $("#artist_trade_history_status").text(data.msg);
    //             $("#artist_trade_history_status").show();
    //         }
    //         else
    //         {
    //             if(data.trade_history_type == "shares repurchase")
    //             {
    //                 $("#artist_shares_bought_content").hide();
    //                 $("#artist_shares_repurchase_content").show();
    //             }
    //             else if(data.trade_history_type == "shares bought")
    //             {
    //                 $("#artist_shares_repurchase_content").hide();
    //                 $("#artist_shares_bought_content").show();
    //             }
    //         }
    //     },
    //     error: function(data){

    //     }
    // });
// }

function tradeHistory()
{
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

            if(data.trade_history_size > 0)
            {
                $('#trade_history_not_found').hide();
                $('#trade_history_found').show();
                var content = ""
                for(i=0; i<data.trade_history_size; i++)
                {
                    content += '<tr>\
                        <td>'+ data.date[i] +'</td>\
                        <td>'+ data.price_high[i] + '/' + data.price_low[i] + '</td>\
                        <td>'+ data.volume[i] +'</td>\
                        <td>'+ data.value[i] +'</td>\
                        <td>'+ data.trade[i] +'</td>\
                    </tr>';
                }

                $('#trade_history_table').append(content);
            }
            else if(data.trade_history_size === 0)
            {
                $('#trade_history_found').hide();
                $('#trade_history_not_found').text("No trades found");
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