<?php

namespace App\Http\Controllers;

use App\MembershipPlan;
use App\Traits\FileUpload;
use Illuminate\Http\Request;

class MembershipPlanController extends Controller
{
    use FileUpload;

    public function __construct()
    {
        $this->middleware('auth:admins', ['except' => ['index','onlyMemberships']]);
    }

    public function index()
    {
        return MembershipPlan::query()->latest()->paginate(9);
    }

    /*
     *
     * method for get only membership plan name
     * */
    public function onlyMemberships()
    {
        return response()->json(MembershipPlan::all(['id','name']),200);
    }
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|max:50|unique:membership_plans',
            'no_of_allowed_products' => 'required',
            'no_of_allowed_keywords' => 'required',
        ]);

        $data = $request->all();

        return MembershipPlan::query()->create($data);
    }

    public function show($id)
    {
        return MembershipPlan::query()->findOrFail($id);
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'name' => 'required|max:50|unique:membership_plans,name,' . $id,
            'no_of_allowed_products' => 'required',
            'no_of_allowed_keywords' => 'required',
        ]);

        $plan = MembershipPlan::query()->findOrFail($id);
        $data = $request->all();
        $plan->update($data);

        return MembershipPlan::query()->findOrFail($id);
    }

    public function destroy($id)
    {
        $plan = MembershipPlan::query()->findOrFail($id);
        $plan->delete();

        return response()->json(['result' => 'Success', 'message' => 'Package has been deleted'], 200);
    }
}
