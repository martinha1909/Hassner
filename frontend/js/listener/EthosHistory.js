function historyOptionChange()
{
    console.log($("#user_history_dropdown").val());
    if($("#user_history_dropdown").val() == "BUY")
    {
        $("#user_buy_history_content").show();
    }
}

$( function() {
    $("#user_history_dropdown").on("change", historyOptionChange);
});