<?php

namespace App\models;

use Illuminate\Database\Eloquent\Model;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Eloquent;

class Category extends Eloquent
{
    protected $table = 'category';
    protected $primaryKey = 'id';

    protected $fillable = [
        'id', 'category_name', 'created_at', 'updated_at'
    ];

    public function subCategories() {
        return $this->hasMany('App\Models\SubCategory');
    }
}
