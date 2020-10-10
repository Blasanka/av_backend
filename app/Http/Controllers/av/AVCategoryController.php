<?php

namespace App\Http\Controllers\av;

use App\Http\Controllers\Controller;
use DB;
use Illuminate\Http\Request;
use App\CustomClass\CustomeResponse;
use App\Models\Category;
use App\Models\SubCategory;

class AVCategoryController extends Controller
{
    // Category functions
    public function getAllCategories() {
        $categories = Category::all();
        return CustomeResponse::ResponseMsgWithData("Successful", 200, $categories);
    }
    
    public function getCategory(Request $request) {
        $category = Category::find(request()->route('id'));
        return CustomeResponse::ResponseMsgWithData("Successful", 200, $category);
    }

    // Sub category functions
    public function getSubCategory(Request $request) {
        $subCategory = DB::table('sub_category')
            ->where('sub_category.id', request()->route('id'))
            ->join('category', 'sub_category.category_id', '=', 'category.id')
            ->select('sub_category.*', 'category.category_name')
            ->first();
        return CustomeResponse::ResponseMsgWithData("Successful", 200, $subCategory);
    }

    public function getAllSubCategories() {
        // $subCategories = Category::with('subCategories')->get();
        $subCategories = DB::table('sub_category')
            ->join('category', 'sub_category.category_id', '=', 'category.id')
            ->select('sub_category.*', 'category.category_name')
            ->get();
        return CustomeResponse::ResponseMsgWithData("Successful", 200, $subCategories);
    }
}
