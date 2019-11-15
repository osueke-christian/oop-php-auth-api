<?php
namespace App\Database;

require "../../bootstrap.php";

use App\Traits\ResponseTrait;
use Connection;

class Migration{
    use ResponseTrait;
    public function createUserTable(){
        try{
            $statement = "
                CREATE TABLE users (
                    id INT NOT NULL AUTO_INCREMENT,
                    name VARCHAR(100) NOT NULL,
                    email VARCHAR(100) NOT NULL,
                    password VARCHAR(60) NOT NULL,
                    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
                        ON UPDATE CURRENT_TIMESTAMP,
                    PRIMARY KEY (id)
                ) ENGINE=INNODB;
            ";
            
            $this->connection->exec($statement);
            return $this->jsonResponse(200, 'User table migration successful!');
        }
        catch(\PDOException $e){
            return $this->jsonResponse(400, 'Migration failed ', $e->getMessage());
        }catch(\Exception $e){
            return $this->jsonResponse(400, 'Migration failed ', $e->getMessage());
        }
    }
    
}
(new Migration())->createUserTable();