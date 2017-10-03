<?php

namespace App\Http\Controllers;

use URL;
use Mail;
use Storage;
use App\Models\City;
use App\Models\User;
use GuzzleHttp\Client;
use App\Models\Review;
use App\Models\Service;
use App\Models\Category;
use App\Models\Location;
use App\Models\CmsPages;
use App\Models\Feedback;
use App\Models\Address;
use App\Helpers\KranHelper;
use Illuminate\Http\Request;
use App\Models\ServiceProvider;
use App\Models\CategoryService;
use Illuminate\Support\Facades\Hash;
use App\Http\Traits\WebserviceTrait;
use App\Models\ServiceProviderDetails;
use GuzzleHttp\Exception\GuzzleException;
use App\Models\ServiceProviderCategoryService;
use App\Models\ServiceProviderImages;

class WebServiceController extends Controller
{
	use WebserviceTrait;
	
	/**
     * Create a new controller instance.
     *
     * @return void
     */
	public function __construct()
	{
		$this->middleware('auth', ['only' => ['create', 'store', 'edit', 'delete']]);
		$this->middleware('auth', ['except' => ['index','register','getCms','sendFeedback','getServiceImages','getPrerequisites','mobileVerification','spLogin','spRegister','spForgotPassword','spChangePassword','prerequisitesList','getServiceProvider', 'getreviewlist','updateServiceProvider','mobileOTPVerification','resendOTP','getCategories', 'reviewList','getServiceProviders', 'UserList', 'userDetails','updateCustomer','viewServiceProvider','userReviews','spReviews', 'addReview', 'updateReview']]);

	}
	
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
    	$categories = Category::get();
    	return $categories;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
    	dd($request->all());
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

	/**
	 * To register the user from the mobile end
	 *
	 * @param array
	 * @return array
	 */
	public function register(Request $request)
	{
		try{
			$data = $request->all();
			if($data){
				if($data['fullname'] && $data['email']){
					$data['email'] = ($data['email']) ? $data['email'] : "";
					$data['mobile'] = ($data['mobile']) ? $data['mobile'] : ""; 
					$basePath = URL::to('/').'/..';
					$imagePath = $basePath.trans('main.user_path');	
					$logoPath = trans('main.user_path');
					//$emailCount = User::where('email',$data['email'])->count();
					/*if($emailCount != 0){
						$resultData = array('status'=>false,'message'=>'Email exists already','result'=>'');
						return $resultData;
					}*/
					// To create a directory if not exists
					if (!(Storage::disk('s3')->exists('/uploads/user')))
					{
						Storage::disk('s3')->makeDirectory('/uploads/user/');
					}
					$userData['name'] = $data['fullname'];
					$userData['email'] = $data['email'];
					$userData['mobile'] = ($data['mobile']) ? $data['mobile'] : ""; 
					if($data['register_mode'] == 'Mobile'){
						//$emailExists = User::get()->where('email',$data['email'])->count();
						$mobileExists = User::get()->where('mobile',$data['mobile'])->count();
						if($mobileExists == 0){
							//$data['password'] = bcrypt($data['password']);
							$data['password'] = bcrypt($data['fullname']);
							$data['register_mode'] = $this->getRegisterMode($data['register_mode']);
							//$data['been_there_status'] = ($data['been_there_status']=='Yes') ? 1 : 2;
							$data['registered_on'] = date('Y-m-d H:i:s');
							$data['status'] = 'Active';

							if(isset($data['profile_url']) && $data['profile_url']){
								if (!filter_var($data['profile_url'], FILTER_VALIDATE_URL)) { 
									$input['profile_picture'] = KranHelper::convertStringToImage($data['profile_url'],$data['fullname'],$logoPath);		
									$userData['image'] = $imagePath.$input['profile_picture'];
								}
								// To upload the images into Amazon S3
								$amazonImgUpload = Storage::disk('s3')->put('/uploads/user/'.$input['profile_picture'], file_get_contents($input['profile_picture']), 'public');

							}
							
							$registerStatus = User::create($data);
							$id = User::max('id');
							if($registerStatus){
								$userData['id'] =  $id;
								
								/* To send OTP*/
								$mobileOTP	= $this->generateOTP(); //get the OTP from traits method
								$authKey	= env("SMS_AUTH_KEY","173397Ad58dSrs59afe736");
								$senderId	= env("SMS_SENDER_ID","KRQ102234");
								$route		= env("SMS_ROUTE","4");
								$smsURI		= env("SMS_URI","https://control.msg91.com/api/sendhttp.php");
								$message	= "Your OTP to verify the mobile number is ".$mobileOTP;
								
								$client = new Client(); //GuzzleHttp\Client
								$result = $client->post($smsURI, [
									'form_params' => [
										'authkey' 	=> $authKey,
										'mobiles' 	=> $data['mobile'],
										'message' 	=> $message,
										'sender'	=> $senderId,
										'route' 	=> $route
									]
								]);
								$otpStatus = $result->getStatusCode(); // to get the status code
								if($otpStatus == 200){
									$otpStoreStatus = User::where('mobile', '=', $data['mobile'])->update(['otp' => $mobileOTP,'mobile_verification_status' => '0','resend_otp_status' => '0']);
									if($otpStoreStatus){
										$resultData = array('status'=>true,'message'=>'registered successfully. OTP is sent','result'=>'');
									} else {
										$resultData = array('status'=>false,'message'=>'registered successfully. OTP sent but could not be registered (OTP) in database','result'=>'');
									}
								} else {
									$resultData = array('status'=>false,'message'=>'registered successfully. OTP could not be sent','result'=>'');
								}
								/*End send OTP*/
						
								//$resultData = array('status'=>true,'message'=>'registered successfully','result'=>$userData);	
							} else {
								$resultData = array('status'=>false,'message'=>'registration failed','result'=>'');
							}
						} else {
							$resultData = array('status'=>false,'message'=>'Mobile exists already','result'=>'');
						}
					} else if($data['register_mode'] == 'Facebook'){
						if(!isset($data['facebook_id']) && $data['facebook_id']){
								$resultData = array('status'=>false,'message'=>'facebook id is mandatory','result'=>'');
								return $resultData;
						}
						$facebookAndEmailExists = User::where(function ($q) use ($data){
							return $q->where('facebook_id',$data['facebook_id'])->orWhere('email',$data['email']);
						})->count();
						if($facebookAndEmailExists == 0){

							$data['password'] = bcrypt($data['fullname']);
							$data['register_mode'] = $this->getRegisterMode($data['register_mode']);
							//$data['been_there_status'] = ($data['been_there_status']=='Yes') ? 1 : 2;
							$data['registered_on'] = date('Y-m-d H:i:s');
							$data['status'] = 'Active';
							/*$userProfilePath = '/uploads/user/';
							if(isset($data['profile_picture'])){
								$data['profile_picture'] = KranHelper::convertStringToImage($data['profile_picture'],$data['fullname'],$userProfilePath);
							} else {
								$data['profile_picture'] = '';
							}*/
							
							if(isset($data['profile_url']) && $data['profile_url']){ 
								if (!filter_var($data['profile_url'], FILTER_VALIDATE_URL)) {  
									$data['profile_picture'] = KranHelper::convertStringToImage($data['profile_url'],$data['fullname'],$logoPath);
									
									$userData['image'] = $imagePath.$data['profile_picture'];	
									// To upload the images into Amazon S3
									$amazonImgUpload = Storage::disk('s3')->put('/uploads/user/'.$input['profile_picture'], file_get_contents($input['profile_picture']), 'public');
								}	
							}

							$registerStatus = User::create($data);
							$id = User::max('id');
							if($registerStatus){
								$userData['id'] =  $id;
								$resultData = array('status'=>true,'message'=>'registered successfully','result'=>$userData);	
							} else {
								$resultData = array('status'=>false,'message'=>'registration failed','result'=>'');
							}
						} else {
							$userFacebook = User::get()->where('facebook_id',$data['facebook_id'])->count();
							$userEmail = User::get()->where('email',$data['email'])->count();
							if($userFacebook != 0){
								$user = User::get()->where('facebook_id',$data['facebook_id'])->first();
							}else if($userEmail != 0){
								$user = User::get()->where('email',$data['email'])->first();
							}
							
							$userData['id'] =  $user->id;
							$userData['image'] = ($user->profile_picture) ? $imagePath.$user->profile_picture : "";
							$resultData = array('status'=>true,'message'=>'You already have an account with the given details','result'=>$userData);
						}
					} else {
					}
				} else {
					$resultData = array('status'=>false,'message'=>'Invalid Input','result'=>'');
				}
				
			} else {
				$resultData = array('status'=>false,'message'=>'invalid request','result'=>'');
			}
		} catch(Exception $e){
			$resultData = array('status'=>false,'message'=>'invalid request','result'=>'');
		}
		return $resultData;
	}
	
	/**
	 * To get the pre-requisites data
	 *
	 * @return array
	 */
	public function getPrerequisites(){
		try{
			$categoryData 			= Category::get()->where('status','Active');
			$cityData 				= City::get()->where('status','Active');
			$localityData 			= Location::get()->where('status','Active');
			
			$aboutusData 			= CmsPages::getCmsData('about-us');
			$whatisKranqData 		= CmsPages::getCmsData('what-is-kranq');
			$howitWorksData 		= CmsPages::getCmsData('how-does-it-work');
			$privacyPolicyData 		= CmsPages::getCmsData('privacy-policy');
			$termsConditionsData	= CmsPages::getCmsData('terms-conditions');
			
			$contactData 			= Address::get()->where('id','1')->first();
			
			$basePath = URL::to('/').'/..';
			$categoryPath = $basePath.trans('main.category_path');				
			if($categoryData){
				foreach($categoryData as $index => $row){
					$arrayData[$index] = $row;
					// To get the image form the Amazon s3 account
					if (Storage::disk('s3')->exists('uploads/category/'.$row->category_image)) {
						$arrayData[$index]['category_image']= \Storage::disk('s3')->url('uploads/category/'.$row->category_image);
					} else {
						$arrayData[$index]['category_image'] = ($categoryPath.$row->category_image) ? $categoryPath.$row->category_image : "";
					}
				}
			}
			$data['categoryData']			= $arrayData;	
			$data['cityData']				= $cityData;
			$data['localityData']			= $localityData;
			$data['weekDaysData']			= $this->workingDaysList();
			$data['hours'] 					= $this->getTimeDropDown();
			$data['contactDetailsData']		= $contactData;
			$data['aboutusData']			= $aboutusData;
			$data['whatisKranqData']		= $whatisKranqData;
			$data['howitWorksData']			= $howitWorksData;
			$data['privacyPolicyData']		= $privacyPolicyData;
			$data['termsConditionsData']	= $termsConditionsData;
			$resultData = array('status'=>true,'message'=>'request success','result'=>$data);
		} catch(Exception $e){
			$resultData = array('status'=>false,'message'=>'invalid request','result'=>'');
		}
		return $resultData;
	}
	
	/**
	 * To do the mobile verification
	 *
	 * @param request array
	 * @return array
	 */
	public function mobileVerification(Request $request)
	{
		try{
			$data = $request->all();
			if($data){
				if($data['mobile']){
					$mobileExists	= User::get()->where('mobile',$data['mobile'])->count();
					if($mobileExists == 0){
						$resultData = array('status'=>false,'message'=>'mobile not registered yet','result'=>'');
					} else {
						$mobileOTP	= $this->generateOTP(); //get the OTP from traits method
						$authKey	= env("SMS_AUTH_KEY","173397Ad58dSrs59afe736");
						$senderId	= env("SMS_SENDER_ID","KRQ102234");
						$route		= env("SMS_ROUTE","4");
						$smsURI		= env("SMS_URI","https://control.msg91.com/api/sendhttp.php");
						$message	= "Your OTP to verify the mobile number is ".$mobileOTP;
						
						$client = new Client(); //GuzzleHttp\Client
						$result = $client->post($smsURI, [
							'form_params' => [
								'authkey' 	=> $authKey,
								'mobiles' 	=> $data['mobile'],
								'message' 	=> $message,
								'sender'	=> $senderId,
								'route' 	=> $route
							]
						]);
						$otpStatus = $result->getStatusCode(); // to get the status code
						if($otpStatus == 200){
							$otpStoreStatus = User::where('mobile', '=', $data['mobile'])->update(['otp' => $mobileOTP,'mobile_verification_status' => '0','resend_otp_status' => '0']);
							if($otpStoreStatus){
								$resultData = array('status'=>true,'message'=>'mobile registered. OTP is sent','result'=>'');
							} else {
								$resultData = array('status'=>false,'message'=>'OTP sent but could not register in database','result'=>'');
							}
						} else {
							$resultData = array('status'=>false,'message'=>'mobile registered. OTP could not be sent','result'=>'');
						}
					}
				} else {
					$resultData = array('status'=>false,'message'=>'invalid input','result'=>'');
				}
				
			} else {
				$resultData = array('status'=>false,'message'=>'invalid request','result'=>'');
			}
		} catch(Exception $e){
			$resultData = array('status'=>false,'message'=>'invalid request','result'=>'');
		}
		return $resultData;
	}
	
	/**
	 * To resend the OTP
	 *
	 * @param request array
	 * @return array
	 */
	public function resendOTP(Request $request)
	{
		try{
			$data = $request->all();
			if($data){
				if($data['mobile']){
					$otpData	= User::select('otp')->where('mobile',$data['mobile'])->get();
					if(isset($otpData[0])){
						$mobileOTP	= $otpData[0]->otp; //get the OTP from traits method
						$authKey	= env("SMS_AUTH_KEY","173397Ad58dSrs59afe736");
						$senderId	= env("SMS_SENDER_ID","KRQ102234");
						$route		= env("SMS_ROUTE","4");
						$smsURI		= env("SMS_URI","https://control.msg91.com/api/sendhttp.php");
						$message	= "Your OTP to verify the mobile number is ".$mobileOTP;
						
						$client = new Client(); //GuzzleHttp\Client
						$result = $client->post($smsURI, [
							'form_params' => [
								'authkey' 	=> $authKey,
								'mobiles' 	=> $data['mobile'],
								'message' 	=> $message,
								'sender'	=> $senderId,
								'route' 	=> $route
							]
						]);
						$otpStatus = $result->getStatusCode(); // to get the status code
						if($otpStatus == 200){
							$otpStoreStatus = User::where('mobile', '=', $data['mobile'])->update(['resend_otp_status' => '1']);
							if($otpStoreStatus){
								$resultData = array('status'=>true,'message'=>'OTP is sent again','result'=>'');
							} else {
								$resultData = array('status'=>false,'message'=>'OTP is sent again but could not be stored','result'=>'');
							}
						} else {
							$resultData = array('status'=>false,'message'=>'resend OTP failed','result'=>'');
						}
					} else {
						$resultData = array('status'=>false,'message'=>'invalid mobile number','result'=>'');
					}
				} else {
					$resultData = array('status'=>false,'message'=>'invalid input','result'=>'');
				}
				
			} else {
				$resultData = array('status'=>false,'message'=>'invalid request','result'=>'');
			}
		} catch(Exception $e){
			$resultData = array('status'=>false,'message'=>'invalid request','result'=>'');
		}
		return $resultData;
	}
	
	/**
	 * To do the mobile OTP verification
	 *
	 * @param request array
	 * @return array
	 */
	public function mobileOTPVerification(Request $request)
	{
		try{
			$data = $request->all();
			if($data){
				if($data['mobile'] && $data['otp']){
					$mobileExists	= User::get()->where('mobile',$data['mobile'])->where('otp',$data['otp'])->count();
					$basePath = URL::to('/').'/..';
					$imagePath = $basePath.trans('main.user_path');	
					if($mobileExists == 0){
						$resultData = array('status'=>false,'message'=>'invalid OTP','result'=>'');
					} else {
						$user	= User::get()->where('mobile',$data['mobile'])->where('otp',$data['otp'])->first();
						$userData['id'] = $user->id;
						$userData['name'] = $user->fullname;
						$userData['email'] = $user->email;
						$userData['mobile'] = $user->mobile;
						// To get the image form the Amazon s3 account
						if (Storage::disk('s3')->exists('uploads/user/'.$user->profile_picture)) {
							$userData['profile_picture']= \Storage::disk('s3')->url('uploads/user/'.$user->profile_picture);
						} else {
							$userData['profile_picture'] = ($user->profile_picture) ? $imagePath.$user->profile_picture : "";
						}

						$otpStoreStatus = User::where('mobile', '=', $data['mobile'])->update(['mobile_verification_status' => '1']);
						$resultData = array('status'=>true,'message'=>'OTP verification success','result'=>$userData);
					}
				} else {
					$resultData = array('status'=>false,'message'=>'invalid input','result'=>'');
				}
				
			} else {
				$resultData = array('status'=>false,'message'=>'invalid request','result'=>'');
			}
		} catch(Exception $e){
			$resultData = array('status'=>false,'message'=>'invalid request','result'=>'');
		}
		return $resultData;
	}
	
	/**
	 * To get the categories data
	 *
	 * @return array
	 */
	public function getCategories(){
		try{
			$categoryResult 			= Category::get()->where('status','Active');
			$basePath = URL::to('/').'/..';
			$imagePath = $basePath.trans('main.category_path');			
			foreach($categoryResult as $index => $category){
				$categoryData[$index]['id']						= $category->id;
				$categoryData[$index]['category_name']			= $category->category_name;
				$categoryData[$index]['description']			= $category->description;
				// To get the image form the Amazon s3 account
				if (Storage::disk('s3')->exists('uploads/category/'.$category->category_image)) {
					$categoryData[$index]['category_image'] = \Storage::disk('s3')->url('uploads/category/'.$category->category_image);
				} else {
					$categoryData[$index]['category_image']	= ($category->category_image) ? $imagePath.$category->category_image : "";
				}
				
				$categoryData[$index]['category_service_data']	= $this->getCategoryServices($category->id);
			}
			$data['categoryData']			= $categoryData;	
			$resultData = array('status'=>true,'message'=>'request success','result'=>$data);
		} catch(Exception $e){
			$resultData = array('status'=>false,'message'=>'invalid request','result'=>'');
		}
		return $resultData;
	}
	
	/**
	 * To get the category service Id
	 *
	 * @param int
	 * @return array
	 */
	public function getCategoryServices($categoryId){
		$categoryServiceResult	= CategoryService::getCategoryService($categoryId);
		$categoryServices = explode(',',$categoryServiceResult);
		
		$categoryServiceData = Service::select('service_name','id as service_id')->whereIn('id',$categoryServices)->get();
		return $categoryServiceData;
	}
	
	/**
	 * To get the service providers list data
	 *
	 * @return array
	 */
	public function getServiceProviders(Request $request){
		try{
			$data = $request->all();
			if($data){
				if($data['id']){
					//$page = (isset($data['page'])) ? $data['page'] : "0";	
					//$recordLimit = (isset($data['limit'])) ? $data['limit'] : "20";	

					//$page = ($page > 0) ? $page - 1 : 0;
					//$start =  $page * $recordLimit + 1;
					//$end = $page * $recordLimit + $recordLimit;
					//$end = $recordLimit;
					//$serviceProviderResult	= ServiceProvider::where('status','2')->where('category_id',$data['id'])->skip($page)->take($recordLimit)->get();
					$serviceProviderResult	= ServiceProvider::where('status','2')->where('category_id',$data['id'])->get();
					$basePath = URL::to('/').'/..';
					$imagePath = $basePath.trans('main.provider_path');
					if (count($serviceProviderResult)) {
						$serviceProviderData = [];
						foreach($serviceProviderResult as $index => $serviceProvider){
							$serviceProviderData[$index]['id']				= $serviceProvider->id;						
							$serviceProviderData[$index]['location_id']		= $serviceProvider->location_id;
							$serviceProviderData[$index]['locality']		= $serviceProvider->locality->locality_name;
							$serviceProviderData[$index]['name_sp']			= $serviceProvider->name_sp;
							$serviceProviderData[$index]['city']			= $serviceProvider->cities->city_name;
							// To get the image form the Amazon s3 account
							if (Storage::disk('s3')->exists('uploads/provider/'.$serviceProvider->logo)) {
								$serviceProviderData[$index]['logo'] = \Storage::disk('s3')->url('uploads/provider/'.$serviceProvider->logo);
							} else {
								$serviceProviderData[$index]['logo']	= ($serviceProvider->logo) ? $imagePath.$serviceProvider->logo : "";
							}
							$serviceProviderData[$index]['address']			= ($serviceProvider->address) ? $serviceProvider->address : "";				
							
							$serviceProviderData[$index]['googlemap_latitude']		= ($serviceProvider->googlemap_latitude) ? $serviceProvider->googlemap_latitude : "";
							$serviceProviderData[$index]['googlemap_longitude']		= ($serviceProvider->googlemap_longitude) ? $serviceProvider->googlemap_longitude : "";
							$serviceProviderData[$index]['ratings'] = Review::getRatingsOfServiceProviderById($serviceProvider->id);
							$serviceProviderData[$index]['reviews'] = Review::where('service_provider_id',$serviceProvider->id)->count();

					//$serviceProviderData[$index]['category_service_data']	= $this->getCategoryServices($category->id);
						}
						$resultData['id']			= $data['id'];	
						$resultData['serviceProviderData']			= $serviceProviderData;	
						$resultData = array('status'=>true,'message'=>'request success','result'=>$resultData);
				} else {
					$resultData = array('status'=>false,'message'=>'No Records Found','result'=>'');
				}
				} else {
					$resultData = array('status'=>false,'message'=>'Invalid Input','result'=>'');
				}				
			} else {
				$resultData = array('status'=>false,'message'=>'invalid request','result'=>'');
			}
		} catch(Exception $e){
			$resultData = array('status'=>false,'message'=>'invalid request','result'=>'');
		}
		return $resultData;
	}
	
	/**
	 * To get the servcice provider category services
	 * @param int $service_proivder_id
	 * @param int $category_id
	 * @return array
	 */
	public function getSPCategoryServices($service_provider_id,$category_id){
		$categoryServices	= ServiceProviderCategoryService::getSPCategoryService($service_provider_id,$category_id);
		$categoryServicesResult = explode(',',$categoryServices);
		$categoryServiceData = Service::select('service_name','id as service_id')->whereIn('id',$categoryServicesResult)->get();
		
		return $categoryServiceData;
	}
	
	/**
	 * To get the CMS data by seo id (slug)
	 *
	 * @param string $slug
	 * @return array
	 */
	public function getCms($slug){
		try{
			if($slug){
				$cmsData = CmsPages::get()->where('slug',$slug);
				$cmsData = $cmsData->toArray();
				if($cmsData){
					$resultData = array('status'=>true,'message'=>'request success','result'=>$cmsData);
				} else {
					$resultData = array('status'=>false,'message'=>'invalid slug','result'=>'');
				}
			} else {
				$resultData = array('status'=>false,'message'=>'invalid request','result'=>'');
			}
		} catch(Exception $e){
			$resultData = array('status'=>false,'message'=>'invalid request','result'=>'');
		}
		return $resultData;
	}

	
	/**
	 * To get the feedback data from mobile end and send feedback mail
	 *
	 * @param array
	 * @return array
	 */
	public function sendFeedback(Request $request)
	{
		try{
			$data = $request->all();
			if($data){
				if($data['email'] && $data['feedbackMessage']){
					//$user = User::find($data['id']);
					//$input['email'] = $user->email;
					//$input['title'] = $data['title'];
					//$input['feedbackMessage'] = $data['feedbackMessage'];
					Mail::send('email.feedback', ['data' => $data], function($message)
					{
						//$message->to('logu@boscosofttech.com', 'Loganathan')->subject('Feedback');
						$message->to('joanbritto18@gmail.com', 'Loganathan')->subject('Feedback');
					});
					$feedbackStatus = Feedback::create($data);
					if($feedbackStatus){
						$resultData = array('status'=>true,'message'=>'feedback sent successfully','result'=>'');	
					} else {
						$resultData = array('status'=>false,'message'=>'feedback could not be sent','result'=>'');
					}
				} else {
					$resultData = array('status'=>false,'message'=>'Invalid Input','result'=>'');
				}
				
			} else {
				$resultData = array('status'=>false,'message'=>'invalid request','result'=>'');
			}
		} catch(Exception $e){
			$resultData = array('status'=>false,'message'=>'invalid request','result'=>'');
		}
		return $resultData;
	}
	
	
	/**
	 * To get the image from mobile end as base64 format and store it in local as image
	 *
	 * @param array
	 * @return array
	 */
	public function getServiceImages(Request $request)
	{
		try{
			$data = $request->all();
			if($data){
				if($data['service_provider_id'] && $data['image']){
					$serviceImagePath = '/uploads/service_provider_details/';
					// To create a directory if not exists
					if (!(Storage::disk('s3')->exists('/uploads/service_provider_details')))
					{
						Storage::disk('s3')->makeDirectory('/uploads/service_provider_details/');
					}
					if(isset($data['image'])){
						$imageData = base64_decode($data['image']);
						$data['image'] = KranHelper::convertStringToImage($data['image'],'serviceprovider'.$data['service_provider_id'],$serviceImagePath);
						// To upload the images into Amazon S3
        				$amazonImgUpload = Storage::disk('s3')->put('/uploads/service_provider_details/'.$data['image'], $imageData, 'public');
						
					} else {
						$data['image'] = '';
					}
					$serviceImageStatus = ServiceProviderDetails::create($data);
					if($serviceImageStatus){
						$resultData = array('status'=>true,'message'=>'added successfully','result'=>'');	
					} else {
						$resultData = array('status'=>false,'message'=>'could not add','result'=>'');
					}
				} else {
					$resultData = array('status'=>false,'message'=>'invalid Input','result'=>'');
				}
				
			} else {
				$resultData = array('status'=>false,'message'=>'invalid request','result'=>'');
			}
		} catch(Exception $e){
			$resultData = array('status'=>false,'message'=>'invalid request','result'=>'');
		}
		return $resultData;
	}

	/*********************************** Web Service - Serivce Provider *******************************************/

    /**
	 * To login as a service provider
	 *
	 * @param array
	 * @return array
	 */
    public function spLogin(Request $request)
    {
    	try{
    		$data = $request->all();
    		if($data){
    			if($data['username'] && $data['password']){					
    				$unameExist = ServiceProvider::get()->where('email',$data['username'])->count();
    				$unameData = ServiceProvider::get()->where('email',$data['username'])->first();
    				//$user = User::find($reviewDetails['user_id']);
    				$basePath = URL::to('/').'/..';
    				$imagePath = $basePath.trans('main.provider_path');	
    				if($unameExist != 0){
						//compare the entered password with the password in the db with the given uname
    					$checkpwd = Hash::check($data['password'], $unameData->password);
    					$userData['id'] = $unameData->id;
    					$userData['name'] = $unameData->name_sp;
    					$userData['email'] = $unameData->email;
    					$userData['mobile'] = $unameData->phone;
						// To get the image form the Amazon s3 account
						if (Storage::disk('s3')->exists('uploads/provider/'.$unameData->logo)) {
							$userData['logo'] = \Storage::disk('s3')->url('uploads/provider/'.$unameData->logo);
						} else {
	    					$userData['logo'] = ($unameData->logo) ? $imagePath.$unameData->logo : ""; 
						}
    					if($checkpwd){
    						$resultData = array('status'=>true,'message'=>'service provider login available','result'=>$userData);
    					}else{
    						$resultData = array('status'=>false,'message'=>'invalid password','result'=>'');
    					}
    				}else{
    					$resultData = array('status'=>false,'message'=>'invalid username','result'=>'');
    				}					
    			}else{
    				$resultData = array('status'=>false,'message'=>'invalid Input','result'=>'');
    			}
    		}else{
    			$resultData = array('status'=>false,'message'=>'invalid request','result'=>'');
    		}
    	} catch(Exception $e){
    		$resultData = array('status'=>false,'message'=>'invalid request','result'=>'');
    	}
    	return $resultData;
    }


    /**
	 * To register as a service provider
	 *
	 * @param array
	 * @return array
	 */
    public function spRegister(Request $request)
    {
    	try{
    		$data = $request->all();
    		if($data){
				//check if the required fields are filled out
    			if($data['email'] && $data['password'] && $data['category_id'] && $data['location_id'] && $data['name'] && $data['logo'] && $data['city'] && $data['short_description'] && $data['status_owner_manager'] && $data['opening_hrs'] && $data['closing_hrs'] && $data['working_days'] && $data['phone']){
					//check if the email is already exist
    				$emailExists = ServiceProvider::get()->where('email',$data['email'])->count();
    				if($emailExists == 0){
    					$insertData['category_id'] = $data['category_id'];
    					$insertData['location_id'] = $data['location_id'];
    					$insertData['name_sp'] = $data['name'];
    					$insertData['slug'] = KranHelper::convertString($insertData['name_sp']);
    					$insertData['city'] = $data['city'];
    					$insertData['address'] = $data['address'];
    					$insertData['short_description'] = $data['short_description'];
    					$insertData['status_owner_manager'] = $data['status_owner_manager'];
    					$insertData['owner_name'] = $data['owner_name'];
    					$insertData['owner_phone'] = $data['owner_phone'];
    					$insertData['owner_designation'] = $data['owner_designation'];
    					$insertData['opening_hrs'] = $data['opening_hrs'];
    					$insertData['closing_hrs'] = $data['closing_hrs'];
    					$insertData['working_days'] = $data['working_days'];
    					$insertData['phone'] = $data['phone'];
    					$insertData['website_link'] = $data['website_link'];
    					$insertData['googlemap_latitude'] = $data['latitude'];
    					$insertData['googlemap_longitude'] = $data['longitude'];
    					$insertData['email'] = $data['email'];
    					$insertData['created_at'] = date('Y-m-d H:i:s');

    					$insertData['password'] = bcrypt($data['password']);
						// Local storage
    					$logoPath = trans('main.provider_path');
    					if(isset($data['logo'])){
    						$imageData = base64_decode($data['logo']);
            				//$insertData['logo'] = ServiceProvider::upload_file($request, 'logo');
    						$insertData['logo'] = KranHelper::convertStringToImage($data['logo'],$data['name'],$logoPath);
							// To create a directory if not exists
							if (!(Storage::disk('s3')->exists('/uploads/provider')))
							{
								Storage::disk('s3')->makeDirectory('/uploads/provider/');
							}
							// To upload the images into Amazon S3
							$amazonImgUpload = Storage::disk('s3')->put('/uploads/provider/'.$insertData['logo'], $imageData, 'public');	
    					} 

    					

    					$registerStatus = ServiceProvider::create($insertData);
    					if($registerStatus){
							$id = ServiceProvider::max('id');
    						if(isset($data['service_provider_images']) && $data['service_provider_images']){
								foreach ($data['service_provider_images'] as $row_photo) {
						            $file = $row_photo['image_no'] . '.jpg';
						            $dir = trans('main.provider_path') . $id . '/'; //file upload path  
						            // To check the object is exists or not
									if (Storage::disk('s3')->exists('uploads/provider/'.$row_photo['name'])) {
										// To delete the object from Amazon S3 repository
										Storage::disk('s3')->delete('uploads/provider/'.$row_photo['name']);
									}
						            $checkImageExist = ServiceProviderImages::where('service_provider_id',$id)->where('image_name',$file)->count();
						            if($checkImageExist == 0){
						                if (!filter_var($row_photo['name'], FILTER_VALIDATE_URL)) {
						                    $imageData = base64_decode($row_photo['name']);
						                    $photo = imagecreatefromstring($imageData);
						                    if ($photo) {				                       
						                        //create sub directory if not exist
						                        if (!is_dir($dir)) {
						                            @mkdir($dir);
						                        }
						                        $insert_data['service_provider_id'] = $id;
						                        $insert_data['image_name'] = KranHelper::uploadSPImage($row_photo['name'],$file,$dir);
						                        // To upload the object to the particular path with the permission as (Public)
	        											$amazonImgUpload = Storage::disk('s3')->put('uploads/provider/'.$insert_data['image_name'], $imageData, 'public');
	        						
						                        $insert_photos = ServiceProviderImages::create($insert_data);
						                       
						                    }
						                }
						            }
						        } 
							}
							$basePath = URL::to('/').'/..';
							$imagePath = $basePath.trans('main.provider_path');	
							$userData['id'] = $id;
							$userData['name'] = $insertData['name_sp'];
							$userData['email'] = $insertData['email'];
							$userData['mobile'] = $insertData['phone'];
							// To get the image form the Amazon s3 account
							if (Storage::disk('s3')->exists('uploads/provider/'.$insertData['logo'])) {
								$userData['logo'] = \Storage::disk('s3')->url('uploads/provider/'.$insertData['logo']);
							} else {
								$userData['logo'] = ($insertData['logo']) ? $imagePath.$insertData['logo'] : ""; 
							}
							$resultData = array('status'=>true,'message'=>'registered successfully','result'=>$userData);	
    					} else {
    						$resultData = array('status'=>false,'message'=>'registration failed','result'=>'');
    					}
    				} else {
    					$resultData = array('status'=>false,'message'=>'Email exist already','result'=>'');
    				}
    			}else{
    				$resultData = array('status'=>false,'message'=>'Invalid Input','result'=>'');
    			}
    		}else{
    			$resultData = array('status'=>false,'message'=>'invalid request','result'=>'');
    		}
    	}catch(Exception $e){
    		$resultData = array('status'=>false,'message'=>'invalid request','result'=>'');
    	}
    	return $resultData;
    }
	
	/**
	 * To reset passwrod of the service provider
	 *
	 * @param array
	 * @return array
	 */
	public function spForgotPassword(Request $request)
	{
		try{
			//$data = $request->all();
			//if($data){
				$data['email'] = 'test@gmail.com';
				if($data['email']){	
					$emailExists = ServiceProvider::get()->where('email',$data['email'])->count();
					if($emailExists != 0){
						//$password = KranHelper::generate_random_string(8);
						//$cryptedPassword = bcrypt($password);
						//$updateQuery = ServiceProvider::where('email',$data['email'])->update(['password' => $cryptedPassword]);
						$data['content'] = 'Click here to reset your password <a href="#">Reset Password</a>';
						return view('email.forgot-password',['data' => $data]);
						Mail::send('email.forgot-password', ['data' => $data], function($message) use ($data)
						{
							$message->to($data['email'])->subject('Feedback');
							//$message->to('joanbritto18@gmail.com', 'Joan Britto')->subject('Reset Password');
						});
						$resultData = array('status'=>true,'message'=>'password reset link is sent to the email id','result'=>'');

					}else{
						$resultData = array('status'=>false,'message'=>'invalid email','result'=>'');
					}
				}else{
					$resultData = array('status'=>false,'message'=>'Invalid Input','result'=>'');
				}
			//}else{
			//	$resultData = array('status'=>false,'message'=>'invalid request','result'=>'');
			//}
		}catch(Exception $e){
			$resultData = array('status'=>false,'message'=>'invalid request','result'=>'');
		}
		return $resultData;
	}


	/**
	 * To change the passwrod of the service provider
	 *
	 * @param array
	 * @return array
	 */
	public function spChangePassword(Request $request)
	{
		try{
			$data = $request->all();
			if($data){
				if($data['email'] && $data['old_password'] && $data['new_password']){	
					$emailExist = ServiceProvider::get()->where('email',$data['email'])->count();
					$spData = ServiceProvider::get()->where('email',$data['email'])->first();
					
					if($emailExist != 0){
						//compare the entered password with the password in the db with the given uname
						$checkpwd = Hash::check($data['old_password'], $spData->password);
						if($checkpwd){
							//$password = KranHelper::generate_random_string(8);
							$cryptedPassword = bcrypt($data['new_password']);
							$updateQuery = ServiceProvider::where('email',$data['email'])->update(['password' => $cryptedPassword]);
							if($updateQuery){
								$resultData = array('status'=>true,'message'=>'password changed successfully','result'=>'');
							}else{
								$resultData = array('status'=>true,'message'=>'password could not be changed','result'=>'');
							}
						}else{
							$resultData = array('status'=>false,'message'=>'invalid old password','result'=>'');
						}
					}else{
						$resultData = array('status'=>false,'message'=>'invalid email','result'=>'');
					}
				}else{
					$resultData = array('status'=>false,'message'=>'Invalid Input','result'=>'');
				}
			}else{
				$resultData = array('status'=>false,'message'=>'invalid request','result'=>'');
			}
		}catch(Exception $e){
			$resultData = array('status'=>false,'message'=>'invalid request','result'=>'');
		}

		return $resultData;
	}


	/**
	 * To get the pre-requisites data
	 *
	 * @return array
	 */
	public function prerequisitesList(){
		try{
			$basePath = URL::to('/').'/..';
			$imagePath = $basePath.trans('main.category_path');
			$categoryData 			= Category::get()->where('status','Active');
			if($categoryData){
				foreach ($categoryData as $index => $row) {
					$services = CategoryService::getCategoryService($row->id);

					$serviceArray = [];
					if($services){
						$categoryServices = Service::whereIn('id',[$services])->get();

						if($categoryServices){
							foreach ($categoryServices as $key => $value) {
								$serviceArray[$key]['service_id'] = $value->id;
								$serviceArray[$key]['service_name'] = ($value->service_name) ?  $value->service_name : "";								
							}
						}

					}
					$arrayData[$index]['id'] = $row->id;
					$arrayData[$index]['category_name'] = ($row->category_name) ? $row->category_name : "";
					$arrayData[$index]['description'] = ($row->description) ? $row->description : "";
					// To get the image form the Amazon s3 account
					if (Storage::disk('s3')->exists('uploads/category/'.$row->category_image)) {
						$arrayData[$index]['category_image'] = \Storage::disk('s3')->url('uploads/category/'.$row->category_image);
					} else {
						$arrayData[$index]['category_image'] = ($row->category_image) ? $imagePath.$row->category_image : '';
					}
					$arrayData[$index]['order_by'] = ($row->order_by) ? $row->order_by : "";
					$arrayData[$index]['category_services'] = $serviceArray;
				}
				$data['categories_list']			= $arrayData;
				$resultData = array('status'=>true,'message'=>'request success','result'=>$data);
			}else{
				$resultData = array('status'=>false,'message'=>'No Records Found','result'=>'');
			}			
		} catch(Exception $e){
			$resultData = array('status'=>false,'message'=>'invalid request','result'=>'');
		}
		return $resultData;
		
	}


	/**
	 * To get the service provider details
	 *
	 * @return array
	 */
	public function getServiceProvider(Request $request){
		try{
			$data = $request->all();
			if($data){
				if($data['id']){
					$basePath = URL::to('/').'/..';
					$imagePath = $basePath.trans('main.provider_path');
					$spData = ServiceProvider::get()->where('id',$data['id'])->first();

					if($spData){
						$arrayData['id'] = $spData->id;
						$arrayData['category'] = ($spData->category_id) ? Category::getCategoryNameById($spData->category_id) : "";
						$arrayData['name'] = ($spData->name_sp) ? $spData->name_sp : "";
						// To get the image form the Amazon s3 account
						if (Storage::disk('s3')->exists('uploads/provider/'.$spData->logo)) {
							$arrayData['logo'] = \Storage::disk('s3')->url('uploads/provider/'.$spData->logo);
						} else {
							$arrayData['logo'] = ($spData->logo) ? $imagePath.$spData->logo : "";
						}
						$arrayData['address'] = ($spData->address) ? $spData->address : "";
						$arrayData['phone'] = ($spData->phone) ? $spData->phone : "";
						$arrayData['website_link'] = ($spData->website_link) ? $spData->website_link : "";
						$resultData = array('status'=>true,'message'=>'request success','result'=>$arrayData);
					}else{
						$resultData = array('status'=>false,'message'=>'No Records Found','result'=>'');
					}	
				}else{
					$resultData = array('status'=>false,'message'=>'Invalid Input','result'=>'');
				}	
			}else{
				$resultData = array('status'=>false,'message'=>'invalid request','result'=>'');
			}	
		} catch(Exception $e){
			$resultData = array('status'=>false,'message'=>'invalid request','result'=>'');
		}
		return $resultData;

	}

	/**
	 * To get the Review details
	 *
	 * @return array
	 **/
	public function getreviewlist(Request $request)
	{
		try {
			$data = $request->all();
			if ($data) {
				if ($data['id']) {
					$reviewDetails = Review::where('id', '=', $data['id'])->first();
					if ($reviewDetails) {
						$user = User::find($reviewDetails['user_id']);
						$basePath = URL::to('/').'/..';
						$imagePath = $basePath.trans('main.user_path');	
						$arrayData['id'] = $reviewDetails['id'];
						$arrayData['service_provider_name'] = ($reviewDetails['service_provider_id']) ? ServiceProvider::getServiceNameById($reviewDetails['service_provider_id']) : "";
						$arrayData['user'] = ($reviewDetails['user_id']) ? User::getUserNameById($reviewDetails['user_id']) : "";
						// To get the image form the Amazon s3 account
						if (Storage::disk('s3')->exists('uploads/user/'.$user->profile_picture)) {
							$arrayData['user_image'] = \Storage::disk('s3')->url('uploads/user/'.$user->profile_picture);
						} else {
							$arrayData['user_image'] = $imagePath.$user->profile_picture;
						}
						$arrayData['reviews'] = ($reviewDetails['reviews']) ? $reviewDetails['reviews'] : "";
						$arrayData['ratings'] = ($reviewDetails['rating']) ? $reviewDetails['rating'] : "";
						$arrayData['posted_on'] = ($reviewDetails['postted_on']) ? KranHelper::formatDate($reviewDetails['postted_on']) : "";
						$resultData = array('status' =>true,'message' => 'request success', 'result' => $arrayData);
					} else{
						$resultData = array('status'=>false,'message'=>'No Records Found','result'=>'');
					}
				} else{
					$resultData = array('status'=>false,'message'=>'Invalid Input','result'=>'');
				} 
			} else{
				$resultData = array('status'=>false,'message'=>'invalid request','result'=>'');
			}

		} catch (Exception $e) {
			$resultData = array('status'=>false,'message'=>'invalid request','result'=>'');
		}
		return 	$resultData;
	}

	/**
	 * To get the service provider details
	 *
	 * @return array
	 */
	public function updateServiceProvider(Request $request){
		try{
			$data = $request->all();
			if($data){
			
				//check if the required fields are filled out
				if($data['id'] && $data['category_id'] && $data['location_id'] && $data['name'] && $data['logo'] && $data['city'] && $data['short_description'] && $data['status_owner_manager'] && $data['opening_hrs'] && $data['closing_hrs'] && $data['working_days'] && $data['phone']){ 
					$provider  = ServiceProvider::findorFail($data['id']);

					$input['category_id'] = $data['category_id'];
					$input['location_id'] = $data['location_id'];
					$input['name_sp'] = $data['name'];
					$input['slug'] = KranHelper::convertString($input['name_sp']);
					$input['city'] = $data['city'];
					$input['address'] = $data['address'];
					$input['short_description'] = $data['short_description'];
					$input['status_owner_manager'] = $data['status_owner_manager'];
					$input['owner_name'] = $data['owner_name'];
					$input['owner_phone'] = $data['owner_phone'];
					$input['owner_designation'] = $data['owner_designation'];
					$input['opening_hrs'] = $data['opening_hrs'];
					$input['closing_hrs'] = $data['closing_hrs'];
					$input['working_days'] = $data['working_days'];
					$input['phone'] = $data['phone'];
					$input['website_link'] = $data['website_link'];
					$input['googlemap_latitude'] = $data['latitude'];
					$input['googlemap_longitude'] = $data['longitude'];
					$input['updated_at'] = date('Y-m-d H:i:s');

					$logoPath = trans('main.provider_path');
					if($data['logo']){
						if (!filter_var($data['logo'], FILTER_VALIDATE_URL)) { 
							$input['logo'] = KranHelper::convertStringToImage($data['logo'],$data['name'],$logoPath);
							if($provider->logo){
								@unlink(base_path().$logoPath.'/'.$provider->logo);
							}
							// To check the object is exists or not
							if (Storage::disk('s3')->exists('uploads/provider/'.$provider->logo)) {
								// To delete the object from Amazon S3 repository
								Storage::disk('s3')->delete('uploads/provider/'.$provider->logo);
							}

						}  	
					}
					if(isset($data['service_provider_images']) && $data['service_provider_images']){
						foreach ($data['service_provider_images'] as $row_photo) {
							if (!filter_var($row_photo['name'], FILTER_VALIDATE_URL)) {
								$file = $row_photo['image_no'] . '.jpg';
								$dir = trans('main.provider_path') . $provider->id . '/'; //file upload path  
								$checkImageExist = ServiceProviderImages::where('service_provider_id',$provider->id)->where('image_name',$file)->count();
								if($checkImageExist == 0){
									$imageData = base64_decode($row_photo['name']);
									$photo = imagecreatefromstring($imageData);
									if ($photo) {				                       
										//create sub directory if not exist
										if (!is_dir($dir)) {
											@mkdir($dir);
										}
										$insert_data['service_provider_id'] = $provider->id;
										$insert_data['image_name'] = KranHelper::uploadSPImage($row_photo['name'],$file,$dir);
										// To upload the object to the particular path with the permission as (Public)
	        					$amazonImgUpload = Storage::disk('s3')->put('uploads/provider/'.$insert_data['image_name'], $imageData, 'public');
										$insert_photos = ServiceProviderImages::create($insert_data);
										 
									}
								}
							}
				    } 
					}

					if(isset($data['delete_provider_images']) && $data['delete_provider_images']){
						//$file = $row_photo['image_no'] . '.jpg';
				        $dir = trans('main.provider_path') . $provider->id . '/'; //file upload path  
						$ids = explode(',', $data['delete_provider_images']);
						foreach ($ids as $key => $value) {
							$spImages = ServiceProviderImages::find($value);
							if($spImages){
								$file_name = $dir.$spImages->image_name;
									if (is_file($file_name)) {
			            				unlink($file_name);
				        			}
				        	// To check the object is exists or not
									if (Storage::disk('s3')->exists('uploads/provider/'. $provider->id . '/'. $spImages->image_name)) {
										// To delete the object from Amazon S3 repository
										Storage::disk('s3')->delete('uploads/provider/'. $provider->id . '/'. $spImages->image_name);
									}
								}	
								$spImages->delete();
							}
						}
					$provider->fill($input);
					if($provider->save()){
						$resultData = array('status'=>true,'message'=>'service provider updated successfully','result'=>'');	
					} else {
						$resultData = array('status'=>false,'message'=>'could not update service provider','result'=>'');
					}

				}else{  
					$resultData = array('status'=>false,'message'=>'Invalid Input','result'=>'');
				}	
			}else{ 
				$resultData = array('status'=>false,'message'=>'invalid request','result'=>'');
			}	
		} catch(Exception $e){
			$resultData = array('status'=>false,'message'=>'invalid request','result'=>'');
		}
		return $resultData;

	}

	/**
	 * To get the list of Reivews
	 *
	 * @return array
	 **/
	public function reviewList(Request $request)
	{
		try  {
			if ($request) {
				$reviewData = $request->all();
				$page = (isset($reviewData['page'])) ? $reviewData['page'] : "0";	
				$recordLimit = (isset($reviewData['limit'])) ? $reviewData['limit'] : "20";	
				//$page = ($page > 0) ? $page - 1 : 0;
				$start =  $page * $recordLimit + 1;
				//$end = $page * $recordLimit + $recordLimit;
				$end = $recordLimit;
				$reviewDetails = Review::where('status', 'Active')->skip($start)->take($end)->get();
				$arrayData = [];
				if ($reviewDetails) {
					foreach ($reviewDetails as $index => $value) {
						$user = User::find($value['user_id']);
						$basePath = URL::to('/').'/..';
						$imagePath = $basePath.trans('main.user_path');	
						$arrayData[$index]['id'] = $value['id'];
						$arrayData[$index]['service_provider_name'] = ($value['service_provider_id']) ? ServiceProvider::getServiceNameById($value['service_provider_id']) : "";
						$arrayData[$index]['user_id'] = $value['user_id'];
						$arrayData[$index]['user'] = ($value['user_id']) ? User::getUserNameById($value['user_id']) : "";
						// To get the image form the Amazon s3 account
						if (Storage::disk('s3')->exists('uploads/user/'.$user->profile_picture)) {
							$arrayData[$index]['user_image'] = \Storage::disk('s3')->url('uploads/user/'.$user->profile_picture);
						} else {
							$arrayData[$index]['user_image'] = $imagePath.$user->profile_picture;
						}
						$arrayData[$index]['reviews'] = ($value['reviews']) ? $value['reviews'] : "";
						$arrayData[$index]['ratings'] = ($value['rating']) ? $value['rating'] : "";
						$arrayData[$index]['posted_on'] = ($value['postted_on']) ? KranHelper::formatDate($value['postted_on']) : "";

					}
					if($arrayData) {
						$data['reviews_list'] = $arrayData;
						$resultData = array('status'=>true,'message'=>'request success','result'=>$data);	
					} else {
						$resultData = array('status'=>false,'message'=>'No Records Found','result'=>'');	
					}
				} else{
					$resultData = array('status'=>false,'message'=>'No Records Found','result'=>'');
				}	
			} else {
				$resultData = array('status'=>false,'message'=>'invalid request','result'=>'');
			}	
		} catch (Exception $e) {
			$resultData = array('status'=>false,'message'=>'invalid request','result'=>'');
		}
		return $resultData;
	}

	/**
	 * To get the User Details 
	 *
	 * @return array
	 **/
	public function UserList(Request $request)
	{
		try {
			if ($request) {
				$userData = $request->all();
				$page = (isset($userData['page'])) ? $userData['page'] : "0";	
				$recordLimit = (isset($userData['limit'])) ? $userData['limit'] : "20";	

				$page = ($page > 0) ? $page - 1 : 0;
				$start =  $page * $recordLimit + 1;
				//$end = $page * $recordLimit + $recordLimit;
				$end = $recordLimit;
				$basePath = URL::to('/').'/..';
				$imagePath = $basePath.trans('main.user_path');
				$arrayData = [];
				$userDetails = User::where('status', 'Active')->skip($start)->take($end)->get();
				if ($userDetails) {
					foreach ($userDetails as $index => $users) {
						$arrayData[$index]['id'] = $users['id'];
						$arrayData[$index]['fullname'] = ($users['fullname']) ? $users['fullname'] : "";
						// To get the image form the Amazon s3 account
						if (Storage::disk('s3')->exists('uploads/user/'.$users['profile_picture'])) {
							$arrayData[$index]['profile_picture'] = \Storage::disk('s3')->url('uploads/user/'.$users['profile_picture']);
						} else {
							$arrayData[$index]['profile_picture'] = ($users['profile_picture']) ? $imagePath . $users['profile_picture'] : "";
						}
						$arrayData[$index]['address'] = ($users['address']) ? $users['address'] : "";
						$arrayData[$index]['mobile'] = ($users['mobile']) ? $users['mobile'] : "";
						$arrayData[$index]['email'] = ($users['email']) ? $users['email'] : "";
						$arrayData[$index]['facebook_id'] = ($users['facebook_id']) ? $users['facebook_id'] : "";
						$arrayData[$index]['password'] = ($users['password']) ? $users['password'] : "";
						$arrayData[$index]['register_mode'] = ($users['register_mode']) ? $users['register_mode'] : "";
						$arrayData[$index]['been_there_status'] = ($users['been_there_status']) ? $users['been_there_status'] : "";
						$arrayData[$index]['status'] = ($users['status']) ? $users['status'] : "";
						$arrayData[$index]['otp'] = ($users['otp']) ? $users['otp'] : "";
						$arrayData[$index]['resend_otp_status'] = ($users['resend_otp_status']) ? $users['resend_otp_status'] : "";
						$arrayData[$index]['mobile_verification_status'] = ($users['mobile_verification_status']) ? $users['mobile_verification_status'] : "";
						$arrayData[$index]['registered_on'] = ($users['registered_on']) ? $users['registered_on'] : "";
					}
					if ($arrayData) {
						$data['users_list'] = $arrayData;
						$resultData = array('status'=>true,'message'=>'request success','result'=>$data);		
					} else {
						$resultData = array('status'=>false,'message'=>'No Records Found','result'=>'');
					}

				} else {
					$resultData = array('status'=>false,'message'=>'No Records Found','result'=>'');
				}
			} else {
				$resultData = array('status'=>false,'message'=>'invalid request','result'=>'');
			}
		} catch (Exception $e) {
			$resultData = array('status'=>false,'message'=>'invalid request','result'=>'');
		}
		return $resultData;
	}

	/**
	 * To get the user Details
	 *
	 * @return array
	 **/
	public function userDetails(Request $request)
	{ 
		try {
			$data = $request->all();
			if($data){
				$basePath = URL::to('/').'/..';
				$imagePath = $basePath.trans('main.user_path');
				if ($data['id']) {
					$userData = User::where('id', $data['id'])->first();
					if ($userData) {
						$arrayData['id'] = $userData['id'];
						$arrayData['fullname'] = ($userData['fullname']) ? $userData['fullname'] : "";
						// To get the image form the Amazon s3 account
						if (Storage::disk('s3')->exists('uploads/user/'.$userData['profile_picture'])) {
							$arrayData['profile_picture'] = \Storage::disk('s3')->url('uploads/user/'.$userData['profile_picture']);
						} else {
							$arrayData['profile_picture'] = ($userData['profile_picture']) ? $imagePath . $userData['profile_picture'] : "";
						}
						$arrayData['address'] = ($userData['address']) ? $userData['address'] : "";
						$arrayData['mobile'] = ($userData['mobile']) ? $userData['mobile'] : "";
						$arrayData['email'] = ($userData['email']) ? $userData['email'] : "";
						$arrayData['facebook_id'] = ($userData['facebook_id']) ? $userData['facebook_id'] : "";
						$arrayData['password'] = ($userData['password']) ? $userData['password'] : "";
						$arrayData['register_mode'] = ($userData['register_mode']) ? $userData['register_mode'] : "";
						$arrayData['been_there_status'] = ($userData['been_there_status']) ? $userData['been_there_status'] : "";
						$arrayData['status'] = ($userData['status']) ? $userData['status'] : "";
						$arrayData['otp'] = ($userData['otp']) ? $userData['otp'] : "";
						$arrayData['resend_otp_status'] = ($userData['resend_otp_status']) ? $userData['resend_otp_status'] : "";
						$arrayData['mobile_verification_status'] = ($userData['mobile_verification_status']) ? $userData['mobile_verification_status'] : "";
						$arrayData['registered_on'] = ($userData['registered_on']) ? $userData['registered_on'] : "";
						$resultData = array('status'=>true,'message'=>'request success','result'=>$arrayData);
					} else {
						$resultData = array('status'=>false,'message'=>'No Records Found','result'=>'');
					}
				} else {
					$resultData = array('status'=>false,'message'=>'invalid input','result'=>'');
				}
			} else {
				$resultData = array('status'=>false,'message'=>'invalid request','result'=>'');
			}
		} catch (Exception $e) {
			$resultData = array('status' => false, 'message' => 'invalid request', 'request' => '');
		}
		return $resultData;
	}

	/**
	 * To update the customer details
	 *
	 * @return array
	 */
	public function updateCustomer(Request $request){
		try{
			$data = $request->all();
			if($data){
				//check if ther equired fields are filled out
				if($data['id'] && $data['fullname'] && $data['address'] && $data['mobile'] && $data['email']){
					$user  = User::findorFail($data['id']);
					$checkEmailCount = User::where('id','!=',$data['id'])->where('email',$data['email'])->count();
					$checkEmail = User::where('id','!=',$data['id'])->where('email',$data['email'])->get();
					if($checkEmailCount == 0){
						/*if($data['mobile'] != $user->mobile){
							$resultData = array('status'=>false,'message'=>'you are not allowed to modify the mobile number','result'=>'');
							return $resultData;
						}*/
						$input['fullname'] = $data['fullname'];					
						$input['address'] = $data['address'];
						$input['mobile'] = $data['mobile'];
						$input['email'] = $data['email'];
						//$input['register_mode'] = $data['register_mode'];
						if($data['register_mode']){
							$input['register_mode'] = $this->getRegisterMode($data['register_mode']);
							if($data['register_mode'] == 'Facebook'){		
								$input['facebook_id'] = (isset($data['facebook_id']) && $data['facebook_id']) ? $data['facebook_id'] : '';
							}
						}
						$input['updated_at'] = date('Y-m-d H:i:s');
						if(isset($data['password']) && $data['password']){
							$input['password'] = bcrypt($data['password']);
						}
						$logoPath = trans('main.user_path');
						if(isset($data['profile_picture']) && $data['profile_picture']){
							if (!filter_var($data['profile_picture'], FILTER_VALIDATE_URL)) { 
								// To decode the base64 base
								$imageData = base64_decode($data['profile_picture']);
								$input['profile_picture'] = KranHelper::convertStringToImage($data['profile_picture'],$data['fullname'],$logoPath);
								// To check the object is exists or not
								if (Storage::disk('s3')->exists('uploads/user/'.$user->profile_picture)) {
									// To delete the object from Amazon S3 repository
									Storage::disk('s3')->delete('uploads/user/'.$user->profile_picture);
								}
								// To upload the object to the particular path with the permission as (Public)
								$amazonImgUpload = Storage::disk('s3')->put('uploads/user/'.$input['profile_picture'], imageData, 'public');
								if($user->profile_picture){
									@unlink(base_path().$logoPath.'/'.$user->profile_picture);
								}
							}  	
						}
						$user->fill($input);
						if($user->save()){
							$resultData = array('status'=>true,'message'=>'customer updated successfully','result'=>'');	
						} else {
							$resultData = array('status'=>false,'message'=>'could not update customer','result'=>'');
						}
					}else{
						$resultData = array('status'=>false,'message'=>'email already exist','result'=>'');
					}
				}else{
					$resultData = array('status'=>false,'message'=>'Invalid Input','result'=>'');
				}	
			}else{
				$resultData = array('status'=>false,'message'=>'invalid request','result'=>'');
			}	
		} catch(Exception $e){
			$resultData = array('status'=>false,'message'=>'invalid request','result'=>'');
		}
		return $resultData;

	}


	/**
	 * To get the service providers view by id
	 *
	 * @return array
	 */
	public function viewServiceProvider(Request $request){
		try{
			$data = $request->all();
			if($data){
				//check if ther equired fields are filled out
				if(isset($data['id']) && $data['id']){

					$serviceProvider	= ServiceProvider::find($data['id']);
					$basePath = URL::to('/').'/..';
					$imagePath = $basePath.trans('main.provider_path');	
					if($serviceProvider){ 
						$data['id']				= $serviceProvider->id;
						$data['category_id']		= $serviceProvider->category_id;
						$data['category']		= $serviceProvider->category->category_name;

						$data['category_services']	= $this->getSPCategoryServices($serviceProvider->id,$serviceProvider->category_id);
						$data['location_id']		= ($serviceProvider->location_id) ? $serviceProvider->location_id : "";
						$data['locality']		= ($serviceProvider->locality->locality_name) ? $serviceProvider->locality->locality_name : "";
						$data['name_sp']			= ($serviceProvider->name_sp) ? $serviceProvider->name_sp : "";
						// To get the image form the Amazon s3 account
						if (Storage::disk('s3')->exists('uploads/provider/'.$serviceProvider->logo)) {
							$data['logo'] = \Storage::disk('s3')->url('uploads/provider/'.$serviceProvider->logo);
						} else {
							$data['logo']			= ($serviceProvider->logo) ? $imagePath.$serviceProvider->logo : "";
						}
						$data['city_id']			= ($serviceProvider->city) ? $serviceProvider->city : "";
						$data['city_name']			= ($serviceProvider->city) ? $serviceProvider->cities->city_name : "";
						$data['address']			= ($serviceProvider->address) ? $serviceProvider->address : "";				
						$data['short_description']		= ($serviceProvider->short_description) ? $serviceProvider->short_description : "";
						$data['status_owner_manager']	= ($serviceProvider->status_owner_manager) ? $serviceProvider->status_owner_manager : "";
						$data['owner_name']				= ($serviceProvider->owner_name) ? $serviceProvider->owner_name : "";
						$data['owner_designation']		= ($serviceProvider->owner_designation) ? $serviceProvider->owner_designation : "";
						$data['owner_phone']				= ($serviceProvider->owner_phone) ? $serviceProvider->owner_phone : "";
						$data['opening_hrs_id']				= ($serviceProvider->opening_hrs) ? $serviceProvider->opening_hrs : "";
						$data['opening_hrs']				= ($serviceProvider->opening_hrs) ? KranHelper::getFormattedTime($serviceProvider->opening_hrs) : "";
						$data['closing_hrs_id']				= ($serviceProvider->closing_hrs) ? $serviceProvider->closing_hrs : "";
						$data['closing_hrs']				= ($serviceProvider->closing_hrs) ? KranHelper::getFormattedTime($serviceProvider->closing_hrs) : "";

						$data['working_days']			= ($serviceProvider->working_days) ? $serviceProvider->working_days : "";
						$data['phone']					= ($serviceProvider->phone) ? $serviceProvider->phone : "";
						$data['website_link']			= ($serviceProvider->website_link) ? $serviceProvider->website_link : "";
						$data['googlemap_latitude']		= ($serviceProvider->googlemap_latitude) ? $serviceProvider->googlemap_latitude : "";
						$data['googlemap_longitude']		= ($serviceProvider->googlemap_longitude) ? $serviceProvider->googlemap_longitude : "";
						$data['email']					= ($serviceProvider->email) ? $serviceProvider->email : "";
						$data['ratings'] 	= Review::getRatingsOfServiceProviderById($data['id']);
						$data['reviews'] = Review::where('service_provider_id',$data['id'])->count();
				        $spImagesCount = ServiceProviderImages::where('service_provider_id',$serviceProvider->id)->count();
				        $arrayData = [];
				        if($spImagesCount != 0){
				        	$spImages = ServiceProviderImages::where('service_provider_id',$serviceProvider->id)->get();
							foreach ($spImages as $index => $row) {
									$basePath = URL::to('/').'/..';
									$imagePath = $basePath.trans('main.provider_path');	
									// To get the image form the Amazon s3 account
									if (Storage::disk('s3')->exists('uploads/provider/'.$row['service_provider_id'].'/'.$row['image_name'])) {
										$file = \Storage::disk('s3')->url('uploads/provider/'.$row['service_provider_id'].'/'.$row['image_name']);
									} else {
			            	$file = $imagePath.$row['service_provider_id'].'/'.$row['image_name'];
			            }
			            $arrayData[$index]['image_no'] = $row['id'];
			            $arrayData[$index]['name'] = $file;
					        } 
					    }
					    $data['service_provider_images'] = $arrayData;
						$resultData = array('status'=>true,'message'=>'request success','result'=>$data);
					}else{
						$resultData = array('status'=>false,'message'=>'invalid id','result'=>"");
					}
				}else{
					$resultData = array('status'=>false,'message'=>'Invalid Input','result'=>'');
				}	
			}else{
				$resultData = array('status'=>false,'message'=>'invalid request','result'=>'');
			}	
		} catch(Exception $e){
			$resultData = array('status'=>false,'message'=>'invalid request','result'=>'');
		}
		return $resultData;
	}

	/**
	 * To add or update the reviwe details
	 *
	 * @return array()
	 **/
	public function postReview(Request $request)
	{
		try {
			$data = $request->all();
			if ($data) {
				if (isset($data['id'])) {
					$inputData = Review::updateOrCreate(['id'=> $data['id']],[
						'service_provider_id' => ($data['service_provider_id']) ? $data['service_provider_id'] : "",
						'user_id' => ($data['user_id']) ? $data['user_id'] : "",
						'reviews' => ($data['reviews']) ? $data['reviews'] : "",
						'rating' => ($data['rating']) ? $data['rating'] : "",
						'id' => ($data['id']) ? $data['id'] : "",
					]);
					if ($inputData) {
						$arrayData['service_provider_id']  = ($inputData['service_provider_id']) ? $inputData['service_provider_id'] : "";
						$arrayData['user_id']  = ($inputData['user_id']) ? $inputData['user_id'] : "";
						$arrayData['reviews']  = ($inputData['reviews']) ? $inputData['reviews'] : "";
						$arrayData['rating']  = ($inputData['rating']) ? $inputData['rating'] : "";	
						$resultData = array('status'=>true,'message'=>'review added/edited successfully','result'=>$arrayData);	
					} else {
						$resultData = array('status'=>false,'message'=>'review add/edit failed','result'=>'');
					}
				} else {
					$inputData = Review::updateOrCreate([
						'service_provider_id' => ($data['service_provider_id']) ? $data['service_provider_id'] : "",
						'user_id' => ($data['user_id']) ? $data['user_id'] : "",
						'reviews' => ($data['reviews']) ? $data['reviews'] : "",
						'rating' => ($data['rating']) ? $data['rating'] : "",
					]);
					if ($inputData) {
						$arrayData['service_provider_id']  = ($inputData['service_provider_id']) ? $inputData['service_provider_id'] : "";
						$arrayData['user_id']  = ($inputData['user_id']) ? $inputData['user_id'] : "";
						$arrayData['reviews']  = ($inputData['reviews']) ? $inputData['reviews'] : "";
						$arrayData['rating']  = ($inputData['rating']) ? $inputData['rating'] : "";	
						$resultData = array('status'=>true,'message'=>'review added/edited successfully','result'=>$arrayData);	
					} else {
						$resultData = array('status'=>false,'message'=>'review add/edit failed','result'=>'');
					}
				}
			} else {
				$resultData = array('status'=>false,'message'=>'invalid request','result'=>'');
			}
		} catch (Exception $e) {
			$resultData = array('status'=>false, 'message'=>'invalid request', 'result'=>'');
		}
		return $resultData;
	}


	/**
	 * To get the list of reviews posted by User
	 * @param user ID
	 * @return array
	 **/
	public function userReviews(Request $request)
	{
		try  {
			$data = $request->all();
			if ($data) {
				if (isset($data['id'])) {
					$user = User::find($data['id']);
					if($user){
						//$page = (isset($data['page'])) ? $data['page'] : "0";	
						//$recordLimit = (isset($data['limit'])) ? $data['limit'] : "20";	
						//$page = ($page > 0) ? $page - 1 : 0;
						
						//$start =  $page * $recordLimit + 1;
						//$end = $page * $recordLimit + $recordLimit;
						//$end = $recordLimit;
						//$reviewDetails = Review::where('status','Active')->where('user_id',$data['id'])->skip($page)->take($recordLimit)->get();
						$reviewDetails = Review::where('status','Active')->where('user_id',$data['id'])->get();
						if (count($reviewDetails) > 0) {
							$basePath = URL::to('/').'/..';
							$imagePath = $basePath.trans('main.provider_path');	
							foreach ($reviewDetails as $index => $value) {
								$sp = ServiceProvider::find($value['service_provider_id']);
								$arrayData[$index]['id'] = $value['id'];
								$arrayData[$index]['service_provider_id'] = $value['service_provider_id'];
								$arrayData[$index]['name'] = ($value['service_provider_id']) ? ServiceProvider::getServiceNameById($value['service_provider_id']) : "";
								// To get the image form the Amazon s3 account
								if (Storage::disk('s3')->exists('uploads/provider/'.$sp->logo)) {
									$arrayData[$index]['image'] = \Storage::disk('s3')->url('uploads/provider/'.$sp->logo);
								} else {
									$arrayData[$index]['image'] = ($sp->logo) ? $imagePath.$sp->logo : "";
								}
								//$arrayData[$index]['user'] = ($value['user_id']) ? User::getUserNameById($value['user_id']) : "";
								$arrayData[$index]['reviews'] = ($value['reviews']) ? $value['reviews'] : "";
								$arrayData[$index]['ratings'] = ($value['rating']) ? $value['rating'] : "";
								$arrayData[$index]['posted_on'] = ($value['postted_on']) ? KranHelper::formatDate($value['postted_on']) : "";
							}
							//$data['reviews_list'] = $arrayData;
							$resultData = array('status'=>true,'message'=>'request success','result'=>$arrayData);	
						} else{
							$resultData = array('status'=>false,'message'=>'No Records Found','result'=>'');
						}
					}else{
						$resultData = array('status'=>false,'message'=>'invalid id','result'=>'');
					}
				} else {
					$resultData = array('status'=>false,'message'=>'invalid input','result'=>'');
				}	
			} else {
				$resultData = array('status'=>false,'message'=>'invalid request','result'=>'');
			}
		} catch (Exception $e) {
			$resultData = array('status'=>false,'message'=>'invalid request','result'=>'');
		}
		return $resultData;
	}


	/**
	 * To get the list of reviews posted to Service Provider
	 * @param service Provider ID
	 * @return array
	 **/
	public function spReviews(Request $request)
	{
		try  {
			$data = $request->all();
			if ($data) {
				if (isset($data['id'])) {
					$sp = ServiceProvider::find($data['id']);
					if(count($sp) > 0){
						//$page = (isset($data['page'])) ? $data['page'] : "0";	
						//$recordLimit = (isset($data['limit'])) ? $data['limit'] : "20";	
						//$page = ($page > 0) ? $page - 1 : 0;
						//$start =  $page * $recordLimit + 1;
						//$end = $page * $recordLimit + $recordLimit;
						//$end = $recordLimit;
						//$reviewDetails = Review::where('status','Active')->where('service_provider_id',$data['id'])->skip($page)->take($recordLimit)->get();
						$reviewDetails = Review::where('status','Active')->where('service_provider_id',$data['id'])->get();
						if (count($reviewDetails) > 0) {
							foreach ($reviewDetails as $index => $value) {
								$user = User::find($value->user_id);
								$basePath = URL::to('/').'/..';
								$imagePath = $basePath.trans('main.user_path');				
								$arrayData[$index]['id'] = $value->id;
								$arrayData[$index]['user_id'] = $value->user_id;
							//$arrayData[$index]['service_provider_name'] = ($value['service_provider_id']) ? ServiceProvider::getServiceNameById($value['service_provider_id']) : "";
							
								$arrayData[$index]['name'] = ($value->user_id) ? User::getUserNameById($value->user_id) : "";
								// To get the image form the Amazon s3 account
								if (Storage::disk('s3')->exists('uploads/user/'.$user->profile_picture)) {
									$arrayData[$index]['image'] = \Storage::disk('s3')->url('uploads/user/'.$user->profile_picture);
								} else {
									$arrayData[$index]['image'] = $imagePath.$user->profile_picture;
								}
								$arrayData[$index]['reviews'] = ($value->reviews) ? $value->reviews : "";
								$arrayData[$index]['ratings'] = ($value->rating) ? $value->rating : "";
								$arrayData[$index]['posted_on'] = ($value->postted_on) ? KranHelper::formatDate($value->postted_on) : "";
							}
						//$data['reviews_list'] = $arrayData;
							$resultData = array('status'=>true,'message'=>'request success','result'=>$arrayData);	
						} else{
							$resultData = array('status'=>false,'message'=>'No Records Found','result'=>'');
						}
					} else {
						$resultData = array('status'=>false,'message'=>'invalid id','result'=>'');
					}
				} else {
					$resultData = array('status'=>false,'message'=>'invalid input','result'=>'');
				}	
			} else {
				$resultData = array('status'=>false,'message'=>'invalid request','result'=>'');
			}
		} catch (Exception $e) {
			$resultData = array('status'=>false,'message'=>'invalid request','result'=>'');
		}
		return $resultData;
	}


	/**
	 * To add the reviwe details
	 *
	 * @return array()
	 **/
	public function addReview(Request $request)
	{
		try {
			$data = $request->all();
			if ($data) {
				$arrayData['service_provider_id'] = $data['service_provider_id'];
				$arrayData['user_id'] = $data['user_id'];
				$arrayData['rating'] = $data['rating'];
				$arrayData['reviews'] = $data['reviews'];
				if (Review::create($arrayData)) {
					$resultData = array('status'=>true,'message'=>'review added successfully','result'=>$arrayData);	
				} else {
					$resultData = array('status'=>false,'message'=>'review add failed','result'=>'');
				}
			} else {
				$resultData = array('status'=>false,'message'=>'invalid request','result'=>'');
			}
		} catch (Exception $e) {
			$resultData = array('status'=>false, 'message'=>'invalid request', 'result'=>'');
		}
		return $resultData;
	}

	/**
	 * To update the reviwe details
	 *
	 * @return array()
	 **/
	public function updateReview(Request $request)
	{
		try {
			$data = $request->all();
			if ($data) {
				$reviewDetails = Review::find($data['id']);
				if ($reviewDetails) { 
					$arrayData['service_provider_id'] = ($data['service_provider_id']) ? $data['service_provider_id'] : "";
					$arrayData['user_id'] = ($data['user_id']) ? $data['user_id'] : "";
					$arrayData['rating'] = ($data['rating']) ? $data['rating'] : "";
					$arrayData['reviews'] = ($data['reviews']) ? $data['reviews'] : "";
					$reviewDetails->fill($arrayData);					
					if ($reviewDetails->save()) {
						$resultData = array('status'=>true,'message'=>'review edited successfully','result'=>$arrayData);	
					} else {
						$resultData = array('status'=>false,'message'=>'review edit failed','result'=>'');
					}
				} else {
					$resultData = array('status'=>false,'message'=>'invalid request','result'=>'');	
				}
			} else {
				$resultData = array('status'=>false,'message'=>'invalid id','result'=>'');
			}
		} catch (Exception $e) {
			$resultData = array('status'=>false, 'message'=>'invalid request', 'result'=>'');
		}
		return $resultData;
	}


	/********************************** End Web Service - Serivce Provider *****************************************/

}
