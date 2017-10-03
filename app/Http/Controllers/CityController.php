<?php
/*
------------------------------------------------------------------------------------------------
Project         : KRQ 1.0.0
Created By      : Honest Raj A
Created Date    : 20.07.2017
Purpose         : To handle city details
------------------------------------------------------------------------------------------------
*/
namespace App\Http\Controllers;

use URL;
use Session;
use Redirect;
use Response;
use App\User;
use App\Models\City;
use App\Models\Location;
use Rafwell\Simplegrid\Grid;
//use Illuminate\Http\Request;
use App\Http\Requests\CityRequest;
use Illuminate\Support\ServiceProvider;


class CityController extends Controller
{
    protected $error = 'error';
    protected $warning = 'warning';
    protected $success = 'success';
    protected $route = 'main.city.index';
    protected $title = 'main.city.title';
    protected $notfound = 'main.city.notfound';
    protected $createmsg = 'main.city.createsuccess';
    protected $updatemsg = 'main.city.updatesuccess';
    protected $deletemsg = 'main.city.deletesuccess';
    protected $referencemsg = 'main.referencesuccess';

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // To get the records details from the table
        $Grid = new Grid(City::query()->orderBy('id', 'DESC'), 'Cities');

        // To have header for the values
        $Grid->fields([
                //'id' => 'ID',
                'city_code'=>'City Code',
                'city_name'=>'City Name',
                'status'=>'Status',

        ])
        ->actionFields([
            'id' //The fields used for process actions. those not are showed 
        ]);
        // To have actions for the records
            $Grid->action('View', URL::to('city/show/{id}'), ['class'=>'fa fa-eye'])
                ->action('Edit', URL::to('city/edit/{id}'), ['class'=>'fa fa-edit'])
                ->action('Delete', URL::to('city/destroy/{id}'), [
                'confirm'=>'Are you sure to delete the record?',
                'method'=>'DELETE',
				'class'=>'fa fa-trash-o',
            ]);
            // Pass the values to the view page
        return view('city/index', ['grid'=>$Grid]);
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
        return view('city.form', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CityRequest $request)
    {
        $input = $request->all();
        City::create($input);
        return Redirect::route($this->route)->with($this->success, trans($this->createmsg));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\City  $city
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $data['city'] = City::findorFail($id);
        return view('city.view', $data);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\City  $city
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $data['city'] = City::findorFail($id);
        $data['add'] = trans('main.edit');
        return view('city.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\City  $city
     * @return \Illuminate\Http\Response
     */
    public function update(CityRequest $request, $id)
    {
        $input = $request->all();
        $city  = City::findorFail($id);
        $city->fill($input);
        $city->save();
        return Redirect::route($this->route)->with($this->success, trans($this->updatemsg));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\City  $city
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $locality = Location::where('city_id', '=', $id)->get();
        if (count($locality) > 0) {
            return Redirect::route($this->route)->with($this->warning, trans($this->referencemsg));
        } else {
            $city = City::findorFail($id);
            $city->delete();
            return Redirect::route($this->route)->with($this->success, trans($this->deletemsg));   
        }
    }
}
