@extends('layouts.site')
@section('title',trans('main.user.title'))
@section('header')
<h3><i class="icon-message"></i>{!!trans('main.user.title') !!}</h3>
@stop
@section('help')
<p class="lead">{!!trans('main.user.title') !!}</p>
<p>{!!trans('main.area.help') !!}</p>
@stop
@section('content')
  <section id="content">
    <div class="main">
      <div class="zerogrid">
        <div class="row">
          <article class="col-2-1"><div class="wrap-col">
            <h2>Get KranQed!!</h2>
            <p class="p2"><strong>KranQ</strong> is an one stop search and discovery destination for car related service providers. At <strong>KranQ</strong> we strive to bring to you all the service providers in your  city on a unique platform. </p>
            <p class="p3">If you are someone who loves pampering your car, find the best services near you through our app.</p>
            <h3 class="p1">Here is how you can get KranQed:</h3>
            <div class="row">
              <div class="col-1"><div style="padding-right: 10px;">
                <ul class="list-1">
                  <li>Search  and discover your required service from our extensive service providers.</li>
                                  <li>Book  an appointment with your preferred service provider through us.</li>
                                  <li>Skip  the queue and get priority for all services over walk-in customers .</li>
                  <li>Rate  and review your experinces with our service providers.</li>
                  <li>Get  in touch with our expert team if you need assistance with any car related  service.</li>
                </ul>
              </div></div>
            </div>
          </div></article>
          <article class="col-1-3"></article>
        </div>
      </div>
    </div>
  </section>
@endsection
