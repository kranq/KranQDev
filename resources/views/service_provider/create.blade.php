@extends('layouts.layouts')
@section('title',trans('main.provider.title'))
@section('header')
    <h3><i class="icon-message"></i>{{ trans('main.provider.title') }}</h3>
@stop
@section('help')
    <p class="lead">{{ trans('main.provider.title') }}</p>
@stop
@section('content')
    {!! Form::open( array('route' => 'main.provider.store','class'=>'form-horizontal', 'accept-charset'=>'utf-8','id'=>'form', 'enctype' => 'multipart/form-data')) !!}
	     @include('service_provider/form', ['submit_text' => trans('main.save') ,'btn'=> trans('main.save')])
    {!! Form::close() !!}
@stop
