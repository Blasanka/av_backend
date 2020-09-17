<?php

namespace App\Http\Controllers\av;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\CustomClass\CustomeResponse;
use App\Product;
use Carbon\Carbon;

class AVProductController extends Controller {

    public function __construct() {
        $this->code = 500;
        $this->message = 'Internal Server Error';
    }

    public function getFeaturedProducts() {
        $products = Product::latest()->take(4)->get();
        foreach ($products as $key => $value) {
            $attachment = explode('|', $value->attachment);
            $value->attachment = $attachment;
            $products[$key] = $value;
        }
        return CustomeResponse::ResponseMsgWithData("Successful", 200, $products);
    }

    public function getAllProducts() {
        $products = Product::where('status', 1)->get();
        foreach ($products as $key => $value) {
            $attachment = explode('|', $value->attachment);
            $value->attachment = $attachment;
            $products[$key] = $value;
        }
        return CustomeResponse::ResponseMsgWithData("Successful", 200, $products);
    }

    public function getProduct(Request $request) {
        $product = Product::find(request()->route('id'));
        if (is_null($product)) {
            return CustomeResponse::ResponseMsgOnly("Resource not found!", 404);
        }
        $attachment = explode('|', $product->attachment);
        $product->attachment = $attachment;
        return CustomeResponse::ResponseMsgWithData("Successful", 200, $product);
    }
}
