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
    function dayAndTimeSplitter($date)
    {
        return explode(" ", $date);
    }

    //return array will have:
    //First index: day (DD)
    //Second index: month(MM)
    //Third index: year(YY)
    function dateParser($date)
    {
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
            $day = $day."st";
        }
        else if($last_char == "2")
        {
            $day = $day."nd";
        }
        else if($last_char == "3")
        {
            $day = $day."rd";
        }
        else
        {
            $day = $day."th";
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

    function datePickerParser($time)
    {
        return explode("T", $time);
    }

    function isInTheFuture($exp_day, $release_day, $exp_time, $release_time)
    {
        $ret = TRUE;
        //if the year is in the past, we give an error
        if($exp_day[0] < ($release_day[2] + 2000))
        {
            $ret = FALSE;
        }
        //If the year is the same as the current year, we check the month
        else if($exp_day[0] == ($release_day[2] + 2000))
        {
            //if the month is less than the current month, we return false
            if($exp_day[1] < $release_day[1])
            {
                $ret = FALSE;
            }
            //If the month is the same as the current month, we check the day
            else if($exp_day[1] == $release_day[1])
            {
                //if the day is less than current day (of the same month and same year), we return false
                if($exp_day[2] < $release_day[0])
                {
                    $ret = FALSE;
                }
                //if the day is the same as the current day (of the same month and same year), we check the time
                else if($exp_day[2] == $release_day[0])
                {
                    //If the hour (of the same day, same month, and same year) is less than current hour, return false
                    if($exp_time[0] < $release_time[0])
                    {
                        $ret = FALSE;
                    }
                    //If the hour (of the same day, same month, and same year) is equal to the current hour, check the minute
                    else if($exp_time[0] == $release_time[0])
                    {
                        //We only check til the minute here, if the minutes is the same or less, we return false
                        //The reason behind this is because it wouldn't make sense if a campaign only lasts 
                        //a few seconds
                        if($exp_time[1] <= $release_time[1])
                        {
                            $ret = FALSE;
                        }
                        else
                        {
                            $ret = TRUE;
                        }
                    }
                    //If the hour (of the same day, same month, and same year) is more than current hour, return true
                    else
                    {
                        $ret = TRUE;
                    }
                }
                //if the day is higher than current day (of the same month and same year), we return true
                else
                {
                    $ret = TRUE;
                }
            }
            //if the month is in the future (of the same year), we return true
            else
            {
                $ret = TRUE;
            }
        }
        //If the year is in the future, we return true
        else
        {
            $ret = TRUE;
        }

        return $ret;
    }
?>