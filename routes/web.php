<?php

use App\Http\Controllers\admin\AdminLoginController;
use App\Http\Controllers\admin\HomeController;
use App\Http\Controllers\admin\CategoryController;
use App\Http\Controllers\admin\ImportController;
use App\Http\Controllers\admin\ProductController;
use App\Http\Controllers\admin\ProductImageController;
use App\Http\Controllers\admin\ProductQuantityController;
use App\Http\Controllers\admin\ProductSubCategoryController;
use App\Http\Controllers\admin\TempImagesController;
use App\Http\Controllers\admin\DiscountCodeController;
use App\Http\Controllers\admin\SubCategoryController;
use App\Http\Controllers\admin\OrderController;
use App\Http\Controllers\admin\UserController;
use App\Http\Controllers\admin\PageController;
use App\Http\Controllers\admin\SettingController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\EmailController;
use App\Http\Controllers\FrontController;
use App\Http\Controllers\ShopController;
use App\Http\Controllers\CartController;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Route::get('/', function () {
//     return view('welcome');
// });

//Front-end
Route::get('/',[FrontController::class, 'index'])->name('frontend.homepage');
Route::get('/shop/{categorySlug?}/{subCategorySlug?}',[ShopController::class, 'index'])->name('frontend.shop');
Route::get('/product/{slug}',[ShopController::class, 'product'])->name('frontend.product');
Route::get('/cart',[CartController::class, 'cart'])->name('frontend.cart');
Route::post('/add-to-cart',[CartController::class, 'addToCart'])->name('frontend.addToCart');
Route::post('/update-cart',[CartController::class, 'updateCart'])->name('frontend.updateCart');
Route::delete('/delete-item',[CartController::class, 'deleteItem'])->name('frontend.deleteItem.cart');
Route::get('/checkout',[CartController::class, 'checkout'])->name('frontend.checkout');
Route::post('/process-checkout',[CartController::class, 'processCheckout'])->name('frontend.processCheckout');
Route::get('/thanks/{orderId}',[CartController::class, 'thankyou'])->name('frontend.thanks');
Route::post('/get-order-summary',[CartController::class, 'getOrderSummary'])->name('frontend.getOrderSummary');
Route::post('/apply-discount',[CartController::class, 'applyDiscount'])->name('frontend.applyDiscount');
Route::post('/remove-discount',[CartController::class, 'removeDiscount'])->name('frontend.removeDiscount');
Route::post('/add-to-wishlist',[FrontController::class, 'addToWishlist'])->name('frontend.addToWishlist');
Route::get('/page/{slug}',[FrontController::class, 'page'])->name('frontend.page');

Route::get('/forgot-password',[AuthController::class, 'forgotPassword'])->name('frontend.forgotPassword');
Route::post('/process-forgot-password',[AuthController::class, 'processForgotPassword'])->name('frontend.processForgotPassword');
Route::get('/reset-password/{token}',[AuthController::class, 'resetPassword'])->name('frontend.resetPassword');
Route::post('/process-reset-password',[AuthController::class, 'processResetPassword'])->name('frontend.processResetPassword');
Route::post('/save-rating/{productId}',[ShopController::class, 'saveRating'])->name('frontend.saveRating');


//Test email
Route::get('send-mail', [EmailController::class, 'sendWelcomeEmail']);

//User auth
Route::group(['prefix' => 'account'], function(){
    Route::group(['middleware' => 'guest'], function() {
        Route::get('/register', [AuthController::class, 'register'])->name('frontend.account.register');
        Route::post('/process-register', [AuthController::class, 'processRegister'])->name('frontend.account.processRegister');

        Route::get('/login', [AuthController::class, 'login'])->name('frontend.account.login');
        Route::post('/login', [AuthController::class, 'authenticate'])->name('frontend.account.authenticate');

    });

    Route::group(['middleware' => 'auth'], function() {
        Route::get('/profile', [AuthController::class, 'profile'])->name('frontend.account.profile');
        Route::post('/update-profile', [AuthController::class, 'updateProfile'])->name('frontend.account.updateProfile');
        Route::post('/update-address', [AuthController::class, 'updateAddress'])->name('frontend.account.updateAddress');
        Route::get('/change-password', [AuthController::class, 'showChangePasswordForm'])->name('frontend.account.changePassword');
        Route::post('/process-change-password', [AuthController::class, 'changePassword'])->name('frontend.account.processChangePassword');

        Route::get('/my-orders', [AuthController::class, 'orders'])->name('frontend.account.orders');
        Route::get('/my-wishlist', [AuthController::class, 'wishlist'])->name('frontend.account.wishlist');
        Route::post('/remove-product-from-wishlist', [AuthController::class, 'removeProductFromWishlist'])->name('frontend.account.removeProductFromWishlist');
        Route::get('/order-detail/{orderId}', [AuthController::class, 'orderDetail'])->name('frontend.account.orderDetail');
        Route::get('/logout', [AuthController::class, 'logout'])->name('frontend.account.logout');

        Route::get('cancel-order/{id}',[AuthController::class, 'cancelOrder'])->name('frontend.account.cancelOrder');
        Route::put('/cancel-reason/{id}', [AuthController::class, 'cancelReason'])->name('frontend.account.cancelReason');
        Route::post('/change-order-status/{id}', [AuthController::class, 'changeOrderStatus'])->name('frontend.account.changeOrderStatus');
    });
});
//Back-end
Route::group(['prefix' => 'admin'], function(){
    Route::group(['middleware' => 'admin.guest'], function() {
        Route::get('/login', [AdminLoginController::class, 'index'])->name('admin.login');
        Route::post('/authenticate', [AdminLoginController::class, 'authenticate'])->name('admin.authenticate');
    });

    Route::group(['middleware' => 'admin.auth'], function() {
        Route::get('/dashboard', [HomeController::class, 'index'])->name('admin.dashboard');
        Route::get('/logout', [HomeController::class, 'logout'])->name('admin.logout');

        //Categories
        Route::get('/categories', [CategoryController::class, 'index'])->name('categories.index');
        Route::get('/categories/create', [CategoryController::class, 'create'])->name('categories.create');
        Route::post('/categories', [CategoryController::class, 'store'])->name('categories.store');
        Route::get('/categories/{category}/edit', [CategoryController::class, 'edit'])->name('categories.edit');
        Route::put('/categories/{category}', [CategoryController::class, 'update'])->name('categories.update');
        Route::delete('/categories/{category}', [CategoryController::class, 'delete'])->name('categories.delete');

        //Sub-categories
        Route::get('/sub-categories', [SubCategoryController::class, 'index'])->name('sub-categories.index');
        Route::get('/sub-categories/create', [SubCategoryController::class, 'create'])->name('sub-categories.create');
        Route::post('/sub-categories', [SubCategoryController::class, 'store'])->name('sub-categories.store');
        Route::get('/sub-categories/{subCategory}/edit', [SubCategoryController::class, 'edit'])->name('sub-categories.edit');
        Route::put('/sub-categories/{subCategory}', [SubCategoryController::class, 'update'])->name('sub-categories.update');
        Route::delete('/sub-categories/{subCategory}', [SubCategoryController::class, 'delete'])->name('sub-categories.delete');

        //Products
        Route::get('/products', [ProductController::class, 'index'])->name('products.index');
        Route::get('/products/create', [ProductController::class, 'create'])->name('products.create');
        Route::post('/products', [ProductController::class, 'store'])->name('products.store');
        Route::get('/products/{product}/edit', [ProductController::class, 'edit'])->name('products.edit');
        Route::put('/products/{product}', [ProductController::class, 'update'])->name('products.update');
        Route::delete('/products/{product}', [ProductController::class, 'delete'])->name('products.delete');
        Route::get('/get-products',[ProductController::class, 'getProducts'])->name('products.getProducts');
        Route::get('/ratings',[ProductController::class, 'productRatings'])->name('products.productRatings');
        Route::get('/change-rating-status',[ProductController::class, 'changeRatingStatus'])->name('products.changeRatingStatus');

        Route::get('/products-subcategories', [ProductSubCategoryController::class, 'index'])->name('product-subcategories.index');
        Route::get('/product-quantity', [ProductQuantityController::class, 'index'])->name('product-quantity.index');

        Route::post('/product-images/update', [ProductImageController::class, 'update'])->name('product-images.update');
        Route::delete('/product-images', [ProductImageController::class, 'delete'])->name('product-images.delete');

        //Coupons
        Route::get('/coupons', [DiscountCodeController::class, 'index'])->name('coupons.index');
        Route::get('/coupons/create', [DiscountCodeController::class, 'create'])->name('coupons.create');
        Route::post('/coupons', [DiscountCodeController::class, 'store'])->name('coupons.store');
        Route::get('/coupons/{coupon}/edit', [DiscountCodeController::class, 'edit'])->name('coupons.edit');
        Route::put('/coupons/{coupon}', [DiscountCodeController::class, 'update'])->name('coupons.update');
        Route::delete('/coupons/{coupon}', [DiscountCodeController::class, 'delete'])->name('coupons.delete');

        //Order
        Route::get('order', [OrderController::class, 'index'])->name('orders.index');
        Route::get('order/{id}', [OrderController::class, 'detail'])->name('orders.detail');
        Route::post('order/change-status/{id}', [OrderController::class, 'changeOrderStatus'])->name('orders.changeOrderStatus');
        Route::get('/order/download-Pdf/{id}', [OrderController::class, 'downloadPdf'])->name('orders.downloadPdf');

        //Import Product
        Route::get('records', [ImportController::class, 'index'])->name('admin.records.index');
        Route::get('records/{id}', [ImportController::class, 'detail'])->name('admin.records.detail');
        Route::get('/records/create', [ImportController::class, 'create'])->name('admin.records.create');
        Route::post('/records', [ImportController::class, 'store'])->name('admin.records.store');
        Route::get('/records/download-Pdf/{id}', [ImportController::class, 'downloadPdf'])->name('admin.records.downloadPdf');

        //Page
        Route::get('/pages', [PageController::class, 'index'])->name('pages.index');
        Route::get('/pages/create', [PageController::class, 'create'])->name('pages.create');
        Route::post('/pages', [PageController::class, 'store'])->name('pages.store');
        Route::get('/pages/{page}/edit', [PageController::class, 'edit'])->name('pages.edit');
        Route::put('/pages/{page}', [PageController::class, 'update'])->name('pages.update');
        Route::delete('/pages/{page}', [PageController::class, 'delete'])->name('pages.delete');

        //User
        Route::get('/users', [UserController::class, 'index'])->name('users.index');
        Route::get('/users/create', [UserController::class, 'create'])->name('users.create');
        Route::post('/users', [UserController::class, 'store'])->name('users.store');
        Route::get('/users/{user}/edit', [UserController::class, 'edit'])->name('users.edit');
        Route::put('/users/{user}', [UserController::class, 'update'])->name('users.update');
        Route::delete('/users/{user}', [UserController::class, 'delete'])->name('users.delete');
        Route::get('/change-role',[UserController::class, 'changeRole'])->name('users.changeRole');



        //Create temp image
        Route::post('/upload-temp-image', [TempImagesController::class, 'create'])->name('temp-images.create');

        //Setting routes
        Route::get('/change-password', [SettingController::class, 'showChangePasswordForm'])->name('admin.showChangePasswordForm');
        Route::post('/process-change-password', [SettingController::class, 'processChangePassword'])->name('admin.processChangePassword');



        Route::get('/getSlug', function(Request $request){
            $slug = '';
            if (!empty($request->title)) {
                $slug = Str::slug($request->title);
            }
            return response()->json([
                'status' => true,
                'slug' => $slug
            ]);
        })->name('getSlug');

        Route::get('/getProductNumber', function(Request $request){
            $productNumber = 0;
            if (!empty($request->number_product)) {
                $productNumber = $request->number_product;
            }
            return response()->json([
                'status' => true,
                'number' => $productNumber
            ]);
        })->name('getProductNumber');

    });
});
