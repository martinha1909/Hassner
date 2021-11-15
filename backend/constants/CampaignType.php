<?php
if(!defined('CAMPAIGNTYPE_LOADED')){
    abstract class CampaignType
    {
        const BENCHMARK = "benchmark";
        const RAFFLE = "raffle";
    }
    define('CAMPAIGNTYPE_LOADED', 1);
}
?>