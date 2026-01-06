<?php 
    namespace sportshop\app\models;

    use \sportshop\app\utils\GUID;
    class User extends Identity {
        public string $Name;
        public string $Surname;
        public string $Email;
        public string $PhoneNumber;
        public string $Role;
        public string $HashedPassword;
        public bool $IsActive;

        public function __construct(?GUID $id, string $name, string $surname, string $email, string $phoneNumber, string $hashedPassword, string $role, bool $isActive)
        {
            parent::__construct($id);
            
            $this->Name=$name;
            $this->Surname=$surname;
            $this->Email=$email;
            $this->PhoneNumber=$phoneNumber;
            $this->HashedPassword=$hashedPassword;
            $this->Role=$role;
            $this->IsActive=$isActive;
        }
    }
?>