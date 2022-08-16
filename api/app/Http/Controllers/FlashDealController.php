<?php

namespace App\Http\Controllers;

use App\FlashDeal;
use App\FlashDealProduct;
use App\Product;
use App\ProductRequest;
use App\Traits\FileUpload;
use App\Traits\Slug;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class FlashDealController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admins', ['except' => ['flashDealList']]);
    }

    use FileUpload;
    use Slug;

    public function index(Request $request)
    {
        return DB::table('flash_deals')->select('title', 'id', 'banner', 'start_date', 'end_date', 'slug')->get();
    }

    public function flashDealList()
    {
        $flash_deals =FlashDeal::with([
            'color'=>function($q){$q->select('id','name'); },
            'flashDealProducts'=>function($r){$r->with([
                'product'=>function($s){$s->with([
                    'currency'=>function($q){$q->select('id','symbol');},
                    'price_variation'=>function($q){$q->select('id','product_id','off_price','min_qty','max_qty');},
                    'product_stock',
                ])->get(); }
            ])->get();},
        ])->where('status',1)->where('start_date','<=',Carbon::today())->where('end_date','>=',Carbon::today())->inRandomOrder()->get();
        return response()->json($flash_deals,200);
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'title' => 'required|max:255',
            'dateRange' => 'required',
        ]);
        $insert = new FlashDeal();
        $insert->title = $request->title;
        $insert->bg_color = $request->bg_color;
        $insert->text_color = $request->text_color;
        if ($request->banner != '') {
            $insert->banner = $this->saveImages($request, 'banner', 'upload/flashdeals/', 1920, 500);
        }
        $insert->slug = $this->slugText($request, 'title');
        $insert->start_date = date('Y-m-d h:i:s', strtotime($request->dateRange['startDate']));
        $insert->end_date = date('Y-m-d h:i:s', strtotime($request->dateRange['endDate']));
        $insert->save();


        foreach ($request->product_list as $dis) {
            $product = new FlashDealProduct();
            $product->flash_deal_id = $insert->id;
            $product->product_id = $dis['id'];
            $product->discount = $dis['discount'];
            $product->discount_type = $dis['discount_type'];
            $product->save();
        }

        return $insert;

    }

    public function requestFlashDealList(Request $request)
    {
        $product = collect();
        $request = DB::table('product_requests')->where('request_type', 2)->orderBy('id', 'DESC')->get();
        foreach ($request as $item) {
            $data = DB::table('products')->where('id', $item->product_id)->first();
            $user = DB::table('users')->where('id', $item->user_id)->first();
            $status = $item->status == 0 ? 'Request' : 'Approve';
            $product->push([
                'id' => $item->id, 'image' => $data->thumbnail_img, 'product_name' => $data->name, 'user_name' => $user->first_name . ' ' . $user->last_name, 'status' => $status,
                'discount' => $item->discount, 'discount_type' => $item->discount_type,

            ]);
        }
        return $product;
    }

    public function requestFlashDealStore(Request $request)
    {
        $this->validate($request, [
            'request_id' => 'required',
            'flash_id' => 'required',
        ]);

        $product = ProductRequest::findOrFail($request->request_id);
        $insert = new FlashDealProduct();
        $insert->flash_deal_id = $request->flash_id;
        $insert->product_id = $product->product_id;
        $insert->discount = $product->discount;
        $insert->discount_type = $product->discount_type;
        $insert->save();
        $product->status = 1;
        $product->save();
        return $request->request_id;
    }

    public function statusUpdate(Request $request)
    {
        $product = FlashDeal::findOrFail($request->id);
        $product->status = (int)$request->status;
        $product->save();
    }

    public function destroy($id)
    {
        FlashDeal::findOrFail($id)->delete();
        FlashDealProduct::where('flash_deal_id', $id)->delete();
        return response()->json(['result' => 'Success', 'message' => 'Flash deals has been deleted'], 200);
    }
}
