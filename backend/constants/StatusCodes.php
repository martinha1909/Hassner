<?php
if(!defined('STATUSCODES_LOADED')){
    abstract class StatusCodes
    {
        const Success = "SUCCESS";
        const ErrGeneric = "ERROR"; // Use this as little as possible, new errors should have new entries
        const ErrEmpty = "EMPTY_ERROR";
        const ErrNum = "NUM_ERROR";
        const ErrUsername = "USERNAME_ERR";
        const ErrServer = "SERVER_ERR";
        const ErrEmailDuplicate = "DUPL_EMAIL_ERR";
        const ErrEmailFormat = "EMAIL_FORMAT_ERR";
        const ErrNotEnough = "NOT_ENOUGH_ERR";
        const CampaignEmpty = "CAMPAIGN_EMPTY_ERR";
        const CampaignTimeErr = "CAMPAIGN_TIME_ERR";
    }
    define('STATUSCODES_LOADED', 1);
}
?>