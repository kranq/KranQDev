  <section class="panel form-horizontal">
			<header class="panel-heading">{!! @$add.' '.trans('main.city.title') !!}</header>
			<div class="panel-body">
					<div class="position-left">

          <div class="form-group">
                        <div class="col-sm-3 control-label">
                            {!! Form::label('city_code',trans('main.city.city_code'),array('class'=>'custom_required')) !!}
                        </div>
                        <div class="col-lg-6">
                          {!! Form::text('city_code', @$city->city_code, array('class'=>'form-control', 'placeholder' => 'Enter city Code', 'tabindex' => '1')) !!}
                            @if ($errors->has('city_code'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('city_code') }}</strong>
                                </span>
                            @endif
                        </div>
                </div>
							<div class="form-group">
                        <div class="col-sm-3 control-label">
                            {!! Form::label('city_name',trans('main.city.city_name'),array('class'=>'custom_required')) !!}
                        </div>
												<div class="col-lg-6">
                          {!! Form::text('city_name', @$city->city_name, array('class'=>'form-control', 'placeholder' => 'Enter city Name', 'tabindex' => '1')) !!}
                            @if ($errors->has('city_name'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('city_name') }}</strong>
                                </span>
                            @endif
												</div>
                </div>
             <div class="form-group">
                      <div class="col-sm-3 control-label">
                          {!! Form::label('status',trans('main.city.status'),array('class'=>'custom_required')) !!}
                      </div>
                      <div class="col-lg-6">
                          {!! Form::select('status', array('Active' => 'Active', 'Inactive' => 'Inactive'),@$city->status, array('class' => 'form-control dropdown-height')) !!}
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
                      <a href="{!! route('main.city.index') !!}" class="btn btn-default" tabidex='19' title="{!! trans('main.cancel') !!}">{!! trans('main.cancel') !!}</a>
									</div>
							</div>
					</div>
			</div>
	</section>
<!--main content end-->
@section('page_js')
  <script type="text/javascript">
      $(function() {
        $('#dob').datetimepicker();
    });
  </script>
@endsection
