<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\MembershipPlan;
use App\User;
use Illuminate\Support\Facades\Auth;

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

    /*
     * method for get membership plans
     * */
    public function getAll()
    {
        return MembershipPlan::all();
    }
}
