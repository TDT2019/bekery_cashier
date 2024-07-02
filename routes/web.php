<?php
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\CashierController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function () {
    Route::get('login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('login', [LoginController::class, 'login']);
});

Route::post('logout', [LoginController::class, 'logout'])->name('logout')->middleware('auth');
Route::get('dashboard', function () {
    return view('dashboard');
})->middleware('auth');

Route::middleware('auth')->group(function () {
    Route::get('/', function () {
        return redirect('cashier');
    });
    Route::get('register', [RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('register', [RegisterController::class, 'register']);
    Route::resource('products', ProductController::class);
    Route::get('/cashier', [CashierController::class, 'index'])->name('cashier.index');
    Route::get('/cashier/search', [CashierController::class, 'search'])->name('cashier.search');
    Route::post('/cashier/add-to-cart', [CashierController::class, 'addToCart'])->name('cashier.add_to_cart');
    Route::post('/cashier/remove-from-cart', [CashierController::class, 'removeFromCart'])->name('cashier.remove_from_cart');
    Route::post('/cashier/checkout', [CashierController::class, 'checkout'])->name('cashier.checkout');
    Route::get('/cashier/receipt/{id}', [CashierController::class, 'receipt'])->name('cashier.receipt');
    Route::get('/laporan', [LaporanController::class, 'index'])->name('laporan.index');
    Route::get('/laporan/{id}', [LaporanController::class, 'show'])->name('laporan.show');
    Route::get('/laporan/receipt/{id}', [LaporanController::class, 'receipt'])->name('laporan.receipt');
});
