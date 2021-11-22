<?php
if(!defined('ERRORLOGFILE_LOADED')){
    abstract class ErrorLogPath
    {
        const FRONTEND = "../../backend/logging/files";
        const BACKEND = "../logging/files";
        const BACKEND_INCLUDE = "../../logging/files";
    }
    define('ERRORLOGFILE_LOADED', 1);
}
?>