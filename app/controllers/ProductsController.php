<?php
namespace sportshop\app\controllers;

use sportshop\app\data\interfaces\IProductRepository;
use sportshop\app\data\interfaces\ICategoryRepository;

/**
 * Product Controller - This controller manage al about the products
 */
class ProductsController extends ControllerBase
{
    private readonly IProductRepository $_productRepository;
    private readonly ICategoryRepository $_categoryRepository;
    /**
     * ProductsController constructor.
     * @param IProductRepository $productRepository - The product repository
     * @param ICategoryRepository $categoryRepository - The category repository
     */
    public function __construct(IProductRepository $productRepository, ICategoryRepository $categoryRepository)
    {
        parent::__construct();
        $this->_productRepository=$productRepository;
        $this->_categoryRepository=$categoryRepository;
    }
    //[GET]
    /**
     * This methos show the products paginated
     * @param array $request - The request data (category, sort, search, page, items)
     * @return void
     */
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

        $view = '/app/views/Products/Products.php';
        include LAYOUT;
    }
}

?>