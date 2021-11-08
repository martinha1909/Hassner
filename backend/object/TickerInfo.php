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

        /**
        * Copy a TickerInfo object
        *
        * @param  	ticker TickerInfo object to be copied off of
        *
        * @return   ret    a newly created TickerInfo object
        */
        public static function copy(TickerInfo $ticker): TickerInfo
        {
            $ret = new TickerInfo();

            $ret->setTag($ticker->getTag());
            $ret->setPPS($ticker->getPPS());
            $ret->setChange($ticker->getChange());

            return $ret;
        }

        /**
        * Swaps 2 elements of the array, using copy function as a variable placeholder
        *
        * @param  	arr array in which indices are swapped
        * @param  	i   index to be swapped
        * @param  	j   index to be swapped
        */
        private static function swap(&$arr, $i, $j)
        {
            $temp = TickerInfo::copy($arr[$i]);
            $arr[$i] = TickerInfo::copy($arr[$j]);
            $arr[$j] = $temp;
        }

        /**
        * takes last element as pivot, places the pivot element at its correct position in sorted array, 
        * and places all smaller (smaller than pivot) to left of pivot and all greater elements to right of pivot
        *
        * @param  	ticker_arr	array to be partitiioned
        * @param  	low	        starting index of the array
        * @param  	high        ending index of the array
        * @param  	option      descending or ascending option
        * @param  	item        variable to use as a base to sort
        */
        private static function partition(&$ticker_arr, $low, $high, $option, $item)
        {
            if($item == "CHANGE")
            {
                $pivot = $ticker_arr[$high]->getChange();
            }

            $i = $low - 1;

            for($j = $low; $j <= $high - 1; $j++)
            {
                if($option == "DESCENDING")
                {
                    if($item == "CHANGE")
                    {
                        if($ticker_arr[$j]->getChange() > $pivot)
                        {
                            $i++;
                            TickerInfo::swap($ticker_arr, $i, $j);
                        }
                    }
                }
                else if($option == "ASCENDING")
                {
                    if($item == "CHANGE")
                    {
                        if($ticker_arr[$j]->getChange() < $pivot)
                        {
                            $i++;
                            TickerInfo::swap($ticker_arr, $i, $j);
                        }
                    }
                }
            }
            TickerInfo::swap($ticker_arr, ($i + 1), $high);
            return ($i + 1);
        }

        /**
        * Sort an TickerInfo array using quick sort 
        *
        * @param  	ticker_arr	array to be sorted
        * @param  	low	        starting index of the array
        * @param  	high        ending index of the array
        * @param  	item        variable to use as a base to sort
        */
        public static function sort(&$ticker_arr, $low, $high, $option, $item)
        {
            $option = strtoupper($option);
            $item = strtoupper($item);
            if($low < $high)
            {
                $pi = TickerInfo::partition($ticker_arr, $low, $high, $option, $item);

                TickerInfo::sort($ticker_arr, $low, ($pi - 1), $option, $item);
                TickerInfo::sort($ticker_arr, ($pi + 1), $high, $option, $item);
            }
        }
    }
?>