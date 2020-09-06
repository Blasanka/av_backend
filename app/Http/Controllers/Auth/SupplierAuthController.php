<?php
namespace App\Http\Controllers\Auth;

use App\CustomClass\CustomeResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Supplier;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;
use JWTFactory;
use JWTAuth;

use JWTAuthException;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth as FacadesJWTAuth;
use Tymon\JWTAuth\JWTAuth as JWTAuthJWTAuth;

class SupplierAuthController extends Controller
{
    /**
     * Login user and create token
     *
     * @param  [string] username
     * @param  [string] password
     * @return [string] access_token
     * @return [string] expires_at
     */
    public function login(Request $request)
    {
        $message ="";
        $code ="";

        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email|max:255',
            'password'=> 'required'
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors());
        }

        if(Supplier::checkApproveStaus($request->email)){
            config()->set( 'auth.defaults.guard', 'supplier' );
            Config::set('jwt.user', 'App\Supplier'); 
            Config::set('auth.providers.users.model', \App\Supplier::class);
    
    
            $credentials = $request->only('email', 'password');
    
            try {
                if (! $token = JWTAuth::attempt($credentials)) {
                    return response()->json(['error' => 'invalid_credentials'], 401);
                }
            } catch (JWTException $e) {
                return response()->json(['error' => 'could_not_create_token'], 500);
            }

            $supplier = Supplier::getUsername($request->email);
            
            // return response()->json([
            //     'message' => 'Authorized',
            //     'access_token' => $token,
            //     'token_type' => 'Bearer',
            //     'expires_at' => Carbon::parse(
            //     )->toDateTimeString()
            // ]);
            $message ="Authorized";
            $code ="200";
            $data = [
                'access_token' => $token,
                'token_type' => 'Bearer',
                'expires_at' => Carbon::parse(
                )->toDateTimeString(),
                'username' =>$supplier
                ];
               return CustomeResponse::AuthResponse($message,$code,$token,$data);

        } else {
            $message ="unAthorized";
            $code ="401";
        }

        return CustomeResponse::AuthResponse($message,$code);

    }
  
    /**
     * Logout user (Revoke the token)
     *
     * @return [string] message
     */

    public function logout(Request $request)
    {
        $request->user()->token()->revoke();
        return response()->json([
            'message' => 'Successfully logged out'
        ]);
    }
  
    /**
     * Get the authenticated User
     *
     * @return [json] user object
     */
    public function user(Request $request)
    {
        return response()->json($request->user());
    }
}