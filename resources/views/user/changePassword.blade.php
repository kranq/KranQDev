@extends('layouts.layouts')
@section('title',trans('main.user.changepassword'))
@section('header')
    <h3>
        <i class="icon-message"></i>{!!trans('main.user.changepassword') !!}
    </h3>
@stop
@section('content')
<!--sidebar end-->
<section class="panel form-horizontal">
	<header class="panel-heading"> {!! trans('main.user.changepassword') !!}</header>
	<div class="panel-body">
		<div class="position-left">
		{!! Form::open( array('route' => array('main.home.updatePassword',$user->id),'method'=>'PUT', 'class'=>'form-vartical', 'accept-charset'=>'utf-8')) !!}
			<!-- <div class="form-group">
	            <div class="col-sm-3 control-label">
	                {!! Form::label('currentpassword',trans('main.user.currentpassword'),array('class'=>'custom_required')) !!}
	            </div>
  				<div class="col-lg-6">
  					<input type="password" name="currentpassword" class="form-control" placeholder="{{ trans('main.user.password') }}">
                	@if ($errors->has('currentpassword'))
	                <span class="help-block">
	                    <strong>{{ $errors->first('currentpassword') }}</strong>
	                </span>
	                @endif
		    </div>
            	</div> -->
            <div class="form-group">
	            <div class="col-sm-3 control-label">
	                {!! Form::label('newpassword',trans('main.user.newpassword'),array('class'=>'custom_required')) !!}
	            </div>
				<div class="col-lg-6">
					<input type="password" name="password" class="form-control" placeholder="{{ 	trans('main.user.placepassword') }}">
                    @if ($errors->has('password'))
                    <span class="help-block">
                        <strong>{{ $errors->first('password') }}</strong>
                    </span>
                    @endif
	                </div>
		        </div>
				    <div class="form-group">
	                <div class="col-sm-3 control-label">
	                    {!! Form::label('password_confirmation',trans('main.user.password_confirmation'),array('class'=>'custom_required')) !!}
	                </div>
					<div class="col-lg-6">
	                    <input type="password" name="password_confirmation" class="form-control" placeholder="{{ 	trans('main.user.password_confirmation') }}">
	                    @if ($errors->has('password_confirmation'))
	                    <span class="help-block">
	                        <strong>{{ $errors->first('password_confirmation') }}</strong>
	                    </span>
	                    @endif
	                </div>
		        </div>
  				<div class="form-group">
    					<div class="col-lg-offset-3 col-lg-6">
    						<button type="submit" class="btn btn-primary" title="{!! trans('main.save') !!}">{!! trans('main.save') !!}</button>
    						<a href="{!! URL::to('/'); !!}" class="btn btn-default" title="{!! trans('main.back') !!}">{!! trans('main.back') !!}</a>
    					</div>
  				</div>
			{!! Form::close() !!}
		</div>
	</div>
</section>
@stop
