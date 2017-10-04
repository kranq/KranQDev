<!--main content start-->
<section class="panel">
	<header class="panel-heading">{!! @$add.' '.trans('main.category.title') !!}</header>
	<div class="panel-body">
        <div class="form-group">
            {!! Form::label('category_name',trans('main.category.category_name'),array('class'=>'control-label col-lg-3 custom_required')) !!}
            <div class="col-lg-6">
                {!! Form::text('category_name', @$category->category_name, array('class'=>'form-control', 'placeholder' => 'Enter Category Name', 'maxlength'=>'200', 'id' => 'CategoryNameId')) !!}
                @if ($errors->has('category_name'))
                <span class="help-block">
                    <strong>{{ $errors->first('category_name') }}</strong>
                </span>
                @endif
            </div>
        </div>
        <div class="form-group">
              {!! Form::label('category_image',trans('main.category.category_image'),array('class'=>'control-label col-lg-3')) !!}
            <div class="col-lg-4">
                <div class="form-group">
                    <div class="controls col-md-9">
                        <div class="fileupload fileupload-new" data-provides="fileupload">
                            <span class="btn btn-white btn-file">
                            <span class="fileupload-new"><i class="fa fa-paper-clip"></i> Select file</span>
                            <span class="fileupload-exists"><i class="fa fa-undo"></i> Change</span>
                            {{ Form::file('category_image', array('class' => '', 'accept' => trans('main.file_extension'))) }}
                            {{-- Form::file('logo',array('class'=>'default')) --}}
                            </span>
							@if ($errors->has('category_image'))
								<span class="help-block">
									<strong>{{ $errors->first('category_image') }}</strong>
								</span>
							@endif
                            <span class="fileupload-preview" style="margin-left:5px;"></span>
                            <a href="#" class="close fileupload-exists" data-dismiss="fileupload" style="float: none; margin-left:5px;"></a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3">
                <i  data-original-title="{!! trans('main.image_upload_notes_title') !!}" data-content="{!! trans('main.image_upload_notes') !!}" data-placement="right" data-trigger="hover" class="fa fa-info-circle popovers" aria-hidden="true" data-html="true"></i>
            </div>
        </div>
		@if (@$amazonImgUpload)
			<div class="form-group">
				<div class="col-lg-3 ">
				</div>
				<div class="col-lg-6">
					<a href="#" >
						<img src="{!! $amazonImgUpload !!}" />
					</a>
				</div>
			</div>
        @else
        <div class="form-group">
            <div class="col-lg-3 ">
            </div>
            <div class="col-lg-6">
                <a href="#" >
                @if(@$category->category_image)
                    <img src="{!! URL::to('../uploads/category') !!}/{!! @$category->category_image !!}"  alt="{!! @$category->category_image !!}" title="{!! @$category->category_image !!}" />
                @endif
                </a>
            </div>
        </div>
        @endif
        <div class="form-group">
            {!! Form::label('service_id',trans('main.category.services'),array('class'=>'control-label col-lg-3 custom_required')) !!}
            <div class="col-lg-6">
                {!! Form::select('service_id[]', @$services, @$service, array('class' => 'populate select2-offscreen', 'multiple' => 'true', 'id' => 'e9', 'style' => 'width:485px')) !!}
               @if ($errors->has('service_id'))
                    <span class="help-block">
                        <strong>{{ $errors->first('service_id') }}</strong>
                    </span>
                @endif
            </div>
        </div>
        <div class="form-group">
            {!! Form::label('order_by',trans('main.category.order_by'),array('class'=>'control-label col-lg-3 custom_required')) !!}
            <div class="col-lg-6">
                {!! Form::selectRange('order_by', 1, 10, @$category->order_by, array('class' => 'form-control dropdown-height', 'placeholder' => trans('main.selected'))) !!}
                @if ($errors->has('order_by'))
                    <span class="help-block">
                        <strong>{{ $errors->first('order_by') }}</strong>
                    </span>
                @endif
            </div>
        </div>
		<div class="form-group">
            {!! Form::label('status',trans('main.category.status'),array('class'=>'control-label col-lg-3 custom_required')) !!}
            <div class="col-lg-6">
                {!! Form::select('status', @$status, @$category->status, array('class' => 'form-control dropdown-height')) !!}
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
                <a href="{!! route('main.category.index') !!}" class="btn btn-default" title="{!! trans('main.cancel') !!}">
                {!! trans('main.cancel') !!}
                </a>
			</div>
		</div>
	</div>
</section>
<!--main content end-->
@section('page_js')
<script type="text/javascript">
	$('#CategoryNameId').on('keypress', function (event) {
	    var regex = new RegExp("^[a-zA-Z0-9]+$");
	    var key = String.fromCharCode(!event.charCode ? event.which : event.charCode);
	    if (!regex.test(key)) {
	       event.preventDefault();
	       return false;
	    }
	});
	</script>
@endsection
