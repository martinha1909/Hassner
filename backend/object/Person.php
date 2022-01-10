<?php
    abstract class Person 
    {
        protected $username;
        protected $account_type;
        protected $balance;
        protected $email;
        protected $billing_address;
        protected $full_name;
        protected $city;
        protected $state;
        protected $zip;
        protected $card_number;
        protected $transit_no;
        protected $inst_no;
        protected $account_no;
        protected $swift_code;

        function __construct($account_type)
        {
            $this->username = "";
            $this->account_type = $account_type;
            $this->balance = 0;
            $this->email = "";
            $this->billing_address = "";
            $this->full_name = "";
            $this->city = "";
            $this->state = "";
            $this->zip = "";
            $this->card_number = 0;
            $this->transit_no = 0;
            $this->inst_no = 0;
            $this->account_no = 0;
            $this->swift_code = "";
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
         * Get the value of account_type
         */ 
        public function getAccountType()
        {
                return $this->account_type;
        }

        /**
         * Set the value of account_type
         */ 
        public function setAccountType($account_type)
        {
                $this->account_type = $account_type;
        }

        /**
         * Get the value of balance
         */ 
        public function getBalance()
        {
                return $this->balance;
        }

        /**
         * Set the value of balance
         */ 
        public function setBalance($balance)
        {
                $this->balance = $balance;
        }

        /**
         * Get the value of email
         */ 
        public function getEmail()
        {
                return $this->email;
        }

        /**
         * Set the value of email
         */ 
        public function setEmail($email)
        {
                $this->email = $email;
        }

        /**
         * Get the value of billing_address
         */ 
        public function getBillingAddress()
        {
                return $this->billing_address;
        }

        /**
         * Set the value of billing_address
         */ 
        public function setBillingAddress($billing_address)
        {
                $this->billing_address = $billing_address;
        }

        /**
         * Get the value of full_name
         */ 
        public function getFullName()
        {
                return $this->full_name;
        }

        /**
         * Set the value of full_name
         */ 
        public function setFullName($full_name)
        {
                $this->full_name = $full_name;
        }

        /**
         * Get the value of city
         */ 
        public function getCity()
        {
                return $this->city;
        }

        /**
         * Set the value of city
         */ 
        public function setCity($city)
        {
                $this->city = $city;
        }

        /**
         * Get the value of state
         */ 
        public function getState()
        {
                return $this->state;
        }

        /**
         * Set the value of state
         */ 
        public function setState($state)
        {
                $this->state = $state;
        }

        /**
         * Get the value of zip
         */ 
        public function getZip()
        {
                return $this->zip;
        }

        /**
         * Set the value of zip
         *
         * @return  self
         */ 
        public function setZip($zip)
        {
                $this->zip = $zip;

                return $this;
        }

        /**
         * Get the value of card_number
         */ 
        public function getCardNumber()
        {
                return $this->card_number;
        }

        /**
         * Set the value of card_number
         */ 
        public function setCardNumber($card_number)
        {
                $this->card_number = $card_number;
        }

        /**
         * Get the value of transit_no
         */ 
        public function getTransitNo()
        {
                return $this->transit_no;
        }

        /**
         * Set the value of transit_no
         */ 
        public function setTransitNo($transit_no)
        {
                $this->transit_no = $transit_no;
        }

        /**
         * Get the value of inst_no
         */ 
        public function getInstNo()
        {
                return $this->inst_no;
        }

        /**
         * Set the value of inst_no
         */ 
        public function setInstNo($inst_no)
        {
                $this->inst_no = $inst_no;
        }

        /**
         * Get the value of account_no
         */ 
        public function getAccountNo()
        {
                return $this->account_no;
        }

        /**
         * Set the value of account_no
         */ 
        public function setAccountNo($account_no)
        {
                $this->account_no = $account_no;
        }

        /**
         * Get the value of swift_code
         */ 
        public function getSwiftCode()
        {
                return $this->swift_code;
        }

        /**
         * Set the value of swift_code
         */ 
        public function setSwiftCode($swift_code)
        {
                $this->swift_code = $swift_code;
        }

        abstract protected static function copy(Person $person);
        abstract protected static function sort(&$info_arr, $low, $high, $option, $item);
        abstract protected static function partition(&$info_arr, $low, $high, $option, $item);
        abstract protected static function swap(&$arr, $i, $j);
    }
?>