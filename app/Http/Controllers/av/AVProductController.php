<?php

namespace App\Http\Controllers\av;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\CustomClass\CustomeResponse;
use App\Product;
use App\Models\Category;
use App\Models\SubCategory;
use Carbon\Carbon;

class AVProductController extends Controller {

    public function __construct() {
        $this->code = 500;
        $this->message = 'Internal Server Error';
    }

    public function getFeaturedProducts() {
        $products = Product::where('status', 1)->orderBy('created_at', 'desc')
            ->take(4)->get();
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

        $category = Category::find($product->category_id);
        if (!empty($category)) {
            $product->category_name = $category->category_name;
        }

        $subCategory = SubCategory::find($product->sub_category_id);
        if (!empty($subCategory)) {
            $product->sub_category_name = $subCategory->name;
        }

        return CustomeResponse::ResponseMsgWithData("Successful", 200, $product);
    }

    // Related products for detailed products and inquery view product
    public function getRelatedProducts(Request $request) {
        $subCategoryId = request()->route('subCategoryId');
        $productId = $request->query('productId');
        $products = Product::where('status', 1)
            ->where('id', '!=', $productId)
            ->where('sub_category_id', $subCategoryId)
            ->orderBy('created_at', 'desc')
            ->take(4)->get();
        
        foreach ($products as $key => $value) {
            $attachment = explode('|', $value->attachment);
            $value->attachment = $attachment;
            $products[$key] = $value;
        }
        return CustomeResponse::ResponseMsgWithData("Successful", 200, $products);
    }

    // You May Like products for detailed products and inquery view product
    public function getYouMayLikeProducts(Request $request) {
        $categoryId = request()->route('categoryId');
        $productId = $request->query('productId');
        $products = Product::where('status', 1)
            ->where('id', '!=', $productId)
            ->where('category_id', $categoryId)
            ->orderBy('created_at', 'desc')
            ->take(4)->get();
        
        foreach ($products as $key => $value) {
            $attachment = explode('|', $value->attachment);
            $value->attachment = $attachment;
            $products[$key] = $value;
        }
        return CustomeResponse::ResponseMsgWithData("Successful", 200, $products);
    }

    public function searchProducts(Request $request) {
        $searchQueryName = $request->get('name');
        $searchQueryCatId = $request->get('category_id');

        $products = array();
        
        if ($searchQueryCatId == '0') {
            $products = Product::where('status', 1)
                ->where('product_name', 'like', "%{$searchQueryName}%")
                ->get();
        } else {
            $products = Product::where('status', 1)
                ->where('product_name', 'like', "%{$searchQueryName}%")
                ->where('category_id', '=', $searchQueryCatId)
                ->get();
        }
        
        foreach ($products as $key => $value) {
            $attachment = explode('|', $value->attachment);
            $value->attachment = $attachment;
            $products[$key] = $value;
        }
        return CustomeResponse::ResponseMsgWithData("Successful", 200, $products);
    }

}
