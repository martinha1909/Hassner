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
        const HELPER = "helper";
    }
    define('HX_LOADED', 1);
}
?>