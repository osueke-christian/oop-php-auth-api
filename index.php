<?php 

require "bootstrap.php";

use App\Controllers\UserController;
use App\Controllers\AuthController;

// Comment or Uncomment to disable / enable error displays
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

/**
 * Setting application request headers
 * - To allow cross origin requests
 * - To allow json requests and response
 * - To allow the 4 basic request methods (PUT,POST,GET,DELETE)
 * - To access Authorization headers 
 *	 which is where session tokens generated for authenticated users will be passed from the frontend to the backend
    */
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: OPTIONS,GET,POST,PUT,DELETE");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

/**
 * Setting useful varaibles to be used to determine authentication, request method, payload, and request URI
 */
$requestMethod = $_SERVER["REQUEST_METHOD"];
$payload = count($_POST) > 0 ? $_POST : json_decode(file_get_contents('php://input'), true);
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$uri = explode( '/', $uri );

// All of the endpoints start with /api and returning 404 not found for everything else
if ($uri[1] !== 'api') {
    header("HTTP/1.1 404 Not Found");
    exit('Please prefix all routes URI with /api/');
}

// Grab authorization header if its set, to be used to determine auth status
if(array_key_exists('HTTP_AUTHORIZATION', $_SERVER)) $authHeader = $_SERVER['HTTP_AUTHORIZATION'];
elseif(array_key_exists('Authorization', $_SERVER)) $authHeader = $_SERVER['Authorization'];
else $authHeader = null;

// Declare API routes
$routes = [
    'register'=>['method'=>'post', 'controller'=>'AuthController', ''=>'register'],
    'login'=>['method'=>'post', 'controller'=>'AuthController', ''=>'login'], 
    'logout'=>['method'=>'get', 'controller'=>'AuthController', ''=>'logout', 'middleware'=>'auth'],
    'user'=>['method'=>'get', 'controller'=>'UserController', ''=>'getUser', 'middleware'=>'auth'],
    'user/all'=>['method'=>'get', 'controller'=>'UserController', ''=>'getAllUsers', 'middleware'=>'auth'],
];

/**
 * If user is accessing the login or register route, then confirm its a post request and act accordingly
 * 
 * An improvement will be to check the header and see if user is already authnticated, then log him out
 * or return a 302 redirect
 */
if($uri[2] == 'login' || $uri[2] == 'register'){
    if($requestMethod !== 'POST'){
        exit('Invalid request method');
    }
    $auth = new AuthController();
    if($uri[2] == 'login') {
        $auth->login($payload);
    }
    elseif($uri[2] == 'register'){
        $auth->register($payload);
    }
}

else{
    /**
     * User is trying to access an authenticated route
     * Check headers for bearer token
     */
    preg_match('/Bearer\s(\S+)/', $authHeader, $matches);
    if(!isset($matches[1])) {
        exit('No Bearer Token or Token Invalid');
    }else{
        // Check if the token is valid
        $token = $matches[1];
        $auth = AuthController::isAuth($token);
        if($auth){
            $user = new UserController();
            //Confirm intended route is set
            if($uri[2] !== 'user' && $uri[2] !== 'logout') exit('Route not defined');
            // confirm specified required method for requested route
            if($uri[2] == 'user' && $requestMethod == 'GET'){
                // Grant access to route
                $user->getUser($auth['id']);
            }
            elseif($uri[2] == 'logout' && $requestMethod == 'GET'){
                AuthController::logout($token);
            }
            else{
                exit('Specified route method incorrect (ensure its a GET request)');
            }
        }else{
            exit('Unauthenticated');
        }
    }
}