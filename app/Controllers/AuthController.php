<?php
namespace App\Controllers;

use App\Models\User;

class AuthController extends BaseController{
    private $user;

    public function __construct(){
        $this->user = new User();
        // middlewares and guards can be implemented here
    }

    public function register(Array $payload) {
        try{
            // Perform input validation
            if(
                !isset($payload['name']) || $payload['name'] === null || $payload['name'] === '' || $payload['name'] === 0 ||
                !isset($payload['email']) || $payload['email'] === null || $payload['email'] === '' || $payload['email'] === 0 ||
                !isset($payload['password']) || $payload['password'] === null || $payload['password'] === '' || $payload['password'] === 0 ||
                !isset($payload['confirmed_password'])
            )   return $this->jsonResponse(400, 'Please fill input fields correctly');
            if(!filter_var($payload['email'], FILTER_VALIDATE_EMAIL)) return $this->jsonResponse(400, 'Invalid email');
            if(strlen($payload['password']) < 6) return $this->jsonResponse(400, 'Password must not be less than 6 characters');
            if($payload['confirmed_password'] !== $payload['password']) return $this->jsonResponse(400, 'Password mismatch');

            // Sanitize user input
            $payload = $this->sanitizeInput((array)$payload);

            // Check if email has already been taken
            $email_exists = $this->user->findByEmail($payload['email']);
            if($email_exists) return $this->jsonResponse(400, 'Specified email has been taken');

            // Hash password and create the account
            $payload['password'] = password_hash($payload['password'], PASSWORD_DEFAULT);
            $user = $this->user->create($payload);

            if(!$user) return $this->jsonResponse(400, 'User account creation failed');
            return $this->jsonResponse(200, 'Registeration successful ');

        }catch(\Exception $e){
            return $this->jsonResponse(400, $e->getMessage());
        }
    }

    public function login(Array $payload){
        try{
            // Perform input validation
            if(
                !isset($payload['email']) || $payload['email'] === null || $payload['email'] === '' || $payload['email'] === 0 ||
                !isset($payload['password']) || $payload['email'] === null || $payload['email'] === '' || $payload['email'] === 0
            )   return $this->jsonResponse(400, 'Please fill input fields correctly');
            if(!filter_var($payload['email'], FILTER_VALIDATE_EMAIL)) return $this->jsonResponse(400, 'Invalid email');

            // Sanitize user input
            $payload = $this->sanitizeInput((array)$payload);

            // Check if email has already been taken
            $email_exists = $this->user->findByEmail($payload['email']);
            if(!$email_exists) return $this->jsonResponse(404, 'Email does not exist, please register');
            
            foreach($email_exists as $user){
                if(password_verify($payload['password'], $user['password'])){
                    // create a session token for user
                    $token = password_hash(uniqid().time(), PASSWORD_DEFAULT);
                    $result = ['token'=>$token, 'user_name'=>$user['name'], 'user_email'=>$user['email'] ];
                    $_SESSION[$result['token']] = $user;

                    return $this->jsonResponse(200, 'Login successful', $result);
                }
            }
            
            return $this->jsonResponse(400, 'Password Incorrect');
        }catch(\Exception $e){
            return $this->jsonResponse(400, $e->getMessage());
        }
        
    }

    public static function logout(String $token){
        // Check if user is authenticated
        $self = (new self);
        if(!$self->isAuth($token)) return $self->jsonResponse(200, 'You have already logged out');
        
        try{
            $_SESSION[$token] = null;
            unset($_SESSION[$token]);
            return $self->jsonResponse(200, 'You have successfully logged out');
        }catch(\Exception $e){
            return $self->jsonResponse(400, $e->getMessage());
        }
    }

    public static function isAuth(String $authHeader){
        return self::checkAuth($authHeader);
    }

}