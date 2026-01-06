<?php
namespace sportshop\app\controllers;

use sportshop\app\data\UserRepository;
use sportshop\app\data\ProductRepository;
use sportshop\app\data\OrderRepository;
use sportshop\app\data\CartRepository;
use sportshop\app\data\CategoryRepository;
use sportshop\app\models\Product;
use sportshop\app\utils\GUID;

class AdminController extends ControllerBase
{
    private readonly UserRepository $_userRepository;
    private readonly ProductRepository $_productRepository;
    private readonly OrderRepository $_orderRepository;
    private readonly CartRepository $_cartRepository;
    private readonly CategoryRepository $_categoryRepository;
    public function __construct(
        UserRepository $userRepository,
        ProductRepository $productRepository,
        OrderRepository $orderRepository,
        CartRepository $cartRepository,
        CategoryRepository $categoryRepository)
    {
        parent::__construct();
        $this->_userRepository = $userRepository;
        $this->_productRepository = $productRepository;
        $this->_orderRepository = $orderRepository;
        $this->_cartRepository = $cartRepository;
        $this->_categoryRepository = $categoryRepository;
    }
    //[GET]
    public function UsersDashboard(array $request): void
    {
        $userPerPage = 10;
        $currentPage = isset($request['page']) ? (int)$request['page'] : 1;
        if (!$this->data['isLogin'] || !$this->data['isAdmin']) {
            header('Location: /');
            exit();            
        }

        $usersData = $this->_userRepository->GetAllPaginated($currentPage, $userPerPage);

        $this->data['totalUsers'] = $usersData["users"];
        $this->data['totalUsersCount'] = $usersData["total"];
        $this->data['activeUsersCount'] = $usersData["inactiveCount"];
        $this->data['adminUsersCount'] = $usersData["adminCount"];
        $this->data['currentPage'] = $currentPage;
        $this->data['totalPages'] = ceil($usersData["total"] / $userPerPage);

        $view = '/app/views/admin/users.php';
        include LAYOUT;
    }
    //[POST]
    public function ActivateUser(array $request): void
    {
        if (!$this->data['isLogin'] || !$this->data['isAdmin']) {
            header('Location: /');
            exit();            
        }

        $isActive = isset($request['isActive']) ? ($request['isActive']=='1'?true:false) : null;
        $userId = isset($request['userId']) ? ($request['userId']==''?null:$request['userId']) : null;

        if($isActive === null || $userId === null) {
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'message' => 'Invalid parameters']);
            exit();
        }
        $result = $this->_userRepository->ActivateUser($userId, $isActive);

        $this->ResponseJson($result, 'Error al actualizar el estado del usuario');
        exit();
    }
    //[POST]
    public function ChangeUserRole(array $request): void
    {
        if (!$this->data['isLogin'] || !$this->data['isAdmin']) {
            header('Location: /');
            exit();            
        }

        $userRole = isset($request['role']) ? ($request['role']==''?null:$request['role']) : null;
        $userId = isset($request['userId']) ? ($request['userId']==''?null:$request['userId']) : null;

        if($userRole === null || $userId === null) {
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'message' => 'Invalid parameters']);
            exit();
        }
        $result = $this->_userRepository->ChangeUserRole($userId, $userRole);

        $this->ResponseJson($result, 'Error al actualizar el rol del usuario');
        exit();
    }
    //[GET]
    public function ProductsDashboard(array $request): void
    {
        if (!$this->data['isLogin'] || !$this->data['isAdmin']) {
            header('Location: /auth/login');
            exit();
        }
        $unitPerPage= 10;
        $currentPage = isset($request['page']) ? (int)$request['page'] : 1;
        $retrieveData = $this->_productRepository->GetAllPaginated($unitPerPage, $currentPage);
        $categories = $this->_categoryRepository->GetAll();
        $inStock = $this->_productRepository->CountInStock();
        $totalProducts = $this->_productRepository->CountAll();
        $deactivated = $this->_productRepository->CountDeactivated();

        $this->data['deactivated'] = $deactivated;
        $this->data['products'] = $retrieveData['products'];
        $this->data['currentPage'] = $currentPage;
        $this->data['totalPages'] = $retrieveData['totalPages'];
        $this->data['totalProducts'] = $totalProducts;
        $this->data['unitsPerPage'] = $retrieveData['unitsPerPage'];        
        $this->data['inStock'] = $inStock;
        $this->data['outOfStock'] = $totalProducts - $inStock;
        $this->data['categoriesCount'] = count($categories);
        $this->data['categories'] = $categories;
        
        $view = '/app/views/admin/products.php';
        include LAYOUT;
    }
    //[POST]
    public function AddProduct(array $request): void
    {
        if (!$this->data['isLogin'] || !$this->data['isAdmin']) {
            header('Location: /auth/login');
            exit();
        }
        $name = isset($request['name']) ? $request['name'] : null;
        $description = isset($request['description']) ? $request['description'] : '';
        $categoryId = isset($request['categoryId']) ? $request['categoryId'] : null;
        $price = isset($request['price']) ? (float)$request['price'] : null;
        $stock = isset($request['stock']) ? (int)$request['stock'] : null;
        $discount = isset($request['discount']) ? (float)$request['discount'] : 0.0;
        $imageUrl = isset($request['image']) ? $request['image'] : '';

        if($name === null || $categoryId === null || $price === null || $stock === null) {
            $this->ResponseJson(false, 'Faltan campos obligatorios');
        }

        $category = $this->_categoryRepository->GetById($categoryId);
        if ($category === null) {
            $this->ResponseJson(false, 'Categoría no encontrada');
        }

        $product = new Product(
            GUID::Create(),
            $name,
            $category,
            $price,
            $imageUrl,
            0.0,
            $stock,
            'new',
            $discount,
            $description,
            true
        );
        $response = $this->_productRepository->AddNew($product);
        $this->ResponseJson($response, 'Error al agregar el producto');
    }
    //[POST]
    public function EditProduct(array $request): void
    {
        if (!$this->data['isLogin'] || !$this->data['isAdmin']) {
            header('Location: /auth/login');
            exit();
        }

        $productId = isset($request['id']) ? $request['id'] : null;
        $categoryId = isset($request['categoryId']) ? $request['categoryId'] : null;
        if ($productId === null) {
            header('Location: /admin/products');
            exit();
        }

        $product = $this->_productRepository->GetById($productId);
        $category = $this->_categoryRepository->GetById($categoryId);
        if ($product === null) {
            header('Location: /admin/products');
            exit();
        }

        $product->Name = isset($request['name']) ? $request['name'] : $product->Name;
        $product->Description = isset($request['description']) ? $request['description'] : $product->Description;
        $product->Category = isset($request['categoryId']) ? $category : $product->Category;
        $product->Price = isset($request['price']) ? (float)$request['price'] : $product->Price;
        $product->Stock = isset($request['stock']) ? (int)$request['stock'] : $product->Stock;
        $product->Discount = isset($request['discount']) ? (float)$request['discount'] : $product->Discount;
        $product->ImageUrl = isset($request['image']) ? $request['image'] : $product->ImageUrl;

        $response = $this->_productRepository->Update($product);

        $this->ResponseJson($response, 'Error al actualizar el producto');
        exit();
    }
    //[POST]
    public function DeleteProduct(array $request): void
    {
        if (!$this->data['isLogin'] || !$this->data['isAdmin']) {
            header('Location: /auth/login');
            exit();
        }

        $productId = isset($request['id']) ? $request['id'] : null;
        $activate = isset($request['activate']) ? ($request['activate']=='1'?true:false) : null;
        if ($productId === null) {
            $this->ResponseJson(false, 'ID de producto no proporcionado');
            
        }
        $response = $this->_productRepository->Activate($productId, $activate);

        $this->ResponseJson($response, 'Error al eliminar el producto');
    }
    //[GET]
    public function OrdersDashboard(array $request): void
    {
        if (!$this->data['isLogin'] || !$this->data['isAdmin']) {
            header('Location: /auth/login');
            exit();
        }
        $unitsPerPage= 10;

        $searchName = isset($request['search']) ? $request['search'] : null;
        $filterStatus = isset($request['status']) ? $request['status']=='all' ? '' : $request['status'] : null;
        
        $ordersData = $this->_orderRepository->GetAllWithDetailsPaginated(1, $unitsPerPage  , $searchName ?? '', $filterStatus ?? '');

        $ordersPendant = $this->_orderRepository->CountByStatus('pending');
        $ordersProcessing = $this->_orderRepository->CountByStatus('processing');
        $ordersShipped = $this->_orderRepository->CountByStatus('shipped');
        $ordersDelivered = $this->_orderRepository->CountByStatus('delivered');
        $ordersCancelled = $this->_orderRepository->CountByStatus('cancelled');

        $this->data['ordersCount'] = $ordersData['totalOrders'];
        $this->data['ordersPendant'] = $ordersPendant;
        $this->data['ordersProcessing'] = $ordersProcessing;
        $this->data['ordersShipped'] = $ordersShipped;
        $this->data['ordersDelivered'] = $ordersDelivered;
        $this->data['ordersCancelled'] = $ordersCancelled;
        $this->data['orders'] = $ordersData['orders'];
        $this->data['productsperorder'] = $ordersData['productsperorder'];
        $this->data['userperorder'] = $ordersData['userperorder'];

        //Pagination
        $this->data['currentPage'] = $ordersData['currentPage'];
        $this->data['totalPages'] = ceil($ordersData['totalOrders']/ $ordersData['pageSize']);
        
        $view = '/app/views/admin/orders.php';
        include LAYOUT;
    }
    //[POST]
    public function UpdateOrderStatus(array $request): void
    {
        if (!$this->data['isLogin'] || !$this->data['isAdmin']) {
            header('Location: /auth/login');
            exit();
        }

        $orderId = isset($request['orderId']) ? $request['orderId'] : null;
        $status = isset($request['status']) ? $request['status'] : null;

        if($orderId === null || $status === null) {
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'message' => 'Invalid parameters']);
            exit();
        }
        $result = $this->_orderRepository->UpdateStatusOrder($orderId, $status);

        $this->ResponseJson($result, 'Error al actualizar el estado del pedido');
    }

}