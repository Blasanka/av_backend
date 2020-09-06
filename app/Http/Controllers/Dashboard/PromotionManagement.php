<?php

namespace App\Http\Controllers\Dashboard;

use App\CustomClass\CustomeResponse;
use App\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Controller;


class PromotionManagement extends Controller
{
    public function __construct()
    {
        $this->code = 401;
        $this->message = 'System Error..!';

    }
    
    public function addNewPromotion(Request $request){

    }

    public function viewAllPromotion(Request $request){

    }

    public function assignItemsForPromotion(Request $request){

    }




}
