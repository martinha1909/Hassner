<?php
    if(!defined('SELLORDER_LOADED'))
    {
        class SellOrder
        {
            private $id;
            private $user_username;
            private $artist_username;
            private $selling_price;
            private $no_of_share;
            private $date_posted;
            private $sell_limit;
            private $sell_stop;

            function __construct($id, $user_username, $artist_username, $selling_price, $no_of_share, $date_posted)
            {
                $this->id = $id;
                $this->user_username = $user_username;
                $this->artist_username = $artist_username;
                $this->selling_price = $selling_price;
                $this->no_of_share = $no_of_share;
                $this->date_posted = $date_posted;
                $this->sell_limit = -1;
                $this->sell_stop = -1;
            }

            function getID()
            {
                return $this->id;
            }

            function getUser()
            {
                return $this->user_username;
            }

            function getArtist()
            {
                return $this->artist_username;
            }

            function getSellingPrice()
            {
                return $this->selling_price;
            }

            function getNoOfShare()
            {
                return $this->no_of_share;
            }

            function getDatePosted()
            {
                return $this->date_posted;
            }

            function getSellLimit()
            {
                return $this->sell_limit;
            }

            function getSellStop()
            {
                return $this->sell_stop;
            }

            function setID($id)
            {
                $this->id = $id;
            }

            function setUser($user_username)
            {
                $this->user_username = $user_username;
            }

            function setArtist($artist_username)
            {
                $this->artist_username = $artist_username;
            }

            function setSellingPrice($selling_price)
            {
                $this->selling_price = $selling_price;
            }

            function setNoOfShare($no_of_share)
            {
                $this->no_of_share = $no_of_share;
            }

            function setDatePosted($date_posted)
            {
                $this->date_posted = $date_posted;
            }

            function setSellLimit($sell_limit)
            {
                $this->sell_limit = $sell_limit;
            }

            function setSellStop($sell_stop)
            {
                $this->sell_stop = $sell_stop;
            }

            public static function copy(SellOrder $sell_order): SellOrder
            {
                return (new SellOrder($sell_order->getID(),
                                      $sell_order->getUser(),
                                      $sell_order->getArtist(),
                                      $sell_order->getSellingPrice(),
                                      $sell_order->getNoOfShare(),
                                      $sell_order->getDatePosted()));
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
                $temp = SellOrder::copy($arr[$i]);
                $arr[$i] = SellOrder::copy($arr[$j]);
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
            private static function partition(&$sell_order_arr, $low, $high, $option, $item)
            {
                if($item == "PRICE")
                {
                    $pivot = $sell_order_arr[$high]->getSellingPrice();
                }

                $i = $low - 1;

                for($j = $low; $j <= $high - 1; $j++)
                {
                    if($option == "DESCENDING")
                    {
                        if($item == "PRICE")
                        {
                            if($sell_order_arr[$j]->getSellingPrice() > $pivot)
                            {
                                $i++;
                                SellOrder::swap($sell_order_arr, $i, $j);
                            }
                        }
                    }
                    else if($option == "ASCENDING")
                    {
                        if($item == "PRICE")
                        {
                            if($sell_order_arr[$j]->getSellingPrice() < $pivot)
                            {
                                $i++;
                                SellOrder::swap($sell_order_arr, $i, $j);
                            }
                        }
                    }
                }
                SellOrder::swap($sell_order_arr, ($i + 1), $high);
                return ($i + 1);
            }

            /**
            * Sort an SellOrder array using quick sort 
            *
            * @param  	sell_order_arr	array to be sorted
            * @param  	low	            starting index of the array
            * @param  	high            ending index of the array
            * @param  	item            variable to use as a base to sort
            */
            public static function sort(&$sell_order_arr, $low, $high, $option, $item)
            {
                $option = strtoupper($option);
                $item = strtoupper($item);
                if($low < $high)
                {
                    $pi = SellOrder::partition($sell_order_arr, $low, $high, $option, $item);

                    SellOrder::sort($sell_order_arr, $low, ($pi - 1), $option, $item);
                    SellOrder::sort($sell_order_arr, ($pi + 1), $high, $option, $item);
                }
            }

            public static function toString($sell_order_arr): string
            {
                $ret = "";

                for($i = 0; $i < sizeof($sell_order_arr); $i++)
                {
                    $ret .= "Sell order id: ".$sell_order_arr[$i]->getID()."\n".
                            "Seller: ".$sell_order_arr[$i]->getUser()."\n".
                            "Artist: ".$sell_order_arr[$i]->getArtist()."\n".
                            "Selling price: ".$sell_order_arr[$i]->getSellingPrice()."\n".
                            "No of share: ".$sell_order_arr[$i]->getNoOfShare()."\n".
                            "Date posted: ".$sell_order_arr[$i]->getDatePosted()."\n".
                            "Sell limit: ".$sell_order_arr[$i]->getSellLimit()."\n".
                            "Sell stop: ".$sell_order_arr[$i]->getSellStop()."\n".
                            "----------------------------------------------\n";
                }

                return $ret;
            }

        }

        define('SELLORDER_LOADED', 1);
    }
?>