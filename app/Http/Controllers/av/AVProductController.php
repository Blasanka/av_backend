<?php

namespace App\Http\Controllers\av;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\CustomClass\CustomeResponse;
use App\Product;
use Carbon\Carbon;

class AVProductController extends Controller
{

    public function __construct() {
        $this->code = 500;
        $this->message = 'Internal Server Error';
    }

    public function getProduct(Request $request) {
    }

    public function getFeaturedProducts() {
        $products = Product::latest()->take(4)->get();
        return CustomeResponse::ResponseMsgWithData("Successful", 200, $products);
    }

    public function getAllProducts() {
        $products = Product::where('status', 1)->get();
        return CustomeResponse::ResponseMsgWithData("Successful", 200, $products);
    }
}
