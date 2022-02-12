<?php
if(!defined('STATUSCODES_LOADED')){
    abstract class StatusCodes
    {
        const NONE = 0;
        const Success = "SUCCESS";
        const ErrGeneric = "ERROR"; // Use this as little as possible, new errors should have new entries
        const ErrEmpty = "EMPTY_ERROR";
        const ErrNum = "NUM_ERROR";
        const ErrCard = "CARD_ERROR";
        const ErrUsername = "USERNAME_ERR";
        const ErrPassword = "PASSWORD_ERR";
        const ErrUsernameFormat = "USERNAME_FORMAT_ERR";
        const ErrServer = "SERVER_ERR";
        const ErrEmailDuplicate = "DUPL_EMAIL_ERR";
        const ErrEmailFormat = "EMAIL_FORMAT_ERR";
        const ErrNotEnough = "NOT_ENOUGH_ERR";
        const ErrTickerDuplicate = "DUPL_TICKER_ERR";
        const ErrTickerFormat = "TICKER_FORMAT_ERR";
        const CampaignEmpty = "CAMPAIGN_EMPTY_ERR";
        const CampaignTimeErr = "CAMPAIGN_TIME_ERR";
        const PRICE_OUTDATED = "Price Outdated";
        const TIME_ERR = "TIME_ERR";
        const EMAIL_SENT_ERR_RECIPIENT = "EMAIL_SENT_ERR_RECIPIENT";
        const EMAIL_SENT_ERR_HX = "EMAIL_SENT_ERR_HX";
    }
    define('STATUSCODES_LOADED', 1);
}
?>