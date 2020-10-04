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
        'id', 'name', 'created_at', 'updated_at'
    ];

    public function subCategories() {
        return $this->morphMany('App\Models\SubCategory', 'subCategories');
    }
}