<?php
if(!defined('TIMEZONE_LOADED')){
    abstract class Timezone
    {
        const MST = 'America/Edmonton';
        const EST = 'America/New_York';
    }
    define('TIMEZONE_LOADED', 1);
}
?>