<?php
if(!defined('LOGMODES_LOADED')){
    abstract class LogModes
    {
        const NONE = "NONE";
        const PERSONAL = "PERSONAL_PAGE";
        const SHARE_DIST = "SHARE_DIST";
        const SELL_SHARE = "SELL_SHARE";
        const BUY_SHARE = "BUY_SHARE";
        const SELL_SILIQAS = "SELL_SILIQAS";
        const BUY_SILIQAS = "BUY_SILIQAS";
        const CAMPAIGN = "CAMPAIGN";
        const NON_EXIST = "NON_EXIST";
        const EXIST = "EXIST";
    }
    define('LOGMODES_LOADED', 1);
}
?>