<?php

namespace App\Http\Controllers;

use App\CompanyBasicInfo;
use App\CompanyDetails;
use App\User;
use Illuminate\Http\Request;

class CompanyController extends Controller
{
    /*
     * method for get company basic info by user id
     * */
    public function companyBasic(User $user)
    {
        return response()->json(CompanyBasicInfo::where(['user_id'=>$user->id])->first(),200);
    }

    /*
     * method for get company basic by display name
     * */
    public function companyBasicByDisplayName($display_name)
    {
        return response()->json(CompanyBasicInfo::where(['display_name'=>$display_name])->first(),200);
    }
    /*
     * method for get company details by user io
     * */
    public function companyDetails(User $user)
    {
        return response()->json(CompanyDetails::where(['user_id'=>$user->id])->first(),200);
    }
}
