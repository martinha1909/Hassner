<?php

    //Prints the indicated info with only first and last characters to be visible, the other characters is replaced with a *
    function printUserImportantInfo($info): string
    {
        $ret = '';
        $chars = str_split($info);
        $i = 0;

        foreach($chars as $char)
        {
            if($i == 0 || $i == sizeof($chars)-1)
            {
                $ret.=$char;
            }
            else if($char == '-')
            {
                $ret.=$char;
            }
            else
            {
                $ret.='*';
            }
            $i++;
        }

        return $ret;
    }
?>