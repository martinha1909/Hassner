<?php
if(!defined('ERRORLOGTYPE_LOADED')){
    abstract class ErrorLogType
    {
        const USER = "user";
        const DATABASE = "database";
        const ARTIST = "artist";
    }
    define('ERRORLOGTYPE_LOADED', 1);
}
?>