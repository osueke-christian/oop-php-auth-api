<?php
require 'vendor/autoload.php';

use Dotenv\Dotenv;

$dotenv = new DotEnv(__DIR__);
$dotenv->load();

// Testing .env config
// echo getenv('APP_NAME');