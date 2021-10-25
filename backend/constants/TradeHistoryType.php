<?php
if(!defined('TRADEHISTORY_LOADED')){
    abstract class TradeHistoryType
    {
        const SHARE_REPURCHASE = "shares repurchase";
        const SHARE_BOUGHT = "shares bought";
    }
    define('TRADEHISTORY_LOADED', 1);
}
?>