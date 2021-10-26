<?php
    class TradeHistoryItem
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
    }
?>