@extends('layouts.layouts')
@section('title',trans('main.user.title'))
@section('header')
    <h3>
        <i class="icon-message"></i>{{trans('main.user.title') }}
    </h3>
@stop
@section('content')
<!--sidebar end-->
<section class="panel">
<header class="panel-heading"> {{ trans('main.view').' '.trans('main.user.title') }} - {{ @$user->fullname }}</header>
    <header class="panel-heading tab-bg-dark-navy-blue">
        <ul class="nav nav-tabs nav-justified ">
            <li class="active">
                <a data-toggle="tab" href="#overview">
                    {{ trans('main.user.viewprofile') }}
                </a>
            </li>
            <!--li>
                <a data-toggle="tab" href="#job-history">
                    {{ trans('main.user.bookmarks') }}
                </a>
            </li-->
            <li>
                <a data-toggle="tab" href="#contacts" class="contact-map">
                    {{ trans('main.user.reviews') }}
                </a>
            </li>

        </ul>
    </header>
    <div class="panel-body">
        <div class="tab-content tasi-tab">
            <div id="overview" class="tab-pane active">
                <div class="row">
                    <div class="col-md-12">
                        <div class="form cmxform form-horizontal">
                            <div class="form-group">
                                    <label for="cname" class="control-label col-lg-3">{{ trans('main.user.fullname') }}</label>
                                    <div class="col-lg-6">
                                        <p class="form-control-static">{{ @$user->fullname }}</p>
                                    </div>
                            </div>
                            <div class="form-group">
                                    <label for="cname" class="control-label col-lg-3">{{ trans('main.user.profilepicture') }}</label>
                                    <div class="col-lg-6">
										@if (@$s3image)
										<img src="{{ @$s3image }}">
                                    	@elseif(file_exists(URL::to('../uploads/user/').$user->profile_picture))
                                        <img src="{{ URL::to('../uploads/user') }}/{!! @$user->profile_picture !!}">
                                        @else
                                        <img src="{{ URL::to('/images') }}/noimage.jpg">
                                        @endif
                                    </div>
                            </div>
                            <div class="form-group">
                                <label for="cname" class="control-label col-lg-3">{{ trans('main.user.address') }}</label>
                                <div class="col-lg-6">
                                    <p class="form-control-static">{{ @$user->address }}</p>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="cname" class="control-label col-lg-3">{{ trans('main.user.mobileno') }}</label>
                                <div class="col-lg-6">
                                    <p class="form-control-static">{{ @$user->mobile }}</p>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="cname" class="control-label col-lg-3">{{ trans('main.user.email') }}</label>
                                <div class="col-lg-6">
                                    <p class="form-control-static">{{ @$user->email }}</p>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="cname" class="control-label col-lg-3">{{ trans('main.user.registermode') }}</label>
                                <div class="col-lg-6">
                                    <p class="form-control-static">{{ @$registered_mode[0]->value }}</p>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="cname" class="control-label col-lg-3">{{ trans('main.user.registeron') }}</label>
                                <div class="col-lg-6">
                                    <p class="form-control-static">{{ @$user->registered_on }}</p>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="cname" class="control-label col-lg-3">{{ trans('main.user.status') }}</label>
                                <div class="col-lg-6">
                                    <p class="form-control-static">{{ @$user->status }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div id="job-history" class="tab-pane ">
                <div class="row">
                    <div class="col-md-12">
                        <table class="table table-striped table-hover table-bordered">
                            <thead>
                                <tr>
                                    <th>{{ trans('main.user.serviceprovider') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if (@$bookmarks)
                                    @foreach (@$bookmarks as $bookmark)
                                        <tr>
                                            <td>{{ $bookmark->service_provider_name }}</td>
                                        </tr>
                                    @endforeach
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div id="contacts" class="tab-pane ">
                <div class="row">
                    <div class="col-md-12">
                        <table class="table table-striped table-hover table-bordered">
                            <thead>
                                <tr>
                                    <th width="70%">User Review</th>
                                    <th width="10%">Rating</th>
                                    <th width="10%">Service Provider</th>
                                    <th width="10%">Posted On</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if(count(@$reviews)>0)
                                    @foreach (@$reviews as $review)
                                        <tr>
                                            <td>{{ $review->reviews }}</td>
                                            <td>{{ $review->rating }}</td>
                                            <td>{{ $review->service_provider_name }}</td>
                                            <td>{{ $review->postted_on }}</td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="4">{{ trans('main.norecords') }}</td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                    </div>
                </div>

            <div class="form-group">
                <div class="col-lg-offset-3 col-lg-6">
                        <a href="{!! route('main.user.index') !!}" class="btn btn-default">{!! trans('main.back') !!}</a>
                </div>
            </div>
        </div>
    </div>
</section>
@stop
