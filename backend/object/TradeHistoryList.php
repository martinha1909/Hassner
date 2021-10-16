<?php
    class TradeHistoryList
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

        function dateHasExisted($date): bool
        {
            $ret = false;

            if(!$this->isListEmpty())
            {
                $current_node = $this->head;
                while(($current_node != null))
                {
                    if($current_node->getData()->getDate() == $date)
                    {
                        $ret = true;
                        break;
                    }

                    $current_node = $current_node->getNext();
                }
            }

            return $ret;
        }

        function addToExistedDate($date, $price, $volumn)
        {
            $current_node = $this->head;
            while(($current_node != null))
            {
                if($current_node->getData()->getDate() == $date)
                {
                    $current_node->getData()->addPrice($price);
                    $current_node->getData()->addValue($price);
                    $current_node->getData()->addVolumn($volumn);
                    $current_node->getData()->addTrade();
                    break;
                }

                $current_node = $current_node->getNext();
            }
        }

        function addItem(TradeHistoryItem $t)
        {
            $new_node = new Node();
            $new_node->setData($t);
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

        function finalize()
        {
            $current_node = $this->head;
            while(($current_node != null))
            {
                $current_node->getData()->finalizeMin();
                $current_node->getData()->finalizeMax();
                $current_node->getData()->finalizeVolumn();
                $current_node->getData()->finalizeValue();

                $current_node = $current_node->getNext();
            }
        }

        function getListSize(): int
        {
            return $this->size;
        }

        function getIndex($index): TradeHistoryItem
        {
            $ret = NULL;

            if($index < $this->getListSize())
            {
                $counter = 0;
                $current_node = $this->head;

                while(($current_node != null))
                {
                    if($counter == $index)
                    {
                        $ret = $current_node->getData();
                        break;
                    }

                    $current_node = $current_node->getNext();
                    $counter++;
                }
            }

            return $ret;
        }

        function toString(): string
        {
            $ret = "";
            // $temp = new Node();
            // $temp = $this->head;
            // if($temp != null) 
            // {
            //     $ret .= "The list contains: <br>";
            //     while($temp != null) 
            //     {
            //         $ret .= $temp->getData()->toString()."<br>";
            //         $temp = $temp->getNext();
            //     }
            //     $ret .= "\n";
            // } 
            // else 
            // {
            //     $ret .= "The list is empty.\n";
            // }

            return $ret;
        }
    }
?>