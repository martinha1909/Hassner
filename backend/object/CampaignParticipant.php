<?php
    class CampaignParticipant
    {
        private $participant_name;
        //Ethos owned of the participant at the time the campaign expires
        private $ethos_owned;
        private $artist_username;

        function __construct()
        {
            $this->participant_name = "";
            $this->artist_username = "";
            $this->ethos_owned = 0;
            $this->weighted_chance = 0;
        }

        function setParticipantName($participant_name) 
        {
            $this->participant_name = $participant_name;
        }

        function setEthosOwned($ethos) 
        {
            $this->ethos_owned = $ethos;
        }

        function setArtistName($artist_username) 
        {
            $this->artist_username = $artist_username;
        }

        function getParticipantName(): string
        {
            return $this->participant_name;
        }

        function getEthosOwned(): int
        {
            return $this->ethos_owned;
        }

        function getArtistName(): string 
        {
            return $this->artist_username;
        }

        function tostring(): string
        {
            $ret = "";

            $ret = "Participant name: ".$this->participant_name."<br>Ethos owned: ".$this->ethos_owned."<br>Artist Username: ".$this->artist_username;
            return $ret;
        }
    }
?>