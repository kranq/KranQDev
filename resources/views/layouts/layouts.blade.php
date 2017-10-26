<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="KranQ">
    <link rel="shortcut icon" href="{!! URL::to('/') !!}/images/img.png">
    <title>{!! trans('main.site_name') !!} - @yield('title')</title>
    {{ Html::style('css/app.css') }}
    {{-- Html::style('css/bootstrap.min.css') --}}
    {!! Html::style('css/bootstrap-reset.css') !!}
    {!! Html::style('font-awesome/css/font-awesome.min.css') !!}
    {!! Html::style('css/bootstrap-fileupload.css') !!}
    {!! Html::style('css/style.css') !!}
    {!! Html::style('css/style-responsive.css') !!}

    <!-- Multi select css -->
    {!! Html::style('js/select2/select2.css') !!}
    <!-- Multi select css End's here-->

    <!-- ONLY INCLUDE IF YOU NOT HAVE THOSE DEPENDENCIES -->
    <!-- <link rel="stylesheet" href="vendor/rafwell/simple-grid/eonasdan-bootstrap-datetimepicker/build/css/bootstrap-datetimepicker.min.css" /> -->
    <!-- CSS LARAVEL SIMPLEGRID -->
    <!--<link rel="stylesheet" href="http://localhost:801/svnprojects/KRQ/Source/1.0.0/public/vendor/rafwell/simple-grid/css/simplegrid.css">-->
	{!! Html::style('vendor/rafwell/simple-grid/css/simplegrid.css') !!}
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Raleway:100,600" rel="stylesheet" type="text/css">
    <!-- Css Ends here-->
    @yield('page_style')
</head>
<body id="app-layout" class="col-md-12 col-xs-12 col-lg-12 col-sm-12">
<section id="container">
<!--header start-->
<header class="header fixed-top clearfix">
<!--logo start-->
<div class="brand">
    <a href="{{ url('/category') }}" class="logo">
      <img src="{!! URL::to('/') !!}/images/logocrop.png" style="width: 170px; margin-top: -15px;" alt="KranQ" title="KranQ">
    </a>
    <div class="sidebar-toggle-box">
        <div class="fa fa-bars"></div>
    </div>
</div>
<!--logo end-->
<div class="top-nav clearfix">
    <!--search & user info start-->
    <ul class="nav pull-right top-menu">
        <!-- user login dropdown start-->
        <li class="dropdown">
            <a data-toggle="dropdown" class="dropdown-toggle" href="#">
                @if (!empty(Auth::user()->profile_picture))
                    <img src="{!! URL::to('../uploads/') !!}/userProfile/{{ Auth::user()->profile_picture }}" width="34px" height="34px" alt="KranQ">
                    <!-- <img alt="" src="assets/images/admin.png" width="34px" height="34px"> -->
                    <span class="username">{{ Auth::user()->first_name }}</span>
                    <b class="caret"></b>
                @else
                    <img src="{!! URL::to('/') !!}/images/admin.png" width="34px" height="34px" alt="KranQ">
                    <!-- <img alt="" src="assets/images/admin.png" width="34px" height="34px"> -->
                    <span class="username">{{ Auth::user()->first_name }}</span>
                    <b class="caret"></b>
                @endif
            </a>
            <ul class="dropdown-menu extended logout">
                <li><a href="{{ URL::to('profileView') }}/{{ Auth::user()->id }}"><i class=" fa fa-suitcase"></i>Profile</a></li>
                <li><a href="{{ URL::to('changePassword') }}/{{ Auth::user()->id }}"><i class="fa fa-cog"></i>Change Password</a></li>
                <li>
                  <a href="{{ route('logout') }}"
                      onclick="event.preventDefault();
                               document.getElementById('logout-form').submit();">
                               <i class="fa fa-key"></i>
                      Logout
                  </a>
                  <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                      {{ csrf_field() }}
                  </form>
              </li>
            </ul>
        </li>
        <!-- user login dropdown end -->
    </ul>
    <!--search & user info end-->
</div>
</header>
@include('layouts.sidemenu')
<section id="main-content">
  <section class="wrapper">
    <!--mini statistics end-->
    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
          @include('common.alert')
          @yield('content')
        </div>
    </div>
	<!-- to display the MSG91 logo (for free SMS)-->
	<div align="right"><a href="https://msg91.com/startups/?utm_source=startup-banner"><img src="https://msg91.com/images/startups/msg91Badge.png" width="120" height="90" title="MSG91 - SMS for Startups" alt="Bulk SMS - MSG91"></a></div>
	<!-- End to display the MSG91 logo (for free SMS)-->
  </section>
</section>
    {{-- Html::script('js/app.js') --}}
    {!! Html::script('js/jquery.js') !!}
    {!! Html::script('js/bootstrap.min.js') !!}
    {!! Html::script('js/jquery.dcjqaccordion.2.7.js') !!}
    {!! Html::script('js/jquery.scrollTo.min.js') !!}
    {!! Html::script('js/jquery.nicescroll.js') !!}
    {!! Html::script('js/sparkline/jquery.sparkline.js') !!}
    {!! Html::script('js/scripts.js') !!}
	{!! Html::script('js/bootstrap-fileupload.js') !!}
	{!! Html::script('js/ckeditor/ckeditor.js') !!}

    <!-- Multi select js -->
    {!! Html::script('js/select2/select2.js') !!}
    {!! Html::script('js/select-init.js') !!}
    <!-- Multi select js End's here -->

    <!-- Js Starts here-->
    <!-- ONLY INCLUDE IF YOU NOT HAVE THOSE DEPENDENCIES -->
    <!-- <script src="vendor/rafwell/simple-grid/moment/moment.js"></script> -->
    <!-- <script type="text/javascript" src="vendor/rafwell/simple-grid/eonasdan-bootstrap-datetimepicker/build/js/bootstrap-datetimepicker.min.js"></script> -->
    <!-- JS LARAVEL SIMPLEGRID -->
    <script src="vendor/rafwell/simple-grid/js/simplegrid.js"></script>
    @yield('page_js')
</body>
</html>
