<?php

namespace sportshop\app\data;

use sportshop\app\data\interfaces\ICategoryRepository;
use sportshop\app\models\Category;
use sportshop\app\utils\GUID;

class CategoryRepository implements ICategoryRepository
{
    private readonly DbContext $_context;
    public function __construct(DbContext $context)
    {
        $this->_context = $context;
    }
    public function GetAll(): array
    {
        $conn = $this->_context->getConnection();
        $stmt = $conn->prepare("SELECT * FROM category");
        $stmt->execute();
        $categories = [];
        while ($row = $stmt->fetch(\PDO::FETCH_ASSOC)){
            $categories[] = new Category(
                GUID::Create($row['id']),
                $row['name'],
                $row['description']
            );
        }
        return $categories??[];
    }
    public function GetById(string $id): ?Category
    {
        $conn = $this->_context->getConnection();
        $stmt = $conn->prepare("SELECT * FROM category WHERE id = :id");
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        if ($row = $stmt->fetch(\PDO::FETCH_ASSOC)){
            return new Category(
                GUID::Create($row['id']),
                $row['name'],
                $row['description']
            );
        }
        return null;
    }
    public function GetByName(string $name): ?Category
    {
        return null;
    }
    public function Add(Category $category): bool
    {
        return false;
    }
    public function Update(Category $category): bool
    {
        return false;
    }
    public function Delete(Category $category): bool
    {
        return false;
    }
}