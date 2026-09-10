<?php
use App\Http\Controllers\AuthController;
use App\Http\Controllers\FrontendController;
use App\Http\Controllers\User\UserDashboardController;
use App\Http\Controllers\User\UserProfileController;
use App\Http\Controllers\User\UserJobController;
use App\Http\Controllers\User\UserDepositController;
use App\Http\Controllers\User\UserDealController;
use App\Http\Controllers\User\UserWithdrawController;
use App\Http\Controllers\User\UserNotificationController;
use App\Http\Controllers\User\UserSubmitJobController;
use App\Http\Controllers\User\FinishJobController;
use App\Http\Controllers\User\BoostJobController;
use App\Http\Controllers\User\UserBannerController;
use App\Http\Controllers\User\HideJobController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\ContinentController;
use App\Http\Controllers\Admin\CountryController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\SubCategoryController;
use App\Http\Controllers\Admin\PaymentMethodController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\JobController;
use App\Http\Controllers\Admin\DepositController;
use App\Http\Controllers\Admin\BreakingNoticeController;
use App\Http\Controllers\Admin\BannerController;
use App\Http\Controllers\Admin\WithdrawController;
use App\Http\Controllers\Admin\ProfileController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\User\TopFreelancerController;
use App\Http\Controllers\User\TopBuyerController;
use App\Http\Controllers\Auth\SocialAuthController;
use Illuminate\Support\Facades\Route;

// web pages
Route::get('/', function () {
    return view('welcome');
});

Route::get('about-us', [FrontendController::class, 'aboutUs'])->name('about');
Route::get('article', [FrontendController::class, 'article'])->name('article');
Route::get('article-details', [FrontendController::class, 'articledetails'])->name('article.details');
Route::get('privacy-policy', [FrontendController::class, 'policy'])->name('policy');
Route::get('terms-conditions', [FrontendController::class, 'terms'])->name('terms'); 
Route::get('microjob-marketplace', [FrontendController::class, 'marketplace'])->name('marketplace'); 
Route::get('deal-marketplace', [FrontendController::class, 'dealMarketplace'])->name('deal');



Route::middleware('throttle:10,1')->group(function () {
    Route::get('auth/{provider}/redirect', [SocialAuthController::class, 'redirect'])->name('social.redirect');
    Route::get('auth/{provider}/callback', [SocialAuthController::class, 'callback'])->name('social.callback');
});



// Guest routes (login/register)
Route::middleware('guest.redirect')->group(function () {
    Route::get('/login', [AuthController::class, 'loginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'registerForm'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);


    // Step 1 - Email form
    Route::get('/forgot-password', [ForgotPasswordController::class, 'showEmailForm'])
        ->name('password.request');

    // Step 2 - Send OTP
    Route::post('/forgot-password/send-otp', [ForgotPasswordController::class, 'sendOtp'])
        ->name('password.otp.send');

    // Step 3 - OTP verify form
    Route::get('/forgot-password/verify-otp', [ForgotPasswordController::class, 'showOtpForm'])
        ->name('password.otp.form');

    // Step 4 - Verify OTP
    Route::post('/forgot-password/verify-otp', [ForgotPasswordController::class, 'verifyOtp'])
        ->name('password.otp.verify');

    // Step 5 - New password form
    Route::get('/forgot-password/reset', [ForgotPasswordController::class, 'showResetForm'])
        ->name('password.reset.form');

    // Step 6 - Save new password
    Route::post('/forgot-password/reset', [ForgotPasswordController::class, 'resetPassword'])
        ->name('password.reset');

});




// Protected routes
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});

//user routes group
Route::middleware(['auth', 'user'])
    ->prefix('user')
    ->name('user.')
    ->group(function () {

        Route::get('/dashboard', [UserDashboardController::class, 'userDashboard'])
            ->name('dashboard');

       //profile route
        Route::get('/profile', [UserProfileController::class, 'userProfile'])
            ->name('profile');
        Route::post('/profile-update', [UserProfileController::class, 'userProfileUpdate'])
            ->name('profile.update');
        Route::post('/password-update', [UserProfileController::class, 'userPasswordUpdate'])
            ->name('password.update');
        Route::post('/photo-update', [UserProfileController::class, 'userPhotoUpdate'])
            ->name('photo.update');


        //job route
        Route::get('/create-job',  [UserJobController::class, 'create'])->name('create.job');
        Route::post('/create-job', [UserJobController::class, 'store'])->name('create.job.store');
        Route::patch('/jobs/{id}/update-workers', [UserJobController::class, 'update']);

        //Top job route
        Route::patch('/jobs/{id}/make-top', [UserJobController::class, 'makeTopJob']);

         // Job Boost Route
        Route::post('/jobs/{id}/boost', [BoostJobController::class, 'boost'])->name('job.boost');
        
        //job delete route for user
        Route::delete('/delete-job/{id}/{code}', [UserJobController::class, 'delete'])->name('job.delete');
        //job paused route for user
        Route::patch('/pause-job/{id}/{code}', [UserJobController::class, 'jobStopMoneyBack'])->name('job.money-back');


        Route::patch('/mute-job/{id}/{code}', [UserJobController::class, 'jobMute'])->name('job.mute');
        Route::patch('/unmute-job/{id}/{code}', [UserJobController::class, 'jobUnmute'])->name('job.unmute');



        Route::get('/job-details/{code}',  [UserJobController::class, 'details'])->name('job-details');
        Route::get('/my-jobs',  [UserJobController::class, 'myjobs'])->name('my.jobs');
        Route::get('/find-jobs',  [UserJobController::class, 'findjobs'])->name('find.jobs'); 

        //hide job

        Route::post('/jobs/{job}/hide', [HideJobController::class, 'hideJob'])->name('job.hide');
       
        //user submitted jobs
        Route::get('/finished-job',  [FinishJobController::class, 'finishedJobs'])->name('finished.jobs'); 


        //submit job route
        Route::post('/submit-job/{code}/{slug}',  [UserSubmitJobController::class, 'storeSubmitjob'])->name('submit-job'); 


         //submit job proof route
        Route::get('/job-proof/{id}/{code}',  [UserSubmitJobController::class, 'proof'])->name('submit-job-proof');
        Route::patch('/submit-jobs/{id}/approve', [UserSubmitJobController::class, 'submitApprove'])->name('submit-job.approve');
        Route::patch('/submit-jobs/{id}/reject',  [UserSubmitJobController::class, 'submitReject'])->name('submit-job.reject');

      Route::post('/submit-jobs/approve-selected', [UserSubmitJobController::class, 'submitApproveSelected'])->name('submit-job.approve-selected');  


        //banner ads route
        Route::get('/browse-deal',  [UserDealController::class, 'browsedeal'])->name('browse.deal'); 
        Route::get('/deal-create',  [UserDealController::class, 'dealcreate'])->name('deal.create');
        Route::get('/my-deal-post',  [UserDealController::class, 'mydealpost'])->name('my.deal.post'); 
        Route::get('/deal-order',  [UserDealController::class, 'dealorder'])->name('deal.order');
        Route::post('/store-baner-ads',  [UserBannerController::class, 'store'])->name('store.babber.ads');

        Route::get('/banner/click/{id}', [UserBannerController::class, 'bannerClick'])
    ->name('banner.click');




        //deposit route for user
        Route::get('/deposit',  [UserDepositController::class, 'create'])->name('deposit');
         Route::post('/deposit-store',  [UserDepositController::class, 'store'])->name('deposit-store');
        Route::get('/deposit-history',  [UserDepositController::class, 'depositHistory'])->name('deposit.history');



        Route::get('continents/', [UserJobController::class, 'continents']);
        Route::get('continents/{id}/countries',[UserJobController::class, 'countries']);
        Route::get('job-categories',[UserJobController::class, 'categories']);
        Route::get('job-categories/{id}/subcategories',[UserJobController::class, 'subcategories']);
        

        //withdraw route
        Route::get('/withdraw',  [UserWithdrawController::class, 'index'])->name('withdraw');
        Route::post('/withdraw-create',  [UserWithdrawController::class, 'create'])->name('withdraw.create'); 
        Route::get('/withdraw-history',  [UserWithdrawController::class, 'withdrawHistory'])->name('withdraw.history');

        //notification route
        Route::get('/unseen-notifications',  [UserNotificationController::class, 'unSeenNotification'])->name('unseen-notifications');
        Route::get('/seen-notifications',  [UserNotificationController::class, 'SeenNotification'])->name('seen-notifications');
        Route::post('/notification/read/{id}', [UserNotificationController::class, 'markAsRead'])->name('notification.read');

        //top freelancer
      Route::get('/top-freelancers',  [TopFreelancerController::class, 'index'])->name('top-freelancer');

      //top buyers
      Route::get('/top-buyers',  [TopBuyerController::class, 'index'])->name('top-buyers');



  });
//user routes group end

//admin route group
Route::middleware(['auth', 'admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

        Route::get('/dashboard', [AdminDashboardController::class, 'adminDashboard'])
            ->name('dashboard');

        //continents route
        Route::get('/continent-add', [ContinentController::class, 'index'])->name('continent');
        Route::post('/continent-store', [ContinentController::class, 'store'])->name('continent.store');
        Route::get('/continent-delete/{id}', [ContinentController::class, 'delete'])->name('continent.delete');

        Route::post('/continent-update/', [ContinentController::class, 'update'])->name('continent.update');



        //country route
        Route::get('/country-add', [CountryController::class, 'index'])
            ->name('country');
        Route::post('/country-store', [CountryController::class, 'store'])
            ->name('country.store');
        Route::get('/country-delete/{id}', [CountryController::class, 'delete'])
            ->name('country.delete');

        //category route
        Route::get('/category-add', [CategoryController::class, 'index'])
            ->name('category');
        Route::post('/category-store', [CategoryController::class, 'store'])
            ->name('category.store');
        Route::get('/category-delete/{id}', [CategoryController::class, 'delete'])
            ->name('category.delete');

        //sub category
        Route::get('/subcategory-add', [SubCategoryController::class, 'index'])
            ->name('subcategory');
        Route::post('/subcategory-store', [SubCategoryController::class, 'store'])
            ->name('subcategory.store');
        Route::get('/subcategory-delete/{id}', [SubCategoryController::class, 'delete'])
            ->name('subcategory.delete');

        //method method route
        Route::get('/payment-method', [PaymentMethodController::class, 'index'])
            ->name('payment.method'); 
        Route::post('/payment-method-store', [PaymentMethodController::class, 'store'])
            ->name('payment-method.store');   
        Route::post('/payment-method/update', [PaymentMethodController::class, 'update'])->name('payment-method.update');
       Route::delete('/payment-method/delete/{id}', [PaymentMethodController::class, 'delete'])->name('payment-method.delete');

       //deposit route for admin
       Route::get('/deposit-pending', [DepositController::class, 'pendingDeposit'])->name('pending-deposit');
       Route::get('/deposit-approved', [DepositController::class, 'approvedDeposit'])->name('approved-deposit'); 
       Route::get('/deposit-rejected', [DepositController::class, 'rejectedDeposit'])->name('rejected-deposit');  
       Route::patch('/deposit/{id}/reject', [DepositController::class, 'rejectDeposit'])->name('deposit-reject');  
       Route::patch('/deposit/{id}/approve', [DepositController::class, 'approveDeposit'])->name('deposit-approve'); 

       //withdraw route for admin
     Route::get('/withdraw', [WithdrawController::class, 'index'])->name('withdraw.index');
     Route::get('/pending-withdraw',  [WithdrawController::class, 'pendignWithdraw'])->name('pending.withdraw');
     
     Route::get('/approved-withdraw',[WithdrawController::class, 'approvedWithdraw'])->name('approved.withdraw');
    
     Route::get('/reject-withdraw',[WithdrawController::class, 'rejectedWithdraw'])->name('reject.withdraw');
    
    
    Route::get('/withdraw/{id}/approve', [WithdrawController::class, 'approve'])->name('withdraw.approve');
    Route::post('/withdraw/{id}/reject', [WithdrawController::class, 'reject']) ->name('withdraw.reject');

    

       //site setting route
       Route::get('/website-setting/', [SettingController::class, 'index'])->name('setting');
       Route::post('/settings-update', [SettingController::class, 'update'])->name('settings.update');

       //user route(for admin)
       Route::get('/users/', [UserController::class, 'index'])->name('users');
       Route::post('/user-status-update/', [UserController::class, 'userActiveInactive'])->name('update-user-status');
      Route::get('/user-delete/{id}', [UserController::class, 'delete'])->name('user-delete');

      Route::get('/user/details/{id}', [UserController::class,'userDetails'])
    ->name('user.details');
      
      //jobs route
      Route::get('/all-jobs', [JobController::class, 'jobs'])->name('all-jobs');

      Route::get('/active-jobs', [JobController::class, 'activeJobs'])->name('active-jobs');
      Route::get('/pending-jobs', [JobController::class, 'pendingJobs'])->name('pending-jobs');
      Route::get('/rejected-jobs', [JobController::class, 'rejectedJobs'])->name('rejected-jobs');
      Route::get('/completed-jobs', [JobController::class, 'completedJobs'])->name('completed-jobs');
      Route::get('/paused-jobs', [JobController::class, 'pausedJobs'])->name('paused-jobs');
      Route::get('/delete-job/{id}', [JobController::class, 'deleteJob'])->name('delete-job');
      Route::get('/approve-job/{id}', [JobController::class, 'approveJob'])->name('approve-job');
      Route::get('/details-job/{id}', [JobController::class, 'detailsJob'])->name('job-datails');
      Route::post('reject-job/{id}',  [JobController::class, 'rejectJob'])->name('reject-job');

     Route::get('pause-job/{id}',    [JobController::class, 'pauseJob'])->name('make-pause-job');
     Route::get('/live-job/{id}',     [JobController::class, 'liveJob'])->name('make-un-pause-job');

    

      // Banner Management
    Route::get('/banners',                [BannerController::class, 'index'])   ->name('banners');
    Route::get('/banners/{id}/approve',   [BannerController::class, 'approve']) ->name('banner.approve');
    Route::get('/banners/{id}/inactive',  [BannerController::class, 'inactive'])->name('banner.inactive');
    Route::post('/banners/reject',        [BannerController::class, 'reject'])  ->name('banner.reject');
    Route::get('/banners/{id}/delete',    [BannerController::class, 'delete'])  ->name('banner.delete');

    // routes/web.php


    //notice route
     Route::get('/notice-create',     [BreakingNoticeController::class, 'index'])->name('notice-create');
     Route::post('/notice-store',     [BreakingNoticeController::class, 'store'])->name('notice-store');
     Route::get('/notice-delete/{id}',     [BreakingNoticeController::class, 'destroy'])->name('notice.delete');
    Route::post('/notice-update/',     [BreakingNoticeController::class, 'update'])->name('notice-update');


    Route::get('/profile', [ProfileController::class, 'Profile'])->name('profile');
    Route::put('/profile/update', [ProfileController::class, 'updateProfile'])
        ->name('profile.update')->middleware('throttle:2,1');
    Route::put('/admin/password/update', [ProfileController::class, 'updatePassword'])
        ->name('password.update')->middleware('throttle:5,1');

    });
 
//admin route group end