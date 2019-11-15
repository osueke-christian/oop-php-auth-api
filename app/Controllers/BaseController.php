<?php
namespace App\Controllers;

session_start();

use App\Traits\ResponseTrait;

class BaseController{
    use ResponseTrait;

}