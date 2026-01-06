<?php
namespace sportshop\app\services;

use sportshop\app\controllers\AdminController;
use sportshop\app\controllers\AuthController;
use sportshop\app\controllers\HomeController;
use sportshop\app\controllers\ProductsController;
use sportshop\app\controllers\UserController;
use sportshop\app\utils\Router;
use sportshop\app\utils\ServiceCollection;

class RouteMapper
{

    protected ServiceCollection $_services;

    public function __construct(ServiceCollection $services)
    {
        $this->_services = $services;
    }
    public function Start()
    {
        //Include Controllers
        $router = new Router();

        /**
         * Home controller requests
         */
        $router->map("GET", "/", function () {
            $controller = new HomeController(
                $this->_services->GetService('ProductRepository'),
                $this->_services->GetService('CategoryRepository')
            );
            $controller->Index();
        });
        /**
         * Auth controller requests
         */
        $router->map("GET", "/auth/login", function () {
            $controller = new AuthController(
                $this->_services->GetService('UserRepository'),
                $this->_services->GetService('CartRepository'));
            $controller->SignInView();
        });
        $router->map("GET", "/auth/logout", function () {
            $controller = new AuthController(
                $this->_services->GetService('UserRepository'),
                $this->_services->GetService('CartRepository'));
            $controller->SignOut();
        });
        $router->map("GET", "/auth/register", function () {
            $controller = new AuthController(
                $this->_services->GetService('UserRepository'),
                $this->_services->GetService('CartRepository'));
            $controller->SignUpView();
        });
        $router->map("POST", "/auth/register", function ($request) {
            $controller = new AuthController(
                $this->_services->GetService('UserRepository'),
                $this->_services->GetService('CartRepository'));
            $controller->SignUp($request);
        });
        $router->map("POST", "/auth/login", function ($request) {
            $controller = new AuthController(
                $this->_services->GetService('UserRepository'),
                $this->_services->GetService('CartRepository')
            );
            $controller->SignIn($request);
        });
        /**
         * User controller requests
         */
        $router->map("GET", "/user/cart/count", function () {
            $controller = new UserController(
                $this->_services->GetService('UserRepository'),
                $this->_services->GetService('CartRepository'),
                $this->_services->GetService('OrderRepository'),
                $this->_services->GetService('ProductRepository')
            );
            $controller->CartCount();
        });
        $router->map("GET", "/user/profile", function () {
            $controller = new UserController(
                $this->_services->GetService('UserRepository'),
                $this->_services->GetService('CartRepository'),
                $this->_services->GetService('OrderRepository'),
                $this->_services->GetService('ProductRepository')
            );
            $controller->Profile();
        });        
        $router->map("POST", "/user/profile", function ($request) {
            $controller = new UserController(
                $this->_services->GetService('UserRepository'),
                $this->_services->GetService('CartRepository'),
                $this->_services->GetService('OrderRepository'),
                $this->_services->GetService('ProductRepository')
            );
            $controller->ChangePassword($request);
        });
        $router->map("GET", "/user/cart", function () {
            $controller = new UserController(
                $this->_services->GetService('UserRepository'),
                $this->_services->GetService('CartRepository'),
                $this->_services->GetService('OrderRepository'),
                $this->_services->GetService('ProductRepository')
            );
            $controller->Cart();
        });
        $router->map("POST", "/user/cart", function ($request) {
            $controller = new UserController(
                $this->_services->GetService('UserRepository'),
                $this->_services->GetService('CartRepository'),
                $this->_services->GetService('OrderRepository'),
                $this->_services->GetService('ProductRepository')
            );
            $controller->CartUpdate($request);
        });
        $router->map("POST", "/user/cart/delete", function ($request) {
            $controller = new UserController(
                $this->_services->GetService('UserRepository'),
                $this->_services->GetService('CartRepository'),
                $this->_services->GetService('OrderRepository'),
                $this->_services->GetService('ProductRepository')
            );
            $controller->CartDelete($request);
        });
        $router->map("POST", "/user/cart/add", function ($request) {
            $controller = new UserController(
                $this->_services->GetService('UserRepository'),
                $this->_services->GetService('CartRepository'),
                $this->_services->GetService('OrderRepository'),
                $this->_services->GetService('ProductRepository')
            );
            $controller->CartAdd($request);
        });
        $router->map("GET", "/user/cart/buy", function () {
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
        $router->map("GET", "/admin/users", function ($request) {
            $controller = new AdminController(
                $this->_services->GetService('UserRepository'),
                $this->_services->GetService('ProductRepository'),
                $this->_services->GetService('OrderRepository'),
                $this->_services->GetService('CartRepository'),
                $this->_services->GetService('CategoryRepository')
            );
            $controller->UsersDashboard($request);
        });
        $router->map("GET", "/admin/products", function ($request) {
            $controller = new AdminController(
                $this->_services->GetService('UserRepository'),
                $this->_services->GetService('ProductRepository'),
                $this->_services->GetService('OrderRepository'),
                $this->_services->GetService('CartRepository'),
                $this->_services->GetService('CategoryRepository')
            );
            $controller->ProductsDashboard($request);
        });
        $router->map("GET", "/admin/orders", function ($request) {
            $controller = new AdminController(
                $this->_services->GetService('UserRepository'),
                $this->_services->GetService('ProductRepository'),
                $this->_services->GetService('OrderRepository'),
                $this->_services->GetService('CartRepository'),
                $this->_services->GetService('CategoryRepository')
            );
            $controller->OrdersDashboard($request);
        });
        $router->map("POST", "/admin/users/activate", function ($request) {
            $controller = new AdminController(
                $this->_services->GetService('UserRepository'),
                $this->_services->GetService('ProductRepository'),
                $this->_services->GetService('OrderRepository'),
                $this->_services->GetService('CartRepository'),
                $this->_services->GetService('CategoryRepository')
            );
            $controller->ActivateUser($request);
        });
        $router->map("POST", "/admin/users/role", function ($request) {
            $controller = new AdminController(
                $this->_services->GetService('UserRepository'),
                $this->_services->GetService('ProductRepository'),
                $this->_services->GetService('OrderRepository'),
                $this->_services->GetService('CartRepository'),
                $this->_services->GetService('CategoryRepository')
            );
            $controller->ChangeUserRole($request);
        });
        $router->map("POST", "/admin/products/delete", function ($request) {
            $controller = new AdminController(
                $this->_services->GetService('UserRepository'),
                $this->_services->GetService('ProductRepository'),
                $this->_services->GetService('OrderRepository'),
                $this->_services->GetService('CartRepository'),
                $this->_services->GetService('CategoryRepository')
            );
            $controller->DeleteProduct($request);
        });
        $router->map("POST", "/admin/products/update", function ($request) {
            $controller = new AdminController(
                $this->_services->GetService('UserRepository'),
                $this->_services->GetService('ProductRepository'),
                $this->_services->GetService('OrderRepository'),
                $this->_services->GetService('CartRepository'),
                $this->_services->GetService('CategoryRepository')
            );
            $controller->EditProduct($request);
        });
        $router->map("POST", "/admin/products/create", function ($request) {
            $controller = new AdminController(
                $this->_services->GetService('UserRepository'),
                $this->_services->GetService('ProductRepository'),
                $this->_services->GetService('OrderRepository'),
                $this->_services->GetService('CartRepository'),
                $this->_services->GetService('CategoryRepository')
            );
            $controller->AddProduct($request);
        });
        $router->map("POST", "/admin/orders/update", function ($request) {
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
        $router->map("GET", "/products", function ($request) {
            $controller = new ProductsController(
                $this->_services->GetService('ProductRepository'),
                $this->_services->GetService('CategoryRepository')
            );
            $controller->Index($request);
        });
        /**
         * Default route
         */
        $router->map("ALL", "default", function () {
            $controller = new HomeController(
                $this->_services->GetService('ProductRepository'),
                $this->_services->GetService('CategoryRepository')
            );
            $controller->Index();
        });
    }

}