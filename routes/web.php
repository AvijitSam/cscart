<?php

use Illuminate\Support\Facades\Route;

// Route::get('/', function () {
//     return view('welcome');
// });
Route::get('/', 'App\Http\Controllers\admin\AuthController@indexFirst')->name('indexpages');
/*****************admin copied from other project ***********************/

Route::get('/login', 'App\Http\Controllers\admin\AuthController@index')->name('login');
Route::post('/authentication','App\Http\Controllers\admin\AuthController@verifyCredentials')->name('admin.authentication');

Route::any('/forgot-password', 'App\Http\Controllers\admin\AuthController@forgotPassword')->name('admin.forgot.password');
Route::any('/reset-password/{encryptCode}','App\Http\Controllers\admin\AuthController@resetPassword')->name('admin.reset.password');

//Route::get('/dashboard', 'App\Http\Controllers\admin\DashboardController@dashboardView')->name('admin.dashboard')->middleware('auth:admin');

Route::group(['middleware' => 'auth:admin','as' => 'admin.'], function () {
    //BookingController@theCalendar
    Route::get('/dashboard', 'App\Http\Controllers\admin\DashboardController@dashboardView')->name('dashboard');

    Route::group(['prefix' => 'order-management', 'as' => 'order-management.'], function () {
        Route::get('/order-list', 'App\Http\Controllers\admin\OrderController@OrderList')->name('order-list');
        Route::get('/order-list-table', 'App\Http\Controllers\admin\OrderController@OrderListTable')->name('order-list-table');
        Route::get('/coupon-list', 'App\Http\Controllers\admin\OrderController@CouponList')->name('coupon-list');
        Route::get('/coupon-list-table', 'App\Http\Controllers\admin\OrderController@CouponDetails')->name('coupon-list-table');
    });
    // Route::get('/dashboard', 'App\Http\Controllers\admin\BookingController@theCalendar')->name('dashboard');
    Route::any('/settings', 'App\Http\Controllers\admin\DashboardController@settings')->name('settings');
    Route::get('/logout', 'App\Http\Controllers\admin\AuthController@logout')->name('logout');
    Route::get('/change-password','App\Http\Controllers\admin\DashboardController@showChangePasswordForm')->name('changePassword');
    Route::post('/change-password','App\Http\Controllers\admin\DashboardController@changePassword')->name('changePassword');

    Route::group(['prefix' => 'project-management', 'as' => 'project-management.'], function () {
        /* Project Management */

        Route::get('/project-list', 'App\Http\Controllers\admin\ProjectController@ProjectList')->name('project-list');
        Route::get('/project-list-user/{encryptCode}', 'App\Http\Controllers\admin\ProjectController@ProjectListByUser')->name('project-list-user');
        Route::get('/project-list-table', 'App\Http\Controllers\admin\ProjectController@ProjectListTable')->name('project-list-table');

        Route::get('/project-list-table-user/{encryptCode}', 'App\Http\Controllers\admin\ProjectController@ProjectListTableUser')->name('project-list-table-user');


        Route::any('/save-double-amount', 'App\Http\Controllers\admin\ProjectController@SaveDoubleAmount')->name('save-double-amount');

        Route::get('/set-double-amount/{encryptCode}', 'App\Http\Controllers\admin\ProjectController@SetDoubleAmount')->name('set-double-amount');

        Route::get('/project-detail/{encryptCode}', 'App\Http\Controllers\admin\ProjectController@ProjectDetail')->name('project-detail');
        Route::get('/project-modify/{encryptCode}', 'App\Http\Controllers\admin\ProjectController@ProjectModify')->name('project-modify');
        Route::post('/project-edit-save', 'App\Http\Controllers\admin\ProjectController@ProjectEditSave')->name('project-edit-save');
        Route::post('/project-gallery-save', 'App\Http\Controllers\admin\ProjectController@ProjectEditGallerySave')->name('project-gallery-save');
        Route::get('/project-status-change/{encryptCode}', 'App\Http\Controllers\admin\ProjectController@ProjectStatusChange')->name('project-status-change');
        Route::any('/project-status-change-save', 'App\Http\Controllers\admin\ProjectController@ProjectStatusUpdate')->name('project-status-change-save');

        Route::get('/donation-list/{encryptCode}', 'App\Http\Controllers\admin\ProjectController@DonationList')->name('donation-list');
        Route::get('/donation-list-table/{encryptCode}', 'App\Http\Controllers\admin\ProjectController@DonationListTable')->name('donation-list-table');

        /*----vishakha 16-8-2022----------*/
        Route::get('/donation-detail/{encryptCode}', 'App\Http\Controllers\admin\ProjectController@DonationDetails')->name('donation-detail');
        Route::get('/donation-download/{encryptCode}', 'App\Http\Controllers\admin\ProjectController@DonationExcelDownload')->name('donation-download');
        Route::post('/donation-status/{encryptCode}', 'App\Http\Controllers\admin\ProjectController@DonationStatus')->name('donation-status');
        Route::get('/add-donation/{encryptCode}', 'App\Http\Controllers\admin\ProjectController@DonationAddManual')->name('add-donation');
        Route::post('/donation-add-save', 'App\Http\Controllers\admin\ProjectController@DonationAddManualSave')->name('donation-add-save');
        /*----vishakha 16-8-2022----------*/

        

        Route::get('/set-double-amount/{encryptCode}', 'App\Http\Controllers\admin\ProjectController@SetDoubleAmount')->name('set-double-amount');
        // Route::get('/payment-detail/{encryptCode}','App\Http\Controllers\admin\PaymentController@paymentDetail')->name('payment-detail');

        // Route::get('/invoice-download/{encryptCode}','App\Http\Controllers\admin\PaymentController@invoiceDownload')->name('invoice-download');
        // Route::any('/pay-download','App\Http\Controllers\admin\PaymentController@paymentReportDownload')->name('pay-download');
    });


    Route::group(['prefix' => 'user-management', 'as' => 'user-management.'], function () {
        /*User Management*/
        

        Route::get('/site-customer-user-list', 'App\Http\Controllers\admin\UserController@SiteuserCustomerList')->name('site.user.customer.list');
        Route::get('/site-customer-user-list-table', 'App\Http\Controllers\admin\UserController@SiteuserCustomerListTable')->name('site.user.customer.list.table');
        Route::get('/user-add', 'App\Http\Controllers\admin\UserController@userAdd')->name('user-add');
        Route::post('/user-add-save', 'App\Http\Controllers\admin\UserController@userAddSave')->name('user-add-save');
        Route::get('/user-customer-delete/{encryptCode}','App\Http\Controllers\admin\UserController@userCustomerDelete')->name('user-customer-delete');
        Route::get('/reset-customer-user-status/{encryptCode}','App\Http\Controllers\admin\UserController@resetuserCustomerStatus')->name('reset-customer-user-status');
        Route::get('/user-customer-detail/{encryptCode}', 'App\Http\Controllers\admin\UserController@userCustomerdetail')->name('user-customer-detail');
        Route::get('/user-customer-add', 'App\Http\Controllers\admin\UserController@SiteuserCustomerAdd')->name('user-customer-add');
        Route::any('/admin-add', 'App\Http\Controllers\admin\UserController@adminAdd')->name('site.user.admin.add');

        Route::get('/user-edit/{encryptCode}', 'App\Http\Controllers\admin\UserController@userCustomerEdit')->name('user-edit');

        Route::post('/user-edit-save', 'App\Http\Controllers\admin\UserController@userCustomerEditSave')->name('user-edit-save');

        Route::get('/user-customer-add', 'App\Http\Controllers\admin\UserController@SiteuserCustomerAdd')->name('user-customer-add');

        Route::post('/user-customer-add-save', 'App\Http\Controllers\admin\UserController@userCustomerAddSave')->name('user-customer-add-save');

        Route::get('/user-customer-detail/{encryptCode}', 'App\Http\Controllers\admin\UserController@userCustomerDetail')->name('user-customer-detail');

        /* Credit Management */

        Route::get('/user-customer-credit/{encryptCode}', 'App\Http\Controllers\admin\CreditController@customerCreditDetailList')->name('user-customer-credit');
        Route::get('/user-customer-credit-table/{encryptCode}', 'App\Http\Controllers\admin\CreditController@customerCreditDetailListTable')->name('user-customer-credit-table');

        Route::get('/customer-credit-add/{encryptCode}', 'App\Http\Controllers\admin\CreditController@CustomerCreditAdd')->name('customer-credit-add');

        Route::post('/customer-credit-add-save', 'App\Http\Controllers\admin\CreditController@CustomerCreditAddSave')->name('customer-credit-add-save');

        Route::get('/user-customer-credit-delete/{encryptCode}','App\Http\Controllers\admin\CreditController@customerCreditDelete')->name('user-customer-credit-delete');


        
    });

    /*-----------vishakha 22-08-2022------------*/
    Route::group(['prefix' => 'donation-management', 'as' => 'donation-management.'], function () {
        Route::get('/donation-list', 'App\Http\Controllers\admin\DonationController@DonationList')->name('donation-list');
        Route::get('/donation-list-table','App\Http\Controllers\admin\DonationController@DonationListTable')->name('donation-list-table');
        /*----vishakha 10-10-2022 -----*/
        Route::get('/donation-reminder/{encryptCode}', 'App\Http\Controllers\admin\DonationController@DonationReminderMail')->name('donation-reminder');
            /*----vishakha 10-10-2022 -----*/
    });
    /*-----------vishakha 22-08-2022------------*/

    
    

    Route::group(['prefix' => 'payment-management', 'as' => 'payment-management.'], function () {
        /* Payment Management */

        Route::get('/payment-list', 'App\Http\Controllers\admin\PaymentController@PaymentList')->name('payment-list');
        Route::get('/payment-list-table', 'App\Http\Controllers\admin\PaymentController@PaymentListTable')->name('payment-list-table');

        Route::get('/payment-detail/{encryptCode}','App\Http\Controllers\admin\PaymentController@paymentDetail')->name('payment-detail');

        Route::get('/invoice-download/{encryptCode}','App\Http\Controllers\admin\PaymentController@invoiceDownload')->name('invoice-download');
        Route::any('/pay-download','App\Http\Controllers\admin\PaymentController@paymentReportDownload')->name('pay-download');
    });

    Route::group(['prefix' => 'booking-management', 'as' => 'booking-management.'], function () {
        /* Booking Management */

        Route::get('/booking-list', 'App\Http\Controllers\admin\BookingController@BookingList')->name('booking-list');
        Route::get('/booking-list-table', 'App\Http\Controllers\admin\BookingController@BookingListTable')->name('booking-list-table');

        Route::get('/booking-detail/{encryptCode}','App\Http\Controllers\admin\BookingController@bookingDetail')->name('booking-detail');

        Route::get('/booking-modify/{encryptCode}','App\Http\Controllers\admin\BookingController@bookingModify')->name('booking-modify');

        Route::get('/booking-cancel/{encryptCode}','App\Http\Controllers\admin\BookingController@bookingCancel')->name('booking-cancel');

        Route::post('/booking-cancel-save', 'App\Http\Controllers\admin\BookingController@bookingCancelSave')->name('booking-cancel-save');

        Route::get('/customer-booking-list/{encryptCode}', 'App\Http\Controllers\admin\BookingController@CustomerBookingList')->name('customer-booking-list');
        Route::get('/customer-booking-list-table/{encryptCode}', 'App\Http\Controllers\admin\BookingController@CustomerBookingListTable')->name('customer-booking-list-table');

        Route::any('/get-that-date-slot', 'App\Http\Controllers\admin\BookingController@getThatDateSlot')->name('get-that-date-slot');



        Route::post('/booking-modify-save', 'App\Http\Controllers\admin\BookingController@bookingModifySave')->name('booking-modify-save');

        Route::any('/calendar', 'App\Http\Controllers\admin\BookingController@theCalendar')->name('calendar');

        Route::get('/booking-list-thisdate-table/{month}/{day}/{year}', 'App\Http\Controllers\admin\BookingController@BookingListThisDateTable')->name('booking-list-thisdate-table');

        // Route::get('/booking-calendar', function () {
        //         return view('admin/bookingmanagement/calendar');
        //     });
    });


    Route::group(['prefix' => 'holiday-management', 'as' => 'holiday-management.'], function () {
        /*holiday Management*/
        

        Route::get('/holiday-list', 'App\Http\Controllers\admin\HolidayController@HolidayList')->name('holiday-list');
        Route::get('/holiday-list-table', 'App\Http\Controllers\admin\HolidayController@HolidayListTable')->name('holiday-list-table');
        Route::get('/holiday-delete/{encryptCode}','App\Http\Controllers\admin\HolidayController@holidayDelete')->name('holiday-delete');

        Route::post('/holiday-add-save', 'App\Http\Controllers\admin\HolidayController@holidayAddSave')->name('holiday-add-save');
    });


    

    
});

        

