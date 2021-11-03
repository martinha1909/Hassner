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
            private $time_posted;

            function __construct($id, $user_username, $artist_username, $selling_price, $no_of_share, $date_posted, $time_posted)
            {
                $this->id = $id;
                $this->user_username = $user_username;
                $this->artist_username = $artist_username;
                $this->selling_price = $selling_price;
                $this->no_of_share = $no_of_share;
                $this->date_posted = $date_posted;
                $this->time_posted = $time_posted;
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

            function getTimePosted()
            {
                return $this->time_posted;
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

            function setTimePosted($time_posted)
            {
                $this->time_posted = $time_posted;
            }

            public static function copy(SellOrder $sell_order): SellOrder
            {
                return (new SellOrder($sell_order->getID(),
                                      $sell_order->getUser(),
                                      $sell_order->getArtist(),
                                      $sell_order->getSellingPrice(),
                                      $sell_order->getNoOfShare(),
                                      $sell_order->getDatePosted(),
                                      $sell_order->getTimePosted()));
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
            public static function partition(&$sell_order_arr, $low, $high, $option, $item)
            {
                if($item == "Price")
                {
                    $pivot = $sell_order_arr[$high]->getSellingPrice();
                }

                $i = $low - 1;

                for($j = $low; $j <= $high - 1; $j++)
                {
                    if($option == "Descending")
                    {
                        if($item == "Price")
                        {
                            if($sell_order_arr[$j]->getSellingPrice() > $pivot)
                            {
                                $i++;
                                SellOrder::swap($sell_order_arr, $i, $j);
                            }
                        }
                    }
                    else if($option == "Ascending")
                    {
                        if($item == "Price")
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
                if($low < $high)
                {
                    $pi = SellOrder::partition($sell_order_arr, $low, $high, $option, $item);

                    SellOrder::sort($sell_order_arr, $low, ($pi - 1), $option, $item);
                    SellOrder::sort($sell_order_arr, ($pi + 1), $high, $option, $item);
                }
            }
        }

        define('SELLORDER_LOADED', 1);
    }
?>