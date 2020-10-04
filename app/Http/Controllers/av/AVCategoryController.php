<?php

namespace App\Http\Controllers\av;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\CustomClass\CustomeResponse;
use App\Category;
use App\SubCategory;

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
        $subCategory = SubCategory::find(request()->route('id'));
        return CustomeResponse::ResponseMsgWithData("Successful", 200, $subCategory);
    }

    public function getAllSubCategories() {
        $subCategories = SubCategory::all();
        return CustomeResponse::ResponseMsgWithData("Successful", 200, $subCategories);
    }
}
