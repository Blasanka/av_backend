<?php

namespace App\Http\Controllers\Dashboard;

use App\CustomClass\CustomeResponse;
use App\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class SupplierManagement extends Controller
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
            return CustomeResponse::ResponseRequestValidationFailed();
        }

        $insert_supplier = Supplier::Create_supplier([
            'username' => $request->username,
            'email' => $request->email,
            'mobile' => $request->mobile,
            'password' => Hash::make($request->password),
            'status' => 0,
        ]);


        if ($insert_supplier) {
            $this->code = 200;
            $this->message = 'Supplier Created Successfully';
        }

        return CustomeResponse::ResponseMsgOnly($this->message, $this->code);
    }

    public function changeSupplierStatus(Request $request)
    {
        $validator = Validator::make($request->json()->all(), [
            'id' => 'required',
            'status' => 'required'
        ]);

        if ($validator->fails()) {
            return CustomeResponse::ResponseRequestValidationFailed();

        }

        if (Supplier::change_status($request->id, $request->status)) {
            $this->code = 200;
            $this->message = 'Supplier Status Change';
        }

        return CustomeResponse::ResponseMsgOnly($this->message, $this->code);
    }

    public function getSingleSupplierDetails()
    {
        $user = Auth::guard('supplier')->user();
        return Supplier::get_single_supplier($user->id);
    }

    public function updateSupplierDetails(Request $request)
    {
        $validator = Validator::make($request->json()->all(), [
            'supEmail' => 'required',
            'supMobile' => 'required',
            'supUsername' => 'required'
        ]);

        if ($validator->fails()) {
            return CustomeResponse::ResponseRequestValidationFailed();
        }

        $user = Auth::guard('supplier')->user();
        $data=[];
        $data['email']=$request->supEmail;
        $data['mobile']=$request->supMobile;
        $data['legal_name']=$request->supLegName;
        $data['address']=$request->supAddress;
        $data['personalic']=$request->supPIC;
        $data['br_num']=$request->supBr;
        $data['nic_copy']=$request->supID;
        $data['bis_info']=$request->supBusinessInfo;

        if(Supplier::update_details($user->id, $data)){
            $this->code = 200;
            $this->message = 'Supplier updated successfully';
        };

        return CustomeResponse::ResponseMsgOnly($this->message, $this->code);
    }
}
