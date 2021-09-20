<?php
if(!defined('ACCOUNTS_LOADED')){
    abstract class AccountType
    {
        const Artist = "artist";
        const User = "user";
        const Admin = "admin";
    }
    define('ACCOUNTS_LOADED', 1);
}
?>