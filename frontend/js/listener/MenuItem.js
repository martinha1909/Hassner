function unselectPortfolio()
{
    $("#portfolio_btn").removeClass("menu-text menu-style");
    $("#portfolio_btn").addClass("menu-text menu-no-underline");
    $("#portfolio_btn").val('Portfolio');
    $("#li_portfolio").removeClass("selected-no-hover");

    $("#portfolio_content").hide();
}

function unselectCampaign()
{
    $("#campaign_btn").removeClass("menu-text menu-style");
    $("#campaign_btn").addClass("menu-text menu-no-underline");
    $("#campaign_btn").val('Campaign');
    $("#li_campaign").removeClass("selected-no-hover");

    $("#campaign_content").hide();
}

function unselectBalance()
{
    $("#balance_btn").removeClass("menu-text menu-style");
    $("#balance_btn").addClass("menu-text menu-no-underline");
    $("#balance_btn").val('Balance');
    $("#li_balance").removeClass("selected-no-hover");

    $("#balance_content").hide();
}

function unselectArtists()
{
    $("#artists_btn").removeClass("menu-text menu-style");
    $("#artists_btn").addClass("menu-text menu-no-underline");
    $("#artists_btn").val('Artists');
    $("#li_artists").removeClass("selected-no-hover");

    $("#artists_content").hide();
}

function unselectAccount()
{
    $("#account_btn").removeClass("menu-text menu-style");
    $("#account_btn").addClass("menu-text menu-no-underline");
    $("#account_btn").val('Account');
    $("#li_account").removeClass("selected-no-hover");

    $("#account_content").hide();
}

function unselectHelp()
{
    $("#help_btn").removeClass("menu-text menu-style");
    $("#help_btn").addClass("menu-text menu-no-underline");
    $("#help_btn").val('Help');
    $("#li_help").removeClass("selected-no-hover");

    $("#help_content").hide();
}

function selectPortfolio(button)
{
    unselectCampaign();
    unselectBalance();
    unselectArtists();
    unselectAccount();
    unselectHelp();
    
    button.removeClass("menu-text menu-no-underline");
    button.addClass("menu-text menu-style");
    button.val("❖ Portfolio");
    $("#li_portfolio").addClass("selected-no-hover");

    $("#portfolio_content").show();
}

function selectCampaign(button)
{
    unselectPortfolio();
    unselectBalance();
    unselectArtists();
    unselectAccount();
    unselectHelp();

    button.removeClass("menu-text menu-no-underline");
    button.addClass("menu-text menu-style");
    button.val("◔ Campaign");
    $("#li_campaign").addClass("selected-no-hover");

    $("#campaign_content").show();
}

function selectBalance(button)
{
    unselectPortfolio();
    unselectCampaign();
    unselectArtists();
    unselectAccount();
    unselectHelp();

    button.removeClass("menu-text menu-no-underline");
    button.addClass("menu-text menu-style");
    button.val("※ Balance");
    $("#li_balance").addClass("selected-no-hover");

    $("#balance_content").show();
}

function selectArtists(button)
{
    unselectPortfolio();
    unselectCampaign();
    unselectBalance();
    unselectAccount();
    unselectHelp();

    button.removeClass("menu-text menu-no-underline");
    button.addClass("menu-text menu-style");
    button.val("◈ Artists");
    $("#li_artists").addClass("selected-no-hover");

    $("#artists_content").show();
}

function selectAccount(button)
{
    unselectPortfolio();
    unselectCampaign();
    unselectBalance();
    unselectArtists();
    unselectHelp();

    button.removeClass("menu-text menu-no-underline");
    button.addClass("menu-text menu-style");
    button.val("▤ Account");
    $("#li_account").addClass("selected-no-hover");

    $("#account_content").show();
}

function selectHelp(button)
{
    unselectPortfolio();
    unselectCampaign();
    unselectBalance();
    unselectArtists();
    unselectAccount();

    button.removeClass("menu-text menu-no-underline");
    button.addClass("menu-text menu-style");
    button.val("Help");
    $("#li_help").addClass("selected-no-hover");

    $("#help_content").show();
}

function portfolioPressed()
{
    var button = $(this);
    $.ajax({
        type: "POST",
        url: window.location.protocol + "//" + window.location.hostname + ":" + window.location.port + "/Hassner/backend/control/MenuDisplayListenerBackend.php",
        data: {
            display_type: "Portfolio"
        },
        async: false,
        dataType: "json",
        success: function(data){
            selectPortfolio(button);
        },
        error: function(data){

        }
    });
}

function campaignPressed()
{
    var button = $(this);
    $.ajax({
        cache: false,
        type: "POST",
        url: window.location.protocol + "//" + window.location.hostname + ":" + window.location.port + "/Hassner/backend/control/MenuDisplayListenerBackend.php",
        data: {
            display_type: "Campaign"
        },
        async: false,
        dataType: "json",
        success: function(data){
            selectCampaign(button);
        },
        error: function(data){

        }
    });
    return false;
}

function balancePressed()
{
    var button = $(this);
    $.ajax({
        cache: false,
        type: "POST",
        url: window.location.protocol + "//" + window.location.hostname + ":" + window.location.port + "/Hassner/backend/control/MenuDisplayListenerBackend.php",
        data: {
            display_type: "Balance"
        },
        async: false,
        dataType: "json",
        success: function(data){
            selectBalance(button);
        },
        error: function(data){

        }
    });
    return false;
}

function artistsPressed()
{
    var button = $(this);
    $.ajax({
        cache: false,
        type: "POST",
        url: window.location.protocol + "//" + window.location.hostname + ":" + window.location.port + "/Hassner/backend/control/MenuDisplayListenerBackend.php",
        data: {
            display_type: "Artists"
        },
        async: false,
        dataType: "json",
        success: function(data){
            selectArtists(button);
        },
        error: function(data){

        }
    });
    return false;
}

function accountPressed()
{
    var button = $(this);
    $.ajax({
        cache: false,
        type: "POST",
        url: window.location.protocol + "//" + window.location.hostname + ":" + window.location.port + "/Hassner/backend/control/MenuDisplayListenerBackend.php",
        data: {
            display_type: "Account"
        },
        async: false,
        dataType: "json",
        success: function(data){
            selectAccount(button);
        },
        error: function(data){

        }
    });
    return false;
}

function helpPressed()
{
    var button = $(this);
    $.ajax({
        cache: false,
        type: "POST",
        url: window.location.protocol + "//" + window.location.hostname + ":" + window.location.port + "/Hassner/backend/control/MenuDisplayListenerBackend.php",
        data: {
            display_type: "Help"
        },
        async: false,
        dataType: "json",
        success: function(data){
            selectHelp(button);
        },
        error: function(data){

        }
    });
    return false;
}

$( function() {
    $("#portfolio_btn").click(portfolioPressed);
    $("#campaign_btn").click(campaignPressed);
    $("#balance_btn").click(balancePressed);
    $("#artists_btn").click(artistsPressed);
    $("#account_btn").click(accountPressed);
    $("#help_btn").click(helpPressed);
});