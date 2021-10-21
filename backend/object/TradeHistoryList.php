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
            return ($this->size <= 0);
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

        function addToExistedDate($date, $price, $volume)
        {
            $current_node = $this->head;
            while(($current_node != null))
            {
                if($current_node->getData()->getDate() == $date)
                {
                    $current_node->getData()->addPrice($price);
                    $current_node->getData()->addValue($price);
                    $current_node->getData()->addVolume($volume);
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
                $current_node->getData()->finalizeVolume();
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

        function addListToTable()
        {
            if($this->size > 0)
            {
                $current_node = $this->head;
                while($current_node != NULL)
                {
                    echo '
                                <tr>
                                    <td>' . $current_node->getData()->getDate() . '</td>
                                    <td>' . $current_node->getData()->getFinalizeMin() . '/'.$current_node->getData()->getFinalizeMax().'</td>
                                    <td>' . $current_node->getData()->getFinalizeVolume() . '</td>
                                    <td>' . $current_node->getData()->getFinalizeValue() . '</td>
                                    <td>' . $current_node->getData()->getTrade() . '</td>
                                </tr>
                    ';

                    $current_node = $current_node->getNext();
                }
            }
        }
    }
?>