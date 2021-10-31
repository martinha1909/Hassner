<?php
    class ArtistInfo
    {
        private $username;
        private $market_tag;
        //total number of shares other users have bought from the artist
        private $shares_bought;
        private $share_distributed; 
        private $pps;
        private $monthly_shareholder;
        private $market_cap;
        private $share_repurchase;
        private $day_change;

        function __construct()
        {
            $this->username = "";
            $this->market_tag = "";
            $this->shares_bought = 0;
            $this->share_distributed = 0;
            $this->pps = 0;
            $this->monthly_shareholder = 0;
            $this->market_cap = 0;
            $this->share_repurchase = 0;
            $this->day_change = 0;
        } 

        /**
         * Get the value of username
         */ 
        public function getUsername()
        {
            return $this->username;
        }

        /**
         * Set the value of username
         */ 
        public function setUsername($username)
        {
            $this->username = $username;
        }

        /**
         * Get the value of market_tag
         */ 
        public function getMarketTag()
        {
            return $this->market_tag;
        }

        /**
         * Set the value of market_tag
         */ 
        public function setMarketTag($market_tag)
        {
            $this->market_tag = $market_tag;
        }

        /**
         * Get the value of shares_bought
         */ 
        public function getSharesBought()
        {
            return $this->shares_bought;
        }

        /**
         * Set the value of shares_bought
         */ 
        public function setSharesBought($shares_bought)
        {
            $this->shares_bought = $shares_bought;
        }

        /**
         * Get the value of share_distributed
         */ 
        public function getShareDistributed()
        {
            return $this->share_distributed;
        }

        /**
         * Set the value of share_distributed
         */ 
        public function setShareDistributed($share_distributed)
        {
            $this->share_distributed = $share_distributed;
        }

        /**
         * Get the value of pps
         */ 
        public function getPPS()
        {
            return $this->pps;
        }

        /**
         * Set the value of pps
         */ 
        public function setPPS($pps)
        {
            $this->pps = $pps;
        }

        /**
         * Get the value of monthly_shareholder
         */ 
        public function getMonthlyShareholder()
        {
            return $this->monthly_shareholder;
        }

        /**
         * Set the value of monthly_shareholder
         */ 
        public function setMonthlyShareholder($monthly_shareholder)
        {
            $this->monthly_shareholder = $monthly_shareholder;
        }

        /**
         * Get the value of market_cap
         */ 
        public function getMarketCap()
        {
            return $this->market_cap;
        }

        /**
         * Set the value of market_cap
         */ 
        public function setMarketCap($market_cap)
        {
            $this->market_cap = $market_cap;
        }

        /**
         * Get the value of share_repurchase
         */ 
        public function getShareRepurchase()
        {
            return $this->share_repurchase;
        }

        /**
         * Set the value of share_repurchase
         */ 
        public function setShareRepurchase($share_repurchase)
        {
            $this->share_repurchase = $share_repurchase;
        }

        /**
         * Get the value of day_change
         */ 
        public function getDayChange()
        {
            return $this->day_change;
        }

        /**
         * Set the value of day_change
         */ 
        public function setDayChange($day_change)
        {
            $this->day_change = $day_change;
        }

        public static function copy(ArtistInfo $artist)
        {
            $ret = new ArtistInfo();

            $ret->setUsername($artist->getUsername());
            $ret->setMarketTag($artist->getMarketTag());
            $ret->setSharesBought($artist->getSharesBought());
            $ret->setShareDistributed($artist->getShareDistributed());
            $ret->setPPS($artist->getPPS());
            $ret->setMonthlyShareholder($artist->getMonthlyShareholder());
            $ret->setMarketCap($artist->getMarketCap());
            $ret->setShareRepurchase($artist->getShareRepurchase());
            $ret->setDayChange($artist->getDayChange());

            return $ret;
        }

        /**
        * Swaps 2 elements of the array, using copy function as a variable placeholder
        *
        * @param  	arr array in which indices are swapped
        * @param  	i   index to be swapped
        * @param  	j   index to be swapped
        */
        public static function swap(&$arr, $i, $j)
        {
            $temp = ArtistInfo::copy($arr[$i]);
            $arr[$i] = ArtistInfo::copy($arr[$j]);
            $arr[$j] = $temp;
        }

        /**
        * takes last element as pivot, places the pivot element at its correct position in sorted array, 
        * and places all smaller (smaller than pivot) to left of pivot and all greater elements to right of pivot
        *
        * @param  	artist_info_arr	array to be partitiioned
        * @param  	low	            starting index of the array
        * @param  	high            ending index of the array
        * @param  	option          descending or ascending option
        * @param  	item            variable to use as a base to sort
        */
        public static function partition(&$artist_info_arr, $low, $high, $option, $item)
        {
            if($item == "Day Change")
            {
                $pivot = $artist_info_arr[$high]->getDayChange();
            }
            else if($item == "Market Cap")
            {
                $pivot = $artist_info_arr[$high]->getMarketCap();
            }

            $i = $low - 1;

            for($j = $low; $j <= $high - 1; $j++)
            {
                if($option == "Descending")
                {
                    if($item == "Day Change")
                    {
                        if($artist_info_arr[$j]->getDayChange() > $pivot)
                        {
                            $i++;
                            ArtistInfo::swap($artist_info_arr, $i, $j);
                        }
                    }
                    else if($item == "Market Cap")
                    {
                        if($artist_info_arr[$j]->getMarketCap() > $pivot)
                        {
                            $i++;
                            ArtistInfo::swap($artist_info_arr, $i, $j);
                        }
                    }
                }
                else if($option == "Ascending")
                {
                    if($item == "Day Change")
                    {
                        if($artist_info_arr[$j]->getDayChange() < $pivot)
                        {
                            $i++;
                            ArtistInfo::swap($artist_info_arr, $i, $j);
                        }
                    }
                    else if($item == "Market Cap")
                    {
                        if($artist_info_arr[$j]->getMarketCap() < $pivot)
                        {
                            $i++;
                            ArtistInfo::swap($artist_info_arr, $i, $j);
                        }
                    }
                }
            }
            ArtistInfo::swap($artist_info_arr, ($i + 1), $high);
            return ($i + 1);
        }

        /**
        * Sort an ArtistInfo array using quick sort 
        *
        * @param  	artist_info_arr	array to be sorted
        * @param  	low	            starting index of the array
        * @param  	high            ending index of the array
        * @param  	item            variable to use as a base to sort
        */
        public static function sort(&$artist_info_arr, $low, $high, $option, $item)
        {
            if($low < $high)
            {
                $pi = ArtistInfo::partition($artist_info_arr, $low, $high, $option, $item);

                ArtistInfo::sort($artist_info_arr, $low, ($pi - 1), $option, $item);
                ArtistInfo::sort($artist_info_arr, ($pi + 1), $high, $option, $item);
            }
        }
    }
?>