<?php

/*
  ------------------------------------------------------------------------------------------------
  Project		: KRQ 1.0.0
  Created By    	: Vijay Felix Raj C
  Created Date  	: 25.10.2017
  Purpose       	: To handle Site Frontend details
  ------------------------------------------------------------------------------------------------
 */

namespace App\Http\Controllers;

use URL;
use Mail;
use Redirect;
use App\Models\Site;
use App\Models\Category;
use Illuminate\Http\Request;

class SiteController extends Controller {

    protected $success = 'success';
    protected $createmsg = 'Mail sent successfully';
    protected $errormsg = 'Mail could not be sent';

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        $array = [];
        return view('site.index', $array);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) {
        
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Site  $site
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request) {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Site  $site
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $site) {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Site  $site
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Request $site) {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Site  $site
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $site) {
        //
    }

    /**
     *
     */
    public function getServices() {
        $data['categories'] = Category::OrderBy('id', 'asc')->get();
        return view('site.services', $data);
    }

    /**
     * To return the contact form
     *
     * @return page.
     */
    public function getContact() {
        return view('site.contact');
    }

    /**
     * To store the Contact details
     *
     * @return true
     */
    public function contactStore(Request $request) {
        //echo '<pre>';print_r($request->all());exit;
    }

    /**
     * To  send Contact mail
     *
     * @return true
     */
    public function contactMail(Request $request) {

        $data = $request->all();
        if ($data) {
            if ($data['name'] && $data['email'] && $data['subject'] && ['message']) {
                $name = $data['name'];
                $email = $data['email'];
                $subject = $data['subject'];
                $message = $data['message'];
                Mail::send('email.contact', ['data' => $data], function($message) {
                    // $message->to('vijayfelixraj@gmail.com', 'Kranq')->subject('Contact Details');
                    $message->to('vijayfelixraj@gmail.com', 'Kranq');
                });
                return Redirect::back()->with($this->success, trans($this->createmsg));
            } else {
                die("else2");
                return Redirect::back()->with($this->success, trans($this->errormsg));
            }
        } else {
            die("else3");
            return Redirect::route($this->route)->with($this->success, trans($this->errormsg));
        }
    }

}
