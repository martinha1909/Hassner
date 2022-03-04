<?php
if(!defined('LOGLEVEL_LOADED')){
    abstract class LogLevel
    {
        
        const DEBUG = "debug";
        const INFO = "info";
        const ERROR = "error";
    }
    define('LOGLEVEL_LOADED', 1);
}
?>