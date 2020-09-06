<?php

namespace App\Http\Controllers\Dashboard;

use App\CustomClass\CustomeResponse;
use App\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Controller;

class OrderManagement extends Controller
{
    public function __construct()
    {
        $this->code = 401;
        $this->message = 'System Error..!';

    }
    
    //Create Supplier 
    public function storeSupplierDetails(Request $request)
    {
        $validator = Validator::make($request->json()->all(), [
            'username' => 'required|unique:supplier|max:20',
            'email' => 'required|unique:supplier',
            'mobile' => 'required|unique:supplier|max:12',
            'password' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'code' => 401,
                'message' => 'Invalid Request',
            ], 401);
        }

        $insert_supplier = Supplier::Create_supplier([
            'username' => $request->username,
            'email' => $request->email,
            'mobile' => $request->mobile,
            'password' => encrypt($request->password),
            'status' => 0,
        ]);

      
        if ($insert_supplier) {
            $this->code = 200;
            $this->message = 'Supplier Created Successfully';
        }

        return CustomeResponse::ResponseMsgOnly($this->message ,$this->code);
    }

    public function changeSupplierStatus(Request $request){
        $validator = Validator::make($request->json()->all(), [
            'id' => 'required',
            'status' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'code' => 401,
                'message' => 'Invalid Request',
            ], 401);
        }

        if(Supplier::change_status($request->id,$request->status)){
            $this->code = 200;
            $this->message = 'Supplier Status Change';
        }

        return CustomeResponse::ResponseMsgOnly($this->message ,$this->code);
    }

    public function getSingleSupplierDetails(Request $request){


    }
    
    public function updateSupplierDetails(Request $request){


    }



}
