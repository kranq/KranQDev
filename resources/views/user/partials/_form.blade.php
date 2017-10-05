<!--main content start-->
	<section class="panel">
			<header class="panel-heading">{!! @$add.' '. trans('main.user.title') !!}</header>
			<div class="panel-body">
                    <div class="prf-contacts sttng">
                        <h2>{!! trans('main.user.personal') !!}</h2>
                    </div>
					<div class="form-group">
                        {!! Form::label('fullname',trans('main.user.fullname'),array('class'=>'control-label col-lg-3 custom_required')) !!}
						<div class="col-lg-6">
                            {!! Form::text('fullname', @$user->fullname, array('class'=>'form-control', 'placeholder' => trans('main.user.enterusername'))) !!}
                            @if ($errors->has('fullname'))
                            <span class="help-block">
                                <strong>{{ $errors->first('fullname') }}</strong>
                            </span>
                            @endif
                        </div>
                    </div>
                    <div class="form-group">
                          {!! Form::label('profilepicture',trans('main.user.profilepicture'),array('class'=>'control-label col-lg-3')) !!}
                        <div class="col-lg-3">
                            <div class="form-group">
                                <div class="controls col-md-9">
                                    <div class="fileupload fileupload-new" data-provides="fileupload">
                                        <span class="btn btn-white btn-file">
                                        <span class="fileupload-new"><i class="fa fa-paper-clip"></i> Select file</span>
                                        <span class="fileupload-exists"><i class="fa fa-undo"></i> Change</span>
                                        {{ Form::file('profile_picture', array('class' => 'default', 'accept' => trans('main.image_file_extension'))) }}
                                        </span>
                                        <span class="fileupload-preview" style="margin-left:5px;"></span>
                                        <a href="#" class="close fileupload-exists" data-dismiss="fileupload" style="float: none; margin-left:5px;"></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-4 col-md-4 col-lg-4">
                            <i  data-original-title="{!! trans('main.image_upload_notes_title') !!}" data-content="{!! trans('main.image_upload_notes') !!}" data-placement="right" data-trigger="hover" class="fa fa-info-circle popovers" aria-hidden="true" data-html="true"></i>
                        </div>
                    </div>
					@if (@$amazonImgUpload)
					<div class="form-group">
                        <div class="col-lg-3 ">
                        </div>
                        <div class="col-lg-6">
                            <a href="#" >
                                <img src="{!! @$amazonImgUpload !!}" />
                            </a>
                        </div>
                    </div>
                    @elseif (file_exists(URL::to('../uploads/user/').@$user->profile_picture))
                    <div class="form-group">
                        <div class="col-lg-3 ">
                        </div>
                        <div class="col-lg-6">
                            <a href="#" >
                                <img src="{!! URL::to('../uploads/user') !!}/{!! @$user->profile_picture !!}"  alt="{!! @$user->profile_picture !!}" width="50px" height="50px" title="{!! @$user->profile_picture !!}" />
                            </a>
                        </div>
                    </div>
										@else
										<div class="form-group">
                        <div class="col-lg-3 ">
                        </div>
                        <div class="col-lg-6">
                            <a href="#" >
														</a>
                                <img src="{{ URL::to('/images') }}/noimage.jpg">
                        </div>
                    </div>
                    @endif
                    <div class="form-group">
                          {!! Form::label('address',trans('main.user.address'),array('class'=>'control-label col-lg-3')) !!}
    					<div class="col-lg-6">
                            {!! Form::textarea('address', @$user->address, array('class'=>'form-control textarea-height', 'placeholder' => trans('main.user.address'), 'rows' => '2', 'maxlength' => '1000')) !!}
                            @if ($errors->has('address'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('address') }}</strong>
                                </span>
                                @endif
						</div>
                    </div>
                    <div class="form-group">
                        {!! Form::label('mobileno',trans('main.user.mobileno'),array('class'=>'control-label col-lg-3 custom_required')) !!}
						<div class="col-lg-6">
                            {!! Form::text('mobile', @$user->mobile, array('class'=>'form-control', 'placeholder' => trans('main.user.mobileno'), 'maxlength' => '20', 'onkeypress'=>'checkAlphaNumericWithComma(event)')) !!}
                            @if ($errors->has('mobile'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('mobile') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>
                    <div class="form-group">
                            {!! Form::label('email',trans('main.user.email'),array('class'=>'control-label col-lg-3 custom_required')) !!}
                        <div class="col-lg-6">
                            {!! Form::email('email', @$user->email, array('class' => 'form-control dropdown-height', 'placeholder' => trans('main.user.email'))) !!}
                            @if ($errors->has('email'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('email') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>
                    <div class="form-group">
                            {!! Form::label('password',trans('main.user.password'),array('class'=>'control-label col-lg-3 custom_required')) !!}
                        <div class="col-lg-6">
                            {!! Form::password('password', array('class' => 'form-control', 'placeholder' => 'Enter Password')) !!}
                            @if ($errors->has('password'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('password') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>
                    <div class="form-group">
                            {!! Form::label('registermode',trans('main.user.registermode'),array('class'=>'control-label col-lg-3')) !!}
                        <div class="col-lg-6">
                            {!! Form::select('register_mode', @$registered_mode, @$user->register_mode, array('class' => 'form-control dropdown-height', 'placeholder' => trans('main.selected'))) !!}
                            @if ($errors->has('registermode'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('registermode') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>
                    <div class="form-group">
                            {!! Form::label('status',trans('main.user.status'),array('class'=>'control-label col-lg-3 custom_required')) !!}
						<div class="col-lg-6">
                            {!! Form::select('status', @$status,@$user->status, array('class' => 'form-control dropdown-height')) !!}
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
                            <a href="{!! route('main.user.index') !!}" class="btn btn-default" title="{!! trans('main.cancel') !!}">{!! trans('main.cancel') !!}</a>
						</div>
					</div>
				</div>
	</section>
<!--main content end-->
