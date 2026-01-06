<?php
namespace sportshop\app\data;

use sportshop\app\data\interfaces\IUserRepository;
use sportshop\app\models\Cart;
use sportshop\app\models\User;
use sportshop\app\utils\GUID;
readonly class UserRepository implements IUserRepository
{

    protected readonly DbContext $_context;

    public function __construct(DbContext $context)
    {
        $this->_context = $context;
    }
    function ActivateUser(string $userId, bool $isActive): bool
    {
        $stmt = $this->_context->getConnection()->prepare("UPDATE user SET is_active = :is_active WHERE user.id = :user_id");
        $stmt->bindParam(':user_id', $userId);
        $stmt->bindParam(':is_active', $isActive, \PDO::PARAM_BOOL);
        $stmt->execute();
        $this->_context->disconnect();

        return $stmt->rowCount() === 1;
    }
    function ChangeUserRole(string $userId, string $role): bool
    {
        $stmt = $this->_context->getConnection()->prepare("UPDATE user SET role = :role WHERE user.id = :user_id");
        $stmt->bindParam(':user_id', $userId);
        $stmt->bindParam(':role', $role);
        $stmt->execute();
        $this->_context->disconnect();

        return $stmt->rowCount() === 1;
    }
    function GetAll(): ?array
    {
        $stmt = $this->_context->getConnection()->prepare("SELECT * FROM user");
        $stmt->execute();
        $users = [];
        while ($row = $stmt->fetch(\PDO::FETCH_ASSOC)) {
            $users[] = new User(
                GUID::Create($row['id']),
                $row['name'],
                $row['surname'],
                $row['email'],
                $row['phone'],
                $row['password'],
                $row['role'],
                $row['is_active']
            );
        }
        $this->_context->disconnect();

        return $users;
    }
    function GetAllPaginated(int $page, int $pageSize): ?array
    {
        $conn = $this->_context->getConnection();
        $conn->beginTransaction();
        $offset = ($page - 1) * $pageSize;
        $stmt = $conn->prepare("SELECT * FROM user LIMIT :offset, :limit");
        $stmt->bindParam(':limit',$pageSize, \PDO::PARAM_INT);
        $stmt->bindParam(':offset', $offset, \PDO::PARAM_INT);
        $stmt->execute();
        $users = [];
        while ($row = $stmt->fetch(\PDO::FETCH_ASSOC)) {
            $users[] = new User(
                GUID::Create($row['id']),
                $row['name'],
                $row['surname'],
                $row['email'],
                $row['phone_number'],
                $row['hashed_password'],
                $row['role'],
                $row['is_active']
            );
        }

        $stmt = $conn->prepare("SELECT COUNT(*) as total FROM user");
        $stmt->execute();
        $totalRow = $stmt->fetch(\PDO::FETCH_ASSOC);

        $stmt = $conn->prepare("SELECT COUNT(*) as inactiveCount FROM user WHERE is_active = 0");
        $stmt->execute();
        $inactiveRow = $stmt->fetch(\PDO::FETCH_ASSOC);

        $stmt = $conn->prepare("SELECT COUNT(*) as adminCount FROM user WHERE role = 'admin'");
        $stmt->execute();
        $adminRow = $stmt->fetch(\PDO::FETCH_ASSOC);
        
        $conn->commit();
        $this->_context->disconnect();

        return [
            "users" => $users,
            "total" => $totalRow['total'],
            "inactiveCount" => $inactiveRow['inactiveCount'],
            "adminCount" => $adminRow['adminCount'],
            "page" => $page,
            "pageSize" => $pageSize
        ];
    }
    function GetById(string $id): ?User
    {
        $stmt = $this->_context->getConnection()->prepare("SELECT * FROM user WHERE id = :id");
        $stmt->execute(['id' => $id]);
        $row = $stmt->fetch(\PDO::FETCH_ASSOC);
        $user = new User(
            GUID::Create($row['id']),
            $row['name'],
            $row['surname'],
            $row['email'],
            $row['phone_number'],
            $row['hashed_password'],
            $row['role'],
            $row['is_active']
        );
        $this->_context->disconnect();
        return $user;
    }
    function GetByEmail(string $email): ?User
    {
        $db = $this->_context->getConnection();
        $stmt = $db->prepare("SELECT * FROM user WHERE email = ?");
        $stmt->execute([$email]);
        $row = $stmt->fetch(\PDO::FETCH_ASSOC);
        if (!$row) {
            $this->_context->disconnect();
            return null;
        }
        $user = new User(
            GUID::Create($row['id']),
            $row['name'],
            $row['surname'],
            $row['email'],
            $row['phone_number'],
            $row['hashed_password'],
            $row['role'],
            $row['is_active']
        );
        $this->_context->disconnect();
        return $user;
    }
    function CreateUser(User $user, Cart $cart): bool
    {
        $conn = $this->_context->getConnection();
        $conn->beginTransaction();
        $stmt = $conn->prepare(
            "INSERT INTO user (id, email, hashed_password, name, surname, role, phone_number) VALUES (?, ?, ?, ?, ?, ?, ?)"
        );
        $stmt->execute([
            $user->Id->Id,
            $user->Email,
            $user->HashedPassword,
            $user->Name,
            $user->Surname,
            $user->Role,
            $user->PhoneNumber
        ]);

        $stmt = $conn->prepare("INSERT INTO cart (id,user_id) VALUES (?, ?)");
        $stmt->execute([
            $cart->Id->Id,
            $cart->UserId
        ]);
        $conn->commit();
        $this->_context->disconnect();
        return true;
    }
    function UpdateUser(User $user): bool
    {
        $stmt = $this->_context->getConnection()->prepare("UPDATE user SET name = :name, surname = :surname, email = :email, phone_number = :phone_number, hashed_password = :hashed_password, role = :role, is_active = :is_active WHERE user.id = :user_id");
        $stmt->bindParam(':user_id', $user->Id->Id);
        $stmt->bindParam(':name', $user->Name);
        $stmt->bindParam(':surname', $user->Surname);
        $stmt->bindParam(':email', $user->Email);
        $stmt->bindParam(':phone_number', $user->PhoneNumber);
        $stmt->bindParam(':hashed_password', $user->HashedPassword);
        $stmt->bindParam(':role', $user->Role);
        $stmt->bindParam(':is_active', $user->IsActive);
        $stmt->execute();
        $this->_context->disconnect();

        return $stmt->rowCount() === 1;
    }    
    function UpdatePassword(string $userId, string $hashedPassword): bool
    {
        $stmt = $this->_context->getConnection()->prepare("UPDATE user SET hashed_password = :hashed_password WHERE user.id = :user_id");
        $stmt->bindParam(':user_id', $userId);
        $stmt->bindParam(':hashed_password', $hashedPassword);
        $stmt->execute();
        $this->_context->disconnect();

        return $stmt->rowCount() === 1;
    }    
    function DeleteUser(int $id): bool
    {
        return true;
    }
}