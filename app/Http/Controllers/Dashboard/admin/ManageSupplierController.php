<?php

namespace App\Http\Controllers\Dashboard\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\CustomClass\CustomeResponse;
use App\Supplier;

class ManageSupplierController extends Controller
{
    public function getAllSuppliers() {
        $suppliers = Supplier::all();
        // foreach ($suppliers as $key => $value) {
        //     $attachment = explode('|', $value->attachment);
        //     $value->attachment = $attachment;
        //     $suppliers[$key] = $value;
        // }
        if (!empty($suppliers)) {    
            return CustomeResponse::ResponseMsgWithData("Successful", 200, $suppliers);
        } else {
            return CustomeResponse::ResponseMsg("Cannot find any supplier", 404, $suppliers);
        }
    }

    public function changeStatus(Request $request) {
        if (!empty($request->id)) {
            $data = Supplier::find($request->id);
            $data->status = $request->status;
            $data->save();
            return CustomeResponse::ResponseMsgOnly("Successful", 200);
        } else {
            return CustomeResponse::ResponseMsgOnly("Bad Request", 403);
        }
    }

    public function getSupplier(Request $request) {
        if (!empty($request->id)) {
            $supplier = Supplier::find($request->id);
            if (!is_null($supplier)) {
                // $attachment = explode('|', $supplier->attachment);
                // $supplier->attachment = $attachment;
                return CustomeResponse::ResponseMsgWithData("Successful", 200, $supplier);
            } else {
                return CustomeResponse::ResponseMsgOnly("Not found", 404);
            }
        } else {
            return CustomeResponse::ResponseMsgOnly("Bad Request", 403);
        }
    }
}
