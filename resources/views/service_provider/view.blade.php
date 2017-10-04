@extends('layouts.layouts')
@section('title',trans('main.provider.title'))
@section('header')
    <h3>
        <i class="icon-message"></i>{{trans('main.provider.title') }}
    </h3>
@stop
@section('content')
<!--sidebar end-->
<section class="panel">
<header class="panel-heading"> {{ trans('main.view').' '.trans('main.provider.title') }} - {{ @$provider->name_sp }}</header>
    <header class="panel-heading tab-bg-dark-navy-blue">
        <ul class="nav nav-tabs nav-justified ">
            <li class="active">
                <a data-toggle="tab" href="#overview">
                    {{ trans('main.provider.basicdetails') }}
                </a>
            </li>
            <li>
                <a data-toggle="tab" href="#job-history">
                    {{ trans('main.provider.photos') }}
                </a>
            </li>
            <li>
                <a data-toggle="tab" href="#contacts" class="contact-map">
                    {{ trans('main.review.title') }}
                </a>
            </li>
            <!--li>
                <a data-toggle="tab" href="#settings">
                    {{ trans('main.provider.ratings') }}
                </a>
            </li-->
        </ul>
    </header>
    <div class="panel-body">
        <div class="tab-content tasi-tab">
            <div id="overview" class="tab-pane active">
                <div class="row">
                    <div class="col-md-12">
                        <div class="prf-contacts sttng">
                            <h2>{!! trans('main.provider.basicdetails') !!}</h2>
                        </div>
                        <div class="form cmxform form-horizontal">
                            <div class="form-group">
                                <label for="cname" class="control-label col-lg-3 custom_required">{{ trans('main.provider.name') }}</label>
                                <div class="col-lg-6">
                                    <p class="form-control-static">{{ @$provider->name_sp }}</p>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="cname" class="control-label col-lg-3 custom_required">{{ trans('main.provider.logo') }}</label>
                                <div class="col-lg-6">
									@if (@$s3image)
										<img src="{{ @$s3image }}">
                                    @elseif (file_exists(URL::to('../uploads/provider/').$provider->logo))
                                        <img src="{{ URL::to('../uploads/provider') }}/{!! @$provider->logo !!}">
                                    @else
                                        <img src="{{ URL::to('/images/noimage.jpg') }}">
                                    @endif
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="cname" class="control-label col-lg-3 custom_required">{{ trans('main.provider.category') }}</label>
                                <div class="col-lg-6">
                                    <p class="form-control-static">{{ @$provider->category_id }}</p>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="cname" class="control-label col-lg-3 custom_required">{{ trans('main.provider.phone') }}</label>
                                <div class="col-lg-6">
                                    <p class="form-control-static">{{ @$provider->phone }}</p>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="cname" class="control-label col-lg-3 custom_required">{{ trans('main.provider.city') }}</label>
                                <div class="col-lg-6">
                                    <p class="form-control-static">{{ @$provider->city }}</p>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="cname" class="control-label col-lg-3 custom_required">{{ trans('main.provider.locality') }}</label>
                                <div class="col-lg-6">
                                    <p class="form-control-static">{{ @$provider->location_id }}</p>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="cname" class="control-label col-lg-3">{{ trans('main.provider.address') }}</label>
                                <div class="col-lg-6">
                                    <p class="form-control-static">{{ @$provider->address }}</p>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="cname" class="control-label col-lg-3 custom_required">{{ trans('main.provider.status_owner_manager') }}</label>
                                <div class="col-lg-6">
                                    <p class="form-control-static">{{ @$provider->status_owner_manager }}</p>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="cname" class="control-label col-lg-3 custom_required">{{ trans('main.provider.open_close_hours') }}</label>
                                <div class="col-lg-6">
                                    <p class="form-control-static">{{ @$provider->opening_hrs }} to
                                     {{ @$provider->closing_hrs }}</p>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="cname" class="control-label col-lg-3 custom_required">{{ trans('main.provider.working_days') }}</label>
                                <div class="col-lg-6">
                                    <p class="form-control-static">{{ @$provider->working_days }}</p>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="cname" class="control-label col-lg-3 custom_required">{{ trans('main.provider.phone') }}</label>
                                <div class="col-lg-6">
                                    <p class="form-control-static">{{ @$provider->phone }}</p>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="cname" class="control-label col-lg-3 custom_required">{{ trans('main.provider.website_link') }}</label>
                                <div class="col-lg-6">
                                    <p class="form-control-static">{{ @$provider->website_link }}</p>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="cname" class="control-label col-lg-3">{{ trans('main.provider.coordinates') }}</label>
                                <div class="col-lg-6">
                                    <p class="form-control-static">{{ @$provider->googlemap_latitude }}, {{ @$provider->googlemap_longitude }}</p>
                                </div>
                            </div>
                            <div class="prf-contacts sttng">
                                <h2>{!! trans('main.provider.accountdetails') !!}</h2>
                            </div>
                            <div class="form-group">
                                <label for="cname" class="control-label col-lg-3 custom_required">{{ trans('main.provider.email') }}</label>
                                <div class="col-lg-6">
                                    <p class="form-control-static">{{ @$provider->email }}</p>
                                </div>
                            </div>
                            <!-- <div class="form-group">
                                <label for="cname" class="control-label col-lg-3">{{ trans('main.provider.password') }}</label>
                                <div class="col-lg-6">
                                    <p class="form-control-static">{{ @$provider->password }}</p>
                                </div>
                            </div> -->
                            <div class="form-group">
                                <label for="cname" class="control-label col-lg-3">{{ trans('main.provider.submittedon') }}</label>
                                <div class="col-lg-6">
                                    <p class="form-control-static">{{ @$provider->created_date }}</p>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="cname" class="control-label col-lg-3 custom_required">{{ trans('main.status') }}</label>
                                <div class="col-lg-6">
                                    <p class="form-control-static">
                                        @if ( @$provider->status == '1' )
                                            @php echo 'Under Review'; @endphp
                                        @elseif ( @$provider->status == '2' )
                                            @php echo 'Approved'; @endphp
                                        @elseif ( @$provider->status == '3' )
                                            @php echo 'Rejected'; @endphp
                                        @else
                                            @php echo 'Nil'; @endphp
                                        @endif
                                    </p>
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
                                    <th>{{ trans('main.provider.services') }}</th>
                                    <th>{{ trans('main.provider.images') }}</th>
                                    <th>{{ trans('main.provider.actions') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if (@$service_providers)
                                @php //echo '<pre>';print_r($service_providers);exit; @endphp

                                    @foreach (@$service_providers as $service_provider)
                                        <tr>
                                            <td width="60%">{{ $service_provider->service_description }}</td>
                                           <td width="30%"> <!-- <td>{{ $service_provider->image }}</td> -->
                                                @if(!empty(@$service_provider->image))
                                                    <img src="{{ URL::to('../uploads/service_provider_details') }}/{!! @$service_provider->image !!}" width="50%" height="10%">
                                                @else
                                                    <img src="{{ URL::to('/images/noimage.jpg') }}">
                                                @endif
                                            </td>
                                            <td width="10%">
                                                <a href="{{ URL::to('serviceproviderdetails/approvel') }}/{{ $service_provider->id }}" title="Approve"><i class="fa fa-thumbs-o-up" aria-hidden="true"></i></a>|
                                                <a href="{{ URL::to('serviceproviderdetails/reject') }}/{{ $service_provider->id }}" title="Reject"><i class="fa fa-thumbs-o-down" aria-hidden="true"></i></a>|
                                                <form action="{{ URL::to('serviceproviderdetails/destroy') }}/{{ $service_provider->id }}" method="POST" onsubmit="if(!confirm('Do you with so continue?')){event.preventDefault; return false;}; ">
                                                    <input type="hidden" name="_token" value="GgTEoRbhbFcMh1GpqUrxfygT82MczEVQgxltNN4C">
                                                        <input type="hidden" name="_method" value="DELETE">
                                                        <button type="submit" title="Delete" class="btn btn-xs action btn-danger">
                                                            <span class="fa fa-trash-o"></span>
                                                        </button>
                                                </form>
                                            </td>
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
                                    <th width="20%">User</th>
                                    <th width="50%">Reviews</th>
                                    <th width="10%">Rating</th>
                                    <th width="20%">Posted On</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if(@$reviews)
                                    @foreach (@$reviews as $review)
                                        <tr>
                                            <td>{{ $review->username }}</td>
                                            <td>{{ $review->reviews }}</td>
                                            <td>{{ $review->rating }}</td>
                                            <td>{{ $review->postted_on }}</td>
                                        </tr>
                                    @endforeach
                                @endif
                            </tbody>
                        </table>
                    </div>
                    </div>
                </div>
            <!--div id="settings" class="tab-pane ">
                <div class="table-responsive">
                    <table class="table table-striped table-hover table-bordered" id="editable-sample">
                        <thead>
                        <tr>
                            <th>User</th>
                            <th>Ratings</th>
                        </tr>
                        </thead>
                        <tbody>
                            @if (@$reviews)
                                @foreach (@$reviews as $review)
                                    <tr class="">
                                        <td>
                                            <p>{{ @$users[$review->user_id] }}</p>
                                        </td>
                                        <td>
                                            {{ @$review->rating }}
                                            <?php /*for ($i=0;$i < round($rating->rating_value); $i++)
                                                 echo '&#9733;'; */
                                            ?>
                                        </td>
                                    </tr>
                                @endforeach
                            @endif
                        </tbody>
                    </table>
                </div>
            </div-->
            <div class="form-group">
                <div class="col-lg-offset-3 col-lg-6">
                    <a href="{{ route('main.provider.index') }}" class="btn btn-default">Back</a>
                </div>
            </div>
        </div>
    </div>
</section>
@stop
