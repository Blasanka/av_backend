<?php
namespace App\CustomClass;
 
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Hash;
use App\t_log;
use App\User;
class CustomeResponse
{
    //Explane codes use for http responce. make sure to use only below codes
    /*
        200 - success
        400 - error
        401 - unauthorize 
    */
    public static function  ResponseRequestValidationFailed()
    {
        return response()->json([
            'code' => 400,
            'message' => 'Bad Request',
        ], 401);
    }

    public static function  AuthResponse($message, $code, $token=null,$data=null)
    {
        return response()->json([
            'code' => $code,
            'message' => $message,
            'token' => $token,
            'data' => $data
        ]);
    }

    public static function  ResponseMsgOnly($message, $code)
    {
        return response()->json([
            'code' => $code,
            'message' => $message,
        ]);
    }

    public static function  ResponseMsgWithData($message, $code, $data)
    {
        return response()->json([
            'code' => $code,
            'message' => $message,
            'data' => $data,
        ]);
    }




}