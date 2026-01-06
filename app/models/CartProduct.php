<?php
namespace sportshop\app\models;

class CartProduct {
    public string $CartId;
    public Product $Product;
    public int $Quantity;

    public function __construct(string $cartId, Product $product, int $quantity)
    {
        $this->CartId = $cartId;
        $this->Product = $product;
        $this->Quantity = $quantity;
    }
}