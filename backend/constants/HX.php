<?php
if(!defined('HX_LOADED')){
    abstract class HX
    {
        const SIGNUP = "signup";
        const LOGIN = "login";
        const DB = "database";
        const QUERY = "query";
        const CURRENCY = "currency";
        const HELPER = "helper";
    }
    define('HX_LOADED', 1);
}
?>