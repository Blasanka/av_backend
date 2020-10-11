<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Eloquent;

class VerifyCode extends Eloquent
{
    protected $table = 'verify_code';
    protected $primaryKey = 'id';

    protected $fillable = [
        'id', 'mobile_number', 'verify_code', 'created_at', 'updated_at'
    ];
    
    public function verifiyCode() {
        return $this->hasOne('App\Models\VerifyCode');
    }
}
