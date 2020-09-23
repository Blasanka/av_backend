<?php

namespace App\Http\Controllers\Dashboard\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\CustomClass\CustomeResponse;
use App\Product;

class ProductController extends Controller {
    public function getAllProducts() {
        $products = Product::all();
        foreach ($products as $key => $value) {
            $attachment = explode('|', $value->attachment);
            $value->attachment = $attachment;
            $products[$key] = $value;
        }
        return CustomeResponse::ResponseMsgWithData("Successful", 200, $products);
    }

    public function changeStatus(Request $request) {
        if (!empty($request->id)) {
            $data = Product::find($request->id);
            $data->status = $request->status;
            $data->save();
            return CustomeResponse::ResponseMsgOnly("Successful", 200);
        } else {
            return CustomeResponse::ResponseMsgOnly("Bad Request", 403);
        }
    }
}
