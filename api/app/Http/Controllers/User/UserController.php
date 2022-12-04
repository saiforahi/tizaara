<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Product;
use App\ProductFavorite;
use App\VerifyRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:users,admins');
    }

    public function index()
    {
        //
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        //
    }

    public function show($id)
    {
        //
    }

    public function edit($id)
    {
        //
    }

    public function update(Request $request, $id)
    {
        //
    }

    public function destroy($id)
    {
        //
    }

    /*
     * method for store verify request
     * */
    public function verifyRequestStore(Request $request)
    {
        Validator::make($request->all(),[
            'message'=>'required',
        ])->validate();
        $user = auth()->user();
        $basic = $user->companyBasicInfo;
        $data = [
            'company_basic_info_id'=>$basic->id,
            'message'=>$request->message,
        ];
        $ss = $user->verifyRequest()->create($data);
        if(isset($ss->id)) return response()->json(['status'=>'success','message'=>'successfully stored'],200);
        else return response()->json(['status'=>'error','message'=>'Invalid request try again'],200);
    }

    public function verifyRequests()
    {
        $rs =VerifyRequest::with([
            'user','companyBasicInfo'
        ])->orderBy('id','DESC')->paginate(20);
        return response()->json($rs,200);
    }
    /*
     * method for store verify request
     * */
    public function verify(VerifyRequest $verifyRequest, $status)
    {
        $verifyRequest->update(['status'=>$status]);
        return response()->json(['status'=>'success','message'=>'successfully status change'],200);
        // $ss= $verifyRequest->companyBasicInfo()->update(['is_verified'=>$status]);
        // if(isset($ss)) return response()->json(['status'=>'success','message'=>'successfully status change'],200);
        // else return response()->json(['status'=>'error','message'=>'Invalid request try again'],200);
    }
}
