<?php
    namespace sportshop\app\utils;

    /**
     * ServiceCollection
     * This class manages a collection of services for dependency injection.
     * It follows the singleton pattern to ensure a single instance throughout the application.
     */
    class ServiceCollection {
        protected array $collection;
        private static ?ServiceCollection $instance = null;
        private function __construct(){
            $this->collection=[];
        }
        public static function GetInstance(): ServiceCollection{
            if(self::$instance===null){
                self::$instance=new ServiceCollection();
            }
            return self::$instance;
        }
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