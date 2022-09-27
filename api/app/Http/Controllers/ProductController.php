<?php

namespace App\Http\Controllers;

use App\Address;
use App\Brand;
use App\BusinessType;
use App\Category;
use App\City;
use App\Color;
use App\CompanyBasicInfo;
use App\discount_variation;
use App\GeneralSetting;
use App\price_variation;
use App\Product;
use App\Product_stock;
use App\ProductKeyword;
use App\SubCategory;
use App\SubSubCategory;
use App\Traits\FileUpload;
use App\Traits\Slug;
use App\User;
use App\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class ProductController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admins', ['except' => [
            'productListing','topCityProducts','topItems','topSuppliers','verifiedSuppliers',
            'companyProducts', 'search', 'searchName','singleProductBySlug','basicProduct']]);
    }

    use FileUpload;
    use Slug;

    /*
     * method for get in house products
     * */
    public function index(Request $request)
    {
        $columns = ['id', 'name'];
        $length = $request->input('length');
        $column = $request->input('column'); //Index
        $dir = $request->input('dir');
        $searchValue = $request->input('search');
        if ($length == null && $column == null && $dir == null && $searchValue == null) {
            return Product::select('id', 'name', 'subcategory_id')->orderBy('id', 'DESc')->get();
        }
        $query = Product::where('added_by','admin')->with([
            'price_variation'=>function($q){$q->select('id','product_id','off_price','min_qty','max_qty');},
            'product_stock',
            'flashDealProducts'=>function($q){$q->with([
                'flashDeal'=>function($r){$r->where('status',1)->where('start_date','<=',Carbon::today())->where('end_date','>=',Carbon::today());}
            ]);},
        ])->select('id', 'name', 'num_of_sale', 'thumbnail_img', 'stockManagement', 'quantity', 'priceType', 'unit_price', 'slug', 'is_approved')
            ->orderBy($columns[$column], $dir);

        if ($searchValue) {
            $query->where(function ($query) use ($searchValue) {
                $query->where('name', 'like', '%' . $searchValue . '%')
                    ->orWhere('sku', 'like', '%' . $searchValue . '%');
            });
        }

        $projects = $query->latest()->paginate($length);
        return ['data' => $projects, 'draw' => $request->input('draw')];
    }

    /*
     * method for seller product get only
     * */
    public function sellerProducts(Request $request)
    {
        $columns = ['id', 'name'];
        $length = $request->input('length');
        $column = $request->input('column'); //Index
        $dir = $request->input('dir');
        $searchValue = $request->input('search');
        if ($length == null && $column == null && $dir == null && $searchValue == null) {
            return Product::select('id', 'name', 'subcategory_id')->orderBy('id', 'DESc')->get();
        }
        $query = Product::where('added_by','supplier')->with([
            'price_variation'=>function($q){$q->select('id','product_id','off_price','min_qty','max_qty');},
            'product_stock',
            'user'=>function($q){$q->select('id','first_name','last_name');},
            'flashDealProducts'=>function($q){$q->with([
                'flashDeal'=>function($r){$r->where('status',1)->where('start_date','<=',Carbon::today())->where('end_date','>=',Carbon::today());}
            ]);},
        ])->select('id', 'name','added_by','user_id', 'num_of_sale', 'thumbnail_img', 'stockManagement', 'quantity', 'priceType', 'unit_price', 'slug', 'is_approved')
            ->orderBy($columns[$column], $dir);

        if ($searchValue) {
            $query->where(function ($query) use ($searchValue) {
                $query->where('name', 'like', '%' . $searchValue . '%')
                    ->orWhere('sku', 'like', '%' . $searchValue . '%');
            });
        }

        $projects = $query->latest()->paginate($length);
        return ['data' => $projects, 'draw' => $request->input('draw')];
    }

    public function search(Request $request)
    {
        $search = $request->input('searchProduct');
        if ($search != null) {
            return Product::select('id', 'name', 'thumbnail_img', 'sku')
                ->where('name', 'like', '%' . $search . '%')
                ->orWhere('sku', 'like', '%' . $search . '%')->get();
        } else {
            return [];
        }
    }

    public function getProductGroup(Request $request)
    {
        $search = $request->input('searchProduct');
        if ($search != null) {
            $search = json_decode($search);
            return Product::select('id', 'name', 'thumbnail_img', 'sku')
                ->whereIn('id', $search)->get();
        } else {
            return [];
        }
    }

    public function store(Request $request)
    {


        $this->validate($request, [
            'name' => 'required|max:200',
            'added_by' => 'required|max:10',
            'weight' => 'max:100',
            'length' => 'max:10',
            'width' => 'max:10',
            'height' => 'max:10',
            'video_link' => 'max:100',
            'sku' => 'required|max:255|unique:products',
        ]);

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
        return 'done';
    }

    public function show($id)
    {
        $prduct = Product::with('unit', 'discount_variation_data', 'price_variation',
            'subsubcategories', 'subcategory', 'category', 'currency', 'brand', 'product_stock')
            ->where('slug', $id)->first();
        if ($prduct) {
            return $prduct;
        } else {
            return response()->json(['result' => 'Error', 'message' => 'Product not found'], 404);
        }
    }

    public function productListing(Request $request)
    {
        $conditions = array();

        $keyword = $request->keyword;
        $type = $request->type;
        /*
         * if search by search bar than it working
         * */
        if ($request->input('keyword')) {
            if ($type == 'Products') {
                $products=Product::with([
                    'brand'=>function($q){$q->select('id','name');},
                    'unit'=>function($q){$q->select('id','name');},
                    'currency'=>function($q){$q->select('id','name','code','symbol','exchange_rate');},
                    'price_variation'=>function($q){$q->select('id','product_id','off_price','min_qty','max_qty');},
                    'product_stock',
                    'user'=>function($q){$q->select('id','account_type','telephone','first_name','last_name');},
                    'user.companyBasicInfo'=>function($q){
                        $q->with(['businessTypes'])->get(['id','user_id','name','establishment_date','office_space','ownership_type','phone']);
                    },
                    'flashDealProducts'=>function($q){$q->with([
                        'flashDeal'=>function($r){$r->where('status',1)->where('start_date','<=',Carbon::today())->where('end_date','>=',Carbon::today());}
                    ]);},
                ])->where(['is_approved'=>1])->where('name', 'like', '%' . $keyword . '%')->orderBy('updated_at','DESC')->inRandomOrder()->limit(30)->get();
            } else {
                $supplierIds = User::where('first_name', 'like', '%' . $keyword . '%')
                    ->orwhere('last_name', 'like', '%' . $keyword . '%')
                    ->orWhere('mobile', 'like', '%' . $keyword . '%')
                    ->orWhereHas('companyBasicInfo', function($q) use ($keyword) {
                        $q->where('name', 'like', '%' . $keyword . '%');
                    })->pluck('id');
                if (count($supplierIds)>0){
                    $products=Product::with([
                        'brand'=>function($q){$q->select('id','name');},
                        'unit'=>function($q){$q->select('id','name');},
                        'currency'=>function($q){$q->select('id','name','code','symbol','exchange_rate');},
                        'price_variation'=>function($q){$q->select('id','product_id','off_price','min_qty','max_qty');},
                        'product_stock',
                        'user'=>function($q){$q->select('id','account_type','telephone','membership_plan_id','first_name','last_name');},
                        'user.companyBasicInfo'=>function($q){
                            $q->with(['businessTypes'])->get(['id','user_id','name','establishment_date','office_space','ownership_type','phone']);
                        },
                        'flashDealProducts'=>function($q){$q->with([
                            'flashDeal'=>function($r){$r->where('status',1)->where('start_date','<=',Carbon::today())->where('end_date','>=',Carbon::today());}
                        ]);},
                    ])->where(['is_approved'=>1])->whereIn('user_id', $supplierIds)->orderBy('updated_at','DESC')->inRandomOrder()->limit(30)->get();
                }else $products=[];

            }
        }else{ //if product find by category/sub category/sub sub category
            if ($request->input('category')) $conditions += ['category_id' => $request->category];
            if ($request->input('subcategory')) $conditions += ['subcategory_id' => $request->subcategory];
            if ($request->input('subsubcategory')) $conditions += ['subsubcategory_id' => $request->subsubcategory];
            $products=Product::with([
                'brand'=>function($q){$q->select('id','name');},
                'unit'=>function($q){$q->select('id','name');},
                'currency'=>function($q){$q->select('id','name','code','symbol','exchange_rate');},
                'price_variation'=>function($q){$q->select('id','product_id','off_price','min_qty','max_qty');},
                'product_stock',
                'user'=>function($q){$q->select('id','account_type','telephone','membership_plan_id','first_name','last_name');},
                'user.companyBasicInfo'=>function($q){
                    $q->with(['businessTypes'])->get(['id','user_id','name','establishment_date','office_space','ownership_type','phone']);
                },
                'flashDealProducts'=>function($q){$q->with([
                    'flashDeal'=>function($r){$r->where('status',1)->where('start_date','<=',Carbon::today())->where('end_date','>=',Carbon::today());}
                ]);},
            ])->where(['is_approved'=>1])->where($conditions)->orderBy('updated_at','DESC')->inRandomOrder()->limit(30)->get();
        }

        if ($request->input('business_type')){
            $business_type = BusinessType::where('name',$request->business_type)->first();
            if (isset($business_type->id)) {
                $userIds = $business_type->companies->pluck('user_id');
            }
        }

        /*
         * business type wise product find
         * */
        if (isset($userIds)) $products= $products->whereIn('user_id',$userIds);

        /*
         * product unique brand name list
         * */
        $brands = Brand::whereIn('id',$products->pluck('brand_id')->unique()->whereNotNull())->get(['id','name']);
        /*
         * product unique colors name list
         * */
        $colors = Color::whereIn('id',DB::table('color_product')->whereIn('product_id',$products->pluck('id'))->pluck('color_id')->unique())->get(['id','name']);

        return response()->json(['brands'=>$brands,'products'=>$products,'colors'=>$colors],200);
    }

    public function searchName(Request $request)
    {
        $type = $request->input('type');
        $keyword = $request->input('keyword');
        $products = Product::query()->where('is_approved', true);
        if ($type == 'Suppliers') {
            if (isset($keyword)) {
                $products = $products->where('name', 'like', '%' . $keyword . '%');
            }
            $products = $products->with('product_stock')->select('id', 'name', 'thumbnail_img', 'priceType', 'unit_price', 'slug', 'property_options', 'added_by', 'user_id', 'video_link')
                ->take(5)->get();
            $supplier = [];
            foreach ($products as $product) {
                if ($product->added_by == 'supplier') {
                    $company = DB::table('company_basic_infos')->where('user_id', $product->user_id)->select('name', 'phone', 'address_type', 'ope_address_id', 'reg_address_id')->first();
                    if ($company) {
                        $address = DB::table('addresses')->where('id', $company->address_type == 2 ? $company->ope_address_id : $company->reg_address_id)->pluck('address')->first();
                        $supplier[$product->user_id]['name'] = $company->name;
                        $supplier[$product->user_id]['phone'] = $company->phone;
                        $supplier[$product->user_id]['address'] = $address;
                    } else {
                        $user = User::query()->findOrFail($product->user_id);
                        $supplier[$product->user_id]['name'] = $user->first_name . ' ' . $user->last_name;
                        $supplier[$product->user_id]['phone'] = $user->mobile;
                        $supplier[$product->user_id]['address'] = '';
                    }
                    $supplier[$product->user_id]['id'] = $product->user_id;
                    $supplier[$product->user_id]['products'][] = $product;
                } else {
                    $general_setting = DB::table('general_settings')->get()->first();
                    $supplier[-1]['name'] = $general_setting->site_name;
                    $supplier[-1]['phone'] = $general_setting->phone;
                    $supplier[-1]['address'] = $general_setting->address;
                    $supplier[-1]['id'] = -1;
                    $supplier[-1]['products'][] = $product;
                }
            }

            return array_values($supplier);
        } else {
            $keywords = ProductKeyword::query()
                ->where('key_name', 'like', '%' . $keyword . '%')
                ->get()->unique('key_name')->pluck('key_name');

            $products = $keywords->map(function ($k) {
                return $product = Product::query()
                    ->where('name', 'like', '%' . $k . '%')
                    ->select('id', 'name')
                    ->skip(0)->take(5)->get();
            })->flatten()->unique('name')->values();

            return $products;
        }
    }

    public function update(Request $request, $id)
    {
        //return $request->all();
        $this->validate($request, [
            'name' => 'required|max:200',
            'added_by' => 'required|max:10',
            'weight' => 'max:100',
            'length' => 'max:10',
            'width' => 'max:10',
            'height' => 'max:10',
            'video_link' => 'max:100',
            'sku' => 'required|max:255|unique:products,sku,' . $id,
        ]);

        $product = Product::findOrFail($id);
        $colors = [];
        $photos = [];
        if ($request->color_type == 1) {
            foreach ($request->color_image as $photo) {
                if (array_key_exists("image", $photo) && strlen($photo['image'][0]['path']) > 200) {
                    $image = $this->saveImagesVue($photo['image'][0], 'path', 'upload/product/color/', 370, 370);
                    $image = [
                        'name' => $photo['name'],
                        'image' => $image,
                    ];
                    array_push($colors, $image);
                } else {
                    foreach (json_decode($product->color_image) as $pho) {
                        if (strpos($photo['image'][0]['path'], $pho->image)) {
                            $image = [
                                'name' => $photo['name'],
                                'image' => $pho->image,
                            ];
                            array_push($colors, $image);
                        }
                    }
                }
            }
        }

        //return $colors;

        if ($request->photos != '' && is_array($request->photos)) {
            foreach ($request->photos as $photo) {
                if (array_key_exists("path", $photo) && strlen($photo['path']) > 200) {
                    $image = $this->saveImagesVue($photo, 'path', 'upload/product/product/', 370, 370);
                    array_push($photos, $image);
                } else {
                    foreach (json_decode($product->photos) as $pho) {
                        if (strpos($photo['path'], $pho)) {
                            array_push($photos, $pho);
                        }
                    }
                }
            }
        }

        if (count($request->featured_img) > 0 && strlen($request->featured_img[0]['path']) > 200) {
            $feature = $this->saveImagesVue($request->featured_img[0], 'path', 'upload/product/feature/', 290, 300);
            $product->featured_img = $feature;
        }

        if (count($request->flash_deal_img) > 0 && strlen($request->flash_deal_img[0]['path']) > 200) {
            $flash = $this->saveImagesVue($request->flash_deal_img[0], 'path', 'upload/product/flash_deal/', 290, 300);
            $product->flash_deal_img = $flash;
        }

        if (count($request->thumbnail_img) > 0 && strlen($request->thumbnail_img[0]['path']) > 200) {

            $thumbnail = $this->saveImagesVue($request->thumbnail_img[0], 'path', 'upload/product/thumbnail/', 290, 300);
            $product->thumbnail_img = $thumbnail;
        }
        //return $photos;

        if (count($request->meta_img) > 0 && strlen($request->meta_img[0]['path']) > 200) {
            $meta_image = $this->saveImagesVue($request->meta_img[0], 'path', 'upload/product/meta_image/', 290, 300);
            $product->meta_img = $meta_image;
        }


        $product->name = $request->name;
        $product->sort_desc = $request->sort_desc;
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
        if (count($photos) > 0) {
            $product->photos = json_encode($photos);
        }
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
        $product->description = $request->description;
        $product->shipping_type = $request->shipping_type;
        $product->shipping_cost = $request->shipping_cost;
        $product->meta_title = $request->meta_title;
        $product->meta_description = $request->meta_description;
        $product->save();
        //return $request;

        /*
         * colors ids find and store colors id in pivot table
         * */
        if ($request->input('color')){
            $colorIds = Color::whereIn('name',$request->color)->pluck('id');
            $product->productColors()->sync($colorIds);
        }

        if ($request->discount_variation == 1) {
            discount_variation::where('product_id', $product->id)->delete();
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

        if ($request->priceType == 2) {
            price_variation::where('product_id', $product->id)->delete();
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

        if ($request->priceType == 1) {
            Product_stock::where('product_id', $product->id)->delete();
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
        return 'done';
    }

    public function destroy(Product $product)
    {
        $ss = $product->delete();
        if (isset($ss)) return response()->json(['status'=>'fail','message'=>'Successfully remove product'],200);
        return response()->json(['status'=>'fail','message'=>'Invalid request!'],404);
    }

    public function approve($id)
    {
        $product = Product::with('user')->where('id', $id)->first();
        if (!$product) {
            return response()->json(['result' => 'Error', 'message' => 'Product not found'], 404);
        }

        if ($product->is_approved) {
            $result = 'Rejected';
            $product->is_approved = false;
            $product->approved_date = null;
            $product->approved_by = null;

            $subject = $product->name . ' has been rejected';
            if($product->added_by=='admin'){
                $email = Admin::where('id',$product->user_id)->first()->email;
                $name = Admin::where('id',$product->user_id)->first()->first_name . ' ' . Admin::where('id',$product->user_id)->first()->last_name;
            }
            else{
                $email = $product->user->email;
                $name = $product->user->first_name . ' ' . $product->user->last_name;
            }
            
            Mail::send('email.product_reject', ['content' => $subject],
                function ($mail) use ($email, $name, $subject) {
                    $mail->from("rofequlislamnayem@gmail.com", "Tizaara.com");
                    $mail->to($email, $name);
                    $mail->subject($subject);
                });
        } else {
            $result = 'Approved';
            $product->is_approved = true;
            $product->approved_date = now();
            $product->approvedBy()->associate(auth()->user());
        }

        if ($product->save()) {
            return response()->json(['result' => $result], 200);
        }

        return response()->json(['result' => 'Error'], 500);
    }

    /*
     * method for get single product by id
     * */
    public function singleProductBySlug($slug)
    {
        $product = Product::where(['slug'=>$slug])->first();
        if (!isset($product->id)) return [];
        $product = $product->with([
            'category'=>function($q){$q->select('id','name','banner','slug');},
            'subcategory'=>function($q){$q->select('id','name','banner','slug');},
            'subsubcategory'=>function($q){$q->select('id','name','slug');},
            'unit'=>function($q){$q->select('id','name');},
            'brand'=>function($q){$q->select('id','name','slug');},
            'currency'=>function($q){$q->select('id','name','code','symbol','exchange_rate');},
            'productFavorites',
            'user'=>function($q){$q->select('id','account_type','telephone','membership_plan_id');},
            'user.membershipPlan'=>function($q){$q->select('id','name','no_of_allowed_products');},
            'user.companyBasicInfo'=>function($q){$q->with([
                'companyCertifications'=>function($q){$q->where('start_date','<=',Carbon::today())->where('end_date','>=',Carbon::today())->get();},
                'companyTradeInfo',
                'companyNearestPort',
                'companyFactories'
            ])->get(['id','user_id','name','establishment_date','main_product','number_of_employee','office_space','other_product','ownership_type','phone']);},
            'user.companyDetails'=>function($q){$q->select('id','user_id','company_logo','company_photos','company_video'); },
            'price_variation'=>function($q){$q->select('id','product_id','off_price','min_qty','max_qty');},
            'discount_variation_data'=>function($q){$q->select('id','product_id','min_qty','percent_off');},
            'product_stock',
            'ecomZoneProducts'=>function($q){$q->where('status',1)->where('request_type',1);},
            'flashDealProducts'=>function($q){$q->with([
                'flashDeal'=>function($r){$r->where('status',1)->where('start_date','<=',Carbon::today())->where('end_date','>=',Carbon::today());}
            ]);},
        ])->where(['id'=>$product->id,'is_approved'=>1])->first();

        /*
         * related product information get
         * */
        if (isset($product->user) && isset($product->user->membershipPlan)){
            /*number of product take for related product*/
            $number_of_product = $product->user->membershipPlan->no_of_allowed_products<8? $product->user->membershipPlan->no_of_allowed_products:8;
            $related_products=Product::with([
                'unit'=>function($q){$q->select('id','name');},
                'currency'=>function($q){$q->select('id','name','code','symbol','exchange_rate');},
                'price_variation'=>function($q){$q->select('id','product_id','off_price','min_qty','max_qty');},
                'product_stock',
                'flashDealProducts'=>function($q){$q->with([
                    'flashDeal'=>function($r){$r->where('status',1)->where('start_date','<=',Carbon::today())->where('end_date','>=',Carbon::today());}
                ]);},
            ])->where(['user_id'=>$product->user->id,'category_id'=>$product->category_id,
                'subcategory_id'=>$product->subcategory_id,
                'is_approved'=>1,
            ])->where('id','!=',$product->id)->inRandomOrder()->get()->take($number_of_product);
        }else{
            $use_ids=User::where(['is_verified'=>1])->where('membership_plan_id','!=',null)->pluck('id');
            $related_products=Product::with([
                'unit'=>function($q){$q->select('id','name');},
                'currency'=>function($q){$q->select('id','name','code','symbol','exchange_rate');},
                'price_variation'=>function($q){$q->select('id','product_id','off_price','min_qty','max_qty');},
                'product_stock',
                'flashDealProducts'=>function($q){$q->with([
                    'flashDeal'=>function($r){$r->where('status',1)->where('start_date','<=',Carbon::today())->where('end_date','>=',Carbon::today());}
                ]);},
            ])->whereIn('user_id',$use_ids)->where(['category_id'=>$product->category_id,
                'subcategory_id'=>$product->subcategory_id,
                'is_approved'=>1,
            ])->where('id','!=',$product->id)->inRandomOrder()->get()->take(8);
        }
        return response()->json(['product'=>$product,'related_products'=>$related_products],200);
    }

    /*
     * public function product basic info get
     * */
    public function basicProduct(Product $product)
    {
        $product =$product->with([
            'user'=>function($q) use ($product) {$q->with([
                'companyBasicInfo'=>function($q){$q->select('id','user_id','address_type','display_name','name','email','phone','office_space');},
                'companyDetails'=>function($q){$q->select('id','user_id','company_logo');}
            ])->where('id',$product->user_id)->first('id','first_name','last_name','photo_type','photo');},
            'unit'=>function($q){$q->select('id','name');}
        ])->where('id',$product->id)->first(['id','user_id','name','slug','thumbnail_img','unit']);
        return response()->json($product,200);
    }

    /*
     * method for get top city product list
     * */

    public function topCityProducts(City $city)
    {
        $address_ids = Address::where(['city_id'=>$city->id])->pluck('id');
        $user_ids=CompanyBasicInfo::whereIn('reg_address_id',$address_ids)->pluck('user_id')->unique()->whereNotNull();

        $products=Product::with([
            'brand'=>function($q){$q->select('id','name');},
            'unit'=>function($q){$q->select('id','name');},
            'currency'=>function($q){$q->select('id','name','code','symbol','exchange_rate');},
            'price_variation'=>function($q){$q->select('id','product_id','off_price','min_qty','max_qty');},
            'product_stock',
            'user'=>function($q){$q->select('id','account_type','telephone','membership_plan_id','first_name','last_name');},
            'user.companyBasicInfo'=>function($q){
                $q->with(['businessTypes'])->get(['id','user_id','name','establishment_date','office_space','ownership_type','phone']);
            },
            'flashDealProducts'=>function($q){$q->with([
                'flashDeal'=>function($r){$r->where('status',1)->where('start_date','<=',Carbon::today())->where('end_date','>=',Carbon::today());}
            ]);},
        ])->where(['is_approved'=>1])->whereIn('user_id', $user_ids)->orderBy('updated_at','DESC')->inRandomOrder()->limit(30)->get();
        /*
         * product unique brand name list
         * */
        $brands = Brand::whereIn('id',$products->pluck('brand_id')->unique()->whereNotNull())->get(['id','name']);
        /*
         * product unique colors name list
         * */
        $colors = Color::whereIn('id',DB::table('color_product')->whereIn('product_id',$products->pluck('id'))->pluck('color_id')->unique())->get(['id','name']);

        return response()->json(['brands'=>$brands,'products'=>$products,'colors'=>$colors],200);
    }

    /*
     * method for get company some products
     * */
    public function companyProducts(User $user)
    {
        $products=Product::with([
            'currency'=>function($q){$q->select('id','name','code','symbol','exchange_rate');},
            'price_variation'=>function($q){$q->select('id','product_id','off_price','min_qty','max_qty');},
            'product_stock',
            'flashDealProducts'=>function($q){$q->with([
                'flashDeal'=>function($r){$r->where('status',1)->where('start_date','<=',Carbon::today())->where('end_date','>=',Carbon::today());}
            ]);},
        ])->where(['is_approved'=>1])->where(['user_id'=>$user->id])->orderBy('updated_at','DESC')->inRandomOrder()->limit(12)->get([
            'id','is_approved','currency_id','discount','discount_type','discount_variation','name',
            'priceType','quantity','stockManagement','thumbnail_img','unit_price','slug',
        ]);

        return response()->json($products,200);
    }

    /*
     * method for get top items
     * */

    public function topItems()
    {
        $products=Product::with([
            'brand'=>function($q){$q->select('id','name');},
            'unit'=>function($q){$q->select('id','name');},
            'currency'=>function($q){$q->select('id','name','code','symbol','exchange_rate');},
            'price_variation'=>function($q){$q->select('id','product_id','off_price','min_qty','max_qty');},
            'product_stock',
            'user'=>function($q){$q->select('id','account_type','telephone','membership_plan_id','first_name','last_name');},
            'user.companyBasicInfo'=>function($q){
                $q->with(['businessTypes'])->get(['id','user_id','name','establishment_date','office_space','ownership_type','phone']);
            },
            'flashDealProducts'=>function($q){$q->with([
                'flashDeal'=>function($r){$r->where('status',1)->where('start_date','<=',Carbon::today())->where('end_date','>=',Carbon::today());}
            ]);},

        ])->withCount([
            'productRating'=> function ($query) {
                $query->select(DB::raw("AVG(rating) as avgRating"));
            },
            'productReviews','orders'
        ])->where(['is_approved'=>1])->orderBy('updated_at','DESC')->inRandomOrder()->limit(30)->get();
        /*
         * product unique brand name list
         * */
        $brands = Brand::whereIn('id',$products->pluck('brand_id')->unique()->whereNotNull())->get(['id','name']);
        /*
         * product unique colors name list
         * */
        $colors = Color::whereIn('id',DB::table('color_product')->whereIn('product_id',$products->pluck('id'))->pluck('color_id')->unique())->get(['id','name']);

        return response()->json(['brands'=>$brands,'products'=>$products,'colors'=>$colors],200);
    }

    /*
     * method for get top suppliers
     * */

    public function topSuppliers()
    {
        $products=Product::withCount([
            'productRating'=> function ($query) {
                $query->select(DB::raw("AVG(rating) as avgRating"));
            },
            'productReviews','orders'
        ])->where(['is_approved'=>1]);
        $user_ids= $products->orderBy('product_rating_count', 'desc')
            ->orderBy('product_reviews_count', 'desc')
            ->orderBy('orders_count', 'desc')
            ->pluck('user_id')->unique()->whereNotNull()->toArray();
        $users = User::with([
            'companyBasicInfo'=>function($q){
                $q->with(['businessTypes'])->get(['id','user_id','name','establishment_date','office_space','ownership_type','phone']);
            },
            'companyDetails'
        ])->whereIn('id',$user_ids)->orderByRaw('FIELD(id, '.implode(',', $user_ids).')')->get();

        return response()->json($users,200);
    }

    /*
     * method for get top suppliers
     * */

    public function verifiedSuppliers()
    {
        $user_ids= CompanyBasicInfo::where(['is_verified'=>1])
            ->pluck('user_id')->unique()->whereNotNull()->toArray();
        $users = User::with([
            'companyBasicInfo'=>function($q){
                $q->with(['businessTypes'])->get(['id','user_id','name','establishment_date','office_space','ownership_type','phone']);
            },
            'companyDetails'
        ])->whereIn('id',$user_ids)->orderByRaw('FIELD(id, '.implode(',', $user_ids).')')->get();

        return response()->json($users,200);
    }
}
