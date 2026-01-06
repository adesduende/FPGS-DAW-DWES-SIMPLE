<?php
namespace sportshop\app\models;

use DateTime;
use sportshop\app\utils\GUID;

class Order extends Identity
{
    public string $UserId;
    public int $OrderNumber;
    public float $Total;
    public string $Status;
    public array $Products;
    public DateTime $CreatedAt;

    public function __construct(
        ?GUID $id,
        string $userId,
        int $orderNumber,
        float $total,
        string $status,
        array $products,
        ?DateTime $createdAt = new DateTime()
    ) {
        parent::__construct($id);
        $this->UserId = $userId;
        $this->OrderNumber = $orderNumber;
        $this->Total = $total;
        $this->Status = $status;
        $this->Products = $products;
        $this->CreatedAt = $createdAt;
    }
}