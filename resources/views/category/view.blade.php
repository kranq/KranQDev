@extends('layouts.layouts')
@section('title',trans('main.category.title'))
@section('header')
    <h3>
        <i class="icon-message"></i>{!! trans('main.view').' '.trans('main.category.title') !!}
    </h3>
@stop
@section('content')
<!--sidebar end-->
<section class="panel form-horizontal">
	<header class="panel-heading"> {!! trans('main.view').' '.trans('main.category.title') !!}</header>
	<div class="panel-body">
		<div class="position-left">
			<div class="form-group">
				<label class=" col-sm-3 control-label">{!! trans('main.category.title') !!}</label>
				<div class="col-lg-6">
					<p class="form-control-static">{!! @$category->category_name; !!}</p>
				</div>
			</div>
			<div class="form-group">
				<label class=" col-sm-3 control-label">{!! trans('main.category.category_image') !!}</label>
				<div class="col-lg-6">
					<p class="form-control-static">
					@php
							//print_r($s3image);exit;
							//print_r(\Storage::disk('s3')->url('uploads/category/'.$category->category_image));exit;
					@endphp
					@if (@$s3image)
						<img src="{{ @$s3image }}"/>
					@elseif(file_exists(URL::to('../uploads/category/').$category->category_image))
              <img src="{!! URL::to('../uploads/category') !!}/{!! @$category->category_image !!}"  alt="{!! @$category->category_image !!}" title="{!! @$category->category_image !!}" />
					@else
              <img src="{!! URL::to('images') !!}/noimage.jpg"  alt="No Image" width="150px" height="150px" title="No Image" />
          @endif
					</p>
				</div>
			</div>
			<div class="form-group">
				<label class=" col-sm-3 control-label">{!! trans('main.category.services') !!}</label>
				<div class="col-lg-6">
					<p class="form-control-static">
					@if (isset($services))
						@foreach(@$services as $service)
							@php echo $service[0].',';@endphp
						@endforeach
					@endif
					</p>
				</div>
			</div>
			<div class="form-group">
				<label class=" col-sm-3 control-label">{!! trans('main.category.order_by') !!}</label>
				<div class="col-lg-6">
					<p class="form-control-static">{!! @$category->order_by; !!}</p>
				</div>
			</div>
			<div class="form-group">
				<label class=" col-sm-3 control-label">{!! trans('main.category.status') !!}</label>
				<div class="col-lg-6">
					<p class="form-control-static">{!! @$category->status; !!}</p>
				</div>
			</div>
			<div class="form-group">
				<div class="col-lg-offset-3 col-lg-6">
						<a href="{!! route('main.category.index') !!}" class="btn btn-default">{!! trans('main.back') !!}</a>
				</div>
			</div>
		</div>
	</div>
</section>

@stop
