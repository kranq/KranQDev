<?php
/*
------------------------------------------------------------------------------------------------
Project		    : KRQ 1.0.0
Created By    	: Loganathan N
Created Date  	: 20.07.2017
Purpose       	: To handle location details
------------------------------------------------------------------------------------------------
*/

namespace App\Http\Controllers;

use URL;
use Image;
use Session;
use Response;
use Redirect;
use App\Models\City;
use App\Models\Location;
use App\Models\ServiceProvider;
use Rafwell\Simplegrid\Grid;
use App\Http\Requests\LocationRequest;
//use Illuminate\Support\ServiceProvider;

class LocationController extends Controller
{

    protected $error = 'error';
    protected $warning = 'warning';
    protected $success = 'success';
    protected $route = 'main.location.index';
    protected $title = 'main.location.title';
    protected $notfound = 'main.location.notfound';
    protected $createmsg = 'main.location.createsuccess';
    protected $updatemsg = 'main.location.updatesuccess';
    protected $deletemsg = 'main.location.deletesuccess';
    protected $referencemsg = 'main.referencesuccess';

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // To get the records details from the table with join query
        $localities = Location::join('cities','city_id','=','cities.id')->orderBy('id', 'DESC');
        $Grid = new Grid($localities, 'localities');
        // To have header for the values
        $Grid->fields([
                //'localities.id' => 'ID',
                'city_name'=>'City',
                'locality_name'=>'Locality Name',
                'localities.status'=>'Status'
        ]);
        $Grid->actionFields([
            'localities.id' //The fields used for process actions. those not are showed
        ]);
        // To have actions for the records
        $Grid->action('View', URL::to('location/show/{id}'), ['class'=>'fa fa-eye'])
                    ->action('Edit', URL::to('location/edit/{id}'), ['class'=>'fa fa-edit'])
                    ->action('Delete', URL::to('location/destroy/{id}'), [
                  'confirm'=>'Are you sure to delete the record?',
                  'method'=>'DELETE',
				  'class'=>'fa fa-trash-o',
        ]);

        // Pass the values to the view page
        return view('location.index', ['grid'=>$Grid]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data = array();
        $data['cities'] = City::orderBy('city_name','asc')->pluck('city_name', 'id');
        $data['add'] = trans('main.add');
        return view('location.form', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(LocationRequest $request)
    {
        $input = $request->all();
        $input = $request->except('_token');
        Location::create($input);
        return Redirect::route($this->route)->with($this->success, trans($this->createmsg));
    }

  /**
   * Display the specified resource.
   *
   * @param type $id
   * @return type
   */
    public function show($id)
    {
        $data['location'] = Location::findorFail($id);
        $cityId = $data['location']->city_id;
        $data['cities'] = City::where('id', '=', $cityId)->get();
        return view('location.view', $data);
    }

    /**
     * Show the form for editing the specified resource.
     * @param type $id
     * @return type
     */
    public function edit($id)
    {
        $data['location'] = Location::findorFail($id);
        $data['cities'] = City::orderBy('city_name','asc')->pluck('city_name', 'id');
        $data['add'] = trans('main.edit');
        return view('location.edit', $data);
    }


    /**
     * Update the specified resource in storage.
     * @param \App\Http\Controllers\Request $request
     * @param Location $location
     * @return type
     */
    public function update(LocationRequest $request, $id)
    {
        $input = $request->all();
        $location  = Location::findorFail($id);
        $location->fill($input);
        $location->save();
        return Redirect::route($this->route)->with($this->success, trans($this->updatemsg));
    }


    /**
     * Remove the specified resource from storage.
     * @param type $id
     * @return type
     */
    public function destroy($id)
    {
        $location = Location::findorFail($id);
		$serviceProvider = ServiceProvider::where('location_id','=',$id)->get();
        if (count($serviceProvider) > 0) {
            return Redirect::route($this->route)->with($this->warning, trans($this->referencemsg));
        } else {
            $location->forceDelete();
            return Redirect::route($this->route)->with($this->success, trans($this->deletemsg));
        }

    }
}
