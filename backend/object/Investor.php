<?php
    include 'Person.php';

    class Investor extends Person
    {
        private $total_shares_bought;
        //This could be used as both amount of money invested throughout the whole platform or towards a specific artist
        private $amount_invested;
        private $campaigns_won;
        private $campaigns_participated;

        function __construct()
        {
            parent::__construct("user");
            $this->total_shares_bought = 0;
        }

        /**
         * Get the value of total_shares_bought
         */ 
        public function getTotalSharesBought()
        {
                return $this->total_shares_bought;
        }

        /**
         * Set the value of total_shares_bought
         */ 
        public function setTotalSharesBought($total_shares_bought)
        {
                $this->total_shares_bought = $total_shares_bought;
        }

        /**
         * Get the value of amount_invested
         */ 
        public function getAmountInvested()
        {
                return $this->amount_invested;
        }

        /**
         * Set the value of amount_invested
         */ 
        public function setAmountInvested($amount_invested)
        {
                $this->amount_invested = $amount_invested;
        }

        /**
         * Get the value of campaigns_won
         */ 
        public function getCampaignsWon()
        {
                return $this->campaigns_won;
        }

        /**
         * Set the value of campaigns_won
         */ 
        public function setCampaignsWon($campaigns_won)
        {
                $this->campaigns_won = $campaigns_won;
        }

        /**
         * Get the value of campaigns_participated
         */ 
        public function getCampaignsParticipated()
        {
                return $this->campaigns_participated;
        }

        /**
         * Set the value of campaigns_participated
         */ 
        public function setCampaignsParticipated($campaigns_participated)
        {
                $this->campaigns_participated = $campaigns_participated;
        }
    }
?>