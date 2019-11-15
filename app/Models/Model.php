<?php

namespace App\Models;

use App\Database\Connection;
use App\Traits\ResponseTrait;

class Model{
    use ResponseTrait;
    
    public $connection;

    function __construct(){
        $this->connection = (new Connection())->getConnection();
    }


    protected function filterArgs(Array $args){
        // Check if fillables are set and if it was set as an arrays
        if(!isset($this->fillables)) return $args;
        if(!is_array($this->fillables)) throw new Exception('Fillables varaible must be an array');

        $filtered = [];
        foreach($args as $key=>$value){
            if(in_array($key, $this->fillables)) $filtered[$key] = $value;
        }
        return $filtered;
    }
}