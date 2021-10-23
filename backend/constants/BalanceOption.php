<?php
if(!defined('BALANCEOPTION_LOADED')){
    abstract class BalanceOption
    {
        const NONE = 0;
        const WITHDRAW = "Withdraw";
        const DEPOSIT = "Deposit";
        const WITHDRAW_CAPS = "WITHDRAW";
        const DEPOSIT_CAPS = "DEPOSIT";
    }
    define('BALANCEOPTION_LOADED', 1);
}
?>