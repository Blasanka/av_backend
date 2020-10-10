<?php

namespace App\models;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Eloquent;

class SubCategory extends Eloquent
{
    protected $table = 'sub_category';
    protected $primaryKey = 'id';
    protected $foreignKey = 'category_id';

    protected $fillable = [
        'id', 'name', 'category_id', 'created_at', 'updated_at'
    ];
    
    public function categories() {
        return $this->belongsTo('App\Models\Category', 'id');
    }
}
