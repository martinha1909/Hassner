<?php
if(!defined('BALANCEOPTION_LOADED')){
    abstract class BalanceOption
    {
        const NONE = 0;
        const WITHDRAW = "WITHDRAW";
        const DEPOSIT = "DEPOSIT";
    }
    define('BALANCEOPTION_LOADED', 1);
}
?>