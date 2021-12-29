<?php
if(!defined('CAMPAIGNDELIVERPROGRESS_LOADED')){
    abstract class CampaignDeliverProgress
    {
        const POSITIVE = "positive";
        const NEGATIVE = "negative";
        const IN_PROGRESS = "in progress";
    }
    define('CAMPAIGNDELIVERPROGRESS_LOADED', 1);
}
?>