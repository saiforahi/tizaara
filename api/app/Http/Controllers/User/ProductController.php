<?php

namespace App\Http\Controllers\User;

use App\Category;
use App\Color;
use App\discount_variation;
use App\Http\Controllers\Controller;
use App\Http\Resources\ProductResource;
use App\price_variation;
use App\Product;
use App\Product_stock;
use App\ProductFavorite;
use App\ProductKeyword;
use App\ProductRequest;
use App\Traits\FileUpload;
use App\Traits\Slug;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class ProductController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:users');
    }

    use FileUpload;
    use Slug;

    public function index()
    {
        return Product::where('user_id', Auth::user()->id)->orderBy('id', 'DESC')->get();
    }

    /*
     * method for get user product uses unique categories
     * */
    public function productCategories()
    {
        $categories = Category::whereIn('id',auth()->user()->products()->pluck('category_id')->unique()->whereNotNull())->get(['id','name']);
        return response()->json($categories,200);
    }

    /**
     * @throws ValidationException
     */
    public function store(Request $request)
    {
        Validator::make($request->all(), [
            'name' => 'required|max:200',
            'added_by' => 'required|max:10',
            'weight' => 'max:100',
            'length' => 'max:10',
            'width' => 'max:10',
            'height' => 'max:10',
            'video_link' => 'max:100',
            'sku' => 'required|max:255|unique:products',
        ])->validate();

        $validation = [];
        $allowedProduct = auth()->user()->getAllowedProducts();
        $productCount = auth()->user()->products()->count();
        if ($productCount > $allowedProduct) {
            $validation[] = [
                'product' => "Your upload product limit is over. Your maximum limit is {$allowedProduct}."
            ];
        }

        $allowedKeyword = auth()->user()->getAllowedKeywords();
        $keywords = $request->get('keywords', []);
        if (count($keywords) > $allowedKeyword) {
            $validation[] = [
                'keyword' => "Your allowed keyword is not more then {$allowedKeyword}."
            ];
        }

        if (count($validation)) {
            throw ValidationException::withMessages($validation);
        }

        $colors = [];
        $photos = [];
        $feature = '';
        $flash = '';
        $thumbnail = '';
        $meta_image = '';
        if ($request->color_type == 1) {
            foreach ($request->color_image as $photo) {
                if (array_key_exists("image", $photo)) {
                    $image = $this->saveImagesVue($photo['image'][0], 'path', 'upload/product/color/', 370, 370);
                    $image = [
                        'name' => $photo['name'],
                        'image' => $image,
                    ];
                    array_push($colors, $image);
                }
            }
        }

        if ($request->photos != '') {
            foreach ($request->photos as $photo) {
                if (array_key_exists("path", $photo)) {
                    $image = $this->saveImagesVue($photo, 'path', 'upload/product/product/', 370, 370);
                    array_push($photos, $image);
                }
            }
        }


        if ($request->featured_img != '' && count($request->featured_img) != 0) {
            $feature = $this->saveImagesVue($request->featured_img[0], 'path', 'upload/product/feature/', 290, 300);
        }

        if ($request->flash_deal_img != '' && count($request->flash_deal_img) != 0) {
            $flash = $this->saveImagesVue($request->flash_deal_img[0], 'path', 'upload/product/flash_deal/', 290, 300);
        }

        if ($request->thumbnail_img != '' && count($request->thumbnail_img) != 0) {
            $thumbnail = $this->saveImagesVue($request->thumbnail_img[0], 'path', 'upload/product/thumbnail/', 290, 300);
        }

        if ($request->meta_img != '' && count($request->meta_img) != 0) {
            $meta_image = $this->saveImagesVue($request->meta_img[0], 'path', 'upload/product/meta_image/', 290, 300);
        }

        $product = new Product();
        $product->name = $request->name;
        $product->sort_desc = $request->sort_desc;
        $product->slug = $this->slugText($request, 'name');
        $product->added_by = $request->added_by;
        $product->user_id = Auth::user()->id;
        $product->category_id = $request->category_id;
        $product->subcategory_id = $request->sub_category_id;
        $product->subsubcategory_id = $request->sub_subcategory_id;
        $product->property_options = json_encode($request->properties);
        $product->brand_id = $request->brand_id;
        $product->unit = $request->unit;
        $product->weight = $request->weight;
        $product->length = $request->length;
        $product->width = $request->width;
        $product->height = $request->height;
        $product->tags = json_encode($request->tags);
        $product->product_type = $request->product_type;
        $product->photos = json_encode($photos);
        $product->thumbnail_img = $thumbnail;
        $product->featured_img = $feature;
        $product->flash_deal_img = $flash;
        $product->video_link = $request->video_link;
        $product->colors = json_encode($request->color);
        $product->color_image = json_encode($colors);
        $product->color_type = $request->color_type;
        $product->attributes = json_encode($request->attribute);
        $product->attribute_options = json_encode($request->attribute_options);
        $product->tax = $request->tax;
        $product->tax_type = $request->tax_type;
        $product->discount = $request->discount;
        $product->discount_type = $request->discount_type;
        $product->discount_variation = $request->discountMethod;
        $product->orderQtyLimit = $request->orderQtyLimit;
        $product->orderQtyLimitMax = $request->orderQtyLimitMax;
        $product->orderQtyLimitMin = $request->orderQtyLimitMin;
        $product->priceType = $request->priceType;
        $product->stockManagement = $request->stockManagement;
        $product->unit_price = $request->unit_price;
        $product->currency_id = $request->currency_id;
        $product->quantity = $request->quantity;
        $product->sku = $request->sku;
        $product->description = $request->description;
        $product->shipping_type = $request->shipping_type;
        $product->shipping_cost = $request->shipping_cost;
        $product->meta_title = $request->meta_title;
        $product->meta_description = $request->meta_description;
        $product->meta_img = $meta_image;
        $product->save();
        /*
         * colors ids find and store colors id in pivot table
         * */
        if ($request->input('color')){
            $colorIds = Color::whereIn('name',$request->color)->pluck('id');
            $product->productColors()->sync($colorIds);
        }
        /*discount variation store*/
        if ($request->discountMethod == 1) {
            $discounts=[];
            foreach ($request->tierDiscount as $dis) {
                $discounts[]=[
                    'product_id'=>$product->id,
                    'percent_off'=>$dis['value'],
                    'min_qty'=>$dis['unit'],
                    'created_at'=>now(),
                    'updated_at'=>now(),
                ];
            }
            discount_variation::insert($discounts);
        }
        /*product variation store*/
        if ($request->priceType == 2) {
            $price_vs=[];
            foreach ($request->tierPrice as $prices) {
                $price_vs[]=[
                    'product_id'=>$product->id,
                    'off_price'=>$prices['value'],
                    'min_qty'=>$prices['min_unit'],
                    'max_qty'=>$prices['max_unit'],
                    'created_at'=>now(),
                    'updated_at'=>now(),
                ];
            }
            price_variation::insert($price_vs);
        }
        /*product stock store*/
        if ($request->priceType == 1) {
            $product_stocks=[];
            foreach ($request->priceMenu as $prices) {
                $product_stocks[]=[
                    'product_id'=>$product->id,
                    'variant'=>json_encode($prices['variant']),
                    'sku'=>$this->sku($prices, $product->id),
                    'price'=>$prices['variant_price'],
                    'qty'=>$prices['quantity'],
                    'created_at'=>now(),
                    'updated_at'=>now(),
                ];
            }
            Product_stock::insert($product_stocks);
        }

        /*product keyword store*/
        $kwd=[];
        foreach ($keywords as $keyword) {
            if ($keyword['value']) {
                $kwd[]=[
                    'product_id'=>$product->id,
                    'key_name'=>$keyword['value'],
                    'created_by'=>auth()->id(),
                    'created_at'=>now(),
                    'updated_at'=>now(),
                ];
            }
        }
        if (count($kwd)>0){
            ProductKeyword::insert($kwd);
        }
        return 'done';
    }

    public function show($id)
    {
        $product = Product::with('user', 'unit', 'discount_variation_data', 'price_variation', 'productKeywords',
            'subsubcategories', 'subcategory', 'category', 'currency', 'brand', 'product_stock')
            ->find($id);
        if ($product) {
            return new ProductResource($product);
        } else {
            return response()->json(['result' => 'Error', 'message' => 'Product not found'], 404);
        }
    }

    /**
     * @throws ValidationException
     */
    public function update(Request $request, $id)
    {
        Validator::make($request->all(), [
            'name' => 'required|max:200',
            'added_by' => 'required|max:10',
            'weight' => 'max:100',
            'length' => 'max:10',
            'width' => 'max:10',
            'height' => 'max:10',
            'video_link' => 'max:100',
            'sku' => 'required|max:255|unique:products,sku,' . $id,
        ])->validate();

        $validation = [];
        $allowedKeyword = auth()->user()->getAllowedKeywords();
        $keywords = $request->get('product_keywords', []);
        if (count($keywords) > $allowedKeyword) {
            $validation[] = [
                'keyword' => "Your allowed keyword is not more then {$allowedKeyword}."
            ];
        }

        if (count($validation)) {
            throw ValidationException::withMessages($validation);
        }

        $product = Product::query()->find($id);
        if (empty($product)) {
            return response()->json(['result' => 'Error', 'message' => 'Product not found'], 404);
        }

        $colors = collect(json_decode($product->color_image));
        $photos = collect(json_decode($product->photos));
        $feature = $product->featured_img;
        $flash = $product->flash_deal_img;
        $thumbnail = $product->thumbnail_img;
        $meta_image = $product->meta_img;

        if ($request->color_type == 1) {
            foreach ($request->color_image as $photo) {
                if (!is_string($photo['image'])) {
                    if (array_key_exists("image", $photo)) {
                        $image = $this->saveImagesVue($photo['image'][0], 'path', 'upload/product/color/', 370, 370);
                        $colors->push((object)[
                            'name' => $photo['name'],
                            'image' => $image,
                        ]);
                    } else {
                        $newColor = collect();
                        $newColor->push((object)[
                            'name' => $photo['name'],
                            'image' => $image,
                        ]);
                        $colors = $newColor;
                    }
                }
            }
            $color = collect($request->color);
            $colors = $colors->filter(function ($e) use ($color) {
                return $color->contains($e->name);
            });
        }

        if ($request->has('photos') && $request->photos != '') {
            foreach ($request->photos as $photo) {
                if (!is_string($photo)) {
                    if (array_key_exists("name", $photo)) {
                        $image = $this->saveImagesVue($photo, 'path', 'upload/product/product/', 370, 370);
                        $photos->push($image);
                    } else {
                        $newPhoto = collect();
                        $newPhoto->push($photo['hash']);
                        $photos = $newPhoto;
                    }
                }
            }
        }


        if ($request->featured_img != '' && !is_string($request->featured_img)) {
            $feature = $this->saveImagesVue($request->featured_img[0], 'path', 'upload/product/feature/', 290, 300);
        }

        if ($request->flash_deal_img != '' && !is_string($request->flash_deal_img)) {
            $flash = $this->saveImagesVue($request->flash_deal_img[0], 'path', 'upload/product/flash_deal/', 290, 300);
        }

        if ($request->thumbnail_img != '' && !is_string($request->thumbnail_img)) {
            $thumbnail = $this->saveImagesVue($request->thumbnail_img[0], 'path', 'upload/product/thumbnail/', 290, 300);
        }

        if ($request->meta_img != '' && !is_string($request->meta_img)) {
            $meta_image = $this->saveImagesVue($request->meta_img[0], 'path', 'upload/product/meta_image/', 290, 300);
        }

        $product->name = $request->name;
        $product->sort_desc = $request->sort_desc;
        $product->slug = $this->slugText($request, 'name');
        $product->added_by = $request->added_by;
        $product->user_id = Auth::user()->id;
        $product->category_id = $request->category_id;
        $product->subcategory_id = $request->subcategory_id;
        $product->subsubcategory_id = $request->subsubcategory_id;
        $product->property_options = json_encode($request->property_options);
        $product->brand_id = $request->brand_id;
        $product->unit = $request->unit_id;
        $product->weight = $request->weight;
        $product->length = $request->length;
        $product->width = $request->width;
        $product->height = $request->height;
        $product->tags = json_encode($request->tags);
        $product->product_type = $request->product_type;
        $product->photos = json_encode($photos);
        $product->thumbnail_img = $thumbnail;
        $product->featured_img = $feature;
        $product->flash_deal_img = $flash;
        $product->video_link = $request->video_link;
        $product->colors = json_encode($request->color);
        $product->color_image = json_encode($colors);
        $product->color_type = $request->color_type;
        $product->attributes = json_encode($request->attribute);
        $product->attribute_options = json_encode($request->attribute_options);
        $product->tax = $request->tax;
        $product->tax_type = $request->tax_type;
        $product->discount = $request->discount;
        $product->discount_type = $request->discount_type;
        $product->discount_variation = $request->discount_variation;
        $product->orderQtyLimit = $request->orderQtyLimit;
        $product->orderQtyLimitMax = $request->orderQtyLimitMax;
        $product->orderQtyLimitMin = $request->orderQtyLimitMin;
        $product->priceType = $request->priceType;
        $product->stockManagement = $request->stockManagement;
        $product->unit_price = $request->unit_price;
        $product->currency_id = $request->currency_id;
        $product->quantity = $request->quantity;
        $product->sku = $request->sku;
        $product->description = $request->description;
        $product->shipping_type = $request->shipping_type;
        $product->shipping_cost = $request->shipping_cost;
        $product->meta_title = $request->meta_title;
        $product->meta_description = $request->meta_description;
        $product->meta_img = $meta_image;
        $product->update();

        /*
         * colors ids find and store colors id in pivot table
         * */
        if ($request->input('color')){
            $colorIds = Color::whereIn('name',$request->color)->pluck('id');
            $product->productColors()->sync($colorIds);
        }

        if ($request->discount_variation == 1) {
            foreach ($request->tierDiscount as $dis) {
                if (isset($dis['id'])) {
                    $discount = discount_variation::query()->find($dis['id']);
                    $discount->product_id = $product->id;
                    $discount->min_qty = $dis['min_qty'];
                    $discount->percent_off = $dis['percent_off'];
                    $discount->save();
                } else {
                    $discount = new discount_variation();
                    $discount->product_id = $product->id;
                    $discount->min_qty = $dis['min_qty'];
                    $discount->percent_off = $dis['percent_off'];
                    $discount->save();
                }
            }
        }

        if ($request->priceType == 1) {
            foreach ($request->priceMenu as $prices) {
                if (isset($prices['id'])) {
                    $stock = Product_stock::query()->find($prices['id']);
                    $stock->product_id = $product->id;
                    $stock->variant = json_encode($prices['variant']);
                    $stock->sku = $this->sku($prices, $product->id);
                    $stock->price = $prices['price'];
                    $stock->qty = $prices['qty'];
                    $stock->update();
                } else {
                    $stock = new Product_stock();
                    $stock->product_id = $product->id;
                    $stock->variant = json_encode($prices['variant']);
                    $stock->sku = $this->sku($prices, $product->id);
                    $stock->price = $prices['price'];
                    $stock->qty = $prices['qty'];
                    $stock->save();
                }
            }
        }

        if ($request->priceType == 2) {
            foreach ($request->tierPrice as $prices) {
                if (isset($prices['id'])) {
                    $price = price_variation::query()->find($prices['id']);
                    $price->product_id = $product->id;
                    $price->off_price = $prices['off_price'];
                    $price->min_qty = $prices['min_qty'];
                    $price->max_qty = $prices['max_qty'];
                    $price->update();
                } else {
                    $price = new price_variation();
                    $price->product_id = $product->id;
                    $price->off_price = $prices['off_price'];
                    $price->min_qty = $prices['min_qty'];
                    $price->max_qty = $prices['max_qty'];
                    $price->save();
                }
            }
            $tierPriceIds = collect($request->tierPrice)->pluck('id')->toArray();
            $product->price_variation()->whereNotIn('id', $tierPriceIds)->delete();
        }

        foreach ($keywords as $keyword) {
            if ($keyword['key_name']) {
                if (isset($keyword['id'])) {
                    $productKeyword = ProductKeyword::query()->find($keyword['id']);
                    $productKeyword->product_id = $product->id;
                    $productKeyword->key_name = $keyword['key_name'];
                    $productKeyword->updated_by = auth()->user()->id;
                    $productKeyword->update();
                } else {
                    $productKeyword = new ProductKeyword();
                    $productKeyword->product_id = $product->id;
                    $productKeyword->key_name = $keyword['key_name'];
                    $productKeyword->created_by = auth()->user()->id;
                    $productKeyword->save();
                }
            }
        }
        $keywordIds = collect($keywords)->pluck('id')->toArray();
        $product->productKeywords()->whereNotIn('id', $keywordIds)->delete();
        return 'done';
    }

    public function destroy($id)
    {
        //
    }

    public function productRequest(Request $request)
    {
        $this->validate($request, [
            'product_id' => 'required',
            'request_type' => 'required',
            'user_id' => 'required',
        ]);

        if ($request->user_id !== Auth::user()->id) {
            return response()->json(['result' => 'Warning', 'message' => 'User Id did not match'], 401);
        }

        $product = ProductRequest::where('request_type', $request->request_type)->where('product_id', $request->product_id)->first();
        if ($product) {
            return ProductRequest::findOrFail($product->id)->update($request->all());
        } else {
            return ProductRequest::create($request->all());
        }
    }

    public function productEcommerceList()
    {
        $product = collect();
        $request = DB::table('product_requests')->where('request_type', 1)->where('user_id', Auth::user()->id)->orderBy('id', 'DESC')->get();
        foreach ($request as $item) {
            $data = DB::table('products')->where('id', $item->product_id)->first();
            $status = $item->status == 0 ? 'Request' : 'Approve';
            $product->push([
                'image' => $data->thumbnail_img, 'product_name' => $data->name, 'status' => $status
            ]);
        }
        return $product;
    }

    public function productFlashList()
    {
        $product = collect();
        $request = DB::table('product_requests')->where('request_type', 2)->where('user_id', Auth::user()->id)->orderBy('id', 'DESC')->get();
        foreach ($request as $item) {
            $data = DB::table('products')->where('id', $item->product_id)->first();
            $status = $item->status == 0 ? 'Request' : 'Approve';
            $product->push([
                'image' => $data->thumbnail_img, 'product_name' => $data->name, 'status' => $status, 'discount' => $item->discount, 'discount_type' => $item->discount_type
            ]);
        }
        return $product;
    }

    /*
     * method for remove user product by product id
     * */
    public function remove(Product $product)
    {
        if ($product->user_id != auth()->id()) return response()->json(['status'=>'fail','message'=>'You don\'t have permission to remove this'],403);
        $ss = $product->delete();
        if (isset($ss)) return  response()->json(['status'=>'success','message'=>'Successfully removed product'],200);
        return  response()->json(['status'=>'fail','message'=>'Invalid request'],404);
    }
}
