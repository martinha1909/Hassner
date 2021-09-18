<?php
if(!defined('CONSTANTS_LOADED')){
    abstract class StatusCodes
{
    const Success = "SUCCESS";
    const ErrGeneric = "ERROR"; // Use this as little as possible, new errors should have new entries
    const ErrEmpty = "EMPTY_ERROR";
    const ErrUsername = "USERNAME_ERR";
    const ErrServer = "SERVER_ERR";
    const ErrEmailDuplicate = "DUPL_EMAIL_ERR";
    const ErrEmailFormat = "EMAIL_FORMAT_ERR";
}

abstract class Currency
{
    const CAD = "CAD";
    const USD = "USD";
    const EUR = "EUR";
}

abstract class AccountType
{
    const Artist = "artist";
    const User = "user";
    const Admin = "admin";
}

define('CONSTANTS_LOADED', 1);
}


?>