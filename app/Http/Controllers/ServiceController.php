<?php

/*
------------------------------------------------------------------------------------------------
Project			: KRQ 1.0.0
Created By    	: Loganathan N
Created Date  	: 09.08.2017
Purpose       	: To handle service details
------------------------------------------------------------------------------------------------
*/

namespace App\Http\Controllers;

use URL;
use Session;
use Redirect;
use Response;
use App\User;
use App\Models\Service;
use App\Models\Location;
use Rafwell\Simplegrid\Grid;
use App\Models\CategoryService;
use Illuminate\Support\ServiceProvider;
use App\Http\Requests\ServiceRequest;
use App\Models\ServiceProviderCategoryService;

class ServiceController extends Controller
{

    protected $error = 'error';
    protected $warning = 'warning';
    protected $success = 'success';
    protected $route = 'main.service.index';
    protected $title = 'main.service.title';
    protected $notfound = 'main.service.notfound';
    protected $createmsg = 'main.service.createsuccess';
    protected $updatemsg = 'main.service.updatesuccess';
    protected $deletemsg = 'main.service.deletesuccess';
    protected $referencemsg = 'main.referencesuccess';

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // To get the records details from the table
        $Grid = new Grid(Service::query()->orderBy('id', 'DESC'), 'Services');

        // To have header for the values
        $Grid->fields([
                'service_name'=>'Service Name',
                'status'=>'Status',
        ])
        ->actionFields([
            'id' //The fields used for process actions. those not are showed
        ]);
        // To have actions for the records
           // $Grid->action('View', URL::to('service/show/{id}'))
              $Grid->action('Edit', URL::to('service/edit/{id}'), ['class'=>'fa fa-edit'])
                ->action('Delete', URL::to('service/destroy/{id}'), [
                'confirm'=>'Are you sure to delete the record?',
                'method'=>'DELETE',
				'class'=>'fa fa-trash-o',
            ]);
        // Pass the values to the view page
        return view('service/index', ['grid'=>$Grid]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data = array();
        $data['add'] = trans('main.add');
        return view('service.form', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ServiceRequest $request)
    {
        $input = $request->all();
        Service::create($input);
        return Redirect::route($this->route)->with($this->success, trans($this->createmsg));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Service  $service
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $data['service'] = Service::findorFail($id);
        return view('service.view', $data);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Service  $service
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
         $data['service'] = Service::findorFail($id);
        $data['add'] = trans('main.edit');
        return view('service.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Service  $service
     * @return \Illuminate\Http\Response
     */
    public function update(ServiceRequest $request, $id)
    {
         $input = $request->all();
        $service  = Service::findorFail($id);
        $service->fill($input);
        $service->save();
        return Redirect::route($this->route)->with($this->success, trans($this->updatemsg));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Service  $service
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
		$service = Service::findorfail($id);
		$serviceExists = 0;
		$servicePoviderExists = 0;
		// To check the Category services
		$categoryService = CategoryService::select('service_id')->orderBy('id', 'desc')->get();
		for ($i=0;$i<count($categoryService);$i++) {
				$serviceNo = explode(',',$categoryService[$i]->service_id);
				foreach($serviceNo as $services) {
					if ($services == $id) {
						$serviceExists = 1;
					}
				}
		}
		// To check the Services Provider Category Service
		$serviceProviderCategoryService = ServiceProviderCategoryService::select('service_id')->orderBy('id', 'desc')->get();
		for ($i=0;$i<count($serviceProviderCategoryService);$i++) {
				$serviceProviderCategoryNumbers = explode(',',$serviceProviderCategoryService[$i]->service_id);
				foreach($serviceProviderCategoryNumbers as $serviceProviders) {
					if ($serviceProviders == $id) {
						$servicePoviderExists = 1;
					}
				}
		}
		if (isset($serviceExists) || isset($servicePoviderExists)) {
		   if ($serviceExists == 1 || $servicePoviderExists == 1) {
			   return Redirect::route($this->route)->with($this->warning, trans($this->referencemsg));
		   } else {
			   $service->delete();
			   return Redirect::route($this->route)->with($this->success, trans($this->deletemsg));
		   }
		}
    }
}
