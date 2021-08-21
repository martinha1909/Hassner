<?php
    //time will have format dd-mm-yy h:m:s 
    function getCurrentDate($timezone)
    {
        date_default_timezone_set($timezone);

        //h - Represent hour in 12-hour format with leading zeros (01 to 12)
        //H - Represent hour in in 24-hour format with leading zeros (00 to 23)
        $date = date('d-m-y H:i:s');
        return $date;
    }

    //first index of return array will contain current date, second index will contain current time
    function currentTimeParser($date)
    {
        return explode(" ", $date);
    }

    function calculateTimeRemaining($now, $past)
    {
        
    }
?>