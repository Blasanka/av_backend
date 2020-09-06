<?php

namespace App\Http\Controllers\Dashboard;

use App\CustomClass\CustomeResponse;
use App\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Controller;


class ReviewManagement extends Controller
{
    public function __construct()
    {
        $this->code = 401;
        $this->message = 'System Error..!';

    }
    

    public function getAllReviews(Request $request){

    }

    public function getAReview(Request $request){

    }

    public function updateReviews(Request $request){

    }

}
