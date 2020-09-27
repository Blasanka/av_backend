<?php

namespace App\Http\Controllers\Dashboard\supplier;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\CustomClass\CustomeResponse;
use App\Product;
use Carbon\Carbon;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Log;
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
        $image = $request->allFiles();
        
        $destinationPath = "public/images/products";
        foreach ($image as $key => $value) {
            foreach ($value as $i => $file) {
                if ($request->hasFile('attachment')) {
                    $imageName = $file->getClientOriginalName();
                    $path = $file->storeAs($destinationPath, $imageName);
                    if (count($value) > 1) {
                        if ($i == count($value)-1) {
                            $attachment .= 'images/products/'.$imageName;
                        } else
                            $attachment .= 'images/products/'.$imageName.'|';
                    } else {
                        $attachment = 'images/products/'.$imageName;
                    }
                }
            }
        }
        // info($request->images);
        $this->code = 200;
        $this->message = "Successfully images stored";
        $this->data = $attachment;
        return CustomeResponse::ResponseMsgWithData($this->message, $this->code, $this->data);

    }
    
    public function updateProduct(Request $request) {
        $validator = Validator::make($request->json()->all(), [
            'product_name' => 'required',
            'description' => 'required',
            'specifications' => 'required',
            'price' => 'required',
            'aqty' => 'required',
            'color' => 'required',
            // 'attachment' => 'required',
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
        $product->attachment = rtrim($request->attachment, '|');

        if ($product::where('id', $request->id)->update($product->toArray())) {
            $this->code = 200;
            $this->message = 'Succeefully updated!, we will review and make your product visible.';
        }
        return CustomeResponse::ResponseMsgOnly($this->message, $this->code);
    }

    public function getProduct(Request $request) {
        if (!empty($request->id)) {
            $product = Product::find($request->id);
            if (!is_null($product)) {
                $attachment = explode('|', $product->attachment);
                $product->attachment = $attachment;
                return CustomeResponse::ResponseMsgWithData("Successful", 200, $product);
            } else {
                return CustomeResponse::ResponseMsgOnly("Not found", 404);
            }
        } else {
            return CustomeResponse::ResponseMsgOnly("Bad Request", 403);
        }
    }

    public function getAllProducts() {
        $products = Product::all();
        foreach ($products as $key => $value) {
            $attachment = explode('|', $value->attachment);
            $value->attachment = $attachment;
            $products[$key] = $value;
        }
        return CustomeResponse::ResponseMsgWithData("Successful", 200, $products);
    }

    public function deleteProduct(Request $request) {
        if (!empty($request->id)) {
            $data = Product::where('id', $request->id)->delete();
            return CustomeResponse::ResponseMsgOnly("Successful", 200);
        } else {
            return CustomeResponse::ResponseMsgOnly("Bad Request", 403);
        }
    }
}
