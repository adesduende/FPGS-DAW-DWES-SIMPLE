<?php

namespace sportshop\app\data;

use DateTime;
use PDO;
use sportshop\app\data\interfaces\IOrderRepository;
use sportshop\app\models\Category;
use sportshop\app\models\Order;
use sportshop\app\models\Product;
use sportshop\app\models\User;
use sportshop\app\utils\GUID;

readonly class OrderRepository implements IOrderRepository
{
    private DbContext $_context;

    function __construct(DbContext $context)
    {
        $this->_context = $context;
    }
    public function CountAll(): int
    {
        $stmt = $this->_context->getConnection()->prepare("SELECT COUNT(*) as total FROM `order`");
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        $this->_context->disconnect();

        return (int)$row['total'];
    }
    public function CountByStatus(string $status): int
    {
        $stmt = $this->_context->getConnection()->prepare("SELECT COUNT(*) as total FROM `order` WHERE status = :status");
        $stmt->bindParam(':status', $status);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        $this->_context->disconnect();

        return (int)$row['total'];
    }
    public function GetAllWithDetails(): array
    {
        $conn = $this->_context->getConnection();
        $conn->beginTransaction();

        //Get all orders
        $stmt = $conn->prepare("SELECT order.id as order_id, order.user_id, order.order_number, order.status as order_status, order.created_at as created_at, order.total FROM `order` ORDER BY order.created_at DESC");
        $stmt->execute();
        $orders = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {

            $createdAt = new DateTime($row["created_at"]);
            $orders[$row['order_id']] = new Order(
                GUID::Create($row['order_id']),
                $row['user_id'],
                (int) $row['order_number'],
                (float) $row['total'],
                $row['order_status'],
                [],
                $createdAt
            );
        };
        //Get products per order
        $stmt = $conn->prepare("
            SELECT count(*) as products_count, order_id
            FROM `order_products`
            INNER JOIN `product` ON order_products.product_id = product.id
            GROUP BY order_id
        ");
        $stmt->execute();
        $productsPerOrder = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $productsPerOrder[$row['order_id']] = (int)$row['products_count'];
        };
        //Get user per order
        $stmt = $conn->prepare("SELECT user.id as user_id, user.name, user.surname, `order`.id as order_id FROM `order` INNER JOIN `user` ON `order`.user_id = user.id");
        $stmt->execute();
        $userPerOrder = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $userPerOrder[$row['order_id']] = $row['name'] . ' ' . $row['surname'];
        };
        $conn->commit();
        $this->_context->disconnect();
        
        return [
            "orders" => $orders,
            "productsperorder" => $productsPerOrder,
            "userperorder" => $userPerOrder
        ];
    }
    public function GetAllWithDetailsPaginated(int $page, int $pageSize, string $searchName = '', string $filterStatus = ''): array
    {
        $searchName = trim($searchName)=='' ? '%' : '%'.strtolower($searchName).'%';
        $filterStatus = trim($filterStatus)=='' ? '%' : strtolower($filterStatus);

        $conn = $this->_context->getConnection();
        $conn->beginTransaction();
        //Get all orders filtered
        $stmt = $conn->prepare(
    "SELECT order.id as order_id, order.user_id, order.order_number, order.status as order_status, order.created_at as created_at, order.total  
            FROM `order`
            INNER JOIN `user` ON `order`.user_id = user.id
            WHERE LOWER(CONCAT(user.name, ' ', user.surname)) LIKE :searchName
            AND LOWER(order.status) LIKE :filterStatus
            ORDER BY order.created_at DESC
            LIMIT :offset, :pageSize"
            );
        $stmt->bindValue(':offset', ($page - 1) * $pageSize, PDO::PARAM_INT);
        $stmt->bindValue(':pageSize', $pageSize, PDO::PARAM_INT);
        $stmt->bindValue(':searchName', $searchName, PDO::PARAM_STR);
        $stmt->bindValue(':filterStatus', $filterStatus, PDO::PARAM_STR);
        $stmt->execute();
        $orders = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {

            $createdAt = new DateTime($row["created_at"]);
            $orders[$row['order_id']] = new Order(
                GUID::Create($row['order_id']),
                $row['user_id'],
                (int) $row['order_number'],
                (float) $row['total'],
                $row['order_status'],
                [],
                $createdAt
            );
        };
        //Get products per order
        $stmt = $conn->prepare("
            SELECT count(*) as products_count, order_id
            FROM `order_products`
            INNER JOIN `product` ON order_products.product_id = product.id
            GROUP BY order_id
        ");
        $stmt->execute();
        $productsPerOrder = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $productsPerOrder[$row['order_id']] = (int)$row['products_count'];
        };
        //Get user per order
        $stmt = $conn->prepare("SELECT user.*, `order`.id as order_id FROM `order` INNER JOIN `user` ON `order`.user_id = user.id");
        $stmt->execute();
        $userPerOrder = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $userPerOrder[$row['order_id']] = new User(
                GUID::Create($row['id']),
                $row['name'],
                $row['surname'],
                $row['email'],
                $row['phone_number'],
                $row['hashed_password'],
                $row['role'],
                $row['is_active']
            );
        };
        $conn->commit();
        $this->_context->disconnect();
        
        return [
            "orders" => $orders,
            "productsperorder" => $productsPerOrder,
            "userperorder" => $userPerOrder,
            "currentPage" => $page,
            "pageSize" => $pageSize,
            "totalOrders" => $this->CountAll()
        ];
    }
    public function GetAllByUser(string $user_id): array
    {
        $stmt = $this->_context->getConnection()->prepare("SELECT product.id as product_id, product.name, product.category_id, product.price, product.image_url, product.rating, product.stock, product.badge, product.discount, product.description, product.is_active, `order`.id as 'order_id', `order`.order_number, `order`.status as order_status, `order`.created_at as created_at, `order`.total, category.id as category_id, category.name as category_name, category.description as category_description, order_products.quantity as order_quantity FROM `order` INNER JOIN `order_products` ON `order`.`id` = `order_products`.`order_id` INNER JOIN `product` ON `order_products`.`product_id` = `product`.`id` INNER JOIN `category` ON `product`.`category_id` = `category`.`id` WHERE `user_id` = :user_id");
        $stmt->bindParam(':user_id', $user_id);
        $stmt->execute();
        $orders = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $category = new Category(
                GUID::Create($row["category_id"]),
                $row["category_name"],
                $row["category_description"]
            );

            $product = new Product(
                GUID::Create($row["product_id"]),
                $row["name"],
                $category,
                $row["price"],
                $row["image_url"],
                $row['rating'],
                $row['stock'],
                $row['badge'],
                $row['discount'],
                $row['description'],
                $row['is_active']
            );

            if (isset($orders[$row['order_id']])) {
                $orders[$row['order_id']]->Product[] = $product;
            } else {
                $createdAt = new DateTime($row["created_at"]);
                $orders[$row['order_id']] = new Order(
                    GUID::Create($row['order_id']),
                    $user_id,
                    (int) $row['order_number'],
                    (float) $row['total'],
                    $row['order_status'],
                    [
                        $product
                    ],
                    $createdAt
                );
            }
        }
        $this->_context->disconnect();

        return $orders;
    }
    public function GetById(string $user_id, $id): ?Order
    {
        return null;
    }
    public function CreateOrder(Order $order): bool
    {
        $orderCreatedAt = $order->CreatedAt->format('Y-m-d H:i:s');
        $conn = $this->_context->getConnection();
        $conn->beginTransaction();
        $stmt = $conn->prepare("INSERT INTO `order` (id, user_id, total, status, created_at) VALUES (:order_id, :user_id, :total, :status, :created_at)");
        $stmt->bindParam(':order_id', $order->Id->Id);
        $stmt->bindParam(':user_id', $order->UserId);
        $stmt->bindParam(':total', $order->Total, PDO::PARAM_INT);
        $stmt->bindParam(':status', $order->Status);
        $stmt->bindParam(':created_at', $orderCreatedAt);
        $stmt->execute();

        foreach ($order->Products as $cart_product) {
            $stmt = $conn->prepare("INSERT INTO `order_products`(order_id, product_id, quantity, price_at_purchase) VALUES (:order_id, :product_id, :quantity, :price_at_purchase)");
            $stmt->bindParam(':order_id', $order->Id->Id);
            $stmt->bindParam(':product_id', $cart_product->Product->Id->Id);
            $stmt->bindParam(':quantity', $cart_product->Quantity);
            $stmt->bindParam(':price_at_purchase', $cart_product->Product->Price); //Real this need to apply the discount
            $stmt->execute();
        }
        $conn->commit();
        $this->_context->disconnect();
        return true;
    }
    public function UpdateStatusOrder(string $order_id, string $status): bool
    {
        $stmt = $this->_context->getConnection()->prepare("UPDATE `order` SET status = :status WHERE id = :order_id");
        $stmt->bindParam(':status', $status);
        $stmt->bindParam(':order_id', $order_id);
        $result = $stmt->execute();
        $this->_context->disconnect();

        return $result;
    }
    public function UpdateOrder(Order $order): bool
    {
        return false;
    }
    public function DeleteOrder(string $id): bool
    {
        return false;
    }
}