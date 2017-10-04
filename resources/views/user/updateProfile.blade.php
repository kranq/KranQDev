@extends('layouts.layouts')
@section('title',trans('main.user.profile'))
@section('header')
    <h3>
        <i class="icon-message"></i>{!!trans('main.user.profile') !!}
    </h3>
@stop
@section('page_style')
<style type="text/css">
	[data-tooltip] {
	      cursor: default;
	      font: normal 1em sans-serif;
	  }

	  [data-tooltip]:hover:after {
	      display: block;
	      content: attr(data-tooltip);
	      white-space: pre-wrap;
	      color: #f00;
	  }
</style>
@stop
@section('content')
<!--sidebar end-->
<section class="panel form-horizontal">
	<header class="panel-heading"> {!! trans('main.user.profile') !!}</header>
	<div class="panel-body">
		<div class="position-left">
		{!! Form::open( array('route' => array('main.home.updateProfile', $user->id),'method'=>'PUT', 'class'=>'form-vartical', 'accept-charset'=>'utf-8', 'enctype' => 'multipart/form-data')) !!}
				<div class="form-group">
	                <div class="col-sm-3 control-label">
	                    {!! Form::label('first_name',trans('main.user.first_name'),array('class'=>'custom_required')) !!}
	                </div>
					<div class="col-lg-6">
	                    {!! Form::text('first_name', @$user->first_name, array('class'=>'form-control', 'placeholder' => 'Enter First Name')) !!}
	                    @if ($errors->has('first_name'))
	                    <span class="help-block">
	                        <strong>{{ $errors->first('first_name') }}</strong>
	                    </span>
	                    @endif
	                </div>
		        </div>
				<div class="form-group">
	                <div class="col-sm-3 control-label">
	                    {!! Form::label('last_name',trans('main.user.last_name'),array('class'=>'custom_required')) !!}
	                </div>
					<div class="col-lg-6">
	                    {!! Form::text('last_name', @$user->last_name, array('class'=>'form-control', 'placeholder' => 'Enter Last Name')) !!}
	                    @if ($errors->has('last_name'))
	                    <span class="help-block">
	                        <strong>{{ $errors->first('last_name') }}</strong>
	                    </span>
	                    @endif
	                </div>
		        </div>
		        <div class="form-group">
	                <div class="col-sm-3 control-label">
	                    {!! Form::label('email',trans('main.user.email'),array('class'=>'custom_required')) !!}
	                </div>
					<div class="col-lg-6">
	                    {!! Form::text('email', @$user->email, array('class'=>'form-control', 'placeholder' => 'Enter Email')) !!}
	                    @if ($errors->has('email'))
	                    <span class="help-block">
	                        <strong>{{ $errors->first('email') }}</strong>
	                    </span>
	                    @endif
	                </div>
		        </div>
		        <div class="form-group">
	                <div class="col-sm-3 control-label">
	                    {!! Form::label('updateprofile',trans('main.user.updateprofile'),array('class'=>'')) !!}
	                </div>
					<div class="col-lg-4">
	                    {{-- Form::file('updateprofile') --}}
	                    <div class="form-group">
	                        <div class="controls col-md-9">
	                            <div class="fileupload fileupload-new" data-provides="fileupload">
	                                <span class="btn btn-white btn-file">
	                                <span class="fileupload-new"><i class="fa fa-paper-clip"></i> Select file</span>
	                                <span class="fileupload-exists"><i class="fa fa-undo"></i> Change</span>
	                                {{ Form::file('updateprofile',array('class'=>'default')) }}
	                                </span>
	                                <span class="fileupload-preview" style="margin-left:5px;"></span>
	                                <a href="#" class="close fileupload-exists" data-dismiss="fileupload" style="float: none; margin-left:5px;"></a>
	                            </div>
	                        </div>
                    	</div>
	                </div>
	                <div class="col-lg-5">
	                	<i  data-original-title="{!! trans('main.image_upload_notes_title') !!}" data-content="{!! trans('main.image_upload_notes') !!}" data-placement="right" data-trigger="hover" class="fa fa-info-circle popovers" aria-hidden="true" data-html="true"></i>
		        	</div>
		        </div>
		        @if(@$user->profile_picture)
	                <div class="form-group">
                        <div class="col-lg-3">
                        </div>
                        <div class="col-lg-6">
                            <a href="#" >
                            @if($user->profile_picture)
                                <img src="{!! URL::to('../uploads/userProfile') !!}/{!! @$user->profile_picture !!}"  alt="{!! @$user->profile_picture !!}" title="{!! @$user->profile_picture !!}" />
                            @endif
                            </a>
                        </div>
                        <div class="col-lg-3">
                        </div>
	                </div>
	            @endif
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
