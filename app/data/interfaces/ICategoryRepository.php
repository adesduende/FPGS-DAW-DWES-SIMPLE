<?php

namespace sportshop\app\data\interfaces;

use sportshop\app\models\Category;


interface ICategoryRepository
{
    //Retrieve
    /**
     * Retrieve all categories
     * @return void
     */
    public function GetAll(): array;
    /**
     * Retrieve category by id
     * @param string $id - category id
     * @return void
     */
    public function GetById(string $id): ?Category;
    /**
     * Retrieve category by name
     * @param string $name - category name
     * @return void
     */
    public function GetByName(string $name): ?Category;
    //Create
    /**
     * Add new category
     * @param Category $category - category to add
     * @return bool
     */
    public function Add(Category $category): bool;
    //Update
    /**
     * Update existing category
     * @param Category $category - category to update
     * @return bool
     */
    public function Update(Category $category): bool;
    //Delete
    /**
     * Delete existing category
     * @param Category $category - category to delete
     * @return bool
     */
    public function Delete(Category $category): bool;
}
