<?php
namespace App\Traits;

trait ResponseTrait{
    public function jsonResponse( $code = 200, $message = null, $data = []){
        $status = array(
            200 => '200 OK',
            400 => '400 Bad Request',
            422 => 'Unprocessable Entity',
            500 => '500 Internal Server Error'
        );

        header("HTTP/1.1 ".$status[$code]);
        echo json_encode([
            'status' => $code < 300, // success or not?
            'message' => $message,
            'data' => $data
        ], false);
        exit();
    }
}