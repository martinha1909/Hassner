<?php
    //time will have format dd-mm-yy h:m:s 
    function getCurrentDate($timezone)
    {
        date_default_timezone_set($timezone);

        //h - Represent hour in 12-hour format with leading zeros (01 to 12)
        //H - Represent hour in in 24-hour format with leading zeros (00 to 23)
        $date = date('d-m-Y H:i:s');
        return $date;
    }

    //first index of return array will contain current date, second index will contain current time
    //date will have format of DD-MM-YYYY
    function dayAndTimeSplitter($date)
    {
        return explode(" ", $date);
    }

    //return array will have:
    //First index: day (DD)
    //Second index: month(MM)
    //Third index: year(YYYY)
    function dateParser($date)
    {
        $date_parser = explode("-", $date);
        $month = monthToText($date_parser[1]);

        $ret = $month." ".$date_parser[0].", ".$date_parser[2];

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

    function monthToText($month)
    {
        if($month == "01")
        {
            $month = "Jan";
        }
        else if($month == "02")
        {
            $month = "Feb";
        }
        else if($month == "03")
        {
            $month = "Mar";
        }
        else if($month == "04")
        {
            $month = "Apr";
        }
        else if($month == "05")
        {
            $month = "May";
        }
        else if($month == "06")
        {
            $month = "Jun";
        }
        else if($month == "07")
        {
            $month = "Jul";
        }
        else if($month == "08")
        {
            $month = "Aug";
        }
        else if($month == "09")
        {
            $month = "Sep";
        }
        else if($month == "10")
        {
            $month = "Oct";
        }
        else if($month == "11")
        {
            $month = "Nov";
        }
        else if($month == "12")
        {
            $month = "Dec";
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

    /**
    * Determines if an expiration date is in the future or not
    *
    * @param  	exp_day	        $exp_day	expiration day to be determined if in the future or not
    *                                       has format of YYYY-MM-DD
    * @param  	exp_time	    $exp_day	expiration time (combine with the day) to be determined if in the future or not
    *                                       has format of HH:MM
    * @param  	release_day	    $exp_day	current day to compare to expiration date
    *                                       has format of DD-MM-YYYY
    * @param  	release_time	$exp_day	current time (combine with the current day) to compare to expiration date
    *                                       has format of HH:MM:SS
    * @return 	ret	a boolean, true if the expiration date is in the future, false otherwise
    */
    function isInTheFuture($exp_day, $release_day, $exp_time, $release_time)
    {
        $ret = TRUE;
        // echo $exp_day[0]." ".$exp_day[1]." ".$exp_day[2];
        // echo "<br>";
        // echo $release_day[0]." ".$release_day[1]." ".$release_day[2];
        //if the year is in the past, we give an error
        if($exp_day[0] < $release_day[2])
        {
            $ret = FALSE;
        }
        //If the year is the same as the current year, we check the month
        else if($exp_day[0] == $release_day[2])
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

    /**
    * Determines if a day is in the future of the current day
    *
    * @param  	current_day	    Today's date, has format of DD-MM-YYYY
    * @param  	day_to_check    day to compare with today's date, has format of DD-MM-YYYY
    * @return 	ret	            a boolean, true if today is in the future compared to day_to_check, 
    *                           false otherwise
    */
    function dateIsInTheFuture($current_day, $day_to_check)
    {
        $ret = FALSE;

        $current_day = strtotime($current_day);
        $day_to_check = strtotime($day_to_check);

        if($current_day > $day_to_check)
        {
            $ret = TRUE;
        }

        return $ret;
    }

    function calculateTimeLeft($current_date, $current_time, $date_expires, $time_expires): string
    {
        //Assuming error check
        $ret = "Error in calculate time remaining";

        //First index contains day
        //Second index contains month
        //Third index contains year
        $day_rn = explode("-", $current_date);

        //First index contains year
        //Second index contains month
        //Third index contains day
        $day_exp = explode("-", $date_expires);

        //First index contains hour
        //Second index contains minute
        //Third index contains second
        $time_rn = explode(":", $current_time);

        //First index contains hour
        //Second index contains minute
        $time_exp = explode(":", $time_expires);

        if(!isInTheFuture($day_exp, $day_rn, $time_exp, $time_rn))
        {
            $ret = "Expired";
        }
        else
        {
            $pre_formatted_current = $day_rn[0]."-".$day_rn[1]."-".$day_rn[2]." ".$current_time;
            $pre_formatted_exp = $date_expires." ".$time_expires.":00";
            $now = new DateTime($pre_formatted_current);
            $now->format('d-m-Y H:i:s');
            $exp = new DateTime($pre_formatted_exp);
            $exp->format('Y-m-d H:i:s');

            $interval = $exp->diff($now);

            $years_remaining = $interval->format("%y");
            $months_remaining = $interval->format("%m");
            $days_remaining = $interval->format("%d");
            $hours_remaining = $interval->format("%h");
            $minutes_remaining = $interval->format("%i");

            //This part is to parse into a string that we want to display, the full relative time left can be 
            //done by doing:
            // echo $interval->format("%y years, %m months, %a days, %h hours, %i minutes, %s seconds");
            if($years_remaining == 0)
            {
                if($months_remaining == 0)
                {
                    if($days_remaining == 0)
                    {
                        //if years, months, days, and hours are 0, we only include minutes
                        if($hours_remaining == 0)
                        {
                            if($minutes_remaining == 0)
                            {
                                $ret = "Expired";
                            }
                            else
                            {
                                $ret = $minutes_remaining." m left";
                            }
                        }
                        //If years, months, and days are 0, we only include hours and minutes
                        else
                        {
                            $ret = $hours_remaining." h & ".$minutes_remaining." m left";
                        }
                    }
                    //if years and months are 0, we only include days and hours
                    else
                    {
                        $ret = $days_remaining." d & ".$hours_remaining." h left";
                    }
                }
                //if years is 0, we only include months and days
                else
                {
                    $ret = $months_remaining." m & ".$days_remaining." d left";
                }
            }
            else
            {
                $ret = $years_remaining." y, ".$months_remaining." m & ".$days_remaining." d left";
            }
        }

        return $ret;
    }

    function toRelativeTime($current_date, $date_posted, $time_posted)
    {
        //Assuming error check
        $ret = "Error in calculate time remaining";
        $pre_formatted_posted = $date_posted." ".$time_posted;

        $now = new DateTime($current_date);
        $now->format('d-m-Y H:i:s');
        $posted = new DateTime($pre_formatted_posted);
        $posted->format('Y-m-d H:i:s');

        $interval = $posted->diff($now);

        $years_ago = $interval->format("%y");
        $months_ago = $interval->format("%m");
        $days_ago = $interval->format("%d");
        $hours_ago = $interval->format("%h");
        $minutes_ago = $interval->format("%i");
        $seconds_ago = $interval->format("%s");

        //This part is to parse into a string that we want to display, the full relative time left can be 
        //done by doing:
        // echo $interval->format("%y years, %m months, %a days, %h hours, %i minutes, %s seconds");
        if($years_ago == 0)
        {
            if($months_ago == 0)
            {
                if($days_ago == 0)
                {
                    //if years, months, days, and hours are 0, we only include minutes
                    if($hours_ago == 0)
                    {
                        if($minutes_ago == 0)
                        {
                            $ret = $seconds_ago."s ago";
                        }
                        else
                        {
                            $ret = $minutes_ago."m & ".$seconds_ago."s ago";
                        }
                    }
                    //If years, months, and days are 0, we only include hours and minutes
                    else
                    {
                        $ret = $hours_ago."h & ".$minutes_ago."m ago";
                    }
                }
                //if years and months are 0, we only include days and hours
                else
                {
                    $ret = $days_ago."d & ".$hours_ago."h ago";
                }
            }
            //if years is 0, we only include months and days
            else
            {
                $ret = $months_ago."m & ".$days_ago."d ago";
            }
        }
        else
        {
            $ret = $years_ago."y, ".$months_ago."m & ".$days_ago."d ago";
        }

        return $ret;
    }

    function reformatDate($date)
    {
        $ret = "Error in reformatting date";

        // reformat to match the expectation of isInTheFuture, which is of form DD-MM-YYYY
        $date = new DateTime($date);
        $ret = $date->format('d-m-Y');

        return $ret;
    }

    /**
    * Determines if an expiration date is in the future or not
    *
    * @param  	date_check	date to be determined if in range
    *                       has format of DD-MM-YYYY
    * @param  	date_from	date that a range starts
    *                       has format of DD-MM-YYYY
    * @param  	date_to	    date that a range ends
    *                       has format of DD-MM-YYYY
    * @return 	ret	a boolean, true if the date is in the indicated range, false otherwise
    */
    function isInRange($date_check, $date_from, $date_to)
    {
        $ret = FALSE;

        $date_from = strtotime($date_from);
        $date_to = strtotime($date_to);
        $date_check = strtotime($date_check);

        if(($date_from <= $date_check ) && ($date_check <= $date_to))
        {
            $ret = true;
        }

        return $ret;
    }

    /**
    * Converts a date to YYYY/MM/DD format
    *
    * @param  	date	    date to be converted, has format of DD/MM/YYYY
    * @return 	ret	        a string of date formatted to YYYY/MM/DD
    */
    function toYYYYMMDD($date): string
    {
        //assume error check
        $ret = "Error in parsing";

        $date = explode("-", $date);
        $ret = $date[2]."-".$date[1]."-".$date[0];

        return $ret;
    }

    /**
    * Converts a date to DD/MM/YYYY format
    *
    * @param  	date	    date to be converted, has format of YYYY/MM/DD
    * @return 	ret	        a string of date formatted to DD/MM/YYYY
    */
    function toDDMMYYYY($date): string
    {
        //assume error check
        $ret = "Error in parsing";

        $date = explode("-", $date);
        $ret = $date[2]."-".$date[1]."-".$date[0];

        return $ret;
    }

    /**
    * Checks to see if a time object has passed the update interval or not
    *
    * @param  	latest_updated_time	    last time that was updated, has format of HH:MM (24-hour format)
    * @param  	time_to_check	        time to be determined if in range between the last updated time and the interval
    * @param  	update_interval	        A specified update interval, in minutes
    * @return 	ret	                    true if time_to_check falls outside of the latest_updated_time and the update interval
    *                                   false otherwise
    */
    function isOutSideOfUpdateInterval($latest_updated_time, $time_to_check, $update_interval)
    {
        // echo $latest_updated_time;
        // echo "--";
        // echo $time_to_check;
        // echo "<br>";
        $ret = FALSE;

        $latest_updated_time = new DateTime($latest_updated_time);
        //We only care about the hours and minutes, not the seconds
        $current_time = new DateTime(substr($time_to_check, 0, 5));
        $interval = $current_time->diff($latest_updated_time);

        //Only update the pps if it has been more than the specified interval
        if($interval->format("%i") > $update_interval)
        {
            $ret = TRUE;
        }

        return $ret;
    }

    function isTradingTime($timezone): bool
    {
        date_default_timezone_set($timezone);

        $ret = FALSE;
        $current_date_time = date('d-m-Y H:i:s');
        $trading_hours_start = date('d-m-Y 09:30:00');
        $trading_hours_end = date('d-m-Y 16:30:00');

        if(isInRange($current_date_time, $trading_hours_start, $trading_hours_end))
        {
            $ret = TRUE;
        }

        return $ret;
    }
?>