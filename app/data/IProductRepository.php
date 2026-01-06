<?php 
    namespace sportshop\app\data;

    use sportshop\app\models\Product;

    interface IProductRepository {
        //Retrieve
        function GetAll() : ?array;
        function GetAllPaginated(int $unitsPerPage, int $page): ?array;
        function GetById(string $id): ?Product;
        function GetByQueryPaginated(int $unitsPerPage, int $page, array $query) : ?array;
        
        //Create
        function AddNew(Product $product): bool;

        //Update
        function Update(Product $product): bool;
        
        //Delete
        function Delete(string $productId): bool;
    }
?>