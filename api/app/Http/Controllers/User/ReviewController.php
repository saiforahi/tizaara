<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Product;
use App\ProductRating;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ReviewController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:users',['except' => ['ratingReviews',]]);
    }

    /*
     * method for store review and rating
     * */
    public function store(Request $request,Product $product)
    {
        Validator::make($request->all(),[
            'rating'=>'required|numeric',
            'review'=>'required|string|max:250'
        ])->validate();
        $rating = $product->productRating()->where(['user_id'=>auth()->id(),'product_id'=>$product->id])->first();

        if (isset($rating->id)) $rating->update(['rating'=>$request->rating]);
        else $product->productRating()->create(['user_id'=>auth()->id(),'rating'=>$request->rating]);

        $ss = $product->productReviews()->create(['user_id'=>auth()->id(),'comment'=>$request->review]);
        if (isset($ss->id)) return response()->json('Thank your for your review',200);
        return response()->json('Invalid request',404);
    }

    /*
     * method for get all review and rating by product id
     * */
    public function ratingReviews(Product $product)
    {
        $reviews = $product->productReviews()->with([
            'user'=>function($q){$q->select('id','first_name', 'last_name');}
        ])->where(['product_id'=>$product->id,'is_approved'=>1])->get();
        $ratings = $product->productRating;
        return response()->json(['reviews'=>$reviews,'ratings'=>$ratings],200);
    }

}
