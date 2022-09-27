<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::post('/ipn', ['SslCommerzPaymentController@ipn']);
Route::group(['middleware' => 'api', 'prefix' => 'admin', 'namespace' => 'Admin'], function ($router) {
    Route::post('login', 'AuthController@login');
    Route::post('register', 'AuthController@register');
    Route::post('logout', 'AuthController@logout');
    Route::get('profile', 'AuthController@profile');
    Route::get('user-list', 'AuthController@userList');
    Route::get('supplier-list', 'AuthController@supplierList');
    Route::post('user-verify/{data}', 'AuthController@userVerify');
    Route::post('user/registration/from/admin', 'AuthController@sbRegister');
    Route::post('user/information/update/from/admin/{user}', 'AuthController@sbUpdate');
    /*dashboard routes*/
    Route::get('get/dashboard/information', 'DashboardController@dashboard');

    Route::post('password/change/request', 'AuthController@changePassword');

    /*profile related routes*/
    Route::post('profile/image/change', 'AdminProfileController@imageChange');

    /*order related routes*/
    Route::get('get/all/order', 'OrderController@orders');
    Route::post('order/status{status}/change{order}', 'OrderController@statusChange');
});

Route::group(['middleware' => 'api', 'prefix' => 'user', 'namespace' => 'User'], function ($router) {
    Route::post('login', 'AuthController@login');
    Route::post('login-google', 'AuthController@loginByGoogle');
    Route::post('login-facebook', 'AuthController@loginByFacebook');
    Route::post('register', 'AuthController@register');
    Route::post('logout', 'AuthController@logout');
    Route::post('verify', 'AuthController@verify');
    Route::get('profile', 'AuthController@profile');
    Route::post('verify-send', 'AuthController@verifyTokenSend');
    Route::post('verify-request/{data}', 'AuthController@verifyRequest');
    Route::get('user-search', 'AuthController@search');
    Route::resource('product', 'ProductController')->names('user.product');
    Route::get('product/related/categories', 'ProductController@productCategories');
    Route::delete('product/remove/byId{product}', 'ProductController@remove');
    Route::get('supplier-search', 'AuthController@supplierSearch');

    /*message related routes*/

    Route::post('contact-supplier/{id}', 'MessageController@contactSupplier');
    Route::get('message/search', 'MessageController@search');
    Route::get('message/conversation', 'MessageController@conversations');
    Route::get('message/{conversation_id}/message', 'MessageController@messages');
    Route::post('message/{conversation_id}/message', 'MessageController@sendMessage');
    Route::post('message/upload-attachment', 'MessageController@uploadAttachment');
    Route::get('unseen/message/count', 'MessageController@unseenMessageCount');
    Route::get('chat/conversation/types', 'MessageController@chatConversationTypes');

    Route::post('register-membership-plan/{id}', 'MembershipPlanController@register');
    Route::get('get/all/membership/plan', 'MembershipPlanController@getAll');

    Route::post('verification/request/store', 'UserController@verifyRequestStore');

    Route::get('all/verify/requests', 'UserController@verifyRequests');
    Route::post('verify/request/status/change{verify_request}/{status}', 'UserController@verify');
});

Route::group(['middleware' => 'api', 'namespace' => 'User'], function ($router) {

    /*
     * user dashboard related routes
     * */
    Route::get('get/dashboard/supplier/related/information', 'DashboardController@suppliers');
    Route::get('get/dashboard/buyer/related/information', 'DashboardController@buyers');


    Route::resource('personal', 'ProfileController');

    Route::post('user/password/change', 'PasswordChangeController@chnagePassword');

    /*
     * forget password related routes
     * */
    Route::post('sent/forget/password/request', 'ForgetPasswordController@verifyTokenSend');
    Route::post('verify/request/verification/token', 'ForgetPasswordController@verificationTokenMatch');
    Route::post('request/set/new/password', 'ForgetPasswordController@passwordSet');

    /*
     * certification related routes
     * */
    Route::post('certificate/store', 'ProfileController@certificateStore');
    Route::get('get/certificates', 'ProfileController@getCertificates');
    Route::post('certificates/update{company_certificate}', 'ProfileController@certificateUpdate');
    Route::delete('certificates/remove{company_certificate}', 'ProfileController@removeCertificate');
    Route::post('user/photo/upload', 'ProfileController@userPhotoChange');

    /*
     * profile some common routes
     * */
    Route::get('rnd/qc/production/annual/output/get', 'ProfileController@RQPAOutput');

    /*Company_factory related routes*/
    Route::post('company/factory/info/store', 'FactoryController@companyFactoryStore');
    Route::put('company/factory/info/update{company_factory}', 'FactoryController@companyFactoryUpdate');
    Route::delete('company/factory/info/remove{company_factory}', 'FactoryController@companyFactoryRemove');
    Route::get('company/factory/info/get', 'FactoryController@companyFactories');
    Route::post('company/user/unique/name/check{name}', 'FactoryController@uniqNameCheck');

    /*
     * company trade related routes
     * */
    Route::get('company/annual/revenue/export/percents', 'CompanyTradeController@aREPercents');
    Route::get('company/trade/info/get', 'CompanyTradeController@trands');
    Route::post('company/trade/info/store', 'CompanyTradeController@store');
    Route::put('company/trade/info/update{company_trade_info}', 'CompanyTradeController@update');
    Route::delete('company/trade/info/remove{company_trade_info}', 'CompanyTradeController@remove');


    /*
     * company nearest port related routes
     * */
    Route::get('company/nearest/ports', 'ProfileController@nearestPorts');
    Route::post('company/nearest/port/store', 'ProfileController@nearestPortStore');
    Route::delete('company/nearest/port/remove{company_nearest_port}', 'ProfileController@removeNearestPort');
    Route::put('company/nearest/port/update{company_nearest_port}', 'ProfileController@nearestPortUpdate');

    Route::resource('factory', 'FactoryController');
    Route::post('factory-details', 'FactoryController@factoryDetails');
    Route::get('supplier-quotation', 'MarketingController@quotation');
    Route::post('user-product-request', 'ProductController@productRequest');
    Route::get('user-product-ecommerce-list', 'ProductController@productEcommerceList');
    Route::get('user-product-flash-list', 'ProductController@productFlashList');

    /*
     * product favorite related routes
     * */
    Route::get('user-product-favorites', 'ProductFavoriteController@favorites');
    Route::post('user-product-favorite/{id}', 'ProductFavoriteController@favorite');
    Route::post('user/product/favorite/list', 'ProductFavoriteController@userFavoriteList');

    /*
     * review related routes
     * */
    Route::post('user/review/store{product}', 'ReviewController@store');
    Route::get('get/product/review/rating{product}', 'ReviewController@ratingReviews');

    /*
     * quotation related routes
     * */
    Route::resource('quotation', 'QuotationController');
    Route::put('quotation/request/accept{quotation}', 'QuotationController@accept');
    Route::put('quotation/request/cancel{quotation}', 'QuotationController@cancel');
    Route::get('quotation/for/buyer', 'QuotationController@buyerQuotations');
    Route::get('quotation/for/supplier', 'QuotationController@supplierQuotations');
    Route::post('supplier/quotation/accept{quotation_user}', 'QuotationController@supplierAccept');
    Route::post('supplier/quotation/cancel{quotation_user}/{note}', 'QuotationController@supplierCancel');


    Route::post('get/buyer/order/information', 'OrderController@buyerOrders');
    Route::post('get/supplier/order/information', 'OrderController@supplierOrders');
    Route::post('supplier/order/status{order}/change{status}', 'OrderController@statusChange');

});

Route::group(['middleware' => 'api'], function ($router) {
    Route::post('pay', 'SslCommerzPaymentController@index');
    Route::post('pay-via-ajax', 'SslCommerzPaymentController@payViaAjax');

    Route::post('success', 'SslCommerzPaymentController@success');
    Route::post('fail', 'SslCommerzPaymentController@fail');
    Route::post('cancel', 'SslCommerzPaymentController@cancel');

    Route::post('checkout', 'User\CheckoutController@checkOut');

    // Product route
    Route::resource('brand', 'BrandController');
    Route::resource('category', 'CategoryController');
    Route::resource('subcategory', 'SubCategoryController');
    Route::resource('subsubcategory', 'SubSubCategoryController');
    Route::resource('property', 'PropertyController');
    Route::resource('product', 'ProductController');
    Route::get('admin/seller/products', 'ProductController@sellerProducts');
    Route::get('product-search', 'ProductController@search');
    Route::get('product-name', 'ProductController@searchName');
    Route::get('single/product/information/{slug}', 'ProductController@singleProductBySlug');
    Route::get('top/city/products{city}', 'ProductController@topCityProducts');
    Route::get('top/item/products', 'ProductController@topItems');
    Route::get('top/suppliers', 'ProductController@topSuppliers');
    Route::get('verified/suppliers', 'ProductController@verifiedSuppliers');

    Route::resource('product-group', 'ProductGroupController');
    Route::get('get-product-group', 'ProductController@getProductGroup');
    Route::get('subcategory-slug/{data}', 'SubCategoryController@slug');
    Route::get('subsubcategory-slug/{data}', 'SubSubCategoryController@slug');
    Route::get('product-listing', 'ProductController@productListing');
    Route::post('product-approve/{id}', 'ProductController@approve');
    Route::get('product/basic/information{product}', 'ProductController@basicProduct');

    Route::get('company/product/by{user}', 'ProductController@companyProducts');

    /*
     * company related routes
     * */
    Route::get('get/company/basic/info/{user}', 'CompanyController@companyBasic');
    Route::get('get/company/basic/info/by/display/name{display_name}', 'CompanyController@companyBasicByDisplayName');
    Route::get('get/company/details/by/user{user}', 'CompanyController@companyDetails');


    // Customer
    Route::resource('customer', 'CustomerController');

    // Customer
    Route::resource('advertisement', 'AdvertisementController');

    /*subscriber related routes*/
    Route::get('get/all/subscriber', 'SubscriberController@subscribers');
    Route::delete('remove/subscriber/from/admin{subscriber}', 'SubscriberController@remove');

    // Sellers
    Route::resource('seller', 'SellerController');
    Route::post('seller-status-update', 'SellerController@updateStatus');
    Route::post('seller/status/change{user}', 'SellerController@statusChange');
    Route::resource('seller-package', 'SellerPackageController');
    Route::apiResource('membership-plan', 'MembershipPlanController');
    Route::get('get/only/membership/plan', 'MembershipPlanController@onlyMemberships');
    Route::apiResource('guest-user-settings', 'GuestUserSettingController');

    // Package Payment
    Route::apiResource('package-payment', 'PackagePaymentController');

    // Region
    Route::resource('country', 'CountryController');
    Route::get('country/{data}/{data2}', 'CountryController@updateStatus');
    Route::resource('division', 'DivisionController');
    Route::get('division/{data}/{data2}', 'DivisionController@updateStatus');
    Route::resource('city', 'CityController');
    Route::get('city/{data}/{data2}', 'CityController@updateStatus');
    Route::get('get/supplier/from/top/cities', 'CityController@topCities');
    Route::resource('area', 'AreaController');
    Route::get('area/{data}/{data2}', 'AreaController@updateStatus');

    Route::post('brand-listing', 'BrandController@brandListing');
    Route::post('category-listing', 'CategoryController@categoryListing');
    Route::post('subcategory-listing', 'SubCategoryController@subcategoryListing');
    Route::post('sub-subcategory-listing', 'SubSubCategoryController@subsubcategoryListing');
    Route::get('home-banner', 'HomeSliderController@homeBannerList');
    Route::post('home-banner', 'HomeSliderController@homeBanner');
    Route::get('home-category-listing', 'HomeSliderController@homeCategoryListing');
    Route::post('home-category-listing', 'CategoryController@homeCategoryListing');

    Route::get('term_condition', 'PageManageController@termCondition');
    Route::post('term_condition', 'PageManageController@termConditionUpdate');
    Route::get('privacy_policy', 'PageManageController@privacyPolicy');
    Route::post('privacy_policy', 'PageManageController@privacyPolicyUpdate');
    Route::get('about_us', 'PageManageController@aboutUs');
    Route::post('about_us', 'PageManageController@aboutUsUpdate');
    Route::get('join_sales', 'PageManageController@joinSales');
    Route::post('join_sales', 'PageManageController@joinSalesUpdate');

    Route::get('help-category', 'HelpController@helpCategoryIndex');
    Route::post('help-category', 'HelpController@helpCategoryStore');
    Route::put('help-category/{data}', 'HelpController@helpCategoryUpdate');
    Route::delete('help-category/{data}', 'HelpController@helpCategoryDestroy');
    Route::get('help-subcategory', 'HelpController@helpSubcategoryIndex');
    Route::post('help-subcategory', 'HelpController@helpSubcategoryStore');
    Route::put('help-subcategory/{data}', 'HelpController@helpSubcategoryUpdate');
    Route::delete('help-subcategory/{data}', 'HelpController@helpSubcategoryDestroy');

    Route::get('help-question', 'HelpController@helpQuestionIndex');
    Route::post('help-question', 'HelpController@helpQuestionStore');
    Route::post('help-question/{data}/status', 'HelpController@helpQuestionStatus');
    Route::put('help-question/{data}', 'HelpController@helpQuestionUpdate');
    Route::delete('help-question/{data}', 'HelpController@helpQuestionDestroy');

    Route::resource('general-settings', 'GeneralController');
    Route::get('general-settings-logo', 'GeneralController@logo');
    Route::put('general-settings-logo/{data}', 'GeneralController@logoUpload');

    Route::resource('color', 'ColourController');
    Route::resource('attribute', 'AttributeController');
    Route::resource('unit', 'UnitController');
    Route::resource('currency', 'CurrencyController');
    Route::resource('business_type', 'BusinessTypeController');
    Route::get('active/business_types', 'BusinessTypeController@activeBusinessTypes');
    Route::get('currency/{data}/{data2}', 'CurrencyController@updateStatus');
    Route::get('business_type/{data}/{data2}', 'BusinessTypeController@updateStatus');

    Route::resource('flash-deals', 'FlashDealController');
    Route::get('flash-deals-list', 'FlashDealController@flashDealList');
    Route::post('flash-deals-status', 'FlashDealController@statusUpdate');
    Route::get('request-flash-deals', 'FlashDealController@requestFlashDealList');
    Route::post('product_flash_request_input', 'FlashDealController@requestFlashDealStore');

    Route::get('ecom-zone-request-list', 'ProductRequestController@ecomZoneRequestList');
    Route::post('ecom-zone-status', 'ProductRequestController@statusUpdate');
    Route::delete('ecom-zone-request/{id}', 'ProductRequestController@destroy');

    Route::get('newsletter', 'NewsletterController@newsletterIndex');
    Route::post('newsletter-post', 'NewsletterController@newsletterStore');
    Route::post('subscribe', 'NewsletterController@subscribeStore');

    /*
     * testimonial related routes
     * */

    Route::resource('testimonial', 'TestimonialController');
    Route::post('testimonial-status', 'TestimonialController@statusUpdate');
    Route::post('testimonial/update{testimonial}', 'TestimonialController@update');
});
