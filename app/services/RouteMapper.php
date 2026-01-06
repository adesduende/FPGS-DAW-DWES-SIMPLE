<?php
namespace sportshop\app\services;

use sportshop\app\controllers\AdminController;
use sportshop\app\controllers\AuthController;
use sportshop\app\controllers\HomeController;
use sportshop\app\controllers\ProductsController;
use sportshop\app\controllers\UserController;
use sportshop\app\utils\Router;
use sportshop\app\utils\ServiceCollection;

/**
 * This service is responsible for mapping routes to their corresponding controllers and actions.
 */
class RouteMapper
{

    protected ServiceCollection $_services;

    /**
     * Constructor for RouteMapper.
     * @param ServiceCollection $services The service collection to be used for dependency injection.
     */
    public function __construct(ServiceCollection $services)
    {
        $this->_services = $services;
    }
    /**
     * Starts the route mapping process by defining routes and their corresponding controller actions.
     */
    public function Start()
    {
        /**
         * Home controller requests
         */
        Router::map("GET", "/", function () {
            $controller = new HomeController(
                $this->_services->GetService('ProductRepository'),
                $this->_services->GetService('CategoryRepository')
            );
            $controller->Index();
        });
        /**
         * Auth controller requests
         */
        Router::map("GET", "/auth/login", function () {
            $controller = new AuthController(
                $this->_services->GetService('UserRepository'),
                $this->_services->GetService('CartRepository'));
            $controller->SignInView();
        });
        Router::map("GET", "/auth/logout", function () {
            $controller = new AuthController(
                $this->_services->GetService('UserRepository'),
                $this->_services->GetService('CartRepository'));
            $controller->SignOut();
        });
        Router::map("GET", "/auth/register", function () {
            $controller = new AuthController(
                $this->_services->GetService('UserRepository'),
                $this->_services->GetService('CartRepository'));
            $controller->SignUpView();
        });
        Router::map("POST", "/auth/register", function ($request) {
            $controller = new AuthController(
                $this->_services->GetService('UserRepository'),
                $this->_services->GetService('CartRepository'));
            $controller->SignUp($request);
        });
        Router::map("POST", "/auth/login", function ($request) {
            $controller = new AuthController(
                $this->_services->GetService('UserRepository'),
                $this->_services->GetService('CartRepository')
            );
            $controller->SignIn($request);
        });
        /**
         * User controller requests
         */
        Router::map("GET", "/user/cart/count", function () {
            $controller = new UserController(
                $this->_services->GetService('UserRepository'),
                $this->_services->GetService('CartRepository'),
                $this->_services->GetService('OrderRepository'),
                $this->_services->GetService('ProductRepository')
            );
            $controller->CartCount();
        });
        Router::map("GET", "/user/profile", function () {
            $controller = new UserController(
                $this->_services->GetService('UserRepository'),
                $this->_services->GetService('CartRepository'),
                $this->_services->GetService('OrderRepository'),
                $this->_services->GetService('ProductRepository')
            );
            $controller->Profile();
        });        
        Router::map("POST", "/user/profile", function ($request) {
            $controller = new UserController(
                $this->_services->GetService('UserRepository'),
                $this->_services->GetService('CartRepository'),
                $this->_services->GetService('OrderRepository'),
                $this->_services->GetService('ProductRepository')
            );
            $controller->ChangePassword($request);
        });
        Router::map("GET", "/user/cart", function () {
            $controller = new UserController(
                $this->_services->GetService('UserRepository'),
                $this->_services->GetService('CartRepository'),
                $this->_services->GetService('OrderRepository'),
                $this->_services->GetService('ProductRepository')
            );
            $controller->Cart();
        });
        Router::map("POST", "/user/cart", function ($request) {
            $controller = new UserController(
                $this->_services->GetService('UserRepository'),
                $this->_services->GetService('CartRepository'),
                $this->_services->GetService('OrderRepository'),
                $this->_services->GetService('ProductRepository')
            );
            $controller->CartUpdate($request);
        });
        Router::map("POST", "/user/cart/delete", function ($request) {
            $controller = new UserController(
                $this->_services->GetService('UserRepository'),
                $this->_services->GetService('CartRepository'),
                $this->_services->GetService('OrderRepository'),
                $this->_services->GetService('ProductRepository')
            );
            $controller->CartDelete($request);
        });
        Router::map("POST", "/user/cart/add", function ($request) {
            $controller = new UserController(
                $this->_services->GetService('UserRepository'),
                $this->_services->GetService('CartRepository'),
                $this->_services->GetService('OrderRepository'),
                $this->_services->GetService('ProductRepository')
            );
            $controller->CartAdd($request);
        });
        Router::map("GET", "/user/cart/buy", function () {
            $controller = new UserController(
                $this->_services->GetService('UserRepository'),
                $this->_services->GetService('CartRepository'),
                $this->_services->GetService('OrderRepository'),
                $this->_services->GetService('ProductRepository')
            );
            $controller->Checkout();
        });
        /**
         * Admin controller requests
         */
        Router::map("GET", "/admin/users", function ($request) {
            $controller = new AdminController(
                $this->_services->GetService('UserRepository'),
                $this->_services->GetService('ProductRepository'),
                $this->_services->GetService('OrderRepository'),
                $this->_services->GetService('CartRepository'),
                $this->_services->GetService('CategoryRepository')
            );
            $controller->UsersDashboard($request);
        });
        Router::map("GET", "/admin/products", function ($request) {
            $controller = new AdminController(
                $this->_services->GetService('UserRepository'),
                $this->_services->GetService('ProductRepository'),
                $this->_services->GetService('OrderRepository'),
                $this->_services->GetService('CartRepository'),
                $this->_services->GetService('CategoryRepository')
            );
            $controller->ProductsDashboard($request);
        });
        Router::map("GET", "/admin/orders", function ($request) {
            $controller = new AdminController(
                $this->_services->GetService('UserRepository'),
                $this->_services->GetService('ProductRepository'),
                $this->_services->GetService('OrderRepository'),
                $this->_services->GetService('CartRepository'),
                $this->_services->GetService('CategoryRepository')
            );
            $controller->OrdersDashboard($request);
        });
        Router::map("POST", "/admin/users/activate", function ($request) {
            $controller = new AdminController(
                $this->_services->GetService('UserRepository'),
                $this->_services->GetService('ProductRepository'),
                $this->_services->GetService('OrderRepository'),
                $this->_services->GetService('CartRepository'),
                $this->_services->GetService('CategoryRepository')
            );
            $controller->ActivateUser($request);
        });
        Router::map("POST", "/admin/users/role", function ($request) {
            $controller = new AdminController(
                $this->_services->GetService('UserRepository'),
                $this->_services->GetService('ProductRepository'),
                $this->_services->GetService('OrderRepository'),
                $this->_services->GetService('CartRepository'),
                $this->_services->GetService('CategoryRepository')
            );
            $controller->ChangeUserRole($request);
        });
        Router::map("POST", "/admin/products/delete", function ($request) {
            $controller = new AdminController(
                $this->_services->GetService('UserRepository'),
                $this->_services->GetService('ProductRepository'),
                $this->_services->GetService('OrderRepository'),
                $this->_services->GetService('CartRepository'),
                $this->_services->GetService('CategoryRepository')
            );
            $controller->DeleteProduct($request);
        });
        Router::map("POST", "/admin/products/update", function ($request) {
            $controller = new AdminController(
                $this->_services->GetService('UserRepository'),
                $this->_services->GetService('ProductRepository'),
                $this->_services->GetService('OrderRepository'),
                $this->_services->GetService('CartRepository'),
                $this->_services->GetService('CategoryRepository')
            );
            $controller->EditProduct($request);
        });
        Router::map("POST", "/admin/products/create", function ($request) {
            $controller = new AdminController(
                $this->_services->GetService('UserRepository'),
                $this->_services->GetService('ProductRepository'),
                $this->_services->GetService('OrderRepository'),
                $this->_services->GetService('CartRepository'),
                $this->_services->GetService('CategoryRepository')
            );
            $controller->AddProduct($request);
        });
        Router::map("POST", "/admin/orders/update", function ($request) {
            $controller = new AdminController(
                $this->_services->GetService('UserRepository'),
                $this->_services->GetService('ProductRepository'),
                $this->_services->GetService('OrderRepository'),
                $this->_services->GetService('CartRepository'),
                $this->_services->GetService('CategoryRepository')
            );
            $controller->UpdateOrderStatus($request);
        });
        /**
         * Products controller requests
         */
        Router::map("GET", "/products", function ($request) {
            $controller = new ProductsController(
                $this->_services->GetService('ProductRepository'),
                $this->_services->GetService('CategoryRepository')
            );
            $controller->Index($request);
        });
        /**
         * Default route
         */
        Router::map("ALL", "default", function () {
            $controller = new HomeController(
                $this->_services->GetService('ProductRepository'),
                $this->_services->GetService('CategoryRepository')
            );
            $controller->Index();
        });
    }

}