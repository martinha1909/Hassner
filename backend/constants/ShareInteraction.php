<?php
if(!defined('SHAREINTERACTION_LOADED')){
    abstract class ShareInteraction
    {
        const BUY = "BUY";
        const SELL = "SELL";
        const BUY_BACK_SHARE = "Buy Back Shares";
    }
    define('SHAREINTERACTION_LOADED', 1);
}
?>