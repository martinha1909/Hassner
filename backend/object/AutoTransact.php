<?php
    if(!defined('AUTOTRANSACT_LOADED'))
    {
        class AutoTransact
        {
            private $buyer_info;
            private $seller_info;
            private $artist;
            
            function __construct()
            {
                $this->buyer_info = null;
                $this->seller_info = null;
            }

            /**
             * Get the value of buyer_info
             */ 
            public function getBuyerInfo()
            {
                return $this->buyer_info;
            }

            /**
             * Set the value of buyer_info
             */ 
            public function setBuyerInfo($buyer_info)
            {
                $this->buyer_info = $buyer_info;
            }

            /**
             * Get the value of seller_info
             */ 
            public function getSellerInfo()
            {
                return $this->seller_info;
            }

            /**
             * Set the value of seller_info
             */ 
            public function setSellerInfo($seller_info)
            {
                $this->seller_info = $seller_info;
            }

            /**
             * Get the value of artist
             */ 
            public function getArtist()
            {
                return $this->artist;
            }

            /**
             * Set the value of artist
             */ 
            public function setArtist($artist)
            {
                $this->artist = $artist;
            }
        }

        define('AUTOTRANSACT_LOADED', 1);
    }
?>