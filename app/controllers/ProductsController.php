<?php
namespace sportshop\app\controllers;

use sportshop\app\data\CategoryRepository;
use sportshop\app\data\ProductRepository;

class ProductsController extends ControllerBase
{
    private readonly ProductRepository $_productRepository;
    private readonly CategoryRepository $_categoryRepository;
    public function __construct(ProductRepository $productRepository, CategoryRepository $categoryRepository)
    {
        parent::__construct();
        $this->_productRepository=$productRepository;
        $this->_categoryRepository=$categoryRepository;
    }
    //[GET]
    public function Index(array $request) : void {

        $request['onlyActive'] = true;
        $retrieveData = $this->_productRepository->GetByQueryPaginated(
            $request['items']??20,
            $request['page']??1, 
            $request
        );

        $products = $retrieveData['products'];
        $categories = array_map(function ($cat){ return $cat->Name;},$this->_categoryRepository->GetAll());
        $categories[] = 'Todos';
        $selectedCategory = $request['category'] ?? 'Todos';
        $sortBy = $request['sort'] ?? 'default';
        $searchQuery = $request['search'] ?? '';

        $this->data['products'] = $products;
        $this->data['categories'] = $categories;
        $this->data['sortBy'] = $sortBy;
        $this->data['searchQuery'] = $searchQuery;
        $this->data['selectedCategory'] = $selectedCategory;
        $this->data['totalPages'] = $retrieveData['totalPages'];
        $this->data['currentPage'] = $retrieveData['currentPage'];
        $this->data['unitsPerPage'] = $retrieveData['unitsPerPage'];
        $this->data['total'] = $retrieveData['total'];

        $view = '\app\views\Products\products.php';
        include LAYOUT;
    }
}

?>