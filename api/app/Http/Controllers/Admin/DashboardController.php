<?php

namespace App\Http\Controllers\Admin;

use App\Area;
use App\Brand;
use App\Category;
use App\City;
use App\Country;
use App\Division;
use App\Http\Controllers\Controller;
use App\MembershipPlan;
use App\Product;
use App\Product_group;
use App\ProductFavorite;
use App\ProductReview;
use App\Quotation;
use App\SubCategory;
use App\Subscriber;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use mysql_xdevapi\Exception;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admins');
    }

    /*
     * method for get admin dashboard information
     * */
    public function dashboard(): \Illuminate\Http\JsonResponse
    {
        $users = User::all(['id','account_type','status','membership_plan_id']);
        $both = $users->where('account_type',0)->count();
        $pending_both = $users->where('account_type',0)->where('status',1)->count();
        $total_product = Product::count();
        $pending_product = Product::where('is_approved',0)->count();
        /*date wise sales amount count*/
        $rfqs=DB::table('quotations')
            ->whereDate('created_at', '>', Carbon::now()->subDays(30))
            ->select(DB::Raw('DATE(created_at) day'), DB::raw('count(id) as total'))
            ->groupBy('day')
            ->pluck('total','day');
        $registration=DB::table('users')
            ->whereDate('created_at', '>', Carbon::now()->subDays(30))
            ->select(DB::Raw('DATE(created_at) day'), DB::raw('count(id) as total'))
            ->groupBy('day')
            ->pluck('total','day');

        return response()->json([
            'total_product'=>$total_product,
            'approved_product'=>$total_product - $pending_product,
            'pending_product'=>$pending_product,
            'product_groups'=>Product_group::count(),
            'brands'=>Brand::count(),
            'categories'=>Category::count(),
            'sub_categories'=>SubCategory::count(),
            'country'=>Country::count(),
            'division'=>Division::count(),
            'city'=>City::count(),
            'area'=>Area::count(),
            'return_order'=>0,
            'total_order'=>0,
            'pending_order'=>0,
            'canceled_order'=>0,
            'both'=>$both,
            'suppliers'=>$users->where('account_type',1)->count()+$both,
            'pending_suppliers'=>$users->where('status',1)->where('account_type',1)->count()+$pending_both,
            'ban_users'=>$users->where('status',3)->count(),
            'premium_users'=>$users->where('membership_plan_id','!=',null)->count(),
            'guest_users'=>$users->where('membership_plan_id','==',null)->count(),
            'customers'=>$users->where('account_type',2)->count()+$both,
            'pending_customers'=>$users->where('status',1)->where('account_type',2)->count()+$pending_both,
            'membership_plans'=>MembershipPlan::count(),
            'total_rfq'=>Quotation::count(),
            'pending_rfq'=>Quotation::where(['status'=>0,'is_cancel'=>0])->count(),
            'canceled_rfq'=>Quotation::where(['is_cancel'=>1])->count(),
            'reviews'=>ProductReview::count(),
            'total_sales'=>0,
            'today_sales'=>0,
            'total_revenue'=>0,
            'today_revenue'=>0,
            'last_month_revenue'=>0,
            'total_transaction'=>0,
            'favorites'=>ProductFavorite::count(),
            'subscribers'=>Subscriber::count(),
            'rfq'=>$rfqs,
            'registration'=>$registration,
        ]);
    }
}
