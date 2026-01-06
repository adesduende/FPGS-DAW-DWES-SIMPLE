<?php
    namespace sportshop\app\utils;

    class ServiceCollection {
        protected array $collection;

        public function Add(string $type, object $service){
            $this->collection[$type]=$service;
        }

        public function GetService(string $type): ?object{
            if(array_key_exists($type,$this->collection))
                return $this->collection[$type];
            return null;
        }
        
    }
?>