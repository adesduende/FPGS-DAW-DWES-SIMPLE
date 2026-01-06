<?php
namespace sportshop\app\data;

use sportshop\app\models\Cart;
use sportshop\app\models\CartProduct;
use sportshop\app\models\Product;
interface ICartRepository {
    //Retrieve
    public function GetCart(string $userId) : Cart;
    //Create
    public function AddToCart(CartProduct $product) : bool;
    //Update
    public function UpdateCart(CartProduct $product) : bool;
    //Delete
    public function RemoveFromCart(CartProduct $product) : bool;
    public function ClearCart(string $userId) : void;
}