<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\CustomClass\CustomeResponse;
use App\Models\Customer;
use App\Models\VerifyCode;
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
            'mobile' => 'required|string|max:255',
            'password'=> 'required|min:5|max:255'
        ]);
        if ($validator->fails()) {
            return CustomeResponse::ResponseMsgOnly($validator->errors(), 401);
        }

        if(Customer::checkApproveStaus($request->mobile)){
            config()->set('auth.defaults.guard', 'customer' );
            Config::set('jwt.user', 'App\Customer'); 
            Config::set('auth.providers.users.model', \App\Customer::class);
    
    
            // $credentials = $request->only('mobile', 'password');
            $customer = Customer::where('mobile', $request->mobile)->first();
    
            try {
                if (!$token = JWTAuth::fromUser($customer)) {
                    return CustomeResponse::ResponseMsgOnly('Invalid Credentials', 401);
                }
            } catch (JWTException $e) {
                return CustomeResponse::ResponseMsgOnly('Could not able to generate session', 501);
            }

            $customer = Customer::getUsername($request->mobile);
            $message ="Authorized";
            $code = 200;
            $data = [
                'access_token' => $token,
                'token_type' => 'Bearer',
                'expires_at' => Carbon::parse(
                )->toDateTimeString(),
                'username' =>$customer
                ];
               return CustomeResponse::AuthResponse($message, $code, $token, $data);

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

    //Create Customer 
    public function register(Request $request)
    {
        $validator = Validator::make($request->json()->all(), [
            'fullname' => 'required',
            'verify_code' => 'required',
            'email' => 'required',
            'mobile' => 'required|max:16',
            'password' => 'required',
            'gender' => 'required',
            'dob' => 'required',
        ]);

        if ($validator->fails()) {
            return CustomeResponse::ResponseRequestValidationFailed();
        }

        $verification = VerifyCode::where('verify_code', $request->verify_code)->first();
        if (empty($verification) || $verification->mobile_number != $request->mobile) {
            $this->code = 400;
            $this->message = 'You have not verified your mobile number yet.';
            return CustomeResponse::ResponseMsgOnly($this->message, $this->code);
        }

        try {
            $inserted = Customer::create([
                'username' => str_replace(' ', '', $request->fullname),
                'full_name' => $request->fullname,
                'email' => $request->email,
                'mobile' => $request->mobile,
                'verify_code' => $request->verify_code,
                'gender' => $request->gender,
                'dob' => $request->dob,
                'password' => Hash::make($request->password),
            ]);
            if ($inserted) {
                $this->code = 200;
                $this->message = 'Successfully registered';
            } else {
                $this->code = 500;
                $this->message = 'Verify code sending failed, please try again!';
            }
         } catch (\Exception $e) {
            $this->code = 500;
            $this->message = 'Something went wrong, please try again!';

            if ($e->getCode() == 23000) {
                $this->code = 400;
                $this->message = 'Please try different mobile number or email address.';
            }
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
            'mobile' => 'required',
            'email' => 'required',
            'address' => 'required',
            'username' => 'required'
        ]);

        if ($validator->fails()) {
            return CustomeResponse::ResponseRequestValidationFailed();
        }

        $user = Auth::guard('customer')->user();
        $data=[];
        $data['mobile']=$request->mobile;

        if(Customer::updateDetails($user->id, $data)){
            $this->code = 200;
            $this->message = 'Customer updated successfully';
        };

        return CustomeResponse::ResponseMsgOnly($this->message, $this->code);
    }
}
