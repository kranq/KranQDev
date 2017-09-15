<!--main content start-->
<section class="panel">
	<header class="panel-heading">{!! @$add.' '.trans('main.address.title') !!}</header>
	<div class="panel-body">
        <div class="form-group">
            {!! Form::label('address',trans('main.address.title'),array('class'=>'control-label col-lg-3 custom_required')) !!}
            <div class="col-lg-6">
                {!! Form::textarea('address', @$address->address, array('class'=>'form-control', 'placeholder' => 'Enter Address', 'rows' => '6')) !!}
                @if ($errors->has('address'))
                <span class="help-block">
                    <strong>{{ $errors->first('address') }}</strong>
                </span>
                @endif
            </div>
        </div>
        <div class="form-group">
            {!! Form::label('phone',trans('main.address.phone'),array('class'=>'control-label col-lg-3 custom_required')) !!}
            <div class="col-lg-6">
                {!! Form::text('phone', @$address->phone, array('class' => 'form-control')) !!}
               @if ($errors->has('phone'))
                    <span class="help-block">
                        <strong>{{ $errors->first('phone') }}</strong>
                    </span>
                @endif
            </div>
        </div>
        <div class="form-group">
            {!! Form::label('phone',trans('main.address.email'),array('class'=>'control-label col-lg-3 custom_required')) !!}
            <div class="col-lg-6">
                {!! Form::text('email', @$address->email, array('class' => 'form-control')) !!}
               @if ($errors->has('email'))
                    <span class="help-block">
                        <strong>{{ $errors->first('email') }}</strong>
                    </span>
                @endif
            </div>
        </div>
		<div class="form-group">
			<div class="col-lg-offset-3 col-lg-6">
				<button type="submit" class="btn btn-primary">{!! @$btn !!}</button>
                <a href="{!! route('main.address.index') !!}" class="btn btn-default">
                {!! trans('main.cancel') !!}
                </a>
			</div>
		</div>
	</div>
</section>
<!--main content end-->
