<?php
    namespace sportshop\app\models;

    use sportshop\app\utils\GUID;

    class Cart extends Identity {

        public string $UserId;
        public array $CartProducts;

        public function __construct(GUID $id, string $userId, array $cartProducts)
        {
            parent::__construct($id);
            $this->UserId = $userId;
            $this->CartProducts = $cartProducts;
        }
    }
?>