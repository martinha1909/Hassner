function unselectEthos()
{
    $("#ethos_btn").removeClass("menu-text menu-style");
    $("#ethos_btn").addClass("menu-text menu-no-underline");
    $("#ethos_btn").val('Ethos');
    $("#li_ethos").removeClass("selected-no-hover");

    $("#ethos_content").hide();
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

function unselectInvestors()
{
    $("#investors_btn").removeClass("menu-text menu-style");
    $("#investors_btn").addClass("menu-text menu-no-underline");
    $("#investors_btn").val('Investors');
    $("#li_investors").removeClass("selected-no-hover");

    $("#investors_content").hide();
}

function unselectAccount()
{
    $("#account_btn").removeClass("menu-text menu-style");
    $("#account_btn").addClass("menu-text menu-no-underline");
    $("#account_btn").val('Account');
    $("#li_account").removeClass("selected-no-hover");

    $("#account_content").hide();
}

function selectEthos(button)
{
    unselectCampaign();
    unselectBalance();
    unselectInvestors();
    unselectAccount();
    
    button.removeClass("menu-text menu-no-underline");
    button.addClass("menu-text menu-style");
    button.val("❖ Ethos");
    $("#li_ethos").addClass("selected-no-hover");

    $("#ethos_content").show();
}

function selectCampaign(button)
{
    unselectEthos();
    unselectBalance();
    unselectInvestors();
    unselectAccount();

    button.removeClass("menu-text menu-no-underline");
    button.addClass("menu-text menu-style");
    button.val("◔ Campaign");
    $("#li_campaign").addClass("selected-no-hover");

    $("#campaign_content").show();
}

function selectBalance(button)
{
    unselectEthos();
    unselectCampaign();
    unselectInvestors();
    unselectAccount();

    button.removeClass("menu-text menu-no-underline");
    button.addClass("menu-text menu-style");
    button.val("※ Balance");
    $("#li_balance").addClass("selected-no-hover");

    $("#balance_content").show();
}

function selectInvestors(button)
{
    unselectEthos();
    unselectCampaign();
    unselectBalance();
    unselectAccount();

    button.removeClass("menu-text menu-no-underline");
    button.addClass("menu-text menu-style");
    button.val("◈ Investors");
    $("#li_investors").addClass("selected-no-hover");

    $("#investors_content").show();
}

function selectAccount(button)
{
    unselectEthos();
    unselectCampaign();
    unselectBalance();
    unselectInvestors();

    button.removeClass("menu-text menu-no-underline");
    button.addClass("menu-text menu-style");
    button.val("▤ Account");
    $("#li_account").addClass("selected-no-hover");

    $("#account_content").show();
}

function ethosPressed()
{
    var button = $(this);
    $.ajax({
        type: "POST",
        url: window.location.protocol + "//" + window.location.hostname + ":" + window.location.port + "/Hassner/backend/control/MenuDisplayArtistBackend.php",
        data: {
            display_type: "Ethos"
        },
        async: false,
        dataType: "json",
        success: function(data){
            selectEthos(button);
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
        url: window.location.protocol + "//" + window.location.hostname + ":" + window.location.port + "/Hassner/backend/control/MenuDisplayArtistBackend.php",
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
        url: window.location.protocol + "//" + window.location.hostname + ":" + window.location.port + "/Hassner/backend/control/MenuDisplayArtistBackend.php",
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

function investorsPressed()
{
    var button = $(this);
    $.ajax({
        cache: false,
        type: "POST",
        url: window.location.protocol + "//" + window.location.hostname + ":" + window.location.port + "/Hassner/backend/control/MenuDisplayArtistBackend.php",
        data: {
            display_type: "Investors"
        },
        async: false,
        dataType: "json",
        success: function(data){
            selectInvestors(button);
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
        url: window.location.protocol + "//" + window.location.hostname + ":" + window.location.port + "/Hassner/backend/control/MenuDisplayArtistBackend.php",
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

$( function() {
    $("#ethos_btn").click(ethosPressed);
    $("#campaign_btn").click(campaignPressed);
    $("#balance_btn").click(balancePressed);
    $("#investors_btn").click(investorsPressed);
    $("#account_btn").click(accountPressed);
});