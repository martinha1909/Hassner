<?php
    include 'Node.php';
    class ParticipantList
    {
        private Node $head;
        private $size;

        public function __construct()
        {
            $this->size = 0;
            $this->head = new Node();
        }

        function isListEmpty()
        {
            //Assume empty
            $ret = true;

            if($this->size > 0)
            {
                $ret = false;
            }

            return $ret;
        }

        function addItem(CampaignParticipant $p)
        {
            $new_node = new Node();
            $new_node->setData($p);
            if($this->isListEmpty())
            {
                unset($this->head);
                $this->head = $new_node;
            }
            else
            {
                $current_node = $this->head;
                while($current_node->getNext() != null)
                {
                    $current_node = $current_node->getNext();
                }
                $current_node->setNext($new_node);
            }
            $this->size++;
        }

        function getLastItem(): CampaignParticipant
        {
            $ret = null;
            if(!($this->isListEmpty()))
            {
                $current_node = $this->head;
                while($current_node->getNext() != null)
                {
                    $current_node = $current_node->getNext();
                }
                $ret = $current_node->getData();
            }

            return $ret;
        }

        function populateWeightedChance($artist_total_shares, &$weighted_chances, &$values)
        {
            if(!($this->isListEmpty()))
            {
                $current_node = $this->head;
                while($current_node != null)
                {
                    $weighted = ($current_node->getData()->getEthosOwned())/($artist_total_shares) * 100;
                    array_push($values, $current_node->getData()->getParticipantName());
                    array_push($weighted_chances, $weighted);
                    $current_node = $current_node->getNext();
                }
            }
        }

        function getListSize(): int
        {
            return $this->size;
        }

        function toString(): string
        {
            $ret = "";
            $temp = new Node();
            $temp = $this->head;
            if($temp != null) 
            {
                $ret .= "The list contains: <br>";
                while($temp != null) 
                {
                    $ret .= $temp->getData()->toString()."<br>";
                    $temp = $temp->getNext();
                }
                $ret .= "\n";
            } 
            else 
            {
                $ret .= "The list is empty.\n";
            }

            return $ret;
        }
    }
?>