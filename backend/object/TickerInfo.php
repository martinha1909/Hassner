<?php
    class TickerInfo
    {
        private $tag;
        private $pps;
        private $change;

        function __construct()
        {
            $this->tag = "";
            $this->pps = 0;
            $this->change = 0;
        }

        function getTag()
        {
            return $this->tag;
        }

        function getPPS()
        {
            return $this->pps;
        }

        function getChange()
        {
            return $this->change;
        }

        function setTag($tag)
        {
            $this->tag = $tag;
        }

        function setPPS($pps)
        {
            $this->pps = $pps;
        }

        function setChange($change)
        {
            $this->change = $change;
        }
    }
?>