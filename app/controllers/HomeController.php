<?php
namespace sportshop\app\controllers;

use sportshop\app\data\interfaces\IProductRepository;
use sportshop\app\data\interfaces\ICategoryRepository;
/**
 * Home controller - This controller manages the home page
 */
class HomeController extends ControllerBase
{
    protected readonly IProductRepository $_productRepository;
    protected readonly ICategoryRepository $_categoryRepository;
    /**
     * Constructor
     * @param IProductRepository $productRepository - Product repository
     * @param ICategoryRepository $categoryRepository - Category repository
     */
    public function __construct(IProductRepository $productRepository, ICategoryRepository $categoryRepository)
    {
        parent::__construct();
        $this->_productRepository=$productRepository;
        $this->_categoryRepository=$categoryRepository;
    }
    //[GET]
    /**
     * This method handles the index action for the home page
     * @return void
     */
    public function Index() : void
    {
        $categories = $this->_categoryRepository->GetAll();
        $products = $this->_productRepository->GetByQueryPaginated(10, 1,["outstanding"=>true,"onlyActive"=>true])['products'];

        $this->data["categories"] = $categories;
        $this->data["products"] = $products;
        $view = "/app/views/Home/Home.php";
        include LAYOUT;
    }
}

?>