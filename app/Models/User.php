<?php
namespace App\Models;

use App\Models\Model;

class User extends Model{
    protected $fillables = ['name', 'email', 'password'];

    function __construct(){
        parent::__construct();
        // We can setup guards for methods here
    }

    public function create(Array $args){
        try{
            $query = $this->connection->prepare("INSERT INTO users (name, email, password) VALUES (:name, :email, :password)");
            $query->execute($this->filterArgs($args));

            return $query->rowCount();
        }catch(\PDOException $e){
            return $this->jsonResponse(400, 'User account creation failed ', $e->getMessage());
        }catch(\Exception $e){
            return $this->jsonResponse(400, 'User account creation failed ', $e->getMessage());
        }
    }

    public function find($id){
        try {
            $query = $this->connection->prepare("SELECT id, name, email, created_at FROM users WHERE id = ? LIMIT 1");
            $query->execute(array($id));
            $result = $query->fetchAll(\PDO::FETCH_ASSOC);
            return $result;
        }catch(\PDOException $e){
            return $this->jsonResponse(400, 'DB Error ', $e->getMessage());
        }catch(\Exception $e){
            return $this->jsonResponse(400, 'Application error ', $e->getMessage());
        }  
    }

    // THIS IS NOT TO BE RETURNED CAUSE IT HAS THE PASSWORD FIELD
    // CURRENTLY USED FOR AUTHENTICATION
    public function findByEmail($email){
        $DB = $this->connection;
        try {
            $query = $this->connection->prepare("SELECT id, name, email, password, created_at FROM users WHERE email = ? LIMIT 1");
            $query->execute(array($email));
            $result = $query->fetchAll(\PDO::FETCH_ASSOC);
            return $result;
        }catch(\PDOException $e){
            return $this->jsonResponse(400, 'DB Error ', $e->getMessage());
        }catch(\Exception $e){
            return $this->jsonResponse(400, 'Application error ', $e->getMessage());
        }   
    }

    public function findAll(){
        try{
            $query = $this->connection->prepare("SELECT id, name, email FROM users;");
            $query->execute();
            $result = $query->fetchAll(PDO::FETCH_ASSOC);
            return $result;
        }catch(\PDOException $e){
            return $this->jsonResponse(400, 'DB Error ', $e->getMessage());
        }catch(\Exception $e){
            return $this->jsonResponse(400, 'Application error ', $e->getMessage());
        }
    }
}