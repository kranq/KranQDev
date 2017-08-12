<?php
/*
------------------------------------------------------------------------------------------------
Created By    : Vijay Felix Raj C
Email Address : vijayfelixraj@gmail.com
Created Date  : 19.07.2017
Purpose       : Add Employees
------------------------------------------------------------------------------------------------
*/
namespace App\Http\Controllers;

use URL;
use Image;
use Session;
use Response;
use Redirect;
use App\Models\User;
use App\Models\Rating;
use App\Models\Review;
use App\Models\Bookmark;
use App\Models\ServiceProvider;
use App\Models\DropdownHelper;
use App\Helpers\KranHelper;
//use Illuminate\Http\Request;
use App\Http\Requests\UserRequest;
use Rafwell\Simplegrid\Grid;

class UserController extends Controller
{
    protected $error = 'error';
    protected $success = 'success';
    protected $route = 'main.user.index';
    protected $title = 'main.user.title';
    protected $notfound = 'main.user.notfound';
    protected $createmsg = 'main.user.createsuccess';
    protected $updatemsg = 'main.user.updatesuccess';
    protected $deletemsg = 'main.user.deletesuccess';
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //$user = User::query()->orderBy('id', 'desc');
        // To get the records details from the table
        $Grid = new Grid(User::query(), 'users');

        // To have header for the values
        $Grid->fields([
              //'id' => 'ID',
              'fullname'=>'Full Name',
              'email'=>'Email',
              'status' => 'Status',
              'registered_on' => 'Registered On'
        ])
        ->actionFields([
           'id' //The fields used for process actions. those not are showed 
        ])
        // To convert the date format
        ->processLine(function($row){
            $row['registered_on'] = KranHelper::dateTimeFormat($row);
            return $row;
        });
        
        // To have actions for the records
        $Grid->action('View', URL::to('user/show/{id}'), ['class'=>'fa fa-eye'])
            ->action('Edit', URL::to('user/edit/{id}'), ['class'=>'fa fa-edit'])
            ->action('Delete', URL::to('user/destroy/{id}'), [
          'confirm'=>'Do you with so continue?',
          'method'=>'DELETE',
		  'class'=>'fa fa-trash-o',
        ]);
        // Pass the values to the view page
        return view('user.index', ['grid'=>$Grid]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data = array();
        $data['status'] = DropdownHelper::where('group_code', '001')->orderBy('key_code', 'asc')->pluck('value', 'key_code');
        $data['registered_mode'] = DropdownHelper::where('group_code', '002')->orderBy('key_code', 'asc')->pluck('value', 'key_code');
        $data['add'] = trans('main.add');
        return view('user.form', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(UserRequest $request)
    {
        $input = $request->all();
        $input = $request->except('_token');
        $input['password'] = bcrypt($input['password']);
        if($request->hasFile('profile_picture')){
           $input['profile_picture'] = User::fileUpload($request, 'profile_picture');
        }
        User::create($input);
        return Redirect::route($this->route)->with($this->success, trans($this->createmsg));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Users  $users
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $data['user'] = User::findorfail($id);
        $data['user']->registered_on = KranHelper::dateTime($data['user']->registered_on);
        $data['ratings'] = Rating::getDetails($id);
        $data['reviews'] = Review::getReviewDetails($id);
        $data['bookmarks'] = Bookmark::getBookMarkDetails($id);
        $data['registered_mode'] = DropdownHelper::where('key_code', '=', $data['user']->register_mode)->where('group_code', '002')->get();
        $data['sponser_list'] = ServiceProvider::orderBy('name_sp','asc')->pluck('name_sp')->all();
        return view('user.view', $data);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Users  $users
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $data['user'] = User::findorfail($id);
        $data['status'] = DropdownHelper::where('group_code', '001')->orderBy('key_code', 'asc')->pluck('value', 'key_code');
        $data['registered_mode'] = DropdownHelper::where('group_code', '002')->orderBy('key_code', 'asc')->pluck('value', 'key_code');
        $data['add'] = trans('main.edit');
        return view('user.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Users  $users
     * @return \Illuminate\Http\Response
     */
    public function update(UserRequest $request, $id)
    {
        $userData = User::findorfail($id);
        $input = $request->all();
        if (empty($input['password'])) {
            $input['password'] = $userData->password;   
        } else {
            $input['password'] = bcrypt($input['password']);
        }
        $userData->fill($input);
        $userData->save();
        return Redirect::route($this->route)->with($this->success, trans($this->updatemsg));   
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Users  $users
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        
    }
    
}
