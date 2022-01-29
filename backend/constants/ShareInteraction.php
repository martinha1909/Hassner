<?php
if(!defined('SHAREINTERACTION_LOADED')){
    abstract class ShareInteraction
    {
        const NONE = 0;
        const BUY = "BUY";
        const SELL = "SELL";
        const BUY_BACK_SHARE = "Buy Back Shares";
        const BUY_FROM_INJECTION = "Buy From Injection";
    }
    define('SHAREINTERACTION_LOADED', 1);
}
?>