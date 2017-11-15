@extends('layouts.layouts')
@section('title',trans('main.review.title'))
@section('header')
    <h3>
        <i class="icon-message"></i>{!!trans('main.review.title') !!}
    </h3>
@stop
@section('content')
<!--sidebar end-->
<section class="panel form-horizontal">
	<header class="panel-heading"> {!! trans('main.view').' '.trans('main.review.title') !!}</header>
	<div class="panel-body">
		<div class="position-left">
		<div class="form-group">
				<div class="col-sm-3 control-label"> {!! trans('main.category.title') !!}</div>
				<div class="col-lg-6">
					<p class="form-control-static">{!! @$category->category_name; !!}</p>
				</div>
			</div>
		<div class="form-group">
				<div class="col-sm-3 control-label"> {!! trans('main.provider.name_sp') !!}</div>
				<div class="col-lg-6">
					<p class="form-control-static">{!! @$service_providers[0]->name_sp; !!}</p>
				</div>
			</div>
			<div class="form-group">
				<div class="col-sm-3 control-label"> {!! trans('main.user.fullname') !!}</div>
				<div class="col-lg-6">
					<p class="form-control-static">{!! @$user[0]->fullname; !!}</p>
				</div>
			</div>
	<div class="form-group">
				<div class="col-sm-3 control-label"> {!! trans('main.review.rating') !!}</div>
				<div class="col-lg-6">
					<p class="form-control-static">{!! @$review->rating; !!}</p>
				</div>
			</div>		
                    <div class="form-group">
				<div class="col-sm-3 control-label"> {!! trans('main.review.reviews') !!}</div>
				<div class="col-lg-6">
					<p class="form-control-static">{!! @$review->reviews; !!}</p>
				</div>
			</div>
			<div class="form-group">
				<div class="col-sm-3 control-label"> {!! trans('main.review.status') !!}</div>
				<div class="col-lg-6">
					<p class="form-control-static">{!! @$review->status; !!}</p>
				</div>
			</div>
			<div class="form-group">
				<div class="col-sm-3 control-label"> {!! trans('main.review.postted_on') !!}</div>
				<div class="col-lg-6">
					<p class="form-control-static">{!! @$review->postted_on; !!}</p>
				</div>
			</div>
			<div class="form-group">
				<div class="col-lg-offset-3 col-lg-6">
						<a href="{!! route('main.review.index') !!}" class="btn btn-default">{!! trans('main.back') !!}</a>
				</div>
			</div>
		</div>
	</div>
</section>
						
@stop