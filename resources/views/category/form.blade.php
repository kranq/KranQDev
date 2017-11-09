@extends('layouts.layouts')
@section('title',trans('main.category.title'))
@section('header')
    <h3><i class="icon-message"></i>{{ trans('main.category.title') }}</h3>
@stop
@section('help')
    <p class="lead">{{ trans('main.category.title') }}</p>
@stop
@section('content')
    {!! Form::open( array('route' => 'main.category.store','class'=>'cmxform form-horizontal', 'accept-charset'=>'utf-8','id'=>'form', 'enctype' => 'multipart/form-data')) !!}
	     @include('category/partials/_form', ['submit_text' => trans('main.save') ,'btn'=> trans('main.save')])
    {!! Form::close() !!}
@stop
