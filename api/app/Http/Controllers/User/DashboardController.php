<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Product;
use App\ProductFavorite;
use App\ProductRating;
use App\ProductReview;
use App\Quotation;
use App\QuotationUser;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:users');
    }

    /*
     * method for get supplier related information
     * */
    public function suppliers(): \Illuminate\Http\JsonResponse
    {
        $product=Product::where(['user_id'=>auth()->id()])->get(['id','user_id','is_approved']);
        $productIds=$product->pluck('id');
        $quotations = QuotationUser::where(['user_id'=>auth()->id()])->get(['id','user_id','status']);
        return response()->json([
            'total_product'=>$product->count(),
            'pending_product'=>$product->where('is_approved',0)->count(),
            'approved_product'=>$product->where('is_approved',1)->count(),
            'total_quotation'=>$quotations->count(),
            'pending_quotation'=>$quotations->where('status',0)->count(),
            'canceled_quotation'=>$quotations->where('status',2)->count(),
            'product_favorite'=>ProductFavorite::whereIn('product_id',$productIds)->count(),
            'average_rating'=>ProductRating::whereIn('product_id',$productIds)->avg('rating'),
            'total_review'=>ProductReview::whereIn('product_id',$productIds)->count(),
        ],200);
    }

    /*
     *
     * method for get buyer related information
     *
     * */
    public function buyers(): \Illuminate\Http\JsonResponse
    {
        $quotations = Quotation::where(['user_id'=>auth()->id()])->get(['id','user_id','status','is_cancel']);
        return response()->json([
            'total_quotation'=>$quotations->count(),
            'pending_quotation'=>$quotations->where(['status'=>1,'is_cancel'=>0])->count(),
            'canceled_quotation'=>$quotations->where('is_cancel',1)->count(),
            'favorite_product'=>ProductFavorite::where('user_id',auth()->id())->count(),
            'total_rated_product'=>ProductRating::where('user_id',auth()->id())->count(),
            'total_reviewed_product'=>ProductReview::where('user_id',auth()->id())->count(),
        ],200);
    }
}
