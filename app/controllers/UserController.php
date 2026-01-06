<?php

namespace sportshop\app\controllers;

use DateTime;
use sportshop\app\data\interfaces\IUserRepository;
use sportshop\app\data\interfaces\IProductRepository;
use sportshop\app\data\interfaces\IOrderRepository;
use sportshop\app\data\interfaces\ICartRepository;
use sportshop\app\models\CartProduct;
use sportshop\app\models\Order;
use sportshop\app\services\Hash;
use sportshop\app\utils\GUID;
/**
 * User Controller - This controller manage all about the user actions
 */
class UserController extends ControllerBase
{

    private readonly IUserRepository $_userRepository;
    private readonly ICartRepository $_cartRepository;
    private readonly IOrderRepository $_orderRepository;
    private readonly IProductRepository $_productRepository;
    /**
     * UserController constructor
     * @param IUserRepository $userRepository - The user repository
     * @param ICartRepository $cartRepository - The cart repository
     * @param IOrderRepository $orderRepository - The order repository
     * @param IProductRepository $productRepository - The product repository
     */
    public function __construct(
        IUserRepository $userRepository,
        ICartRepository $cartRepository,
        IOrderRepository $orderRepository,
        IProductRepository $productRepository
    )
    {
        parent::__construct();
        $this->_userRepository = $userRepository;
        $this->_cartRepository = $cartRepository;
        $this->_orderRepository = $orderRepository;
        $this->_productRepository = $productRepository;
    }
    //[GET]
    /**
     * This method show the user cart
     * @return void
     */
    public function Cart(): void
    {
        if (!$this->data['isLogin']) {
            header('Location: /auth/login');
            exit();
        }

        $cartItems = $this->_cartRepository->GetCart($this->data["user_id"]);
        $subtotal = 0;
        foreach ($cartItems->CartProducts as $item) {
            $subtotal += $item->Product->Price * $item->Quantity;
        }
        $shipping = 5.99;
        $tax = $subtotal * 0.21; // 21% IVA
        $total = $subtotal + $shipping + $tax;
        $this->data['cart_items'] = $cartItems->CartProducts;
        $this->data['cart_items_subtotal'] = $subtotal;
        $this->data['cart_items_shipping'] = $shipping;
        $this->data['cart_items_tax'] = $tax;
        $this->data['cart_items_total'] = $total;

        $view = '/app/views/user/cart.php';
        include LAYOUT;
    }
    //[GET]
    /**
     * This method return the cart count in json
     * @return void
     */
    public function CartCount(): void
    {
        if (!$this->data['isLogin']) {
            exit();
        }
        $cart = $this->_cartRepository->GetCart($this->data["user_id"]);
        $total = 0;
        foreach ($cart->CartProducts as $item) {
            $total += $item->Quantity;
        }

        Header("Content-type: application/json");
        echo json_encode($total);

    }
    //[POST]
    /**
     * This method add a product to the user cart
     * @param array $request - The request data (product_id)
     * @return void
     */
    public function CartAdd($request): void
    {
        if (!$this->data['isLogin']) {
            exit();
        }
        $productId = $request['product_id']??null;
        if($productId==null)
        {
            //Return false and the error
            exit();
        }
        $productToAdd = $this->_productRepository->GetById($productId);
        if(!$productToAdd)
            exit();
        $cart = $this->_cartRepository->GetCart($this->data["user_id"]);

        $cartProduct = array_find($cart->CartProducts, function ($item) use ($productId) { 
            return $item->Product->Id->Id === $productId;
        });
        
        if($cartProduct)
        {
            $cartProduct->Quantity +=1;
            $this->_cartRepository->UpdateCart($cartProduct);
        }else{
            $card_product = new CartProduct(
                $cart->Id->Id,
                $productToAdd,
                1
            );
            $this->_cartRepository->AddToCart($card_product);
        }


        Header("Content-type: application/json");
        echo json_encode([true]);
        exit();
    }
    //[POST]
    /**
     * This method update the quantity of a product in the cart
     * @param array $request - The request data (pid, qty)
     * @return void
     */
    public function CartUpdate($request): void
    {
        if (!$this->data['isLogin']) {
            header('Location: /auth/login');
            exit();
        }
        $productId = $request['pid'];
        $quantity = $request['qty'] ?? null;

        if ($quantity == null || $quantity < 1) {
            Header("'Content-Type': 'application/json'");
            echo '{"updated":' . 0 . '}';
            exit();
        }

        $cart = $this->_cartRepository->GetCart($this->data["user_id"]);
        $cart_product = array_find($cart->CartProducts, function ($p) use ($productId) {
            return $p->Product->Id->Id == $productId;
        });
        $cart_product->Quantity = $quantity;
        //Update the cart
        $response = $this->_cartRepository->UpdateCart($cart_product);
        Header("'Content-Type': 'application/json'");
        echo '{"updated":' . $response . '}';
    }
    //[POST]
    /**
     * This method delete a product from the cart
     * @param array $request - The request data (pid)
     * @return void
     */
    public function CartDelete($request): void
    {
        if (!$this->data['isLogin']) {
            header('Location: /auth/login');
            exit();
        }
        $productId = $request['pid'] ?? null;
        if ($productId == null || $productId == '') {
            Header("'Content-Type': 'application/json'");
            echo '{"updated":' . 0 . '}';
        }
        $cart = $this->_cartRepository->GetCart($this->data["user_id"]);
        $cart_product = array_find($cart->CartProducts, function ($p) use ($productId) {
            return $p->Product->Id->Id == $productId;
        });

        $this->_cartRepository->RemoveFromCart($cart_product);
        Header("'Content-Type': 'application/json'");
        echo '{"updated":1}';
    }
    //[GET]
    /**
     * This method process the checkout of the user cart
     * @return void
     */
    public function Checkout(): void
    {
        if (!$this->data['isLogin']) {
            header('Location: /auth/login');
            exit();
        }

        //Simulate that confirm the buy and do the checkout creating the order
        $cart = $this->_cartRepository->GetCart($this->data["user_id"]);
        $total = 0;
        foreach ($cart->CartProducts as $item) {
            $total += $item->Quantity*$item->Product->Price;
            //Substract the discount
            $total -= $item->Product->Quantity*$item->Product->Price*($item->Product->Discount/100);
        }
        $order = new Order(
            id: GUID::Create(),
            userId: $this->data["user_id"],
            orderNumber: 0,
            total: $total,
            status: 'pending',
            products: $cart->CartProducts,
            createdAt: new DateTime('now')
        );
        $result = $this->_orderRepository->CreateOrder($order);

        if($result===true)
        {
            $this->_cartRepository->ClearCart($this->data["user_id"]);
        }
        Header('Location: /user/profile');
        exit();
    }
    //[GET]
    /**
     * This method show the user profile
     * @return void
     */
    public function Profile(): void
    {
        if (!$this->data['isLogin']) {
            header('Location: /');
            exit();
        }

        $orders = $this->_orderRepository->GetAllByUser($this->data['user_id']);
        $user = $this->_userRepository->GetById($this->data["user_id"]);
        $this->data['orders'] = $orders;
        $this->data['user'] = $user;
        $view = '/app/views/user/profile.php';
        include LAYOUT;
    }
    //[POST]
    /**
     * This method change the user password
     * @param array $request - The request data (oldPassword, newPassword)
     * @return void
     */
    public function ChangePassword($request): void
    {
        $oldPassword = $request['oldPassword'] ?? '';
        $newPassword = $request['newPassword'] ?? '';
        

        // Validate the inputs
        if (empty($oldPassword) || empty($newPassword)) {
            echo $this->ResponseJson(false, 'Por favor, complete todos los campos');
        }

        if (strlen($newPassword) < 8) {
            echo $this->ResponseJson(false, 'La nueva contraseña debe tener al menos 8 caracteres');
        }

        $user = $this->_userRepository->GetById($this->data['user_id']);

        if (!Hash::IsEqual($oldPassword, $user->HashedPassword)) {
            echo $this->ResponseJson(false, 'La contraseña actual es incorrecta');

        }

        $hashedNewPassword = Hash::Encode($newPassword);
        $result = $this->_userRepository->UpdatePassword($this->data['user_id'], $hashedNewPassword);
        
        echo $this->ResponseJson($result, 'Error al actualizar la contraseña');
    }
}