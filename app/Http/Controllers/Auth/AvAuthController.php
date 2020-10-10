<?php

namespace App\Http\Controllers;

use App\CustomClass\CustomeResponse;
use App\Customer;
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


class AvAuthController extends Controller {
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

        if(Customer::checkApproveStaus($request->email)){
            config()->set('auth.defaults.guard', 'customer' );
            Config::set('jwt.user', 'App\Customer'); 
            Config::set('auth.providers.users.model', \App\Customer::class);
    
    
            $credentials = $request->only('email', 'password');
    
            try {
                if (! $token = JWTAuth::attempt($credentials)) {
                    return response()->json(['error' => 'Invalid Credentials'], 401);
                }
            } catch (JWTException $e) {
                return response()->json(['error' => 'Could not able to generate session'], 501);
            }

            $customer = Customer::getUsername($request->email);
            
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
                'username' =>$Customer
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
    public function storeCustomerDetails(Request $request)
    {
        $validator = Validator::make($request->json()->all(), [
            'username' => 'required|max:20',
            'email' => 'required|unique:Customer',
            'mobile' => 'required|unique:Customer|max:12',
            'nic' => 'required|unique:Customer|max:11',
            'address' => 'required|unique:Customer',
            'password' => 'required'
        ]);

        if ($validator->fails()) {
            return CustomeResponse::ResponseRequestValidationFailed();
        }

        $insertCustomer = Customer::create([
            'username' => $request->username,
            'email' => $request->email,
            'mobile' => $request->mobile,
            'address' => $request->address,
            'nic' => $request->nic,
            'password' => Hash::make($request->password),
            'status' => 0,
        ]);


        if ($insertCustomer) {
            $this->code = 200;
            $this->message = 'Customer Created Successfully';
        }

        return CustomeResponse::ResponseMsgOnly($this->message, $this->code);
    }

    public function changeCustomerStatus(Request $request)
    {
        $validator = Validator::make($request->json()->all(), [
            'id' => 'required',
            'status' => 'required'
        ]);

        if ($validator->fails()) {
            return CustomeResponse::ResponseRequestValidationFailed();

        }

        if (Customer::change_status($request->id, $request->status)) {
            $this->code = 200;
            $this->message = 'Customer Status Change';
        }

        return CustomeResponse::ResponseMsgOnly($this->message, $this->code);
    }

    public function getSingleCustomerDetails()
    {
        $user = Auth::guard('customer')->user();
        return Customer::getCustomer($user->id);
    }

    public function updateCustomerDetails(Request $request)
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

        $user = Auth::guard('customer')->user();
        $data=[];
        $data['email']=$request->email;

        if(Customer::updateDetails($user->id, $data)){
            $this->code = 200;
            $this->message = 'Customer updated successfully';
        };

        return CustomeResponse::ResponseMsgOnly($this->message, $this->code);
    }
}
