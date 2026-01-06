<?php
namespace sportshop\app\data;

use sportshop\app\models\Cart;
use sportshop\app\models\User;

interface IUserRepository
{
    function GetAll(): ?array;
    function GetById(string $id): ?User;
    function GetByEmail(string $email): ?User;
    function CreateUser(User $user, Cart $cart): bool;
    function UpdateUser(User $user): bool;
    function UpdatePassword(string $userId, string $hashedPassword): bool;
    function DeleteUser(int $id): bool;
    function ActivateUser(string $userId, bool $isActive): bool;
    function GetAllPaginated(int $page, int $pageSize): ?array;
    function ChangeUserRole(string $userId, string $role): bool;
}