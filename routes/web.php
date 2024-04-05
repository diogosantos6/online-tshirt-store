<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\TshirtImageController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\Auth\ChangePasswordController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PriceController;
use App\Http\Controllers\ColorController;
use App\Http\Controllers\CategoryController;



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



Route::view('home', 'home');

Auth::routes(['verify' => true]);

///Send receipt mail
Route::get('orders/sendEmail', [OrderController::class, 'sendEmail'])->middleware('can:update,order');

///Tshirts
Route::get('/', [TshirtImageController::class, 'catalogo'])->name('root');

Route::get('/catalogo', [TshirtImageController::class, 'catalogo'])->name('tshirt_images.catalogo');

Route::get('tshirt_images/minhas', [TshirtImageController::class, 'minhasTshirtImages'])->name('tshirt_images.minhas')
    ->middleware('can:viewMinhas,App\Models\TshirtImage');

Route::get('catalogo/tshirt_image/{tshirt_image}', [TshirtImageController::class, 'showProduto'])->name('tshirt_images.produto');

Route::get('tshirt_images/create', [TshirtImageController::class, 'create'])->name('tshirt_images.create')
    ->middleware('can:create,App\Models\TshirtImage');

Route::post('tshirt_images', [TshirtImageController::class, 'store'])->name('tshirt_images.store')
    ->middleware('can:store,App\Models\TshirtImage');


///Cart
Route::post('cart/edit', [CartController::class, 'editCartItem'])->name('cart.editCartItem');

Route::post('cart/{tshirt_image}', [CartController::class, 'addToCart'])->name('cart.add');

Route::get('cart', [CartController::class, 'show'])->name('cart.show');

Route::delete('cart', [CartController::class, 'removeFromCart'])->name('cart.remove');

Route::put('cart/{tshirt_image}', [CartController::class, 'updateCartItem'])->name('cart.update');

Route::put('cart', [CartController::class, 'updateItemQty'])->name('cart.updateItemQuantity');

Route::post('cart', [CartController::class, 'store'])->name('cart.store');


///Verified Mail
Route::middleware('verified')->group(function () {

    Route::resource('tshirt_images', TshirtImageController::class)
        ->only(['index'])
        ->middleware('can:viewAny,App\Models\TshirtImage');

    Route::resource('tshirt_images', TshirtImageController::class)
        ->only(['show'])
        ->middleware('can:view,tshirt_image');

    Route::get('users/create', [UserController::class, 'create'])->name('users.create')->middleware('can:create,App\Models\User');
    Route::post('users', [UserController::class, 'store'])->name('users.store')->middleware('can:create,App\Models\User');

    Route::get('tshirt_images/minhas/{image_url?}', [TshirtImageController::class, 'getPrivateTshirtImage'])->name('tshirt_images.minha')
        ->middleware('can:viewMinhas,App\Models\TshirtImage');

    Route::resource('tshirt_images', TshirtImageController::class)
        ->only(['update', 'edit'])
        ->middleware('can:create,tshirt_image');

    Route::resource('tshirt_images', TshirtImageController::class)
        ->only(['destroy'])
        ->middleware('can:delete,tshirt_image');


    ///Orders
    Route::get('encomendas', [OrderController::class, 'minhasEncomendas'])->name('orders.minhas')
        ->middleware('can:viewEncomendas,App\Models\Order');

    Route::get('encomendas/{order}', [OrderController::class, 'minhaEncomenda'])->name('orders.minha')
        ->middleware('can:viewEncomendas,App\Models\Order');

    Route::get('orders/fatura/{receipt_url?}', [OrderController::class, 'getFatura'])->name('orders.fatura')
        ->middleware('can:viewAny,App\Models\Order');


    Route::resource('orders', OrderController::class)
        ->only(['index'])
        ->middleware('can:viewAny,App\Models\Order');

    Route::resource('orders', OrderController::class)
        ->only(['show'])
        ->middleware('can:view,order');

    Route::resource('orders', OrderController::class)
        ->only(['create', 'store'])
        ->middleware('can:create,App\Models\Order');

    Route::resource('orders', OrderController::class)
        ->only(['update', 'edit'])
        ->middleware('can:update,order');

    Route::resource('orders', OrderController::class)
        ->only(['destroy'])
        ->middleware('can:delete,order');


    //Prices
    Route::resource('prices', PriceController::class)
        ->only(['index'])
        ->middleware('can:viewAny,App\Models\Price');

    Route::resource('prices', PriceController::class)
        ->only(['show'])
        ->middleware('can:view,price');

    Route::resource('prices', PriceController::class)
        ->only(['update', 'edit'])
        ->middleware('can:update,price');


    ///Colors
    Route::delete('colors/{color}/delete', [ColorController::class, 'destroy'])->name('colors.destroy')
        ->middleware('can:delete,color');

    Route::resource('colors', ColorController::class)
        ->only(['index'])
        ->middleware('can:viewAny,App\Models\Color');

    Route::resource('colors', ColorController::class)
        ->only(['update', 'edit'])
        ->middleware('can:update,color');


    ///Cart
    Route::get('cart/confirmar', [CartController::class, 'confirmar'])->name('cart.confirmar')
        ->middleware('auth');




    ///Dashboard
    Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard.index')
        ->middleware('can:viewAny,App\Models\User');


    ///Users
    Route::delete('users/{user}/foto', [UserController::class, 'destroy_foto'])->name('users.foto.destroy')
        ->middleware('can:delete,user');

    Route::resource('users', UserController::class)
        ->only(['index'])
        ->middleware('can:viewAny,App\Models\User');

    Route::resource('users', UserController::class)
        ->only(['show'])
        ->middleware('can:view,user');

    Route::resource('users', UserController::class)
        ->only(['create', 'store'])
        ->middleware('can:create,App\Models\User');

    Route::resource('users', UserController::class)
        ->only(['update', 'edit'])
        ->middleware('can:update,user');

    Route::resource('users', UserController::class)
        ->only(['destroy'])
        ->middleware('can:delete,user');


    ///Customers
    Route::delete('customers/{customer}/foto', [CustomerController::class, 'destroy_foto'])->name('customers.foto.destroy')
        ->middleware('can:delete,customer');

    Route::resource('customers', CustomerController::class)
        ->only(['index'])
        ->middleware('can:viewAny,App\Models\Customer');

    Route::resource('customers', CustomerController::class)
        ->only(['show'])
        ->middleware('can:view,customer');

    Route::resource('customers', CustomerController::class)
        ->only(['create', 'store']);

    Route::resource('customers', CustomerController::class)
        ->only(['update', 'edit'])
        ->middleware('can:update,customer');

    Route::resource('customers', CustomerController::class)
        ->only(['destroy']);


    //Categories
    Route::post('categories', [CategoryController::class, 'store'])->name('categories.store')
        ->middleware('can:create,App\Models\Category');

    Route::delete('categories/{category}/delete', [CategoryController::class, 'destroy'])->name('categories.destroy')
        ->middleware('can:delete,category');

    Route::get('categories/create', [CategoryController::class, 'create'])->name('categories.create')
        ->middleware('can:create,App\Models\Category');

    Route::resource('categories', CategoryController::class)
        ->only(['index'])
        ->middleware('can:viewAny,App\Models\Category');


    ///Password
    Route::get('/password/change', [ChangePasswordController::class, 'show'])
        ->name('password.change.show');

    Route::post('/password/change', [ChangePasswordController::class, 'store'])
        ->name('password.change.store');
});
