<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Product;
use App\ProductFavorite;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class ProductFavoriteController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:users');
    }

    /*
     * method for get auth user favorites list
     * */
    public function userFavoriteList()
    {
        $favorites = ProductFavorite::where('user_id',auth()->id())->get();
        return response()->json($favorites,200);
    }
    /*
     * method for update/remove product favorites
     * */
    public function favorite($id)
    {
        $product = Product::query()->find($id);
        if (!$product) {
            return response()->json(['result' => 'Error', 'message' => 'Product not found'], 404);
        }

        try {
            $favorite = ProductFavorite::query()
                ->where('product_id', $id)
                ->where('user_id', auth()->user()->id)->first();
            if (empty($favorite)) {
                $favorite = new ProductFavorite();
                $favorite->product()->associate($product);
                $favorite->user()->associate(auth()->user());
                $favorite->save();
            } else {
                $favorite->delete();
            }

            return response()->json(['result' => 'Ok'], 200);
        } catch (\Exception $ex) {
            return response()->json(['result' => 'Error'], 500);
        }
    }
    /*
     * method for get auth user favorite products
     * */
    public function favorites()
    {
        try {
            $favorites = ProductFavorite::query()->with('product')->where('user_id', auth()->user()->id)->get();

            return response()->json($favorites, 200);
        } catch (\Exception $ex) {
            return response()->json(['result' => 'Error'], 500);
        }
    }
}
