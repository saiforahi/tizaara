<?php

namespace App\Http\Controllers\User;

use App\AnnualRevenue;
use App\CompanyTradeInfo;
use App\ExportPercent;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CompanyTradeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:users');
    }

    /*
     * method for get annual revenues and export percents
     * */
    public function aREPercents()
    {
        return [
            'annual_revenues'=>AnnualRevenue::all(['id','revenue']),
            'export_percents'=>ExportPercent::all(['id','percent']),
        ];
    }
    /*
     * method for get all company trades info
     * */
    public function trands()
    {
        $company = auth()->user()->companyBasicInfo;
        if (!isset($company)) return [];
        return CompanyTradeInfo::with([
            'annualRevenue'=>function($q){$q->select('id','revenue'); },
            'exportPercent'=>function($q){$q->select('id','percent'); },
        ])->where('company_id',$company->id)->get();
    }

    /*
     * method for store trade info
     * */
    public function store(Request $request)
    {
        /*request data validate*/
        $this->val($request->all());
        $company = auth()->user()->companyBasicInfo;
        $data = $request->all();
        $data +=[
            'created_by'=>auth()->id(),
            'company_id'=>$company?$company->id:null,
        ];
        $ss = CompanyTradeInfo::create($data);
        if (isset($ss)) return response()->json(['status'=>'success','message'=>'Successfully store'],200);
        return response()->json(['status'=>'fail','message'=>'Invalid request'],404);

    }

    /*
     * method for trade information update
     * */
    public function update(Request $request, CompanyTradeInfo $companyTradeInfo)
    {
        /*request data validate*/
        $this->val($request->all());
        /*authorize check*/
        $company = auth()->user()->companyBasicInfo;
        if (!isset($company->id) || $company->id !=$companyTradeInfo->company_id) return response()->json(['status'=>'fail','message'=>'you are Unauthorized for this operation'],403);


        $data = $request->all();
        $data +=[
            'updated_by'=>auth()->id(),
        ];
        $ss = $companyTradeInfo->update($data);
        if (isset($ss)) return response()->json(['status'=>'success','message'=>'Successfully updated'],200);
        else return response()->json(['status'=>'fail','message'=>'Invalid request'],404);

    }

    /*
     * method for company trade info remove
     * */
    public function remove(CompanyTradeInfo $companyTradeInfo)
    {
        $company = auth()->user()->companyBasicInfo;
        if (!isset($company->id) || $company->id !=$companyTradeInfo->company_id) return response()->json(['status'=>'fail','message'=>'you are Unauthorized for this operation'],403);

        $ss = $companyTradeInfo->delete();
        if (isset($ss)) return response()->json(['status'=>'success','message'=>'Successfully remove'],200);
        else return response()->json(['status'=>'fail','message'=>'Invalid request'],404);

    }
    /*
     * private method for validate trade request info
     * */
    private function val(array $data)
    {
        return Validator::make($data,[
            'export_started_year'=>'required|numeric',
            'annual_revenue_id'=>'required|not_in:0|exists:annual_revenues,id',
            'export_percent_id'=>'required|not_in:0|exists:export_percents,id',
        ])->validate();
    }
}
