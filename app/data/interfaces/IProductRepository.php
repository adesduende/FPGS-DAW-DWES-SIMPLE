<?php 
    namespace sportshop\app\data\interfaces;

    use sportshop\app\models\Product;

    interface IProductRepository {
        //Retrieve
        /**
         * This method retrieves all products from the repository.
         * @return void
         */
        function GetAll() : ?array;
        /**
         * This method counts all products in the repository.
         * @return void
         */
        function CountAll() : int;
        /**
         * This method counts all products that are currently in stock.
         * @return void
         */
        function CountInStock() : int;
        /**
         * This method counts all deactivated products in the repository.
         * @return void
         */
        function CountDeactivated() : int;
        /**
         * This method retrieves all products with pagination.
         * @param int $unitsPerPage - Number of products per page.
         * @param int $page - Current page number.
         * @return array|null
         */
        function GetAllPaginated(int $unitsPerPage, int $page): ?array;
        /**
         * This method retrieves a product by its unique identifier.
         * @param string $id - The unique identifier of the product.
         * @return void
         */
        function GetById(string $id): ?Product;
        /**
         * This method retrieves products based on a query with pagination.
         * @param int $unitsPerPage - Number of products per page.
         * @param int $page - Current page number.
         * @param array $query - Associative array of query parameters.
         * @return array|null
         */
        function GetByQueryPaginated(int $unitsPerPage, int $page, array $query) : ?array;

        //Create
        /**
         * This method adds a new product to the repository.
         * @param Product $product - The product to be added.
         * @return bool
         */
        function AddNew(Product $product): bool;

        //Update
        /**
         * This method updates an existing product in the repository.
         * @param Product $product - The product with updated information.
         * @return bool
         */
        function Update(Product $product): bool;
        /**
         * This method activates or deactivates a product.
         * @param string $productId - The unique identifier of the product.
         * @param bool $activate - True to activate, false to deactivate.
         * @return bool
         */
        function Activate(string $productId, bool $activate): bool;

        //Delete
        /**
         * This method deletes a product from the repository.
         * @param string $productId - The unique identifier of the product.
         * @return bool
         */
        function Delete(string $productId): bool;
    }
?>