<?php

namespace App\Http\Controllers\av;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\CustomClass\CustomeResponse;
use App\Customer;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AvCustomerController extends Controller
{
    
    //Create Customer 
    public function register(Request $request)
    {
        $validator = Validator::make($request->json()->all(), [
            'username' => 'required|unique:Customer|max:20',
            'email' => 'required|unique:Customer',
            'mobile' => 'required|unique:Customer|max:12',
            'password' => 'required'
        ]);

        if ($validator->fails()) {
            return CustomeResponse::ResponseRequestValidationFailed();
        }

        $insert_Customer = Customer::Create_customer([
            'username' => $request->username,
            'email' => $request->email,
            'mobile' => $request->mobile,
            'password' => Hash::make($request->password),
            'status' => 0,
        ]);


        if ($insert_customer) {
            $this->code = 200;
            $this->message = 'Customer Created Successfully';
        }

        return CustomeResponse::ResponseMsgOnly($this->message, $this->code);
    }

}
