<?php
    class Campaign 
    {
        private $id;
        private $artist_username;
        private $offering;
        private $date_posted;
        private $date_expires;
        private $type;
        private $min_ethos;
        private $eligible_participants;
        private $winner;

        function __construct()
        {
            $this->id = 0;
            $this->artist_username = "";
            $this->offering = "";
            $this->date_posted = "";
            $this->date_expires = "";
            $this->type = "";
            $this->min_ethos = 0;
            $this->eligible_participants = 0;
            $this->winner = "";
        }

        /**
         * Get the value of id
         */ 
        public function getId()
        {
            return $this->id;
        }

        /**
         * Set the value of id
         */ 
        public function setId($id)
        {
            $this->id = $id;
        }

        /**
         * Get the value of artist_username
         */ 
        public function getArtistUsername()
        {
            $this->artist_username;
        }

        /**
         * Set the value of artist_username
         */ 
        public function setArtistUsername($artist_username)
        {
            $this->artist_username = $artist_username;
        }

        /**
         * Get the value of offering
         */ 
        public function getOffering()
        {
            return $this->offering;
        }

        /**
         * Set the value of offering
         */ 
        public function setOffering($offering)
        {
            $this->offering = $offering;
        }

        /**
         * Get the value of date_posted
         */ 
        public function getDatePosted()
        {
            return $this->date_posted;
        }

        /**
         * Set the value of date_posted
        */ 
        public function setDatePosted($date_posted)
        {
            $this->date_posted = $date_posted;
        }

        /**
         * Get the value of date_expires
        */ 
        public function getDateExpires()
        {
            return $this->date_expires;
        }

        /**
         * Set the value of date_expires
        */ 
        public function setDateExpires($date_expires)
        {
            $this->date_expires = $date_expires;
        }

        /**
         * Get the value of type
         */ 
        public function getType()
        {
            return $this->type;
        }

        /**
         * Set the value of type
         */ 
        public function setType($type)
        {
            $this->type = $type;
        }

        /**
         * Get the value of min_ethos
         */ 
        public function getMinEthos()
        {
            return $this->min_ethos;
        }

        /**
         * Set the value of min_ethos
        */ 
        public function setMinEthos($min_ethos)
        {
            $this->min_ethos = $min_ethos;
        }

        /**
         * Get the value of eligible_participants
         */ 
        public function getEligibleParticipants()
        {
            return $this->eligible_participants;
        }

        /**
         * Set the value of eligible_participants
        */ 
        public function setEligibleParticipants($eligible_participants)
        {
            $this->eligible_participants = $eligible_participants;
        }

        /**
         * Get the value of winner
         */ 
        public function getWinner()
        {
            return $this->winner;
        }

        /**
         * Set the value of winner
         */ 
        public function setWinner($winner)
        {
            $this->winner = $winner;
        }
    }
?>