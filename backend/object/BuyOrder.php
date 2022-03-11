<?php
    if(!defined('BUYORDER_LOADED'))
    {
        class BuyOrder
        {
            private $id;
            private $user_username;
            private $artist_username;
            private $request_price;
            private $quantity;
            private $date_posted;
            private $buy_limit;
            private $buy_stop;

            function __construct($id, $user_username, $artist_username, $request_price, $quantity, $date_posted)
            {
                $this->id = $id;
                $this->user_username = $user_username;
                $this->artist_username = $artist_username;
                $this->request_price = $request_price;
                $this->quantity = $quantity;
                $this->date_posted = $date_posted;
                $this->buy_limit = -1;
                $this->buy_stop = -1;
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

            function getRequestPrice()
            {
                return $this->request_price;
            }

            function getQuantity()
            {
                return $this->quantity;
            }

            function getDatePosted()
            {
                return $this->date_posted;
            }

            function getBuyLimit()
            {
                return $this->buy_limit;
            }

            function getBuyStop()
            {
                return $this->buy_stop;
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

            function setRequestPrice($request_price)
            {
                $this->request_price = $request_price;
            }

            function setQuantity($quantity)
            {
                $this->quantity = $quantity;
            }

            function setDatePosted($date_posted)
            {
                $this->date_posted = $date_posted;
            }

            function setBuyLimit($buy_limit)
            {
                $this->buy_limit = $buy_limit;
            }

            function setBuyStop($buy_stop)
            {
                $this->buy_stop = $buy_stop;
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
        }

        define('BUYORDER_LOADED', 1);
    }
?>