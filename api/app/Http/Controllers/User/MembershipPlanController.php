<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\MembershipPlan;
use App\User;
use App\UserMembershipPlan;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class MembershipPlanController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:users');
    }

    public function register($id)
    {
        $plan = MembershipPlan::query()->findOrFail($id);
        $user = User::query()->findOrFail(Auth::user()->id);
        $user->membershipPlan()->associate($plan);

        return $user->save();
    }

    public function registration(Request $req){
        DB::beginTransaction();
        try{
            if(!UserMembershipPlan::where(['user_id'=>Auth::user()->id,'plan_id'=>$req->plan_id])->exists() && Auth::user()->account_type!=2){
                $plan = MembershipPlan::query()->findOrFail($req->plan_id);
                $user = User::query()->findOrFail(Auth::user()->id);
                $user->membership_plan_id=$plan->id;
                $user->save();
                $user_membership_plan=UserMembershipPlan::create([
                    'user_id'=>$user->id,
                    'plan_id'=>$plan->id
                ]);
                DB::commit();
                return response()->json(['success'=>true,'message'=>'Request Submitted, Please complete payment to avail the membership','data'=>$user_membership_plan]);
            }
            else{
                return response()->json(['success'=>false,'message'=>'You already have a membership']);
            }
        }
        catch(Exception $e){
            DB::rollback();
            return response()->json(['success'=>false,'message'=>'Request Failed','error'=>$e->getMessage()],400);
        }
    }

    /*
     * method for get membership plans
     * */
    public function getAll()
    {
        return MembershipPlan::all();
    }
}
