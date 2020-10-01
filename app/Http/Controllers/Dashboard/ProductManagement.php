<?php

namespace App\Http\Controllers\Dashboard;

use App\CustomClass\CustomeResponse;
use App\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Controller;
use App\Product;
use App\ProductImage;
use stdClass;

class ProductManagement extends Controller
{
    public function __construct()
    {
        $this->code = 401;
        $this->message = 'System Error..!';
    }




    public function addNewProduct(Request $request)
    {
        $validator = Validator::make($request->json()->all(), [
            'product_name' => 'required',
            'description' => 'required',
            'specifications' => 'required',
            'price' => 'required',
            'aqty' => 'required',
            'images' => 'required',
            'tags' => 'required',
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
        $product->sale_price = $request->sale_price;
        $product->aqty = $request->aqty;
        $product->status = 0;
        $product->visibility = 0;

        if ($product->save()) {
            $productImgObj = new stdClass();
            foreach ($request->images as $key => $value) {
                $productImgObj->$key['product_id'] =$product->id;
                $productImgObj->$key['image_url'] = $value;
            }

            ProductImage::insert(json_decode(json_encode($productImgObj), TRUE));
            $this->code = 200;
            $this->message = 'Supplier Created Successfully';
        }

        return CustomeResponse::ResponseMsgOnly($this->message, $this->code);
    }
    
    public function updateNewProduct(Request $request)
    {
    }

    public function getProducts(Request $request)
    {
    }

    public function getAProduct(Request $request)
    {
    }
}
