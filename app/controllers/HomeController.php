<?php
namespace sportshop\app\controllers;

use sportshop\app\data\CategoryRepository;
use sportshop\app\data\ProductRepository;
use sportshop\app\data\UserRepository;
use sportshop\app\services\Auth;

class HomeController extends ControllerBase
{
    protected readonly ProductRepository $_productRepository;
    protected readonly CategoryRepository $_categoryRepository;
    public function __construct(ProductRepository $productRepository, CategoryRepository $categoryRepository)
    {
        parent::__construct();
        $this->_productRepository=$productRepository;
        $this->_categoryRepository=$categoryRepository;
    }
    public function Index() : void
    {
        $categories = $this->_categoryRepository->GetAll();
        $products = $this->_productRepository->GetByQueryPaginated(10, 1,["outstanding"=>true])['products'];

        $this->data["categories"] = $categories;
        $this->data["products"] = $products;
        $view = "/app/views/Home/Home.php";
        include LAYOUT;
    }
}

?>