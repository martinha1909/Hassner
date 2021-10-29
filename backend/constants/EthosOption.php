<?php
if(!defined('ETHOSOPTION_LOADED')){
    abstract class EthosOption
    {
        const NONE = 0;
        const QUOTES = "Quotes";
        const BUY_BACK_SHARES = "Buy Back Shares";
        const HISTORY = "History";
    }
    define('ETHOSOPTION_LOADED', 1);
}
?>