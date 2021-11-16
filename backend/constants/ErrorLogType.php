<?php
if(!defined('ERRORLOGTYPE_LOADED')){
    abstract class ErrorLogType
    {
        const SIGNUP = "signup";
        const DB = "database";
    }
    define('ERRORLOGTYPE_LOADED', 1);
}
?>