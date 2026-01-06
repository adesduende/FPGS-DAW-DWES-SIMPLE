<?php
    namespace sportshop\app\data;

    use sportshop\app\models\Category;
    use sportshop\app\models\Product;
    use sportshop\app\utils\GUID;

    readonly class ProductRepository implements IProductRepository {

        protected DbContext $_context;
        public function __construct(DbContext $context)
        {
            $this->_context = $context;
        }

        function GetAll(): ?array
        {
            $stmt = $this->_context->getConnection()->prepare("
                SELECT product.* , category.name as category_name, category.description as category_description 
                FROM product 
                INNER JOIN category ON product.category_id = category.id
            ");
            $stmt->execute();
            $products = [];
            while ($row = $stmt->fetch(\PDO::FETCH_ASSOC)) {
                $products[] = new Product(
                    GUID::Create($row['id']),
                    $row['name'],
                    new Category(GUID::Create($row['category_id']),$row['category_name'], $row['category_description']),
                    $row['price'],
                    $row['image_url'],
                    $row['rating'],
                    $row['stock'],
                    $row['badge'],
                    $row['discount'],
                    $row['description'],
                    $row['is_active'],
                );
            }
            $this->_context->disconnect();

            return $products;
        }
        function CountAll(): int
        {
            $stmt = $this->_context->getConnection()->prepare("SELECT COUNT(*) FROM product");
            $stmt->execute();
            $total = $stmt->fetchColumn();

            $this->_context->disconnect();

            return (int)$total;
        }
        function CountInStock(): int
        {
            $stmt = $this->_context->getConnection()->prepare("SELECT COUNT(*) FROM product WHERE stock > 0");
            $stmt->execute();
            $total = $stmt->fetchColumn();

            $this->_context->disconnect();

            return (int)$total;
        }
        function CountDeactivated(): int
        {
            $stmt = $this->_context->getConnection()->prepare("SELECT COUNT(*) FROM product WHERE is_active = 0");
            $stmt->execute();
            $total = $stmt->fetchColumn();

            $this->_context->disconnect();

            return (int)$total;
        }
        function GetAllPaginated(int $unitsPerPage=20, int $page=1): ?array
        {
            $stmt = $this->_context->getConnection()->prepare("
                SELECT product.* , category.name as category_name, category.description as category_description 
                FROM product 
                INNER JOIN category ON product.category_id = category.id 
                LIMIT :offset, :unitsPerPage
            ");

            $offset = ($page - 1) * $unitsPerPage;
            $stmt->bindParam('offset', $offset, \PDO::PARAM_INT);
            $stmt->bindParam('unitsPerPage', $unitsPerPage, \PDO::PARAM_INT);
            $stmt->execute();
            $products = [];
            while ($row = $stmt->fetch(\PDO::FETCH_ASSOC)) {
                $products[] = new Product(
                    GUID::Create($row['id']),
                    $row['name'],
                    new Category(GUID::Create($row['category_id']),$row['category_name'], $row['category_description']),
                    $row['price'],
                    $row['image_url'],
                    $row['rating'],
                    $row['stock'],
                    $row['badge'],
                    $row['discount'],
                    $row['description'],
                    $row['is_active'],
                );
            }
            $stmt = $this->_context->getConnection()->prepare("SELECT COUNT(*) FROM product");
            $stmt->execute();
            $total = $stmt->fetchColumn();

            $this->_context->disconnect();

            return [
                "unitsPerPage"=>$unitsPerPage,
                "currentPage"=>$page,
                "totalPages"=>ceil($total/$unitsPerPage),
                "total"=>$total,
                "products"=>$products,
            ];
        }
        function GetById(string $id): ?Product
        {
            $stmt = $this->_context->getConnection()->prepare("
                SELECT product.*, category.id as category_id, category.name as category_name, category.description as category_description 
                FROM product 
                INNER JOIN category ON category.id = product.category_id WHERE product.id = :id
            ");
            $stmt->bindParam(':id', $id, \PDO::PARAM_INT);
            $stmt->execute();
            $row = $stmt->fetch(\PDO::FETCH_ASSOC);
            $product = new Product(
                GUID::Create($row['id']),
                $row['name'],
                new Category(GUID::Create($row['category_id']),$row['category_name'], $row['category_description']),
                $row['price'],
                $row['image_url'],
                $row['rating'],
                $row['stock'],
                $row['badge'],
                $row['discount'],
                $row['description'],
                $row['is_active'],
            );
            return $product;
        }
        function GetByQueryPaginated(int $unitsPerPage = 20, int $page=1, array $query): ?array
        {
            //Filter
            $outstanding = ($query['outstanding']===true)?'AND product.badge IS NOT NULL AND product.badge <> "" OR product.discount >= 1 ':'';
            $category_name=($query['category']==='Todos')?'%':$query['category']??'%';
            $product_name=($query['search']==='')?'%':'%'.$query['search'].'%'??'%';
            //Sort
            $sortBy=($query['sort']==='')?'default':$query['sort']??'default';
            $sortQuery='';
            if($sortBy === 'price_asc') $sortQuery = 'ORDER BY product.price ASC ';
            elseif($sortBy === 'price_desc') $sortQuery = 'ORDER BY product.price DESC ';
            elseif($sortBy === 'name') $sortQuery = 'ORDER BY product.name ASC ';
            elseif($sortBy === 'rating') $sortQuery = 'ORDER BY product.rating ASC ';

            $stmt = $this->_context->getConnection()->prepare(
                " SELECT product.* , category.name as category_name, category.description as category_description 
                        FROM product 
                        INNER JOIN category ON product.category_id = category.id                        
                        WHERE category.name LIKE :category AND product.name LIKE :name
                        ".
                        $outstanding.
                        $sortQuery
                        ."
                        LIMIT :offset, :unitsPerPage");

            $offset = ($page - 1) * $unitsPerPage;
            $stmt->bindParam('offset', $offset, \PDO::PARAM_INT);
            $stmt->bindParam('unitsPerPage', $unitsPerPage, \PDO::PARAM_INT);
            $stmt->bindParam('category',$category_name , \PDO::PARAM_STR);
            $stmt->bindParam('name',$product_name , \PDO::PARAM_STR);
            $stmt->execute();
            $products = [];
            while ($row = $stmt->fetch(\PDO::FETCH_ASSOC)) {
                $products[] = new Product(
                    GUID::Create($row['id']),
                    $row['name'],
                    new Category(GUID::Create($row['category_id']),$row['category_name'], $row['category_description']),
                    $row['price'],
                    $row['image_url'],
                    $row['rating'],
                    $row['stock'],
                    $row['badge'],
                    $row['discount'],
                    $row['description'],
                    $row['is_active'],
                );
            }
            $stmt = $this->_context->getConnection()->prepare(
                "SELECT COUNT(product.id)
                FROM product 
                INNER JOIN category ON product.category_id = category.id 
                WHERE category.name LIKE :category AND product.name LIKE :name"
            );
            $offset = ($page - 1) * $unitsPerPage;
            $stmt->bindParam('category',$category_name , \PDO::PARAM_STR);
            $stmt->bindParam('name',$product_name , \PDO::PARAM_STR);
            $stmt->execute();
            $total = $stmt->fetchColumn();

            $this->_context->disconnect();

            return [
                "unitsPerPage"=>$unitsPerPage,
                "currentPage"=>$page,
                "totalPages"=>ceil($total/$unitsPerPage),
                "total"=>$total,
                "products"=>$products,
            ];
        }
        function AddNew(Product $product): bool
        {
            $stmt = $this->_context->getConnection()->prepare("
                INSERT INTO product (id, name, category_id, price, image_url, rating, stock, badge, discount, description,is_Active) 
                VALUES (:id, :name, :category_id, :price, :image_url, :rating, :stock, :badge, :discount, :description, :is_active)
            ");
            $stmt->bindParam(':id', $product->Id->Id, \PDO::PARAM_STR);
            $stmt->bindParam(':name', $product->Name, \PDO::PARAM_STR);
            $stmt->bindParam(':category_id', $product->Category->Id->Id,\PDO::PARAM_STR);
            $stmt->bindParam(':price', $product->Price, \PDO::PARAM_STR);
            $stmt->bindParam(':image_url', $product->ImageUrl, \PDO::PARAM_STR);
            $stmt->bindParam(':rating', $product->Rating, \PDO::PARAM_INT);
            $stmt->bindParam(':stock', $product->Stock, \PDO::PARAM_INT);
            $stmt->bindParam(':badge', $product->Badge, \PDO::PARAM_STR);
            $stmt->bindParam(':discount', $product->Discount, \PDO::PARAM_INT);
            $stmt->bindParam(':description', $product->Description, \PDO::PARAM_STR);
            $stmt->bindParam(':is_active', $product->IsActive, \PDO::PARAM_BOOL);
            $result = $stmt->execute();
            $this->_context->disconnect();
            if ($result) {
                return true;
            }
            return false;
        }
        function Update(Product $product): bool
        {
            $stmt = $this->_context->getConnection()->prepare("
                UPDATE product 
                SET name = :name, category_id = :category_id, price = :price, image_url = :image_url, rating = :rating, stock = :stock, badge = :badge, discount = :discount, description = :description, is_active = :is_active
                WHERE id = :id LIMIT 1
            ");
            $stmt->bindParam(':name', $product->Name, \PDO::PARAM_STR);
            $stmt->bindParam(':category_id', $product->Category->Id->Id, \PDO::PARAM_STR);
            $stmt->bindParam(':price', $product->Price, \PDO::PARAM_STR);
            $stmt->bindParam(':image_url', $product->ImageUrl, \PDO::PARAM_STR);
            $stmt->bindParam(':rating', $product->Rating, \PDO::PARAM_INT);
            $stmt->bindParam(':stock', $product->Stock, \PDO::PARAM_INT);
            $stmt->bindParam(':badge', $product->Badge, \PDO::PARAM_STR);
            $stmt->bindParam(':discount', $product->Discount, \PDO::PARAM_INT);
            $stmt->bindParam(':description', $product->Description, \PDO::PARAM_STR);
            $stmt->bindParam(':is_active', $product->IsActive, \PDO::PARAM_BOOL);
            $stmt->bindParam(':id', $product->Id->Id, \PDO::PARAM_STR);
            $result = $stmt->execute(); 
            $this->_context->disconnect();
            return $stmt->rowCount() === 1;
        }
        function Activate(string $productId, bool $isActive): bool
        {
            $stmt = $this->_context->getConnection()->prepare("UPDATE product SET is_active = :is_active WHERE id = :id LIMIT 1");
            $stmt->bindParam(':is_active', $isActive, \PDO::PARAM_BOOL);
            $stmt->bindParam(':id', $productId, \PDO::PARAM_STR);
            $stmt->execute();
            $this->_context->disconnect();
            return $stmt->rowCount() === 1;
        }
        function Delete(string $productId): bool
        {
            $stmt = $this->_context->getConnection()->prepare("DELETE FROM product WHERE id = :id LIMIT 1");
            $stmt->bindParam(':id', $productId, \PDO::PARAM_STR);
            $stmt->execute();
            $this->_context->disconnect();
            return $stmt->rowCount() === 1;
        }
    }