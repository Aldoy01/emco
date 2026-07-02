<?php
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\HomeContentController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\Admin\QuotationController as AdminQuotationController;
use App\Http\Controllers\Admin\SecurityController as AdminSecurityController;
use App\Http\Controllers\Auth\AdminTwoFactorController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\MemberDashboardController;
use App\Http\Controllers\MemberProfileController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\QuotationController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', [PageController::class,'home'])->name('home');
Route::get('/produk', [PageController::class,'products'])->name('products.index');
Route::get('/kategori/{category:slug}', [PageController::class,'category'])->name('categories.show');
Route::get('/pricelist', [PageController::class,'pricelist'])->name('pricelist');
Route::get('/download-catalogue', [PageController::class,'downloads'])->name('downloads');
Route::get('/solusi/{slug?}', [PageController::class,'solutions'])->name('solutions');
Route::get('/artikel', [PageController::class,'articles'])->name('articles');
Route::get('/kontak', [PageController::class,'contact'])->name('contact');
Route::get('/minta-penawaran', [QuotationController::class,'create'])->name('quotation.create');
Route::post('/minta-penawaran', [QuotationController::class,'store'])->middleware('throttle:rfq')->name('quotation.store');

Route::get('/checkout/{product:slug}', [CheckoutController::class, 'create'])->name('checkout.create');
Route::post('/checkout/{product:slug}', [CheckoutController::class, 'store'])->name('checkout.store');
Route::post('/checkout/{product:slug}/login', [CheckoutController::class, 'login'])->middleware('guest')->name('checkout.login');

Route::middleware('auth')->group(function () {
    Route::get('/member/dashboard', [MemberDashboardController::class, 'index'])->name('member.dashboard');
    Route::get('/member/profile', [MemberProfileController::class, 'edit'])->name('member.profile.edit');
    Route::put('/member/profile', [MemberProfileController::class, 'update'])->name('member.profile.update');
    Route::get('/member/password', [MemberProfileController::class, 'password'])->name('member.password.edit');
    Route::put('/member/password', [MemberProfileController::class, 'updatePassword'])->name('member.password.update');
    Route::get('/invoice/{order:invoice_number}', [CheckoutController::class, 'invoice'])->name('checkout.invoice');
    Route::get('/invoice/{order:invoice_number}/konfirmasi', [CheckoutController::class, 'confirm'])->name('checkout.confirm');
    Route::post('/invoice/{order:invoice_number}/konfirmasi', [CheckoutController::class, 'storeConfirmation'])->name('checkout.confirm.store');
});

Route::get('/produk/{product:slug}', [PageController::class,'product'])->name('products.show');

Auth::routes(['register' => true, 'verify' => true]);

Route::middleware('auth')->prefix('admin')->name('admin.')->group(function(){
    Route::get('two-factor', [AdminTwoFactorController::class, 'show'])->name('two-factor.show');
    Route::post('two-factor', [AdminTwoFactorController::class, 'verify'])->middleware('throttle:6,1')->name('two-factor.verify');
    Route::post('two-factor/resend', [AdminTwoFactorController::class, 'resend'])->middleware('throttle:3,1')->name('two-factor.resend');
});

Route::middleware(['auth','admin','admin.2fa'])->prefix('admin')->name('admin.')->group(function(){
    Route::get('/', [DashboardController::class,'index'])->name('dashboard');
    Route::resource('products', AdminProductController::class)->except('show');
    Route::get('quotations/export', [AdminQuotationController::class,'export'])->name('quotations.export');
    Route::resource('quotations', AdminQuotationController::class)->only(['index','show','update']);
    Route::get('orders/{order:invoice_number}/proof', [AdminOrderController::class, 'proof'])->name('orders.proof');
    Route::resource('orders', AdminOrderController::class)->only(['index', 'show', 'update']);
    Route::get('content/home', [HomeContentController::class, 'edit'])->name('content.home.edit');
    Route::put('content/home', [HomeContentController::class, 'update'])->name('content.home.update');
    Route::get('security', [AdminSecurityController::class, 'index'])->name('security.index');
});
