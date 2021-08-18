<?php

    //Prints the indicated info with only first and last characters to be visible, the other characters is replaced with a *
    function printUserImportantInfo($info)
    {
        $chars = str_split($info);
        echo '<p>';
        $i = 0;
        foreach($chars as $char)
        {
            if($i == 0 || $i == sizeof($chars)-1)
            {
                echo $char;
            }
            else if($char == '-')
            {
                echo $char;
            }
            else
            {
                echo '*';
            }
            $i++;
        }
        echo '</p>';
    }
?>