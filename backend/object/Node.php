<?php
    class Node 
    {
        private $data;
        private $next;

        public function __construct()
        {
            $data = 0;
            $next = null;
        }

        public function setData($data)
        {
            $this->data = $data;
        }

        public function getData()
        {
            return $this->data;
        }

        public function setNext($next)
        {
            $this->next = $next;
        }

        public function getNext()
        {
            return $this->next;
        }
    }
?>