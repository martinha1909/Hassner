<?php
    class TradeHistoryItem
    {
        private $date;
        //contains all the values of that day to determine the highest and lowest
        private $price;
        //contains all of the amount of shares each trade has 
        private $volume;
        //contains the amount of siliqas each trade has
        private $value;
        //contains all the trade of the day
        private $trade;
        //finalized items
        private float $finalize_min;
        private float $finalize_max;
        private int $finalize_volume;
        private float $finalize_value;

        function __construct($date)
        {
            $this->date = $date;
            $this->price = array();
            $this->volume = array();
            $this->value = array();
            $this->trade = 0;
            $this->finalize_min = 0;
            $this->finalize_max = 0;
            $this->finalize_volume = 0;
            $this->finalize_value = 0;
        }

        function finalizeMin()
        {
            if(sizeof($this->price) > 0)
            {
                $this->finalize_min = $this->price[0];
                for($i = 1; $i < sizeof($this->price); $i++)
                {
                    if($this->price[$i] < $this->finalize_min)
                    {
                        $this->finalize_min = $this->price[$i];
                    }
                }
            }
        }

        function finalizeMax()
        {
            if(sizeof($this->price) > 0)
            {
                $this->finalize_max = $this->price[0];
                for($i = 1; $i < sizeof($this->price); $i++)
                {
                    if($this->price[$i] > $this->finalize_max)
                    {
                        $this->finalize_max = $this->price[$i];
                    }
                }
            }
        }

        function finalizeVolume()
        {
            if(sizeof($this->volume) > 0)
            {
                for($i = 0; $i < sizeof($this->volume); $i++)
                {
                    $this->finalize_volume += $this->volume[$i];
                }
            }
        }

        function finalizeValue()
        {
            if(sizeof($this->value) > 0)
            {
                for($i = 0; $i < sizeof($this->value); $i++)
                {
                    $this->finalize_value += $this->value[$i];
                }
            }
        }

        function getDate(): string
        {
            return $this->date;
        }

        function getFinalizeMin()
        {
            return $this->finalize_min;
        }
        
        function getFinalizeMax()
        {
            return $this->finalize_max;
        }

        function getFinalizeVolume()
        {
            return $this->finalize_volume;
        }

        function getFinalizeValue()
        {
            return $this->finalize_value;
        }

        function getTrade()
        {
            return $this->trade;
        }

        function setDate($date)
        {
            $this->date = $date;
        }

        function addPrice($price)
        {
            array_push($this->price, $price);
        }

        function addVolume($volume)
        {
            array_push($this->volume, $volume);
        }

        function addValue($value)
        {
            array_push($this->value, $value);
        }
        
        function addTrade()
        {
            $this->trade++;
        }
    }
?>