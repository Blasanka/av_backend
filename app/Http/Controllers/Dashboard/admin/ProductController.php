<?php

namespace App\Http\Controllers\Dashboard\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\CustomClass\CustomeResponse;
use App\Product;

class ProductController extends Controller
{
    public function getAllProducts() {
        $products = Product::all();
        return CustomeResponse::ResponseMsgWithData("Successful", 200, $products);
    }

    public function changeStatus(Request $request) {
        if (!empty($request->id)) {
            $data = Product::find($request->id);
            $data->status = 1;
            $data->save();
            return CustomeResponse::ResponseMsgOnly("Successful", 200);
        } else {
            return CustomeResponse::ResponseMsgOnly("Bad Request", 403);
        }
    }
}
