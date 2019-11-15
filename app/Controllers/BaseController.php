<?php
namespace App\Controllers;

session_start();

use App\Traits\ResponseTrait;

class BaseController{
    use ResponseTrait;

    protected function sanitizeInput(Array $input){
        $input = $input;
        foreach($input as $key=>$value){
            $input[$key] = htmlspecialchars(strip_tags($value));
        }
        return $input;
    }

    public static function checkAuth(String $authHeader){ // Find a way to auto get the authheader
        if(isset($_SESSION[$authHeader]) && $_SESSION[$authHeader] !== null) return $_SESSION[$authHeader];
        else return false;
    }

}