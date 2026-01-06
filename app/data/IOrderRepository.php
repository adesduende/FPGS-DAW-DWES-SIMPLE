<?php
namespace sportshop\app\data;

use sportshop\app\models\Order;

interface IOrderRepository
{
    //Retrieve
    public function GetAllWithDetailsPaginated(int $page, int $pageSize, string $searchName, string $filterStatus): array;
    public function GetAllWithDetails(): array;
    public function GetAllByUser(string $user_id): array;
    public function GetById(string $user_id, int $id): ?Order;
    //Create
    public function CreateOrder(Order $order): bool;
    //Update
    public function UpdateOrder(Order $order): bool;
    //Delete
    public function DeleteOrder(int $id): bool;
}