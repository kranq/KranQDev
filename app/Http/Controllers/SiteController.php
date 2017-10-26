<?php
/*
------------------------------------------------------------------------------------------------
Project			    : KRQ 1.0.0
Created By    	: Vijay Felix Raj C
Created Date  	: 25.10.2017
Purpose       	: To handle Site Frontend details
------------------------------------------------------------------------------------------------
*/
namespace App\Http\Controllers;

use URL;
use App\Models\Site;
use App\Models\Category;
use Illuminate\Http\Request;

class SiteController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    { 
        $array = [];
        return view('site.index',$array);
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
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Site  $site
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request)
    { 
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Site  $site
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $site)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Site  $site
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Request $site)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Site  $site
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $site)
    {
        //
    }

    /**
     *
     */
    public function getServices()
    {
      $data['categories'] = Category::OrderBy('id','asc')->get();
      return view('site.services', $data);
    }

    /**
     * To return the contact form
     *
     * @return page.
     */
     public function getContact()
     {
       return view('site.contact');
     }

     /**
      * To store the Contact details
      *
      * @return true
      */
      public function contactStore(Request $request)
      {
        echo '<pre>';print_r($request->all());exit;
      }
}
