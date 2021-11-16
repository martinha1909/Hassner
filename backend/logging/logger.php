<?php
    function hx_error($type, $msg, $path)
    {
        date_default_timezone_set($_SESSION['timezone']);

        $log_msg = '['.$type.'] '.$msg."\r\n";
        $log_file = $path.'/error.log';

        error_log('['.date("F j, Y, g:i a e O").']'.$log_msg, 3,  $log_file); 
    }

    function hx_info($type, $msg, $path)
    {
        date_default_timezone_set($_SESSION['timezone']);

        $log_msg = '['.$type.'] '.$msg."\r\n";
        $log_file = $path.'/info.log';

        error_log('['.date("F j, Y, g:i a e O").']'.$log_msg, 3,  $log_file); 
    }
?>