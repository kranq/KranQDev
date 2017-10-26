<!DOCTYPE html>
<html lang="en">
<head>
<title>{!! trans('main.site_name') !!} - one stop search and discovery destination for car related service providers</title>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
{{ Html::style('site/css/reset.css',['type' => 'text/css', 'media'=> 'screen']) }}
{{ Html::style('site/css/style.css',['type' => 'text/css', 'media'=> 'screen']) }}
{{ Html::style('site/css/zerogrid.css',['type' => 'text/css', 'media'=> 'all']) }}
{{ Html::style('site/css/responsive.css',['type' => 'text/css', 'media'=> 'all']) }}
{{ Html::style('site/css/responsiveslides.css') }}
<link rel="shortcut icon" href="{!! URL::to('/') !!}/site/images/favico.png">
<meta name="description"  content="•	KranQ is a search and discovery destination for car related service providers. KranQ is an one stop search and discovery destination for car related service providers. At kranq we strive to bring to you all the service providers in your  city on a unique platform.  If you are someone who loves pampering your car, find the best services near you through our app." />
<meta name="keywords"  content="KranQ, KranQ Car Service Provider, car service provider chennai, kranq car service app, kranq app, kranq chennai" />
<link rel="canonical" href="http://www.kranq.in" />
<link rel="shortlink" href="http://www.kranq.in" />
{!! Html::script('site/js/jquery-1.7.1.min.js') !!}
{!! Html::script('site/js/cufon-yui.min.js') !!}
{!! Html::script('site/js/cufon-replace.js') !!}
{!! Html::script('site/js/Vegur_500.font.js') !!}
{!! Html::script('site/js/Ropa_Sans_400.font.js') !!}
{!! Html::script('site/js/FF-cash.js') !!}
{!! Html::script('site/js/tms-0.3.js') !!}
{!! Html::script('site/js/tms_presets.js') !!}
{!! Html::script('site/js/jquery.easing.1.3.js') !!}
{!! Html::script('site/js/script.js') !!}
{!! Html::script('site/js/css3-mediaqueries.js') !!}
{!! Html::script('site/js/responsiveslides.js') !!}
<script>
		$(function () {
		  $("#slider").responsiveSlides({
			auto: true,
			pager: true,
			nav: true,
			speed: 500,
			maxwidth: 960,
			namespace: "centered-btns"
		  });
		});
	</script>
<!--[if lt IE 8]>
	<div style=' clear: both; text-align:center; position: relative;'>
		<a href="http://windows.microsoft.com/en-US/internet-explorer/products/ie/home?ocid=ie6_countdown_bannercode">
			<img src="http://storage.ie6countdown.com/assets/100/images/banners/warning_bar_0000_us.jpg" border="0" height="42" width="820" alt="You are using an outdated browser. For a faster, safer browsing experience, upgrade for free today." />
		</a>
	</div>
	<![endif]-->
<!--[if lt IE 9]>
 		<script type="text/javascript" src="js/html5.js"></script>
		<link rel="stylesheet" href="css/ie.css" type="text/css" media="screen">
	<![endif]-->
</head>
