function unselectInjectHistory()
{
    $("#user_inject_history_content").hide();
}

function unselectBuyHistory()
{
    $("#user_buy_history_content").hide();
}

function unselectTradeHistory()
{
    $("#user_trade_history_content").hide();
}

function selectInjectHistory()
{
    $("#user_inject_history_content").show();
}

function selectTradeHistory()
{
    $("#user_trade_history_content").show();
}

function selectBuyHistory()
{
    $("#user_buy_history_content").show();
}

function historyOptionChange()
{
    console.log($("#user_history_dropdown").val());
    if($("#user_history_dropdown").val() == "BUY")
    {
        unselectInjectHistory();
        unselectTradeHistory();
        selectBuyHistory();
    }
    else if($("#user_history_dropdown").val() == "TRADE")
    {
        unselectInjectHistory();
        unselectBuyHistory();
        selectTradeHistory();
    }
    else if($("#user_history_dropdown").val() == "INJECT")
    {
        unselectBuyHistory();
        unselectTradeHistory();
        selectInjectHistory();
    }
}

$( function() {
    $("#user_history_dropdown").on("change", historyOptionChange);
});