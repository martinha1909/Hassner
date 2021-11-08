<?php
if(!defined('GRAPHOPTION_LOADED')){
    abstract class GraphOption
    {
        const ONE_DAY = "1D";
        const FIVE_DAY = "5D";
        const ONE_MONTH = "1M";
        const SIX_MONTH = "6M";
        const YEAR_TO_DATE = "YTD";
        const ONE_YEAR = "1Y";
        const FIVE_YEAR = "5Y";
    }
    define('GRAPHOPTION_LOADED', 1);
}
?>