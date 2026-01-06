<?php
    namespace sportshop\app\data;

    use PDO;
    use PDOException;
    use Exception;
    class DbContext {
        private $host = 'localhost';
        private $dbname = 'your_database';
        private $username = 'root';
        private $password = '';
        private $conn = null;

        public function __construct($host = null, $dbname = null, $username = null, $password = null) {
            if ($host) $this->host = $host;
            if ($dbname) $this->dbname = $dbname;
            if ($username) $this->username = $username;
            if ($password !== null) $this->password = $password;
        }

        public function connect() : PDO {
            try {
                $this->conn = new PDO(
                    "mysql:host={$this->host};dbname={$this->dbname};charset=utf8mb4",
                    $this->username,
                    $this->password
                );
                $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                return $this->conn;
            } catch (PDOException $e) {
                throw new Exception("Connection failed: " . $e->getMessage());
            }
        }

        public function getConnection() : PDO {
            if ($this->conn === null) {
                $this->connect();
            }
            return $this->conn;
        }

        public function disconnect() : void {
            $this->conn = null;
        }
    }
?>