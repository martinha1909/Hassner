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
        }

        define('SELLORDER_LOADED', 1);
    }
?>