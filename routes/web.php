<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\SupplierController;
use App\Http\Middleware\TokenVerificationMiddleware;

Route::get('/', function () {
    return view('pages.home');
});

// Auth routes
Route::get('/login', [UserController::class, 'loginPage'])->name('login.page');
Route::post('/user-login', [UserController::class, 'userLogin'])->name('auth.login');

Route::get('/user-registration', [UserController::class, 'registrationPage'])->name('registration.page');
Route::post('/registration', [UserController::class, 'userRegistration'])->name('auth.register');

// OTP
Route::get('/send-otp', [UserController::class, 'sendOTPPage'])->name('otp.send.page');
Route::post('/send-otp', [UserController::class, 'SendOTPCode'])->name('otp.send');
Route::get('/verify-otp', [UserController::class, 'verifyOTPPage'])->name('otp.verify.page');
Route::post('/verify-otp', [UserController::class, 'verifyOTP'])->name('otp.verify');

// Protected routes
Route::middleware([TokenVerificationMiddleware::class])->group(function () {
    Route::get('/reset-password', [UserController::class, 'resetPasswordPage'])->name('reset.password.page');
    Route::post('/reset-password', [UserController::class, 'resetPassword'])->name('reset.password.submit');
    Route::get('/logout', [UserController::class, 'logout'])->name('logout');
    Route::get('/dashboard', [DashboardController::class, 'dashboard'])->name('dashboard');
    Route::get('/profile', [UserController::class, 'profilePage'])->name('profile');
    Route::get('/profile-data', [UserController::class, 'profile']);
    Route::post('/profile-update', [UserController::class, 'profileUpdate'])->name('profile.update');

    // Dashboard Summary
    Route::get('/summary', [DashboardController::class, 'summary'])->name('dashboard.summary');



    //Category
    Route::get('/category', [CategoryController::class, 'index'])->name('category');
    Route::get('/get-categories', [CategoryController::class, 'getCategories'])->name('categories.get');
    Route::post('/category-by-id', [CategoryController::class, 'categoryById']);
    Route::post('/create-category', [CategoryController::class, 'store'])->name('categories.create');
    Route::post('/update-category', [CategoryController::class, 'update'])->name('categories.update');
    Route::post('/delete-category', [CategoryController::class, 'destroy'])->name('categories.delete');

    //Customer
    Route::get('/customer', [CustomerController::class, 'index'])->name('customer');
    Route::get('/get-customers', [CustomerController::class, 'getCustomers'])->name('customers.get');
    Route::post('/customer-by-id', [CustomerController::class, 'customerById']);
    Route::post('/create-customer', [CustomerController::class, 'store'])->name('customers.create');
    Route::post('/update-customer', [CustomerController::class, 'update'])->name('customers.update');
    Route::post('/delete-customer', [CustomerController::class, 'destroy'])->name('customers.delete');

    //Suppliers
    Route::get('/supplier', [SupplierController::class, 'supplierPage'])->name('supplier');
    Route::get('/get-suppliers', [SupplierController::class, 'getSuppliers'])->name('suppliers.get');
    Route::post('/supplier-by-id', [SupplierController::class, 'supplierById']);
    Route::post('/create-supplier', [SupplierController::class, 'store'])->name('suppliers.create');
    Route::post('/update-supplier', [SupplierController::class, 'update'])->name('suppliers.update');
    Route::post('/delete-supplier', [SupplierController::class, 'destroy'])->name('suppliers.delete');

    //Product
    Route::get('/product', [ProductController::class, 'index'])->name('product');
    Route::get('/get-products', [ProductController::class, 'getProducts'])->name('products.get');
    Route::post('/product-by-id', [ProductController::class, 'productById']);
    Route::post('/create-product', [ProductController::class, 'store'])->name('products.create');
    Route::post('/update-product', [ProductController::class, 'update'])->name('products.update');
    Route::post('/delete-product', [ProductController::class, 'destroy'])->name('products.delete');

    //sales
    Route::get('/sale', [InvoiceController::class, 'salesPage'])->name('sales');

    //purchase
    Route::get('/purchase-page', [PurchaseController::class, 'purchasePage'])->name('purchase');
    Route::get('/get-purchases', [PurchaseController::class, 'getPurchases']);

    Route::get('/createPurchasePage', [PurchaseController::class, 'purchaseCreatePage'])->name('purchase.create');
    Route::post('/create-purchase', [PurchaseController::class, 'purchaseCreate']);
    Route::post('/purchase-details', [PurchaseController::class, 'purchaseDetails']);
    Route::post('/delete-purchase', [PurchaseController::class, 'deletePurchase']);



    // Invoice
    Route::get('/invoice', [InvoiceController::class, 'InvoicePage'])->name('invoices');
    Route::get('/invoices-select', [InvoiceController::class, 'invoiceSelect'])->name('invoices.get');

    Route::post('/invoice-details', [InvoiceController::class, 'InvoiceDetails'])->name('invoice.report');

    Route::post('/create-invoice', [InvoiceController::class, 'invoiceCreate'])->name('invoice.create');
    Route::post('/delete-invoice', [InvoiceController::class, 'deleteInvoice'])->name('invoice.delete');

    //Reports
    Route::get('/report', [ReportController::class, 'ReportPage'])->name('reports');
    Route::get('/sales-report/{FromDate}/{ToDate}', [ReportController::class, 'salesReport']);
    Route::get('/purchase-report/{FromDate}/{ToDate}', [ReportController::class, 'purchaseReport']);
    Route::get('/profit-report/{FromDate}/{ToDate}', [ReportController::class, 'profitReport']);

});
