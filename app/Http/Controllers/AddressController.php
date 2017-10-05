<?php
/*
------------------------------------------------------------------------------------------------
Project       : KRQ
Created By    : Vijay Felix Raj C
Created Date  : 28.08.2017
Purpose       : To handle Address Details
------------------------------------------------------------------------------------------------
*/
namespace App\Http\Controllers;

use URL;
use Redirect;
use App\Models\Address;
use Rafwell\Simplegrid\Grid;
use Illuminate\Http\Request;
use App\Http\Requests\AddressRequest;

class AddressController extends Controller
{
    protected $error = 'error';
    protected $success = 'success';
    protected $route = 'main.address.index';
    protected $title = 'main.address.title';
    protected $notfound = 'main.address.notfound';
    protected $createmsg = 'main.address.createsuccess';
    protected $updatemsg = 'main.address.updatesuccess';
    protected $deletemsg = 'main.address.deletesuccess';

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // To get the records details from the table
        $Grid = new Grid(Address::query()->orderBy('id', 'DESC'), 'contact_details');

        // To have header for the values
        $Grid->fields([
                //'id' => 'ID',
                'address'=>'Address',
                'phone_no'=>'Phone',
                'email'=>'Email',
                /*'description'=>'Description',*/

        ])
        ->actionFields([
            'id' //The fields used for process actions. those not are showed
        ]);
        // To have actions for the records
            $Grid->action('View', URL::to('address/show/{id}'), ['class'=>'fa fa-eye'])
                 ->action('Edit', URL::to('address/edit/{id}'), ['class'=>'fa fa-edit']);
                /*->action('Delete', URL::to('city/destroy/{id}'), [
                'confirm'=>'Do you with so continue?',
                'method'=>'DELETE',
            ]);*/

        // Pass the values to the view page
        return view('address/index', ['grid'=>$Grid]);
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
        return view('address.form', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(AddressRequest $request)
    {
        $input = $request->all();
        Address::create($input);
        return Redirect::route($this->route)->with($this->success, trans($this->createmsg));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Address  $address
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $data['address'] = Address::findorFail($id);
        return view('address.view', $data);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Address  $address
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $data['address'] = Address::findorFail($id);
        $data['add'] = trans('main.edit');
        return view('address.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Address  $address
     * @return \Illuminate\Http\Response
     */
    public function update(AddressRequest $request, $id)
    {
        $input = $request->all();
        $address  = Address::findorFail($id);
        $address->fill($input);
        $address->save();
        return Redirect::route($this->route)->with($this->success, trans($this->updatemsg));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Address  $address
     * @return \Illuminate\Http\Response
     */
    public function destroy(Address $address)
    {
        //
    }
}
