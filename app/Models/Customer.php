<?php

namespace App\Models;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\SupplierAuth as Authenticatable;
use Illuminate\Support\Facades\Hash;

class Customer extends Authenticatable implements JWTSubject
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $table = 'customer';
    protected $primaryKey = 'id';

    protected $fillable = [
        'id', 'username', 'password', 'email', 'mobile', 'verify_code', 'full_name', 'dob', 'gender', "created_at", "updated_at"
    ];

     /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password','remember_token'
    ];

    public static function Create_supplier($Supplier){
        return self::insert($Supplier);
    }

    public static function checkApproveStaus($mobile){
        $result = self::select('mobile')
                    ->where('mobile', $mobile)
                    ->first();

        if($result != null){
            return true;
        }
    
        return false;
            
    }

    public static function getUsername($mobile){
        $result = self::select('username')
                    ->where('mobile',$mobile)
                    ->first();

        if($result != null){
            return $result->username;
        }
    
        return false;
            
    }

    public static function get_single_supplier($id){
        $result = self::select('id', 'username', 'address', 'mobile', 'full_name', 'dob', 'gender', 'nic', "created_at", "updated_at")
                    ->where('id',$id)
                    ->first();

        if($result != null){
            return $result;
        }
    
        return false;
            
    }

    public static function update_details($id, $sup_details){
        $result = self::where('id',$id)
                    ->update($sup_details);
        return $result;
    }

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }

    public function setPasswordAttribute($password) {
        if ( !empty($password) ) {
            $this->attributes['password'] = Hash::make($password);
        }
    }

    public function codeOwner() {
        return $this->belongsTo('App\Models\Customer', 'mobile');
    }
}