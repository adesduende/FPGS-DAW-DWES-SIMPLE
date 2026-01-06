<?php
    namespace sportshop\app\models;

    use sportshop\app\utils\GUID;
    class Identity {
        public GUID $Id;

        public function __construct(?GUID $id)
        {

            $this->Id = $id ?? GUID::Create();
        }
    }
?>