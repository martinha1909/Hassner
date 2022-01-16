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
    selectQuotes(button);

    return false;
}

function buyBackSharesPressed()
{
    var button = $(this);
    selectBuyBackShares(button);

    return false;
}

function historyPressed()
{
    var button = $(this);
    selectHistory(button);

    return false;
}

$( function() {
    $("#quotes_btn").click(quotesPressed);
    $("#buy_back_shares_btn").click(buyBackSharesPressed);
    $("#history_btn").click(historyPressed);
}); 