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
				<div class="wrapper">
					<article class="col-2-3"><div class="wrap-col">
						<h3>Contact Form</h3>
						{{-- Form::open(array('route' => 'contact_store','method'=>'POST', 'class' => 'form', 'id'=>'contact-form', 'accept-charset'=>'utf-8')) --}}
						<form method="post" id="contact-form">
							<fieldset>
								  <label><span class="text-form">Name:</span><input name="name" type="text" class="form-control" /></label>
								  <label><span class="text-form">Email:</span><input name="email" type="text" class="form-control" /></label>
								  <label><span class="text-form">Phone:</span><input name="phone" type="text" class="form-control" /></label>
								  <div class="wrapper">
									<div class="text-form">Message:</div>
									<div class="extra-wrap">
										<textarea cols="15" rows="5"></textarea>
										<div class="clear"></div>
										<div class="buttons">
											<a href="#" onClick="document.getElementById('contact-form').reset()" class="btn btn-default">Clear</a>
											<a href="#" onClick="document.getElementById('contact-form').submit()" class="btn btn-primary">Send</a>
										</div>
									</div>
								  </div>
							</fieldset>
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
