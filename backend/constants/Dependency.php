<?php
if(!defined('DEPENDENCIES_LOADED')){
    abstract class Dependency
    {
        const Backend = "BACKEND";
        const Frontend = "FRONTEND";
        const Database = "DB";
    }
    define('DEPENDENCIES_LOADED', 1);
}
?>