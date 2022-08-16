<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Traits\FileUpload;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AdminProfileController extends Controller
{
    use FileUpload;
    public function __construct()
    {
        $this->middleware('auth:admins');
    }
    /*
     * method for update profile image
     * */
    public function imageChange(Request $request)
    {
        //return response()->json($request->all());
        Validator::make($request->all(),[
            'image'=>'required|mimes:jpg,png,jpeg'
        ])->validate();

        if ($request->file('image')) {
            $photo = $this->imageUpload( $request->file('image'), 'upload/admin', 290, 300);
            auth()->user()->update(['photo'=>$photo]);
            if(auth()->user()->photo) $this->removeImage(auth()->user()->photo);
            return response()->json(['status'=>'success','message'=>'Successfully updated','data'=>$photo],200);
        }
        return response()->json(['status'=>'success','message'=>'Invalid request'],404);
    }
}
