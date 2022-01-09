<?php

use App\Http\Controllers\BarcodeController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TestController;
use App\Http\Controllers\WPPageController;
use App\Http\Controllers\WPBlogController;
use App\Http\Controllers\WPFormController;
use App\Http\Controllers\WPMenuController;
use App\Http\Controllers\WPGalleryController;
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

// Route::get('/welcome', function () {
//     return view('welcome');
// });
Route::get('api/test/{token}', [TestController::class, 'index'])->name('api_test');

Route::post('api/page/get_page_by_name', [WPPageController::class, 'get_page_by_name'])->name('api_page_get_page_by_name');
Route::post('api/blog/get_posts', [WPBlogController::class, 'get_posts'])->name('api_blog_get_posts');
Route::post('api/form/send_message', [WPFormController::class, 'send_message'])->name('api_form_send_message');
Route::post('api/menu/get_urls', [WPMenuController::class, 'get_urls'])->name('api_menu_get_urls');
Route::post('api/gallery/get_images', [WPGalleryController::class, 'get_images'])->name('api_gallery_get_images');

Route::post('api/barcode/info', [BarcodeController::class, 'index'])->name('api_barcode_info');
Route::post('api/barcode/set', [BarcodeController::class, 'set'])->name('api_barcode_set');
Route::post('api/barcode/get', [BarcodeController::class, 'get'])->name('api_barcode_get');
Route::post('api/barcode/login', [BarcodeController::class, 'login'])->name('api_barcode_login');

