<?php
if(!defined('ERRORLOGTYPE_LOADED')){
    abstract class ErrorLogType
    {
        const SIGNUP = "signup";
        const LOGIN = "login";
        const DB = "database";
    }
    define('ERRORLOGTYPE_LOADED', 1);
}
?>