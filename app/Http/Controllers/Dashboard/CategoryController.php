<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\CustomClass\CustomeResponse;
use App\Category;
use App\SubCategory;

class CategoryController extends Controller
{
    
    // Manage Category
    public function addCategory(Request $request) {
        $validator = Validator::make($request->json()->all(), [
            'name' => 'required',
        ]);


        if ($validator->fails()) {
            return CustomeResponse::ResponseRequestValidationFailed();
        }

        $category = new Category();
        $category->name = $request->name;

        if ($category->save()) {
            $this->code = 200;
            $this->message = 'Successfully inserted!';
        }
        return CustomeResponse::ResponseMsgOnly($this->message, $this->code);
    }

    public function updateCategory(Request $request) {
        $validator = Validator::make($request->json()->all(), [
            'name' => 'required',
        ]);


        if ($validator->fails()) {
            return CustomeResponse::ResponseRequestValidationFailed();
        }

        $category = new Category();
        $category->name = $request->name;

        if ($category::where('id', request()->route('id'))->update($category->toArray())) {
            $this->code = 200;
            $this->message = 'Successfully updated!';
        }
        return CustomeResponse::ResponseMsgOnly($this->message, $this->code);
    }

    public function deleteCategory(Request $request) {
        $id = request()->route('id');
        if (!empty($id)) {
            $data = Category::where('id', $id)->delete();
            return CustomeResponse::ResponseMsgOnly("Succeeded", 200);
        } else {
            return CustomeResponse::ResponseMsgOnly("Bad Request", 403);
        }
    }

    // Manage SubCategory
    public function addSubCategory(Request $request) {
        $validator = Validator::make($request->json()->all(), [
            'name' => 'required',
            'category_id' => 'required',
        ]);


        if ($validator->fails()) {
            return CustomeResponse::ResponseRequestValidationFailed();
        }

        $subCategory = new SubCategory();
        $subCategory->name = $request->name;
        $subCategory->category_id = $request->category_id;

        if ($subCategory->save()) {
            $this->code = 200;
            $this->message = 'Successfully inserted!';
        }
        return CustomeResponse::ResponseMsgOnly($this->message, $this->code);
    }

    public function updateSubCategory(Request $request) {
        $validator = Validator::make($request->json()->all(), [
            'name' => 'required',
            'category_id' => 'required',
        ]);


        if ($validator->fails()) {
            return CustomeResponse::ResponseRequestValidationFailed();
        }

        $subCategory = new SubCategory();
        $subCategory->name = $request->name;
        $subCategory->category_id = $request->category_id;

        if ($subCategory::where('id', request()->route('id'))->update($subCategory->toArray())) {
            $this->code = 200;
            $this->message = 'Successfully updated!';
        }
        return CustomeResponse::ResponseMsgOnly($this->message, $this->code);
    }

    public function deleteSubCategory(Request $request) {
        $id = request()->route('id');
        if (!empty($id)) {
            $data = SubCategory::where('id', $id)->delete();
            return CustomeResponse::ResponseMsgOnly("Succeeded", 200);
        } else {
            return CustomeResponse::ResponseMsgOnly("Bad Request", 403);
        }
    }
}
