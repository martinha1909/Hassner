<?php
if(!defined('HX_LOADED')){
    abstract class HX
    {
        const SIGNUP = "signup";
        const LOGIN = "login";
        const DB = "database";
    }
    define('HX_LOADED', 1);
}
?>