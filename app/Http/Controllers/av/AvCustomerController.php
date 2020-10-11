<?php

namespace App\Http\Controllers\av;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\CustomClass\CustomeResponse;
use App\Models\VerifyCode;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AvCustomerController extends Controller
{
    public function generateVerificationCode(Request $request) {
        
        $validator = Validator::make($request->json()->all(), [
            'mobile_number' => 'required|string|min:10|max:16',
        ]);

        if ($validator->fails()) {
            return CustomeResponse::ResponseRequestValidationFailed();
        }

        $six_digit_random_number = mt_rand(100000, 999999);
        try {
            $insertCode = VerifyCode::create([
                'verify_code' => $six_digit_random_number,
                'mobile_number' => $request->mobile_number,
            ]);
    
            if ($insertCode) {
                $this->code = 200;
                $this->message = 'Verification code sent.';
            } else {
                $this->code = 500;
                $this->message = 'Verify code sending failed, please try again!';
            }    
         } catch (\Exception $e) {
            $this->code = 500;
            $this->message = 'Something went wrong, please try again!';

            if ($e->getCode() == 23000) {
                $this->code = 400;
                $this->message = 'Your mobile number alredy exist';
            }
         }

        return CustomeResponse::ResponseMsgOnly($this->message, $this->code);
    }
}
