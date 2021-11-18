<?php
if(!defined('ERRORLOGTYPE_LOADED')){
    abstract class ErrorLogType
    {
        const SIGNUP = "signup";
        const LOGIN = "login";
        const DB = "database";
        const QUERY = "query";
        const CURRENCY = "currency";
        //for logging with helper option, helper file name and function name need to be specififed
        const HELPER = "helper";
    }
    define('ERRORLOGTYPE_LOADED', 1);
}
?>