<?php

    namespace sportshop\app\utils;
    class GUID {

        public string $Id;

        private function __construct(?string $id) {
            $this->Id=$id;
        }
        static public function Create($id = null): GUID {
            if($id!==null) return new GUID($id);
            // Generate 16 bytes (128 bits) of random data or use the data passed into the function.
            $data = random_bytes(16);
            assert(strlen($data) == 16);

            // Set version to 0100
            $data[6] = chr(ord($data[6]) & 0x0f | 0x40);
            // Set bits 6-7 to 10
            $data[8] = chr(ord($data[8]) & 0x3f | 0x80);

            // Output the 36 character UUID.
            return new GUID(vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data), 4)));
        }
    }


