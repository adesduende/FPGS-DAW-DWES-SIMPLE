<?php
namespace sportshop\app\models;

use \sportshop\app\utils\GUID;
class Product extends Identity
{
    public string $Name;
    public Category $Category;
    public string $Price;
    public string $ImageUrl;
    public float $Rating;
    public int $Stock;
    public string $Badge;
    public float $Discount;
    public string $Description;
    public bool $IsActive;

    public function __construct(
        ?GUID $id,
        string $name,
        Category $category,
        string $price,
        string $imageUrl,
        float $rating,
        int $stock,
        string $badge,
        float $discount,
        string $description,
        bool $isActive = true
    ) {
        parent::__construct($id);

        $this->Name = $name;
        $this->Category = $category;
        $this->Price = $price;
        $this->ImageUrl = $imageUrl;
        $this->Rating = $rating;
        $this->Stock = $stock;
        $this->Badge = $badge;
        $this->Discount = $discount;
        $this->Description = $description;
        $this->IsActive = $isActive;
    }
}