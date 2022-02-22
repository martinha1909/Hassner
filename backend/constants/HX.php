<?php
if(!defined('HX_LOADED')){
    abstract class HX
    {
        const SIGNUP = "signup";
        const LOGIN = "login";
        const DB = "database";
        const QUERY = "query";
        const CURRENCY = "currency";
        const BUY_SHARES = "buy_shares";
        const SELL_SHARES = "sell_shares";
        const SHARES_INJECT = "shares_inject";
        const BUY_ORDER = "buy_order";
        const SELL_ORDER = "sell_order";
        const CAMPAIGN = "campaign";
        const HELPER = "helper";
        const AJAX_JS = "ajax_or_js";
    }
    define('HX_LOADED', 1);
}
?>