<?php
if(!defined('TRADEHISTORY_LOADED')){
    abstract class TradeHistoryType
    {
        const NONE = "Choose an option";
        //artist history options
        const SHARE_REPURCHASE = "shares repurchase";
        const SHARE_BOUGHT = "shares bought";

        //user history options
        const BUY_HISTORY = "BUY";
        const TRADE_HISTORY = "TRADE";
        const INJECTION_HISTORY = "INJECT";
    }
    define('TRADEHISTORY_LOADED', 1);
}
?>