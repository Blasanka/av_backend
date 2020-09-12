<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\CustomClass\CustomeResponse;
use App\Admin;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;
use JWTFactory;
use JWTAuth;

use JWTAuthException;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth as FacadesJWTAuth;
use Tymon\JWTAuth\JWTAuth as JWTAuthJWTAuth;

class AdminAuthController extends Controller
{
    public function __construct()
    {
        $this->code = 500;
        $this->message = 'Internal Server Error';
    }
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

        if(Admin::checkApproveStaus($request->email)){
            config()->set('auth.defaults.guard', 'admin' );
            Config::set('jwt.user', 'App\Admin'); 
            Config::set('auth.providers.users.model', \App\Admin::class);
    
    
            $credentials = $request->only('email', 'password');
    
            try {
                if (! $token = JWTAuth::attempt($credentials)) {
                    return response()->json(['error' => 'Invalid Credentials'], 401);
                }
            } catch (JWTException $e) {
                return response()->json(['error' => 'Could not able to generate session'], 501);
            }

            $admin = Admin::getUsername($request->email);
            
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
                'username' =>$admin
                ];
               return CustomeResponse::AuthResponse($message,$code,$token,$data);

        } else {
            $message ="Unathorized";
            $code ="401";
        }

        return CustomeResponse::AuthResponse($message,$code);

    }
  
    /**
     * Logout user (Revoke the token)
     *
     * @return [string] message
     */

    public function logout(Request $request) {
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
    public function user(Request $request) {
        return response()->json($request->user());
    }

    //Create Supplier 
    public function storeAdminDetails(Request $request)
    {
        $validator = Validator::make($request->json()->all(), [
            'username' => 'required|max:20',
            'email' => 'required|unique:admin',
            'mobile' => 'required|unique:admin|max:12',
            'nic' => 'required|unique:admin|max:11',
            'address' => 'required|unique:admin',
            'password' => 'required'
        ]);

        if ($validator->fails()) {
            return CustomeResponse::ResponseRequestValidationFailed();
        }

        $insertAdmin = Admin::create([
            'username' => $request->username,
            'email' => $request->email,
            'mobile' => $request->mobile,
            'address' => $request->address,
            'nic' => $request->nic,
            'password' => Hash::make($request->password),
            'status' => 0,
        ]);


        if ($insertAdmin) {
            $this->code = 200;
            $this->message = 'Admin Created Successfully';
        }

        return CustomeResponse::ResponseMsgOnly($this->message, $this->code);
    }

    public function changeAdminStatus(Request $request)
    {
        $validator = Validator::make($request->json()->all(), [
            'id' => 'required',
            'status' => 'required'
        ]);

        if ($validator->fails()) {
            return CustomeResponse::ResponseRequestValidationFailed();

        }

        if (Admin::change_status($request->id, $request->status)) {
            $this->code = 200;
            $this->message = 'Admin Status Change';
        }

        return CustomeResponse::ResponseMsgOnly($this->message, $this->code);
    }

    public function getSingleAdminDetails()
    {
        $user = Auth::guard('admin')->user();
        return Admin::getAdmin($user->id);
    }

    public function updateAdminDetails(Request $request)
    {
        $validator = Validator::make($request->json()->all(), [
            'email' => 'required',
            'mobile' => 'required',
            'address' => 'required',
            'username' => 'required'
        ]);

        if ($validator->fails()) {
            return CustomeResponse::ResponseRequestValidationFailed();
        }

        $user = Auth::guard('admin')->user();
        $data=[];
        $data['email']=$request->email;

        if(Admin::updateDetails($user->id, $data)){
            $this->code = 200;
            $this->message = 'Admin updated successfully';
        };

        return CustomeResponse::ResponseMsgOnly($this->message, $this->code);
    }
}
