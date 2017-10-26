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
						<h3>Services</h3>
						<h6 class="prev-indent-bot">KranQ is a search and discovery destination for car related service providers. </h6>
						<p class="indent-bot">The Categories are:</p>
						<div class="wrapper p2">
						  <div class="col-2-3 extra-wrap">
							  <ul class="list-1">
									@foreach (@$categories as $category)
									<li><a href="#">{{ $category->category_name }}</a></li>
									@endforeach
								</ul>
							</div>
						</div>
						<h3 class="prev-indent-bot">How does it work?</h3>
						<div class="wrapper">
							<div class="col-1-1"><div style="padding-right:10px;">
							  <h6 class="prev-indent-bot">&nbsp;</h6>
								<ul>
								  <li>The app will  bring onboard all existing service providers for cars. Eg: Dealer owned Service  Centers, Authorized Service Centers, Car washes, wheel Alignment centers, Accessories  shop, etc.</li>
							  </ul>
								<p class="p2"><img src="{{ URL::to('/') }}/site/images/kranq-app.png" alt="KranQ"/></p>
						  </div></div>
						</div>
					</div></article>
					<article class="col-1-3"></article>
				</div>
			</div>
		</div>
	</section>
@endsection
