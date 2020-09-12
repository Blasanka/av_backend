<?php

namespace App\Http\Controllers\Dashboard\supplier;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\CustomClass\CustomeResponse;
use App\Product;
use Carbon\Carbon;
use Illuminate\Http\UploadedFile;
class SuplierProductController extends Controller
{

    public function __construct() {
        $this->code = 500;
        $this->message = 'Internal Server Error';
    }

    public function addNewProduct(Request $request) {
        $validator = Validator::make($request->json()->all(), [
            'product_name' => 'required',
            'description' => 'required',
            'specifications' => 'required',
            'price' => 'required',
            'aqty' => 'required',
            'color' => 'required',
            'attachment' => 'required',
        ]);


        if ($validator->fails()) {
            return CustomeResponse::ResponseRequestValidationFailed();
        }

        $product = new Product();
        $product->product_name = $request->product_name;
        $product->description = $request->description;
        $product->specifications = $request->specifications;
        $product->color	 = $request->color;
        $product->price = $request->price;
        $product->aqty = $request->aqty;
        $mytime = Carbon::now();
        $product->created_at = $mytime;
        $product->updated_at = $mytime;
        $product->status = 0;
        $product->attachment = $request->attachment;

        // if ($request->hasFile("attachment")) {
        //     $destinationPath = "public/images/products";
        //     $image = $request->file("attachment");
        //     $imageName = $image->getClientOriginalName();
        //     $path = $request->file("image")->storeAs($destinationPath, $imageName);
        //     $product->attachment = $imageName;
        // }

        if ($product->save()) {
            $this->code = 200;
            $this->message = 'Succeeded, we will review and make your product visible.';
        }
        return CustomeResponse::ResponseMsgOnly($this->message, $this->code);
    }
    
    public function addNewProductImage(Request $request) {
        $attachment = "";
        if ($request->hasFile('attachment')) {
            $destinationPath = "public/images/products";
            $image = $request->file('attachment');
            $imageName = $image->getClientOriginalName();
            $path = $request->file('attachment')->storeAs($destinationPath, $imageName);
            $attachment = $imageName;
        }
        // info($request->images);
        $this->code = 200;
        $this->message = asset("storage/images/products/$attachment");
        return CustomeResponse::ResponseMsgOnly($this->message, $this->code);

    }
    
    public function updateNewProduct(Request $request) {
    }

    public function getProduct(Request $request) {
    }

    public function getAllProducts() {
        $products = Product::all();
        return CustomeResponse::ResponseMsgWithData("Successful", 200, $products);
    }
}
