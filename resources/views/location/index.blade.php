@extends('layouts.layouts')
@section('title',trans('main.location.title'))
@section('header')
<h3><i class="icon-message"></i>{!!trans('main.location.title') !!}</h3>
@stop
@section('help')
<p class="lead">{!!trans('main.location.title') !!}</p>
<p>{!!trans('main.area.help') !!}</p>
@stop
@section('content')

<section class="panel">
    <header class="panel-heading"><b>{{ trans('main.location.title') }}</b>
        <span class="tools pull-right">
            <div class="form-group btn-toolbar">
                <a class="btn btn-primary" href="{{ route('main.location.create') }}" title="{!! trans('main.add').' '.trans('main.location.title') !!}">
                    <i class="visible-xs glyphicon glyphicon-plus"></i>
                    <div class="hidden-xs"></i>{!! trans('main.add').' '.trans('main.location.title') !!}</div>
                </a>
            </div>
        </span>
    </header>
    <div class="panel-body">
        <div id="graph-area" class="main-chart">
            {!! @$grid->make() !!}
        </div>
    </div>
</section>
@endsection
