<?php
namespace sportshop\app\data\interfaces;

use sportshop\app\models\Order;

interface IOrderRepository
{
    //Retrieve
    /**
     * Count all orders in the system.
     * @return void
     */
    function CountAll(): int;
    /**
     * Count orders by status 
     * @param string $filterStatus - status to filter by
     * @return int - count of orders matching criteria
     */
    function CountByStatus(string $status): int;
    /**
     * Retrieve all orders with pagination, search, and filter options.
     * @param int $page - page number
     * @param int $pageSize - number of items per page
     * @param string $searchName - search term for order name
     * @param string $filterStatus - filter by order status
     * @return array - list of orders matching criteria
     */
    public function GetAllWithDetailsPaginated(int $page, int $pageSize, string $searchName, string $filterStatus): array;
    /**
     * Retrieve all orders with details.
     * @return array - list of orders with details
     */
    public function GetAllWithDetails(): array;
    /**
     * Retrieve all orders for a specific user.
     * @param string $user_id - user identifier
     * @return array - list of orders for the user
     */
    public function GetAllByUser(string $user_id): array;
    /**
     * Retrieve a specific order by its ID for a specific user.
     * @param string $user_id - user identifier
     * @param int $id - order identifier
     * @return Order|null - the order if found, null otherwise
     */
    public function GetById(string $user_id, int $id): ?Order;
    //Create
    /**
     * Create a new order.
     * @param Order $order - the order to create
     * @return bool - true if creation was successful, false otherwise
     */
    public function CreateOrder(Order $order): bool;
    //Update
    /**
     * Update an existing order.
     * @param Order $order - the order to update
     * @return bool - true if update was successful, false otherwise
     */
    public function UpdateOrder(Order $order): bool;
    /**
     * Update the status of an order.
     * @param string $order_id - the order identifier
     * @param string $new_status - the new status to set
     * @return bool - true if update was successful, false otherwise
     */
    function UpdateStatusOrder(string $order_id, string $new_status): bool;
    //Delete
    /**
     * Delete an order by its ID.
     * @param string $id - order identifier
     * @return bool - true if deletion was successful, false otherwise
     */
    public function DeleteOrder(string $id): bool;
}