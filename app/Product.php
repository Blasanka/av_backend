<?php

namespace App;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Eloquent;
use Illuminate\Support\Facades\Hash;

class Product extends Eloquent
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $table = 'product';
    protected $primaryKey = 'id';

    protected $fillable = [
        'id', 'product_name','description','specifications','color','price', 'sale_price', 'aqty', 'attachment', 'visibility', 'status', 'created_at', 'updated_at'
    ];

    public static function Create_supplier($Supplier){
        return self::insert($Supplier);
    }

    public static function checkApproveStaus($email){
        $result = self::select('email')
                    ->where('email',$email)
                    ->where('status', 1 )
                    ->first();

        if($result != null){
            return true;
        }
    
        return false;
            
    }

    public static function getUsername($email){
        $result = self::select('username')
                    ->where('email',$email)
                    ->first();

        if($result != null){
            return $result->username;
        }
    
        return false;
            
    }

    public static function get_single_supplier($id){
        $result = self::select('username','email','shopname','bis_info','mobile','legal_name','address','personalic','br_num','nic_copy')
                    ->where('id',$id)
                    ->where('status', 1 )
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

    public function setPasswordAttribute($password)
{
    if ( !empty($password) ) {
        $this->attributes['password'] = Hash::make($password);
    }
}

}