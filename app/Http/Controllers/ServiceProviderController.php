<?php

namespace App\Http\Controllers;

use App\Models\ServiceProvider;
use App\Http\Requests\ServiceProviderRequest;
use DB;
use URL;
use Image;
use Storage;
use Session;
use Response;
use Redirect;
use Carbon\Carbon;
use App\Models\User;
use App\Models\City;
use App\Models\Review;
use App\Models\Service;
use App\Models\Bookmark;
use App\Models\Category;
use App\Models\Location;
use App\Helpers\KranHelper;
use Rafwell\Simplegrid\Grid;
use App\Models\CategoryService;
use App\Http\Requests\UserRequest;
use App\Models\ServiceProviderDetails;
use App\Models\ServiceProviderCategoryService;

class ServiceProviderController extends Controller
{
  protected $error = 'error';
  protected $success = 'success';
  protected $route = 'main.provider.index';
  protected $title = 'main.provider.title';
  protected $notfound = 'main.provider.notfound';
  protected $createmsg = 'main.provider.createsuccess';
  protected $updatemsg = 'main.provider.updatesuccess';
  protected $deletemsg = 'main.provider.deletesuccess';

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // To get the records details from the table
        $providers = ServiceProvider::join('categories','category_id','=','categories.id')->join('localities','location_id','=','localities.id')->join('cities','city','=','cities.id')->orderBy('id', 'DESC');
        $Grid = new Grid($providers,'');
        // To have header for the values
            $Grid->fields([
                  'name_sp'=>'Service Provider',
                  'category_name'=>'Category Name',
                 'locality_name'=>'Locality',
                 'city_name'=>'City',
                 'service_providers.created_at' => 'Submitted On',
                 'service_providers.status'=>'Status'
            ])
            ->processLine(function($row){
               //This function will be called for each row
               $row['status'] = KranHelper::getProviderStatus($row);
               $row['created_at'] = KranHelper::dateTime($row['created_at']);
               return $row;
            });
            $Grid->actionFields([
                 'service_providers.id' //The fields used for process actions. those not are showed
               ]);
            // To have actions for the records
               $Grid->action('View', URL::to('provider/show/{id}'), ['class'=>'fa fa-eye'])
                   ->action('Edit', URL::to('provider/edit/{id}'), ['class'=>'fa fa-edit'])
                   ->action('Delete', URL::to('provider/destroy/{id}'), [
                 'confirm'=>'Are you sure to delete the record?',
                 'method'=>'DELETE',
				 'class'=>'fa fa-trash-o',
             ]);

             // Pass the values to the view page
             return view('service_provider.index', ['grid'=>$Grid]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
      $data = array();
      $data['categories'] = Category::orderBy('category_name','asc')->pluck('category_name', 'id');
      $data['cities'] = City::orderBy('city_name','asc')->pluck('city_name', 'id');
      $data['localities'] = Location::orderBy('locality_name','asc')->pluck('locality_name', 'id');
      //$data['services'] = Service::orderBy('service_name', 'asc')->pluck('service_name', 'id')->all();
      $data['services'] = [];
      $data['all_status'] = KranHelper::getProviderStatusDropdown();
      $data['opening_hrs'] = KranHelper::getTimeDropDown();
      $data['closing_hrs'] = KranHelper::getTimeDropDown();
      $data['working_days'] = KranHelper::getWeekDays();
      $data['selected_working_days'] = KranHelper::getWeekDays();
      return view('service_provider.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ServiceProviderRequest $request)
    {
        $input = $request->all();
        $input = $request->except('_token');
		// To create a directory if not exists
		if (!(Storage::disk('s3')->exists('/uploads/provider')))
		{
			Storage::disk('s3')->makeDirectory('/uploads/provider/');
		}
		// To upload the images into Amazon S3
        $amazonImgUpload = Storage::disk('s3')->put('/uploads/provider/'.$request->file('logo')->getClientOriginalName(), file_get_contents($request->file('logo')), 'public');
        if($request->hasFile('logo')){
            $input['logo'] = ServiceProvider::upload_file($request, 'logo');
        }
        /*if(!empty($input['working_days'])){
            $input['working_days'] = implode(',',$input['working_days']);
        }*/
        $input['slug'] = KranHelper::convertString($input['name_sp']);
        $input['password'] = bcrypt($input['password']);
        //echo '<pre>';print_r($input);exit;
        $last = ServiceProvider::create($input);
        // To get the Last Insert id and insert the value in the Service Provider Table by email
        $lastRecord = ServiceProvider::where('email','=' ,$last->email)->get();
        $serviceProviderInput['service_provider_id'] = $lastRecord[0]->id;
        $serviceProviderInput['category_id'] = $input['category_id'];
        $serviceProviderInput['service_id'] = implode(',', $input['service_id']);
        ServiceProviderCategoryService::create($serviceProviderInput);

      return Redirect::route($this->route)->with($this->success, trans($this->createmsg));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $data['provider'] = ServiceProvider::findorfail($id);
		    // To get the image form the Amazon s3 account
        if (Storage::disk('s3')->exists('uploads/provider/'.$data['provider']->logo)) {
            $data['s3image']= \Storage::disk('s3')->url('uploads/provider/'.$data['provider']->logo);
        }
        $created_at = $data['provider']->created_at;
        $data['provider']->created_date = Carbon::parse($created_at)->format('d/m/Y, h.i a');
        $data['provider']->category_id = Category::getCategoryNameById($data['provider']->category_id);
        $data['provider']->city = City::getCityNameById($data['provider']->city);
        $data['provider']->location_id = Location::getLocationNameById($data['provider']->location_id);
        //$data['ratings'] = Rating::where('service_provider_id', '=', $id)->get();
        $data['users'] = User::orderBy('fullname', 'asc')->pluck('fullname', 'id');
        $data['reviews'] = Review::getServiceProviderReviewDetails($id);
        $data['service_providers'] = ServiceProviderDetails::where('service_provider_id', '=', $id)->get();
        return view('service_provider.view', $data);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $data['provider'] = ServiceProvider::findorFail($id);
		if ($data['provider']->logo) {
            $data['amazonImgUpload'] = \Storage::disk('s3')->url('uploads/provider/'.$data['provider']->logo);
        }
        $data['categories'] = Category::orderBy('category_name','asc')->pluck('category_name', 'id');
        $data['cities'] = City::orderBy('city_name','asc')->pluck('city_name', 'id');
        $data['localities'] = Location::orderBy('locality_name','asc')->pluck('locality_name', 'id');
        $data['all_status'] = KranHelper::getProviderStatusDropdown();
        $data['opening_hrs'] = KranHelper::getTimeDropDown();
        $data['closing_hrs'] = KranHelper::getTimeDropDown();
        $data['working_days'] = KranHelper::getWeekDays();
        $data['selected_working_days'] = $data['provider']->working_days;
        $data['selected_working_saturdays'] = ($data['provider']->working_saturdays) ? 1 : 0;
        $data['selected_working_sundays'] = ($data['provider']->working_sundays) ? 1 : 0;
        //echo '<pre>';print_r($data['provider']->working_sundays);exit;
        /*if($data['selected_working_days']){
            $data['selected_working_days'] = explode(',',$data['selected_working_days']);
            //echo '<pre>';print_r($data['selected_working_days']);exit;
        }*/
        $services = ServiceProviderCategoryService::where('service_provider_id','=' ,$id)->get();
        $service[] = '';
        if (count($services) > 0) {
            $service = explode(',',$services[0]->service_id);
        }
        $data['services'] = Service::orderBy('service_name', 'asc')->pluck('service_name', 'id')->all();
        $data['service'] = Service::whereIn('id',$service)->pluck('id');
        //echo '<pre>';print_r($data['selected_working_days']);exit;
        return view('service_provider.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(ServiceProviderRequest $request, $id)
    {
        $input = $request->all();
        $provider  = ServiceProvider::findorFail($id);
		if ($request->file('logo')) {
			if (Storage::disk('s3')->exists('uploads/provider/'.$provider->logo)) {
				Storage::disk('s3')->delete('uploads/provider/'.$provider->logo);
			}
	        $amazonImgUpload = Storage::disk('s3')->put('uploads/provider/'.$request->file('logo')->getClientOriginalName(), file_get_contents($request->file('logo')), 'public');
		}
        if($request->hasFile('logo')){
            $input['logo'] = ServiceProvider::upload_file($request, 'logo');
        }
        /*if(!empty($input['working_days'])){
            $input['working_days'] = implode(',',$input['working_days']);
        }*/
        $input['slug'] = KranHelper::convertString($input['name_sp']);
        if($input['password']){
            $input['password'] = bcrypt($input['password']);
        }else{
            $input['password'] = $provider->password;
        }
        // To get the Last Insert id and insert the value in the Category Service Table by Category Name
        $serviceProviderInputs = implode(',', $input['service_id']);
        if (!empty($input['service_id'])) {
            $serviceProviderStatus = ServiceProviderCategoryService::where('service_provider_id','=' ,$id)->get();
            if (count($serviceProviderStatus) > 0) {
                $result =  DB::statement('UPDATE service_provider_category_services set service_id="'.$serviceProviderInputs.'" where category_id='.$input['category_id'].' AND service_provider_id='.$id);
            } else {
                $serviceProviderInput['service_provider_id'] = $id;
                $serviceProviderInput['category_id'] = $input['category_id'];
                $serviceProviderInput['service_id'] = $serviceProviderInputs;
                ServiceProviderCategoryService::create($serviceProviderInput);
            }
        }
        $provider->fill($input);
        $provider->save();
        return Redirect::route($this->route)->with($this->success, trans($this->updatemsg));

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $postData = ServiceProvider::findorfail($id);
		// To delete the image from the Amazon S3 account
		if (!empty($postData->logo)) {
			if (Storage::disk('s3')->exists('uploads/provider/'.$postData->logo)) {
				Storage::disk('s3')->delete('uploads/provider/'.$postData->logo);
			}
		}
        $postData->delete();
        return Redirect::route($this->route)->with($this->error, trans($this->deletemsg));
    }


    /**
     * To get the Category based Services
     * @return \Illumniate\Http\Response
     */
    public function cagetoryservices()
    {
        if ($_POST['id']) {
            $id = $_POST['id'];
            $categoryServices = CategoryService::getCategoryService($id);
            if ($categoryServices) {
                $service = explode(',',$categoryServices);
                $result = '';
                foreach ($service as $value) {
                    $services = Service::where('id','=',$value)->get()->all();
                    $result .= '<option value="'.$services[0]->id.'"';
                    $result .= '>'. $services[0]->service_name .'<option>';
                }
                echo $result;
            } else {
                echo '';
            }
        }
    }
}
