<?php
/*
------------------------------------------------------------------------------------------------
Created By    : Vijay Felix Raj C
Email Address : vijayfelixraj@gmail.com
Created Date  : 19.07.2017
Purpose       : To handle User Model
------------------------------------------------------------------------------------------------
*/
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Model
{
    protected $table = 'user';
    public $timestamps = true;
	public $incrementing=false;
	use SoftDeletes;

    protected $fillable = array('id','fullname','profile_picture','address', 'auth_key', 'register_mode', 'been_there_status', 'status', 'registered_on', 'mobile', 'email', 'password','status');

    /**
     * To upload files
     */
    public static function fileUpload($request, $file, $id = false)
    {
     	if (!empty($id)) {
            $fileDetails = Category::where('id', '=', $id)->get();
            $oldFileName = $fileDetails[0]->profile_picture;
            if ($request->hasFile($file) && $request->file($file)->getSize() > 0) {
                $destinationPath = base_path() . "/uploads/user/";
                if (!file_exists($destinationPath)) {
                    mkdir($destinationPath, 0700);
                }
                // To unlink the old file
                if ($oldFileName) {
                        @unlink($destinationPath.'/'.$oldFileName);
                }
                $filename = $request->file($file)->getClientOriginalName();
                $request->file($file)->move($destinationPath, $filename);
            }
        } else {
            if ($request->hasFile($file) && $request->file($file)->getSize() > 0) {
                $destinationPath = base_path() . "/uploads/user/";
                if (!file_exists($destinationPath)) {
                    mkdir($destinationPath, 0700);
                }
                $filename   = $request->file($file)->getClientOriginalName();
                $request->file($file)->move($destinationPath, $filename);
            }
        }
        return $filename;   
    }

    /**
     * To get the username
     * 
     * @return array
     **/
    public static function getUserNameById($id) {
        $userDetails = User::where('id', '=', $id)->first();
		$name = ($userDetails) ? $userDetails->fullname : ""; 
        return $name;
    }
}
