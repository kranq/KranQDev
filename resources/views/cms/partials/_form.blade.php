  <section class="panel form-horizontal">
			<header class="panel-heading">{!! @$add.' '.trans('main.cms.header_title') !!}</header>
			<div class="panel-body">
					<div class="position-left">

          <div class="form-group">
                        <div class="col-sm-3 control-label">
                            {!! Form::label('title',trans('main.cms.title'),array('class'=>'custom_required')) !!}
                        </div>
                        <div class="col-lg-6">
                          {!! Form::text('title', @$cms->title, array('class'=>'form-control', 'placeholder' => 'Enter Title', 'tabindex' => '1')) !!}
                            @if ($errors->has('title'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('title') }}</strong>
                                </span>
                            @endif
                        </div>
                </div>
							<div class="form-group">
                        <div class="col-sm-3 control-label">
                            {!! Form::label('description',trans('main.cms.description'),array('class'=>'custom_required')) !!}
                        </div>
												<div class="col-lg-6">
                          {!! Form::textarea('description', @$cms->description, array('class'=>'form-control ckeditor', 'placeholder' => 'Enter Description', 'tabindex' => '2')) !!}
                            @if ($errors->has('description'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('description') }}</strong>
                                </span>
                            @endif
												</div>
                </div>

							<div class="form-group">
									<div class="col-lg-offset-3 col-lg-6">
											<button type="submit" class="btn btn-primary" title="{!! @$btn !!}">{!! @$btn !!}</button>
                      <a href="{!! route('main.cms.index') !!}" class="btn btn-default" tabidex='19' title="{!! trans('main.cancel') !!}">{!! trans('main.cancel') !!}</a>
									</div>
							</div>
					</div>
			</div>
	</section>
