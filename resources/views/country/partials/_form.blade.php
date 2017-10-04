
  <section class="panel form-horizontal">
			<header class="panel-heading">{!! trans('main.country.title') !!}</header>
			<div class="panel-body">
					<div class="position-left">

          <div class="form-group">
                        <div class="col-sm-3 control-label">
                            {!! Form::label('country_code',trans('main.country.country_code'),array('class'=>'custom_required')) !!}
                        </div>
                        <div class="col-lg-6">
                          {!! Form::text('country_code', @$country->country_code, array('class'=>'form-control', 'placeholder' => 'Enter Country Code', 'tabindex' => '1')) !!}
                            @if ($errors->has('country_code'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('country_code') }}</strong>
                                </span>
                            @endif
                        </div>
                </div>
							<div class="form-group">
                        <div class="col-sm-3 control-label">
                            {!! Form::label('country_name',trans('main.country.country_name'),array('class'=>'custom_required')) !!}
                        </div>
												<div class="col-lg-6">
                          {!! Form::text('country_name', @$country->country_name, array('class'=>'form-control', 'placeholder' => 'Enter Country Name', 'tabindex' => '1')) !!}
                            @if ($errors->has('country_name'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('country_name') }}</strong>
                                </span>
                            @endif
												</div>
                </div>
             <div class="form-group">
                      <div class="col-sm-3 control-label">
                          {!! Form::label('status',trans('main.country.status'),array('class'=>'custom_required')) !!}
                      </div>
                      <div class="col-lg-6">
                          {!! Form::select('status', array('Active' => 'Active', 'Inactive' => 'Inactive'),@$category->status, array('class' => 'form-control dropdown-height', 'placeholder' => trans('main.selected'))) !!}
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
                      <a href="{!! route('main.country.index') !!}" class="btn btn-default" tabidex='19' title="{!! trans('main.cancel') !!}">{!! trans('main.cancel') !!}</a>
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
