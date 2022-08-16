<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SellerController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth:admins');
    }

    public function index()
    {
        return DB::table('users')->whereIn('account_type', [0, 1])->get();
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

    public function updateStatus(Request $request)
    {
        $this->validate($request, [
            'id' => 'required',
            'status' => 'required',
        ]);
        DB::table('users')->where('id', $request->id)->update([
            'status' => $request->status
        ]);
        return $request->all();
    }

    public function destroy($id)
    {
        //
    }
    /*
     * method for seller ban and unban
     * */
    public function statusChange(User $user)
    {
        if ($user->status ==2) $user->update(['status'=>3]);
        else $user->update(['status'=>2]);
        return response()->json(['status'=>'success','message'=>'Successfully change status'],200);
    }
}
