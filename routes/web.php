<?php

use App\Http\Middleware\VerifyCsrfToken;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::post('/userpayment/proccessor/order', 'OrderController@processed')->withoutMiddleware([VerifyCsrfToken::class]);

// api routes
Route::prefix('collection')->group(function () {
    Route::get('/find', 'ProductController@index');
    Route::get('/featured', 'ProductController@getFeatured');
    Route::get('/latest', 'HomeController@latestProducts');
    Route::get('/{category}', 'ProductController@index');
});
Route::get('/product/{product}', 'ProductController@show');
Route::get('/product/{product}/related', 'ProductController@getRelated');

Route::get('/cart', 'CartController@index');
Route::post('/cart/{product}', 'CartController@store')
    ->where('product', "[a-z0-9]+(?:-[a-z0-9]+)*");
Route::put('/cart/{product}/{id}', 'CartController@update')
    ->where('id', "[0-9]+");
Route::post('/cart/{id}/delete', 'CartController@destroy')
    ->where('id', "[0-9]+");
Route::delete('/cart/all', 'CartController@destroyAll');
Route::delete('/cart/delete/wish', 'CartController@destroyAllWish');

Route::get('/product/{id}/rates', 'RateController@index');
Route::prefix('rates')->middleware('auth')->group(function () {
    Route::post('/', 'RateController@store');
    Route::patch('/{rate}', 'RateController@update');
    Route::delete('/{rate}', 'RateController@destroy');
});

Route::post('/address', 'AddressController@store');
Route::middleware(['auth'])->group(function () {
    Route::middleware('can:owner-model,address')->group(function () {
        Route::patch('/address/{address}', 'AddressController@update');
        Route::delete('/address/{address}', 'AddressController@destroy');
    });

    Route::post('/user/profile/image', 'UserProfileController@updateImg')
        ->middleware('can:visit-user-profile,userId')
        ->name('userImageUpload');
});
Route::get('/user/{userId}/addresses', 'AddressController@show')->middleware('auth');

Route::post('/order', 'OrderController@store');
Route::post('/order/payment', 'OrderController@iframeUri');
Route::patch('/order/{order}', 'OrderController@update');


Route::post('/mail', 'SendMail');

Route::group([
    'middleware' => [
        'auth', 'can:root'
    ],
    'namespace' => 'Admin',
], function () {
    Route::patch('/users/{enc_id}/role', 'UserController@updateRole');
    Route::patch('/orders/{enc_id}/complete', 'OrderController@complete');
    Route::group([
        'prefix' => 'product',
    ], function () {
        // Route::get('create', 'ProductController@create');
        Route::post('', 'ProductController@store');
        // Route::get('{product}', 'ProductController@edit');
        Route::patch('star/{product}', 'ProductController@setFeatured');
        Route::post('{product}', 'ProductController@update');
        Route::delete('{product}', 'ProductController@destroy');
    });
    Route::prefix('categories')->group(function () {
        // Route::post('', 'CategoryController@store');
    });
});

Route::post('/track/{order_num}/complete', 'OrderTrackerController@complete')
    ->middleware('auth', 'can:root');

Route::group(
    [
        'prefix' => LaravelLocalization::setLocale(),
        'middleware' => ['localeCookieRedirect', 'localeSessionRedirect']
    ],
    function () {
        Route::get('/', 'HomeController@index')->name('home');

        Route::get('/contact', 'HomeController@contact');

        Auth::routes(['verify' => true]);

        Route::get('/products/find', 'ProductController@index');
        Route::get('/products/{category}', 'ProductController@index');
        Route::get('/product/{product}', 'ProductController@show');

        Route::get('/cart/view', 'CartController@show');
        Route::get('/cart/checkout', 'CartController@checkAmount');

        Route::prefix('/order')->group(function () {
            Route::get('/user-info', 'OrderController@create');
            Route::get('/payment/completed', 'OrderController@completed');

            Route::get('/track', 'OrderTrackerController@tracker');
            Route::post('/track', 'OrderTrackerController@getTracked');
            Route::patch('/track/{order_num}/complete', 'OrderTrackerController@complete');

            // Route::get('/{order}', 'OrderController@show');
        });

        Route::group([
            'prefix' => 'user/{userId?}',
            'middleware' => [
                'auth',
                'verified',
                'can:visit-user-profile,userId'
            ],
            'where' => ['userId' => '^((?!orders|addresses|profile|rates).)*$']
        ], function () {
            Route::get('', 'UserProfileController@index')->name('userDash');
            Route::get('orders', 'UserProfileController@loadOrders')->name('userOrders');
            Route::get('addresses', 'UserProfileController@addressList')->name('userAddress');
            Route::get('rates', 'UserProfileController@loadRates')->name('userRates');
            Route::get('profile', 'UserProfileController@loadProfile')->name('userProfile');
            Route::patch('profile/pass', 'UserProfileController@updatePass')->name('updatePass');
        });

        Route::group([
            'prefix' => 'root',
            'middleware' => [
                'auth', 'can:root'
            ],
            'namespace' => 'Admin',
        ], function () {
            Route::get('', 'DashboardController')->name('admin.dash');
            Route::get('products', 'ProductController@index')->name('admin.products');
            Route::get('orders', 'OrderController@index')->name('admin.orders');
            Route::get('users', 'UserController@index')->name('admin.users');
            Route::prefix('categories')->group(function () {
                Route::get('', 'CategoryController@index')->name('admin.categories');
                Route::post('', 'CategoryController@store')->name('admin.save.cat');
                Route::patch('{category}', 'CategoryController@update')->name('admin.categories.update');
                Route::delete('{category}', 'CategoryController@destroy')->name('admin.categories.delete');

                Route::prefix('sub')->group(function () {
                    Route::post('', 'SubCategoryController@store')->name('admin.sub.save');
                    Route::patch('{category}', 'SubCategoryController@update')->name('admin.sub.update');
                    Route::delete('{category}', 'SubCategoryController@destroy')->name('admin.sub.delete');
                });
            });
            Route::prefix('tags')->group(function () {
                Route::get('', 'TagController@index')->name('admin.tags');
                Route::post('', 'TagController@store')->name('admin.tags.save');
                Route::patch('{tag}', 'TagController@update')->name('admin.tags.update');
                Route::delete('{tag}', 'TagController@destroy')->name('admin.tags.delete');
            });

            Route::prefix('config')->group(function () {
                Route::get('', 'ConfigController@index')->name('admin.config');
                Route::post('carsoul', 'ConfigController@storeImg')->name('admin.config.carsoul');
                Route::delete('carsoul/{id}', 'ConfigController@destroy')->name('admin.config.carsoul.delete');
            });
        });
    }
);
