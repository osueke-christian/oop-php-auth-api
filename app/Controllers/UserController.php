<?php

namespace App\Controllers;

use App\Models\User;

class UserController extends BaseController{
    private $user;

    public function __construct(){
        $this->user = new User();
        // middlewares and guards can be implemented here
    }

    public function getUser($id){
        $result = $this->user->find($id);
        if(!$result) return $this->jsonResponse(404, 'Not found', $result);
        return $this->jsonResponse(200, 'Success', $result);
    }

    public function getAllUsers(){
        $result = $this->user->findAll();
        return $this->jsonResponse(200, 'Success', $result);
    }

}