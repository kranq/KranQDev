<?php
/*
------------------------------------------------------------------------------------------------
Project             : KRQ 1.0.0
Created By      : Honest Raj A
Created Date    : 25.07.2017
Purpose         : To handle Review details
------------------------------------------------------------------------------------------------
*/
namespace App\Http\Controllers;

use URL;
use Image;
use Session;
use Response;
use Redirect;
use App\Models\Review;
use App\Models\User;
use App\Helpers\KranHelper;
use Rafwell\Simplegrid\Grid;
//use Illuminate\Http\Request;
use App\Http\Requests\Request;
//use Illuminate\Support\ServiceProvider;
use App\Models\ServiceProvider;
class ReviewController extends Controller
{
    protected $error = 'error';
    protected $success = 'success';
    protected $route = 'main.review.index';
    protected $title = 'main.review.title';
    protected $notfound = 'main.review.notfound';
    protected $createmsg = 'main.review.createsuccess';
    protected $updatemsg = 'main.review.updatesuccess';
    protected $deletemsg = 'main.review.deletesuccess';
    protected $activemsg = 'main.review.activesuccess';
    protected $inactivemsg = 'main.review.inactivesuccess';
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // To get the records details from the table
        //$serviceProvider=Review::join('service_providers','service_provider_id','=','service_providers.id')->orderBy('id', 'desc');
        $serviceProvider=Review::join('service_providers','service_provider_id','=','service_providers.id');
        $Grid = new Grid($serviceProvider, 'reviews');
        // To have header for the values
      $Grid->fields([
              'reviews'=>'Reviews',
              'name_sp'=>'Service Provider',
              'reviews.status'=>'Status'
          ])

        ->processLine(function($row){
            //This function will be called for each row
            $row['reviews'] = KranHelper::reviewStringLimit($row);
            //Do more you need on this row
            return $row; 
            //Do not forget to return the row
        });
        $Grid->actionFields([
            'reviews.id' //The fields used for process actions. those not are showed
        ]);
        // To have actions for the records
        $Grid->action('View', URL::to('review/show/{id}'))
            ->action('Status', URL::to('review/edit/{id}'))
            ->action('Delete', URL::to('review/destroy/{id}'), [
          'confirm'=>'Do you with so continue?',
          'method'=>'DELETE',
        ]);
        // Pass the values to the view page
        return view('review.index', ['grid'=>$Grid]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data = array();
      return view('review.form', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Review  $review
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $data['review'] = Review::findorFail($id);
        $data['review']->postted_on = KranHelper::dateTime($data['review']->postted_on);
        $serviceProviderId = $data['review']->service_provider_id;
        $user = $data['review']->user_id;        
        $data['service_providers'] = ServiceProvider::where('id', '=', $serviceProviderId)->get();      
        $userData['user'] = User::where('id', '=', $user)->get();       
        return view('review.view', $data,$userData);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Review  $review
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $data = Review::findorfail($id);
        if($data->status == 'Active'){
            $input['status'] = 'Inactive';
            $data->fill($input);
            $data->save();
            return Redirect::back()->with($this->success, trans($this->inactivemsg));
        }
        else{
            $input['status'] = 'Active';
            $data->fill($input);
            $data->save();
            return Redirect::back()->with($this->success, trans($this->activemsg));
        }
       
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Review  $review
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Review $review)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Review  $review
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $review = Review::findorFail($id);
      $review->delete();
      return Redirect::route($this->route)->with($this->success, trans($this->deletemsg));
    }


}