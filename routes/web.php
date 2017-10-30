<?php

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
/*Route::get('/', function () {
    return Redirect::to('login');
});*/
//Route::get('/', function () {
  //  return view('site.index');
    //return Redirect::to('Site/index');
//});

Route::get('/', 'SiteController@index');


Route::get('/clear-cache', function() {
    $clearCache = Artisan::call('cache:clear');
    $clearConfig = Artisan::call('config:clear');
     return Redirect::to('login');
});
/* Route::get('/', function () {
     return view('welcome');
 }); */
Route::auth('/login');
// Home page redirection
Route::get('/home', 'HomeController@index');
// Group

Route::group(['middleware' => 'auth', 'as' => 'main.'], function (){
	$controllers = array(
			'employee' => 'EmployeeController',
            'category' => 'CategoryController',
            'location' => 'LocationController',
            'service' => 'ServiceController',
            'city' => 'CityController',
            'home' => 'HomeController',
            'rating' => 'RatingController',
            'user' => 'UserController',
            'bookmark' => 'BookmarkController',
            'provider' => 'ServiceProviderController',
            'review' => 'ReviewController',
			'cms' => 'CmsPagesController',
            'address' => 'AddressController',
            'site' => 'SiteController',
		);
	foreach ($controllers as $key => $controller){
        //Will generates Crud functions (index,create, edit, delete, update, store)
        Route::get($key . '/index', array('as' => $key . '.index', 'uses' => $controller . '@index'));
        Route::resource($key, $controller);
        Route::post($key . '/index', array('as' => $key . '.search', 'uses' => $controller . '@index'));
        Route::post($key . '/rest', array('as' => $key . '.rest')); // here applied rest route
    }

    // Employee
    Route::get('employee/edit/{id}', array('as' => 'employee.edit', 'uses' => 'EmployeeController@edit'));
    Route::delete('employee/destroy/{id}', array('as' => 'employee.destroy', 'uses' => 'EmployeeController@destroy'));
	// Service
    Route::get('service/edit/{id}', array('as' => 'service.edit', 'uses' => 'ServiceController@edit'));
    Route::delete('service/destroy/{id}', array('as' => 'service.destroy', 'uses' => 'ServiceController@destroy'));
    // Category
    Route::get('category/edit/{id}', array('as' => 'category.edit', 'uses' => 'CategoryController@edit'));
    Route::get('category/show/{id}', array('as' => 'category.show', 'uses' => 'CategoryController@show'));
    Route::delete('category/destroy/{id}', array('as' => 'category.destroy', 'uses' => 'CategoryController@destroy'));
    Route::post('provider/cagetoryservices', array('as' => 'provider.cagetoryservices', 'uses' => 'ServiceProviderController@cagetoryservices'));
    // User
    Route::get('user/edit/{id}', array('as' => 'user.edit', 'uses' => 'UserController@edit'));
    Route::get('user/show/{id}', array('as' => 'user.show', 'uses' => 'UserController@show'));
    Route::delete('user/destroy/{id}', array('as' => 'user.destroy', 'uses' => 'UserController@destroy'));
    // profile update
    Route::get('profileView/{id}', array('as' => 'home.profileView', 'uses' => 'HomeController@profileView'));
    Route::put('updateProfile/{id}', array('as' => 'home.updateProfile', 'uses' => 'HomeController@updateProfile'));
    // Country
    // Route::get('country/edit/{id}', array('as' => 'country.edit', 'uses' => 'CountryController@edit'));
    // Route::get('country/show/{id}', array('as' => 'country.show', 'uses' => 'CountryController@show'));
    // Route::delete('country/destroy/{id}', array('as' => 'country.destroy', 'uses' => 'CountryController@destroy'));
    // City
    Route::get('city/edit/{id}', array('as' => 'city.edit', 'uses' => 'CityController@edit'));
    Route::get('city/show/{id}', array('as' => 'city.show', 'uses' => 'CityController@show'));
    Route::delete('city/destroy/{id}', array('as' => 'city.destroy', 'uses' => 'CityController@destroy'));

	// Cms
	Route::get('cms/show/{id}', array('as' => 'cms.show', 'uses' => 'CmsPagesController@show'));
	Route::get('cms/edit/{id}', array('as' => 'cms.edit', 'uses' => 'CmsPagesController@edit'));

    // Location
    Route::get('location/edit/{id}', array('as' => 'location.edit', 'uses' => 'LocationController@edit'));
    Route::get('location/show/{id}', array('as' => 'location.show', 'uses' => 'LocationController@show'));
    Route::delete('location/destroy/{id}', array('as' => 'location.destroy', 'uses' => 'LocationController@destroy'));
    // Change Password
    Route::put('updatePassword/{id}', array('as' => 'home.updatePassword', 'uses' => 'HomeController@updatePassword'));
    Route::get('changePassword/{id}', array('as' => 'home.changePassword', 'uses' => 'HomeController@changePassword'));
    // Ratings
    Route::get('rating/edit/{id}', array('as' => 'rating.edit', 'uses' => 'RatingController@edit'));
    Route::get('rating/show/{id}', array('as' => 'rating.show', 'uses' => 'RatingController@show'));
    Route::delete('rating/destroy/{id}', array('as' => 'rating.destroy', 'uses' => 'RatingController@destroy'));
    // Reviews
    Route::get('review/edit/{id}', array('as' => 'review.edit', 'uses' => 'ReviewController@edit'));
    Route::get('review/show/{id}', array('as' => 'review.show', 'uses' => 'ReviewController@show'));
    Route::delete('review/destroy/{id}', array('as' => 'review.destroy', 'uses' => 'ReviewController@destroy'));

    // Category
    Route::get('provider/edit/{id}', array('as' => 'provider.edit', 'uses' => 'ServiceProviderController@edit'));
    Route::get('provider/show/{id}', array('as' => 'provider.show', 'uses' => 'ServiceProviderController@show'));
    Route::delete('provider/destroy/{id}', array('as' => 'provider.destroy', 'uses' => 'ServiceProviderController@destroy'));

    // To upload the S3 Image into Amazon bucket
    Route::get('s3-image-upload','S3ImageController@imageUpload');
	  Route::post('s3-image-upload','S3ImageController@imageUploadPost');

    // Address
    Route::get('address/edit/{id}', array('as' => 'address.edit', 'uses' => 'AddressController@edit'));
    Route::get('address/show/{id}', array('as' => 'address.show', 'uses' => 'AddressController@show'));
    Route::delete('address/destroy/{id}', array('as' => 'address.destroy', 'uses' => 'AddressController@destroy'));

});

//Route::resource('webservice', 'WebServiceController');
Route::get('webservice/index', array('as' => 'webservice.index', 'uses' => 'WebServiceController@index'));
Route::get('webservice/getcms/{slug}', array('as' => 'webservice.getcms', 'uses' => 'WebServiceController@getCms'));
Route::post('webservice/register', array('as' => 'webservice.register', 'uses' => 'WebServiceController@register'));
Route::post('webservice/feedback', array('as' => 'webservice.feedback', 'uses' => 'WebServiceController@sendFeedback'));
Route::post('webservice/serviceimage', array('as' => 'webservice.serviceimage', 'uses' => 'WebServiceController@getServiceImages'));
Route::get('webservice/pre-requisites', array('as' => 'webservice.pre-requisites', 'uses' => 'WebServiceController@getPrerequisites'));
Route::post('webservice/mobile-verification', array('as' => 'webservice.mobile-verification', 'uses' => 'WebServiceController@mobileVerification'));
Route::post('webservice/mobile-otp-verification', array('as' => 'webservice.mobile-otp-verification', 'uses' => 'WebServiceController@mobileOTPVerification'));
Route::post('webservice/resend-otp', array('as' => 'webservice.resend-otp', 'uses' => 'WebServiceController@resendOTP'));
Route::get('webservice/categories', array('as' => 'webservice.categories', 'uses' => 'WebServiceController@getCategories'));
Route::post('webservice/serviceproviders', array('as' => 'webservice.serviceproviders', 'uses' => 'WebServiceController@getServiceProviders'));

Route::post('webservice/serviceprovider-login', array('as' => 'webservice.serviceprovider-login', 'uses' => 'WebServiceController@spLogin'));
Route::post('webservice/serviceprovider-register', array('as' => 'webservice.serviceprovider-register', 'uses' => 'WebServiceController@spRegister'));
Route::post('webservice/serviceprovider-forgot-password', array('as' => 'webservice.serviceprovider-forgot-password', 'uses' => 'WebServiceController@spForgotPassword'));
Route::post('webservice/serviceprovider-change-password', array('as' => 'webservice.serviceprovider-change-password', 'uses' => 'WebServiceController@spChangePassword'));
Route::get('webservice/prerequisites-list', array('as' => 'webservice.prerequisites-list', 'uses' => 'WebServiceController@prerequisitesList'));
Route::post('webservice/getserviceprovider', array('as' => 'webservice.getserviceprovider', 'uses' => 'WebServiceController@getServiceProvider'));
Route::post('webservice/getreviewlist', array('as' => 'webservice.getreviewlist', 'uses' => 'WebServiceController@getreviewlist'));
Route::post('webservice/serviceprovider-update', array('as' => 'webservice.serviceprovider-update', 'uses' => 'WebServiceController@updateServiceProvider'));
Route::post('webservice/review-list', array('as' => 'webservice.review-list', 'uses' => 'WebServiceController@reviewList'));
Route::post('webservice/user-list', array('as' => 'webservice.user-list', 'uses' => 'WebServiceController@UserList'));
Route::post('webservice/customer-view', array('as' => 'webservice.customer-view', 'uses' => 'WebServiceController@userDetails'));
Route::post('webservice/customer-update', array('as' => 'webservice.customer-update', 'uses' => 'WebServiceController@updateCustomer'));
Route::post('webservice/serviceprovider-view', array('as' => 'webservice.serviceprovider-view', 'uses' => 'WebServiceController@viewServiceProvider'));
Route::post('webservice/user-reviews', array('as' => 'webservice.user-reviews', 'uses' => 'WebServiceController@userReviews'));
Route::post('webservice/sp-reviews', array('as' => 'webservice.sp-reviews', 'uses' => 'WebServiceController@spReviews'));
Route::post('webservice/review-add', array('as' => 'webservice.review-add', 'uses' => 'WebServiceController@addReview'));
Route::post('webservice/review-update', array('as' => 'webservice.review-update', 'uses' => 'WebServiceController@updateReview'));

// To reset password
Route::get('reset-password/{id}', array('as' => 'provider.reset-password', 'uses' => 'ServiceProviderController@resetPassword'));
Route::post('update-sppassword/{id}', array('as' => 'provider.update-sppassword', 'uses' => 'ServiceProviderController@UpdateServicePassword'));

// Frontend
//Route::resource('Site', 'SiteController');
//Route::resource('site', 'SiteController@index');
Route::get('services', 'SiteController@getServices');
Route::get('contact', 'SiteController@getContact');
//Route::post('contact/contact_store', array('as' => 'webservice.contact_store', 'uses' => 'WebServiceController@contactStore'));
Route::post('contact_store', 'SiteController@contactStore');
// Route::post('store', 'EmployeeController@store');
Route::get('/logout', 'Auth\LoginController@logout');

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
//Route::resource('site','SiteController');
Route::get('site/index','SiteController@index');
Route::post('site/contact-mail','SiteController@contactMail');
Route::resource('site','SiteController');
//Route::get('site/show','SiteController@show');