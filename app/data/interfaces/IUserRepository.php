<?php
namespace sportshop\app\data\interfaces;

use sportshop\app\models\Cart;
use sportshop\app\models\User;

interface IUserRepository
{
    /**
     * This method retrieves all users from the repository.
     * @return void
     */
    function GetAll(): ?array;
    /**
     * This method retrieves a user by their unique identifier.
     * @param string $id - The unique identifier of the user.
     * @return void
     */
    function GetById(string $id): ?User;
    /**
     * This method retrieves a user by their email address.
     * @param string $email - The email address of the user.
     * @return void
     */
    function GetByEmail(string $email): ?User;
    /**
     * This method creates a new user in the repository.
     * @param User $user - The user object to be created.
     * @return void
     */
    function CreateUser(User $user, Cart $cart): bool;
    /**
     * This method updates an existing user in the repository.
     * @param User $user - The user object with updated information.
     * @return void
     */
    function UpdateUser(User $user): bool;
    /**
     * This method updates the password of an existing user.
     * @param string $userId - The unique identifier of the user.
     * @param string $hashedPassword - The new hashed password.
     * @return void
     */
    function UpdatePassword(string $userId, string $hashedPassword): bool;
    /**
     * This method deletes a user from the repository.
     * @param int $id - The unique identifier of the user to be deleted.
     * @return void
     */
    function DeleteUser(int $id): bool;
    /**
     * This method activates or deactivates a user account.
     * @param string $userId - The unique identifier of the user.
     * @param bool $isActive - The activation status to be set.
     * @return void
     */
    function ActivateUser(string $userId, bool $isActive): bool;
    /**
     * This method retrieves users in a paginated format.
     * @param int $page - The page number to retrieve.
     * @param int $pageSize - The number of users per page.
     * @return void
     */
    function GetAllPaginated(int $page, int $pageSize): ?array;
    /**
     * This method changes the role of a user.
     * @param string $userId - The unique identifier of the user.
     * @param string $role - The new role to be assigned to the user.
     * @return void
     */
    function ChangeUserRole(string $userId, string $role): bool;
}