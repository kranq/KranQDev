<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="ThemeBucket">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="shortcut icon" href="{!! URL::to('/') !!}/images/img.png">

    <title>{{ trans('main.site_name') }} - {{ trans('main.login') }}</title>
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <!--Core CSS -->
    <link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/bootstrap-reset.css') }}" rel="stylesheet">
    <link href="{{ asset('font-awesome/css/font-awesome.css') }}" rel="stylesheet">
    <!-- Custom styles for this template -->
    <link href="{{ asset('css/style.css') }}" rel="stylesheet">
    <link href="{{ asset('css/style-responsive.css') }}" rel="stylesheet">
</head>

  <body class="login-body">
    <div class="container">
        @include('common.alert')
        @yield('content')
    </div>
	<!-- to display the MSG91 logo (for free SMS)-->
	<!-- <div align="right"><a href="https://msg91.com/startups/?utm_source=startup-banner"><img src="https://msg91.com/images/startups/msg91Badge.png" width="120" height="90" title="MSG91 - SMS for Startups" alt="Bulk SMS - MSG91"></a></div> -->
	<!-- End to display the MSG91 logo (for free SMS)-->

    <!-- Placed js at the end of the document so the pages load faster -->
    <!--Core js-->
    <script src="{{ asset('js/app.js') }}"></script>
  </body>
</html>
