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

        protected static function copy(Person $investor)
        {
                $ret = new Investor();

                $ret = clone $investor;

                return $ret;
        }

        protected static function swap(&$arr, $i, $j)
        {
                $temp = Investor::copy($arr[$i]);
                $arr[$i] = Investor::copy($arr[$j]);
                $arr[$j] = $temp;
        }

        protected static function partition(&$info_arr, $low, $high, $option, $item)
        {
                if($item == "Amount Invested")
                {
                        $pivot = $info_arr[$high]->getAmountInvested();
                }

                $i = $low - 1;

                for($j = $low; $j <= $high - 1; $j++)
                {
                        if($option == "Descending")
                        {
                                if($item == "Amount Invested")
                                {
                                        if($info_arr[$j]->getAmountInvested() > $pivot)
                                        {
                                                $i++;
                                                Investor::swap($info_arr, $i, $j);
                                        }
                                }
                        }
                        else if($option == "Ascending")
                        {
                                if($item == "Amount Invested")
                                {
                                        if($info_arr[$j]->getAmountInvested() < $pivot)
                                        {
                                                $i++;
                                                Investor::swap($info_arr, $i, $j);
                                        }
                                }
                        }
                }
                Investor::swap($info_arr, ($i + 1), $high);
                return ($i + 1);
        }

        public static function sort(&$info_arr, $low, $high, $option, $item)
        {
                if($low < $high)
                {
                    $pi = Investor::partition($info_arr, $low, $high, $option, $item);
    
                    Investor::sort($info_arr, $low, ($pi - 1), $option, $item);
                    Investor::sort($info_arr, ($pi + 1), $high, $option, $item);
                }
        }
    }
?>