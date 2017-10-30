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
@if ( Session::has('success') )
<div class="alert alert-success alert-dismissible" role="alert">
    <!--button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button-->
    <strong>{!! trans('main.message') !!}</strong> {{ Session::get('success') }}
</div>
@endif
	<section id="content">
		<div class="main">
			<div class="zerogrid">
				<div class="wrapper">
					<article class="col-2-3"><div class="wrap-col">
						<h3>Contact Form</h3>
					 {{-- Form::open(array('route' => 'site.mail','method'=>'POST', 'class' => 'form', 'id'=>'contact-form', 'accept-charset'=>'utf-8')) --}} 

						{{-- Form::open( array('route' => 'site.contact-mail','method'=>'POST','class'=>'form', 'id'=>'contact-form', 'accept-charset'=>'utf-8', 'enctype' => 'multipart/form-data')) --}}

						<form method="post" id="contact-form" action="{{ URL::to('site/contact-mail') }}" accept-charset="UTF-8">
							{!! csrf_field() !!}
							<fieldset>
								  <label><span class="text-form">Name <span class="required">*</span></span><input name="name" id="name" type="text" class="form-control" required/></label>

								  <label><span class="text-form">Email <span class="required">*</span></span><input name="email" id="email" type="Email" class="form-control"  required /></label>
								  

								  <label><span class="text-form">Subject <span class="required">*</span></span><input name="subject" id="subject" type="text" class="form-control" required /></label>

								  	 <div class="wrapper">
									<div class="text-form">Message<span class="required">*</span></div>
									<div class="extra-wrap">
										<textarea cols="15" rows="5" name="message" id="message" required></textarea>
										<div class="clear"></div>
										</div>
								  </div>
							</fieldset>	
							<div class="form-group">
		                	<div class="button-btn">
		                    <button type="submit" title="Save" class="btn btn-primary">{!! trans('main.save') !!}</button>
		                    <button type="reset" title="Cancel" class="btn btn-default">{!! trans('main.cancel') !!}</button>
		                </div>
			            </div>

							
							<form>
						{{-- Form::close() --}}
					</div></article>
					<article class="col-1-3"><div class="wrap-col">
						<div class="indent-top indent-left">
						  <h3 class="p1">&nbsp;</h3>
							<p class="prev-indent-bot"><strong style="font-size:16px;">Mindloop Technologies Pvt Ltd</strong><br><br>
							  <strong>							  Mobile:</strong> +91 9677227711<br>
					        <strong>Email:</strong> <a href="mailto:Customercare.kranq@gmail.com">Customercare.kranq@gmail.com</a></p>
							<dl>
							  <dt class="prev-indent-bot">&nbsp;</dt>
						  </dl>
					  </div>
					</div></article>
				</div>
			</div>
		</div>
	</section>
@endsection
