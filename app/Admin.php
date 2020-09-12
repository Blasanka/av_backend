<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Foundation\Auth\AdminAuth as Authenticatable;
use Illuminate\Support\Facades\Hash;

class Admin extends Authenticatable implements JWTSubject
{
    protected $table = 'admin';
    protected $primaryKey = 'id';

    protected $fillable = [
        "id", "username", "email", "password", "nic", "address", "mobile", "status", "created_at", "updated_at"
    ];

     /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password','remember_token'
    ];

    public static function create($admin){
        return self::insert($admin);
    }

    public static function checkApproveStaus($email) {
        $result = self::select('email')
                    ->where('email', $email)
                    ->where('status', 1)
                    ->first();

        if($result != null){
            return true;
        }
    
        return false;
            
    }

    public static function getUsername($email){
        $result = self::select('username')
                    ->where('email', $email)
                    ->first();

        if($result != null){
            return $result->username;
        }
    
        return false;
            
    }

    public static function getAdmin($id){
        $result = self::select("id", "username", "email", "status", "created_at", "updated_at")
                    ->where('id', $id)
                    ->where('status', 1)
                    ->first();

        if($result != null){
            return $result;
        }
    
        return false;
            
    }

    public static function updateDetails($id, $admin){
        $result = self::where('id',$id)
                    ->update($admin);
        return $result;
    }

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims() {
        return [];
    }

    public function setPasswordAttribute($password) {
        if ( !empty($password) ) {
            $this->attributes['password'] = Hash::make($password);
        }
    }
}
