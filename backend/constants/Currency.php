<?php
if(!defined('CURRENCY_LOADED')){
    abstract class Currency
    {
        const CAD = "CAD";
        const USD = "USD";
        const EUR = "EUR";
    }
    define('CURRENCY_LOADED', 1);
}
?>