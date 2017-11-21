<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Mail;

use App\Models\Category;
use App\Models\City;
use App\Models\Location;
use App\Models\User;
use App\Models\CmsPages;
use App\Models\Feedback;
use App\Models\ServiceProviderDetails;
use App\Helpers\KranHelper;
use App\Http\Traits\WebserviceTrait;
use App\Models\ServiceProvider;
use Illuminate\Support\Facades\Hash;
use URL;
use App\Models\Review;
use App\Models\CategoryService;
use App\Models\Service;

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
		$this->middleware('auth', ['except' => ['index','register','getCms','sendFeedback','getServiceImages','getPrerequisites','mobileVerification','spLogin','spRegister','spForgotPassword','spChangePassword','prerequisitesList','getServiceProvider', 'getreviewlist']]);
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
							/*$userProfilePath = '/uploads/user/';
							if(isset($data['profile_picture'])){
								$data['profile_picture'] = KranHelper::convertStringToImage($data['profile_picture'],$data['fullname'],$userProfilePath);
							} else {
								$data['profile_picture'] = '';
							}*/
							$registerStatus = User::create($data);
							if($registerStatus){
								$resultData = array('status'=>true,'message'=>'registered successfully','result'=>'');	
							} else {
								$resultData = array('status'=>false,'message'=>'registration failed','result'=>'');
							}
						} else {
							$resultData = array('status'=>false,'message'=>'Mobile exists already','result'=>'');
						}
					} else if($data['register_mode'] == 'Facebook'){
						$facebookExists = User::get()->where('facebook_id',$data['facebook_id'])->count();
						if($facebookExists == 0){
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
							$registerStatus = User::create($data);
							if($registerStatus){
								$resultData = array('status'=>true,'message'=>'registered successfully','result'=>'');	
							} else {
								$resultData = array('status'=>false,'message'=>'registration failed','result'=>'');
							}
						} else {
							$resultData = array('status'=>false,'message'=>'Facebook id exists already','result'=>'');
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
			$aboutusData 			= CmsPages::get()->where('slug','about-us');
			$privacyPolicyData 		= CmsPages::get()->where('slug','privacy-policy');
			$termsConditionsData	= CmsPages::get()->where('slug','terms-conditions');
			
			$data['categoryData']			= $categoryData;	
			$data['cityData']				= $cityData;
			$data['localityData']			= $localityData;
			$data['aboutusData']			= $aboutusData;
			$data['privacyPolicyData']		= $privacyPolicyData;
			$data['termsConditionsData']	= $termsConditionsData;
			$resultData = array('status'=>true,'message'=>'request success','result'=>$data);
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
						// Send OTP to be implemented here
						$resultData = array('status'=>true,'message'=>'mobile registered. OTP is sent','result'=>'');
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

					Mail::send('email.feedback', ['data' => $data], function($message)
					{
						$message->to('logu@boscosofttech.com', 'Loganathan')->subject('Feedback');
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
					if(isset($data['image'])){
						$data['image'] = KranHelper::convertStringToImage($data['image'],'serviceprovider'.$data['service_provider_id'],$serviceImagePath);
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

    				if($unameExist != 0){
						//compare the entered password with the password in the db with the given uname
    					$checkpwd = Hash::check($data['password'], $unameData->password);
    					if($checkpwd){
    						$resultData = array('status'=>true,'message'=>'service provider login available','result'=>'');
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
    					$insertData['owner_designation'] = $data['owner_designation'];
    					$insertData['opening_hrs'] = $data['opening_hrs'];
    					$insertData['closing_hrs'] = $data['closing_hrs'];
    					$insertData['working_days'] = $data['working_days'];
    					$insertData['phone'] = $data['phone'];
    					$insertData['website_link'] = $data['website_link'];
    					$insertData['latitude'] = $data['latitude'];
    					$insertData['longitude'] = $data['longitude'];
    					$insertData['email'] = $data['email'];
    					$insertData['created_at'] = date('Y-m-d H:i:s');

    					$insertData['password'] = bcrypt($data['password']);

    					$logoPath = trans('main.provider_path');
    					if(isset($data['logo'])){
            				//$insertData['logo'] = ServiceProvider::upload_file($request, 'logo');
    						$insertData['logo'] = KranHelper::convertStringToImage($data['logo'],$data['name'],$logoPath);
    					} 
    					$registerStatus = ServiceProvider::create($insertData);
    					if($registerStatus){
    						$resultData = array('status'=>true,'message'=>'registered successfully','result'=>'');	
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
			$data = $request->all();
			if($data){
				if($data['email']){	
					$emailExists = ServiceProvider::get()->where('email',$data['email'])->count();
					if($emailExists != 0){
						//$password = KranHelper::generate_random_string(8);
						//$cryptedPassword = bcrypt($password);
						//$updateQuery = ServiceProvider::where('email',$data['email'])->update(['password' => $cryptedPassword]);
						$data['content'] = 'Click here to reset your password <a href="#">Reset Password</a>';
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
			}else{
				$resultData = array('status'=>false,'message'=>'invalid request','result'=>'');
			}
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
					$arrayData[$index]['category_image'] = ($row->category_image) ? $imagePath.$row->category_image : '';
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
						$arrayData['logo'] = ($spData->logo) ? $imagePath.$spData->logo : '';
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
							$arrayData['id'] = $reviewDetails['id'];
							$arrayData['service_provider_name'] = ($reviewDetails['service_provider_id']) ? ServiceProvider::getServiceNameById($reviewDetails['service_provider_id']) : "";
							$arrayData['user'] = ($reviewDetails['user_id']) ? User::getUserNameById($reviewDetails['user_id']) : "";
							$arrayData['reviews'] = ($reviewDetails['reviews']) ? $reviewDetails['reviews'] : "";
							$arrayData['ratings'] = ($reviewDetails['ratings']) ? $reviewDetails['ratings'] : "";
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

	/********************************** End Web Service - Serivce Provider *****************************************/

}
