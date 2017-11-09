@extends('layouts.layouts')
@section('title',trans('main.user.title'))
@section('header')
    <h3><i class="icon-message"></i>{{ trans('main.user.title') }}</h3>
@stop
@section('help')
    <p class="lead">{{ trans('main.user.title') }}</p>
@stop
@section('content')
    {!! Form::open( array('route' => 'main.user.store','class'=>'cmxform form-horizontal', 'accept-charset'=>'utf-8','id'=>'form', 'enctype' => 'multipart/form-data')) !!}
	     @include('user/partials/_form', ['submit_text' => trans('main.save') ,'btn'=> trans('main.save')])
    {!! Form::close() !!}
@stop
