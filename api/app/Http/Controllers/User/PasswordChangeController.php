<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class PasswordChangeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:users');
    }

    /*
     * method for password change
     * */
    public function chnagePassword(Request $request)
    {
        Validator::make($request->all(),[
            'old_password'=>'required',
            'password'=>'required|confirmed|min:6'
        ])->validate();
        $hashpassword = auth()->user()->password;

        if(Hash::check($request->old_password, $hashpassword)){
            if (!Hash::check($request->password,$hashpassword)){
                auth()->user()->update(['password'=>Hash::make($request->password)]);

                $msg = "Successfully change the password";
                return response()->json(['status'=>'success','message'=>$msg],200);

            }else{
                $msg ="Error! New password cannot be the same as old password";
                return response()->json(['status'=>'error','message'=>$msg],200);
            }
        }else{
            $msg ="Error! old password not match!!";
            return response()->json(['status'=>'error','message'=>$msg],200);
        }
    }
}
