<?php

namespace App\Utils;

class ResponseCode{
    static function successPost($message, $data){
        return response()->json([
            'status' => true,
            'message' => $message,
            'data' => $data
        ], 201);
    }

    static function successGet($message, $data){
        return response()->json([
            'status' => true,
            'message' => $message,
            'data' => $data
        ], 200);
    }

    static function errorPost($message){
        return response()->json([
            'status' => false,
            'message' => $message
        ], 400);
    }

    static function unauthorized($message){
        return response()->json([
            'status' => false,
            'message' => $message
        ], 401);
    }

    static function notFound($message){
        return response()->json([
            'status' => false,
            'message' => $message
        ], 404);
    }
}