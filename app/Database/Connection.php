<?php
namespace App\Database;

use App\Traits\ResponseTrait;

class Connection {
    use ResponseTrait;

    private $connection = null;

    public function __construct(){
        // Cases of echo can be replaced with a log function
        
        $host = getenv('DB_HOST');
        $port = getenv('DB_PORT');
        $db   = getenv('DB_NAME');
        $user = getenv('DB_USERNAME');
        $pass = getenv('DB_PASSWORD');

        try {
            $this->connection = new \PDO("mysql:host=$host;port=$port;charset=utf8mb4;dbname=$db", $user, $pass);
            $this->connection->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        } catch (\PDOException $e) {
            $this->jsonResponse(500, 'Cannot connect to DB', $e->getMessage());
        }
    }

    public function getConnection(){
        return $this->connection;
    }
}