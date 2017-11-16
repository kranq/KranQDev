@extends('layouts.layouts')
@section('title',trans('main.user.title'))
@section('header')
    <h3>
        <i class="icon-message"></i>{!!trans('main.user.title') !!}
    </h3>
@stop
@section('help')
    <p class="lead">{!!trans('main.user.title') !!}</p>
    <p>{!!trans('main.user.help') !!}</p>
@stop
@section('content')
    {!! Form::open(array('route' => array('main.user.update',$user->id),'method'=>'PUT','class' => 'form-horizontal row-border','id'=>'editForm','data-toggle'=>"validator",'files' => true)) !!}
    @include('user/partials/_form', ['submit_text' => trans('main.editupdate').' '.trans('main.user.title'),'btn'=>trans('main.update')])
    {!! Form::close() !!}
@stop
