<?php

namespace sportshop\app\data;

use sportshop\app\data\interfaces\ICartRepository;
use sportshop\app\models\Cart;
use sportshop\app\models\CartProduct;
use sportshop\app\models\Category;
use sportshop\app\models\Product;
use sportshop\app\utils\GUID;

readonly class CartRepository implements ICartRepository {

    private DbContext $_context;

    public function __construct(DbContext $context) {
        $this->_context = $context;
    }
    public function AddToCart(CartProduct $product): bool
    {
        $stmt = $this->_context->getConnection()->prepare("INSERT INTO cart_products(cart_id, product_id, quantity) VALUES(:cart_id, :product_id, :quantity)");
        $stmt->bindParam(':cart_id',$product->CartId);
        $stmt->bindParam(':product_id',$product->Product->Id->Id);
        $stmt->bindParam(':quantity',$product->Quantity,\PDO::PARAM_INT);
        $stmt->execute();
        return true;
    }
    public function RemoveFromCart(CartProduct $product): bool
    {
        $stmt = $this->_context->getConnection()->prepare("DELETE FROM cart_products WHERE cart_id = :cart_id AND product_id = :product_id");
        $stmt->bindParam('cart_id',$product->CartId, \PDO::PARAM_STR);
        $stmt->bindParam('product_id',$product->Product->Id->Id, \PDO::PARAM_STR);
        $stmt->execute();
        $this->_context->disconnect();
        return true;
    }
    public function ClearCart(string $userId): void
    {

        $stmt = $this->_context->getConnection()->prepare("DELETE FROM cart_products WHERE (SELECT cart.id FROM cart WHERE user_id = :user_id LIMIT 1) = cart_products.cart_id");
        $stmt->bindParam('user_id',$userId, \PDO::PARAM_STR);
        $stmt->execute();
        $this->_context->disconnect();
    }
    public function GetCart(string $userId): Cart
    {
        $conn = $this->_context->getConnection();
        $conn->beginTransaction();
        //Get cart ID
        $stmt = $conn->prepare("SELECT cart.id FROM cart WHERE user_id = :user_id LIMIT 1");
        $stmt->bindParam('user_id',$userId, \PDO::PARAM_STR);
        $stmt->execute();
        $cartId = $stmt->fetch(\PDO::FETCH_ASSOC);

        //Get all the data
        $stmt = $conn->prepare("
            SELECT c.id as cart_id ,cp.quantity,p.*,ca.name as category_name, ca.description as category_description 
            FROM cart c
            INNER JOIN cart_products cp ON cp.cart_id = c.id
            INNER JOIN product p ON p.id = cp.product_id
            INNER JOIN category ca ON p.category_id = ca.id            
            WHERE c.user_id = :user_id
            ");
        $stmt->bindParam('user_id',$userId, \PDO::PARAM_STR);
        $stmt->execute();
        $products = [];
        while ($product = $stmt->fetch(\PDO::FETCH_ASSOC)) {
            $products[] = new CartProduct(
                $cartId['id'],
                new Product(
                    GUID::Create($product['id']),
                    $product['name'],
                    new Category(
                        GUID::Create($product['category_id']),
                        $product['category_name'],
                        $product['category_description']
                    ),
                    $product['price'],
                    $product['image_url'],
                    $product['rating'],
                    $product['stock'],
                    $product['badge'],
                    $product['discount'],
                    $product['description'],
                    $product['is_active'],

                ),
                $product['quantity']
            );
        }
        $conn->commit();
        $this->_context->disconnect();
        return new Cart(
            GUID::Create($cartId['id']),
            $userId,
            $products
        );
    }
    public function UpdateCart(CartProduct $product): bool
    {
        $stmt = $this->_context->getConnection()->prepare("UPDATE cart_products SET quantity = :quantity WHERE cart_id = :cart_id AND product_id = :product_id");
        $stmt->bindParam('quantity',$product->Quantity, \PDO::PARAM_INT);
        $stmt->bindParam('cart_id',$product->CartId, \PDO::PARAM_STR);
        $stmt->bindParam('product_id',$product->Product->Id->Id, \PDO::PARAM_STR);
        $stmt->execute();
        $this->_context->disconnect();
        return true;
    }
}
