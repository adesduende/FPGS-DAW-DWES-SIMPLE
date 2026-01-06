<?php

namespace sportshop\app\data;

use sportshop\app\models\Category;


interface ICategoryRepository
{
    //Retrieve
    public function GetAll(): array;
    public function GetById(string $id): ?Category;
    public function GetByName(string $name): ?Category;
    //Create
    public function Add(Category $category): bool;
    //Update
    public function Update(Category $category): bool;
    //Delete
    public function Delete(Category $category): bool;
}
