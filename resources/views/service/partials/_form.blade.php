<section class="panel form-horizontal">
    <header class="panel-heading">{!! @$add.' '.trans('main.service.title') !!}</header>
    <div class="panel-body">
        <div class="position-left">

            <div class="form-group">
                <div class="col-sm-3 control-label">
                    {!! Form::label('service_name',trans('main.service.service_name'),array('class'=>'custom_required')) !!}
                </div>
                <div class="col-lg-6">
                    {!! Form::text('service_name', @$service->service_name, array('class'=>'form-control', 'placeholder' => 'Enter Service Name', 'tabindex' => '1')) !!}
                    @if ($errors->has('service_name'))
                    <span class="help-block">
                        <strong>{{ $errors->first('service_name') }}</strong>
                    </span>
                    @endif
                </div>
            </div>
            <div class="form-group">
                <div class="col-sm-3 control-label">
                    {!! Form::label('status',trans('main.service.status'),array('class'=>'custom_required')) !!}
                </div>
                <div class="col-lg-6">
                    {!! Form::select('status', array('Active' => 'Active', 'Inactive' => 'Inactive'),@$service->status, array('class' => 'form-control dropdown-height')) !!}
                    @if ($errors->has('status'))
                    <span class="help-block">
                        <strong>{{ $errors->first('status') }}</strong>
                    </span>
                    @endif
                </div>
            </div>
            <div class="form-group">
                <div class="col-lg-offset-3 col-lg-6">
                    <button type="submit" class="btn btn-primary" title="{!! @$btn !!}">{!! @$btn !!}</button>
                    <a href="{!! route('main.service.index') !!}" class="btn btn-default" tabidex='19' title="{!! trans('main.cancel') !!}">{!! trans('main.cancel') !!}</a>
                </div>
            </div>
        </div>
    </div>
</section>
<!--main content end-->
