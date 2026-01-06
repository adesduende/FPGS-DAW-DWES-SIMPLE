<?php
namespace sportshop\app\data\interfaces;

use sportshop\app\models\Cart;
use sportshop\app\models\CartProduct;
interface ICartRepository {
    //Retrieve
    /**
     * Get the cart for a specific user.
     * @param string $userId
     * @return void
     */
    public function GetCart(string $userId) : Cart;
    //Create
    /**
     * Add a product to the cart.
     * @param CartProduct $product
     * @return void
     */
    public function AddToCart(CartProduct $product) : bool;
    //Update
    /**
     * Update the quantity of a product in the cart.
     * @param CartProduct $product
     * @return void
     */
    public function UpdateCart(CartProduct $product) : bool;
    //Delete
    /**
     * Remove a product from the cart.
     * @param CartProduct $product
     * @return void
     */
    public function RemoveFromCart(CartProduct $product) : bool;
    /**
     * Clear the cart for a specific user.
     * @param string $userId
     * @return void
     */
    public function ClearCart(string $userId) : void;
}