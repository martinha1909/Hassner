<?php
    class BuyHistory
    {
        private $id;
        private $buyer_username;
        private $seller_username;
        private $artist_username;
        private $no_of_share_bought;
        private $price_per_share;
        private $date_purchased;

        function __construct()
        {
            $this->id = -1;
            $this->buyer_username = "";
            $this->seller_username = "";
            $this->artist_username = "";
            $this->no_of_share_bought = -1;
            $this->price_per_share = -1;
            $this->date_purchased = "";
        }

        public function getID()
        {
            return $this->id;
        }

        public function setID($id)
        {
            $this->id = $id;
        }

        public function getBuyer()
        {
            return $this->buyer_username;
        }

        public function setBuyer($buyer_username)
        {
            $this->buyer_username = $buyer_username;
        }

        public function getSeller()
        {
            return $this->seller_username;
        }

        public function setSeller($seller_username)
        {
            $this->seller_username = $seller_username;
        }

        public function getArtist()
        {
            return $this->artist_username;
        }

        public function setArtist($artist_username)
        {
            $this->artist_username = $artist_username;
        }

        public function getNoOfShareBought()
        {
            return $this->no_of_share_bought;
        }

        public function setNoOfShareBought($no_of_share_bought)
        {
            $this->no_of_share_bought = $no_of_share_bought;
        }

        public function getPPS()
        {
            return $this->price_per_share;
        }

        public function setPPS($price_per_share)
        {
            $this->price_per_share = $price_per_share;
        }

        public function getDatePurchased()
        {
            return $this->date_purchased;
        }

        public function setDatePurchased($date_purchased)
        {
            $this->date_purchased = $date_purchased;
        }

        public static function copy(BuyHistory $buy_history): BuyHistory
        {
            $ret = new BuyHistory();

            $ret = clone $buy_history;

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
            $temp = BuyHistory::copy($arr[$i]);
            $arr[$i] = BuyHistory::copy($arr[$j]);
            $arr[$j] = $temp;
        }

        /**
        * takes last element as pivot, places the pivot element at its correct position in sorted array, 
        * and places all smaller (smaller than pivot) to left of pivot and all greater elements to right of pivot
        *
        * @param  	buy_history_arr	array to be partitiioned
        * @param  	low	            starting index of the array
        * @param  	high            ending index of the array
        * @param  	option          descending or ascending option
        * @param  	item            variable to use as a base to sort
        */
        private static function partition(&$buy_history_arr, $low, $high, $option, $item)
        {
            if($item == "PPS")
            {
                $pivot = $buy_history_arr[$high]->getPPS();
            }

            $i = $low - 1;

            for($j = $low; $j <= $high - 1; $j++)
            {
                if($option == "DESCENDING")
                {
                    if($item == "PPS")
                    {
                        if($buy_history_arr[$j]->getPPS() > $pivot)
                        {
                            $i++;
                            BuyHistory::swap($buy_history_arr, $i, $j);
                        }
                    }
                }
                else if($option == "ASCENDING")
                {
                    if($item == "PPS")
                    {
                        if($buy_history_arr[$j]->getPPS() < $pivot)
                        {
                            $i++;
                            BuyHistory::swap($buy_history_arr, $i, $j);
                        }
                    }
                }
            }
            BuyHistory::swap($buy_history_arr, ($i + 1), $high);
            return ($i + 1);
        }

        /**
        * Sort an SellOrder array using quick sort 
        *
        * @param  	buy_history_arr	array to be sorted
        * @param  	low	            starting index of the array
        * @param  	high            ending index of the array
        * @param  	item            variable to use as a base to sort
        */
        public static function sort(&$buy_history_arr, $low, $high, $option, $item)
        {
            $option = strtoupper($option);
            $item = strtoupper($item);
            if($low < $high)
            {
                $pi = BuyHistory::partition($buy_history_arr, $low, $high, $option, $item);

                BuyHistory::sort($buy_history_arr, $low, ($pi - 1), $option, $item);
                BuyHistory::sort($buy_history_arr, ($pi + 1), $high, $option, $item);
            }
        }
    }
?>