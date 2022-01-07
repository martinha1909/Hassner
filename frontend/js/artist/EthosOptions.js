function unselectQuotes()
{
    $("#quotes_btn").removeClass("btn btn-warning");
    $("#quotes_btn").addClass("btn btn-secondary");

    $("#quotes_content").hide();
}

function unselectHistory()
{
    $("#history_btn").removeClass("btn btn-warning");
    $("#history_btn").addClass("btn btn-secondary");

    $("#history_content").hide();
}

function unselectBuyBackShares()
{
    $("#buy_back_shares_btn").removeClass("btn btn-warning");
    $("#buy_back_shares_btn").addClass("btn btn-secondary");

    $("#buy_back_shares_content").hide();
}

function selectQuotes(button)
{
    unselectHistory();
    unselectBuyBackShares();

    $("#quotes_btn").removeClass("btn btn-secondary");
    $("#quotes_btn").addClass("btn btn-warning");

    $("#quotes_content").show();
}

function selectBuyBackShares(button)
{
    unselectHistory();
    unselectQuotes();

    button.removeClass("btn btn-secondary");
    button.addClass("btn btn-warning");

    $("#buy_back_shares_content").show();
}

function selectHistory(button)
{
    unselectBuyBackShares();
    unselectQuotes();

    button.removeClass("btn btn-secondary");
    button.addClass("btn btn-warning");

    $("#history_content").show();
}

function quotesPressed()
{
    var button = $(this);
    $.ajax({
        cache: false,
        type: "POST",
        url: window.location.protocol + "//" + window.location.hostname + ":" + window.location.port + "/Hassner/backend/artist/EthosDashboardOptionSwitcher.php",
        data: {
            ethos_options: "Quotes"
        },
        async: false,
        dataType: "json",
        success: function(data){
            selectQuotes(button);
        },
        error: function(data){

        }
    });
    return false;
}

function buyBackSharesPressed()
{
    var button = $(this);
    $.ajax({
        cache: false,
        type: "POST",
        url: window.location.protocol + "//" + window.location.hostname + ":" + window.location.port + "/Hassner/backend/artist/EthosDashboardOptionSwitcher.php",
        data: {
            ethos_options: "Buy Back Shares"
        },
        async: false,
        dataType: "json",
        success: function(data){
            selectBuyBackShares(button);
        },
        error: function(data){

        }
    });
    return false;
}

function historyPressed()
{
    var button = $(this);
    $.ajax({
        cache: false,
        type: "POST",
        url: window.location.protocol + "//" + window.location.hostname + ":" + window.location.port + "/Hassner/backend/artist/EthosDashboardOptionSwitcher.php",
        data: {
            ethos_options: "History"
        },
        async: false,
        dataType: "json",
        success: function(data){
            selectHistory(button);
        },
        error: function(data){

        }
    });
    return false;
}

$( function() {
    $("#quotes_btn").click(quotesPressed);
    $("#buy_back_shares_btn").click(buyBackSharesPressed);
    $("#history_btn").click(historyPressed);
});