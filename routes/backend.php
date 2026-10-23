<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\BlogController;
use App\Http\Controllers\Admin\CrudController;
use App\Http\Controllers\Admin\IconController;
use App\Http\Controllers\Admin\ItemController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\SliderController;
use App\Http\Controllers\Admin\EnquiryController;
use App\Http\Controllers\Admin\GalleryController;
use App\Http\Controllers\Admin\JobcardController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\ServiceController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\StudentController;
use App\Http\Controllers\Admin\TeacherController;
use App\Http\Controllers\Admin\ActivityController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\EmployeeController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\RazorpayPaymentController;
use App\Http\Controllers\Admin\ItemcategoryController;

//End of use statements

Route::middleware(['auth', 'admin', 'preventBackHistory'])->group(function () {
    Route::prefix('admin')->name('admin.')->group(function () {
        // Slider
        Route::resource('cruds', CrudController::class);
        Route::post('cruds/data', [CrudController::class, 'data'])->name('cruds.data');
        Route::get('cruds/generate/{crud}',[CrudController::class, 'generate'])->name('cruds.generate');
        Route::get('cruds/undo/{crud}',[CrudController::class, 'undo'])->name('cruds.undo');


        Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard.index');

        //Roles
        Route::resource('roles', RoleController::class);
        Route::post('roles/data', [RoleController::class, 'data'])->name('roles.data');
        Route::post('roles/list',[RoleController::class, 'list'])->name('roles.list');

        //Permissions
        Route::get('roles/{role}/permission/show',[RoleController::class, 'permissionsShow'])->name('roles.permissions.show');
        Route::post('roles/{role}/permission/update',[RoleController::class, 'permissionsUpdate'])->name('roles.permissions.update');

        //Users
        Route::resource('users', UserController::class);
        Route::post('users/data', [UserController::class, 'data'])->name('users.data');
        Route::post('users/list',[UserController::class, 'list'])->name('users.list');
        Route::post('users/change-status',[UserController::class, 'changeStatus'])->name('users.change.status');

        //Employees
        Route::resource('employees', EmployeeController::class);
        Route::post('employees/data', [EmployeeController::class, 'data'])->name('employees.data');
        Route::post('employees/list',[EmployeeController::class, 'list'])->name('employees.list');
        Route::post('employees/change-status',[EmployeeController::class, 'changeStatus'])->name('employees.change.status');


        // ---------------------------------------------------------------
        // Laravel V3 updated routes ::
        // ---------------------------------------------------------------

        //Enquiries
        Route::resource('enquiries', EnquiryController::class);
        Route::post('enquiries/data', [EnquiryController::class, 'data'])->name('enquiries.data');
        Route::post('enquiries/list',[EnquiryController::class, 'list'])->name('enquiries.list');

        // Slider
        Route::resource('sliders', SliderController::class);
        Route::post('sliders/data', [SliderController::class, 'data'])->name('sliders.data');
        Route::post('sliders/list',[SliderController::class, 'list'])->name('sliders.list');
        Route::post('sliders/change-status',[SliderController::class, 'changeStatus'])->name('sliders.change.status');


        // Category
        Route::resource('categories', CategoryController::class);
        Route::post('categories/data', [CategoryController::class, 'data'])->name('categories.data');
        Route::post('categories/list',[CategoryController::class, 'list'])->name('categories.list');
        Route::post('categories/change-home_featured-status', [CategoryController::class, 'changeHomeFeaturedStatus'])->name('categories.change.home_featured.status');
        Route::post('categories/change-status',[CategoryController::class, 'changeStatus'])->name('categories.change.status');


        // Product
        Route::resource('products', ProductController::class);
        Route::post('products/data', [ProductController::class, 'data'])->name('products.data');
        Route::post('products/list',[ProductController::class, 'list'])->name('products.list');
        Route::post('products/change-home_featured-status', [ProductController::class, 'changeHomeFeaturedStatus'])->name('products.change.home_featured.status');
        Route::post('products/change-status',[ProductController::class, 'changeStatus'])->name('products.change.status');

        // Service
        Route::resource('services', ServiceController::class);
        Route::post('services/data', [ServiceController::class, 'data'])->name('services.data');
        Route::post('services/list',[ServiceController::class, 'list'])->name('services.list');
        Route::post('services/change-home_featured-status', [ServiceController::class, 'changeHomeFeaturedStatus'])->name('services.change.home_featured.status');
        Route::post('services/change-status',[ServiceController::class, 'changeStatus'])->name('services.change.status');


        // Setting
        Route::resource('settings', SettingController::class);
        Route::post('settings/data', [SettingController::class, 'data'])->name('settings.data');
        Route::post('settings/list',[SettingController::class, 'list'])->name('settings.list');
        Route::post('settings/change-status',[SettingController::class, 'changeStatus'])->name('settings.change.status');
        Route::post('settings/get/data', [SettingController::class, 'data'])->name('settings.get.data');
        Route::post('settings/get/data/page', [SettingController::class, 'getDataPage'])->name('settings.get.data.page');
        Route::post('settings/update/data/page', [SettingController::class, 'updateDataPage'])->name('settings.update.data.page'); 
                           

         //Orders
         Route::resource('orders', OrderController::class);
         Route::post('orders/data', [OrderController::class, 'data'])->name('orders.data');
         Route::post('orders/list',[OrderController::class, 'list'])->name('orders.list');

         // Activity Logs
        Route::resource('activities', ActivityController::class); 
        Route::post('activities/data', [ActivityController::class, 'data'])->name('activities.data'); 
        Route::post('activities/list', [ActivityController::class, 'list'])->name('activities.list'); 


        //End of File
    });
});