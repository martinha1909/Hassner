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
    //date will have format of DD-MM-YY
    function currentTimeParser($date)
    {
        return explode(" ", $date);
    }

    //return array will have:
    //First index: day (DD)
    //Second index: month(MM)
    //Third index: year(YY)
    function dateParser($date)
    {
        // return explode("-", $date);
        $date_parser = explode("-", $date);
        $day = dayToText($date_parser[0]);
        $month = monthToText($date_parser[1]);
        $year = "20".$date_parser[2];

        $ret = $month." ".$day.", ".$year;

        return $ret;
    }

    //return array will have:
    //first index: hour
    //Second index: minute
    //Third index: second
    function timeParser($time)
    {
        $time_parser = explode(":", $time);

        return timeToText($time_parser[0], $time_parser[1]);
    }

    function dayToText($day)
    {
        $last_char = substr($day, -1);

        if($last_char == "1")
        {
            $day = $last_char."st";
        }
        else if($last_char == "2")
        {
            $day = $last_char."nd";
        }
        else if($last_char == "3")
        {
            $day = $last_char."rd";
        }
        else
        {
            $day = $last_char."th";
        }

        return $day;
    }

    function monthToText($month)
    {
        if($month == "01")
        {
            $month = "January";
        }
        else if($month == "02")
        {
            $month = "February";
        }
        else if($month == "03")
        {
            $month = "March";
        }
        else if($month == "04")
        {
            $month = "April";
        }
        else if($month == "05")
        {
            $month = "May";
        }
        else if($month == "06")
        {
            $month = "June";
        }
        else if($month == "07")
        {
            $month = "July";
        }
        else if($month == "08")
        {
            $month = "August";
        }
        else if($month == "09")
        {
            $month = "September";
        }
        else if($month == "10")
        {
            $month = "October";
        }
        else if($month == "11")
        {
            $month = "November";
        }
        else if($month == "12")
        {
            $month = "December";
        }

        return $month;
    }

    function timeToText($hour, $minute)
    {
        $ret = 0;
        if($hour > 12)
        {
            $hour = $hour - 12;
            $ret = $hour.":".$minute." p.m.";
        }
        else if($hour == 12)
        {
            $ret = $hour.":".$minute." p.m.";
        }
        else 
        {
            $ret = $hour.":".$minute." a.m.";
        }

        return $ret;
    }
?>