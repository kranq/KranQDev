<section class="panel"> 
    <header class="panel-heading">{!! @$add.' '.trans('main.provider.title') !!}</header>
    <div class="panel-body">
        <div class="position-left">
            <div class="prf-contacts sttng">
                <h2> Basic Details</h2>
			</div>
            <div class="form-group">
                {!! Form::label('service_provider_name',trans('main.provider.name'),array('class'=>'control-label col-sm-3 custom_required')) !!}
                <div class="col-lg-6">
                    {!! Form::text('name_sp', @$provider->name_sp, array('class'=>'form-control', 'placeholder' => __(trans('main.placeholder'),['name' => trans('main.provider.name')]))) !!}
                      <!-- @if ($errors->has('name_sp'))
                    <span class="help-block">
                        <strong>{{ $errors->first('name_sp') }}</strong>
                    </span>
                    @endif -->
                </div>
            </div>
            <div class="form-group">
	            {!! Form::label('logo',trans('main.provider.logo'),array('class'=>'control-label col-sm-3 custom_required')) !!}
                <div class="col-lg-3">
                    {{-- Form::file('logo') --}}
                    <div class="form-group">
                        <div class="controls col-md-9">
                            <div class="fileupload fileupload-new" data-provides="fileupload">
                                <span class="btn btn-white btn-file">
                                <span class="fileupload-new"><i class="fa fa-paper-clip"></i> Select file</span>
                                <span class="fileupload-exists"><i class="fa fa-undo"></i> Change</span>
                                {{ Form::file('logo',array('class'=>'default', 'accept' => trans('main.image_file_extension'))) }}
                                </span>
                                <span class="fileupload-preview" style="margin-left:5px;"></span>
                                <a href="#" class="close fileupload-exists" data-dismiss="fileupload" style="float: none; margin-left:5px;"></a>
                            </div>
                           <!--  @if ($errors->has('logo'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('logo') }}</strong>
                                </span>
                            @endif -->
                        </div>
                    </div>
                    <!--div class="fileupload fileupload-new" data-provides="fileupload">
                        <div class="fileupload-new thumbnail" style="width: 200px; height: 150px;">
                            <img src="{!! trans('main.default_image_path') !!}" alt="No Image" />
                        </div>
                        <div class="fileupload-preview fileupload-exists thumbnail" style="max-width: 201px; max-height: 150px; line-height: 20px;"></div>
                    <div>
                       <span class="btn btn-white btn-file">
                       <span class="fileupload-new"><i class="fa fa-paper-clip"></i> Select image</span>
                       <span class="fileupload-exists"><i class="fa fa-undo"></i> Change</span>
                         {{ Form::file('logo',array('class'=>'default')) }}
                       </span>
                        </div>
                    </div -->
                </div>
                <div class="col-sm-3">
                    <i  data-original-title="{!! trans('main.image_upload_notes_title') !!}" data-content="{!! trans('main.image_upload_notes') !!}" data-placement="right" data-trigger="hover" class="fa fa-info-circle popovers" aria-hidden="true" data-html="true"></i>
                </div>
			</div>
			@if (@$amazonImgUpload)
			<div class="form-group">
                <div class="col-sm-3 control-label">
                </div>
                <div class="col-lg-3">
                        <a href="#" >
                            <img src="{!! @$amazonImgUpload !!}" />
                        </a>
                    </div>
                </div>
            </div>
            @elseif (@$provider->logo)
            <div class="form-group">
                <div class="col-sm-3 control-label">
                </div>
                <div class="col-lg-3">
                        <a href="#" >
                        @if($provider->logo)
                            <img src="{!! URL::to('../uploads/provider') !!}/{!! @$provider->logo !!}"  alt="{!! @$provider->logo !!}" title="{!! @$provider->logo !!}" />
                        @endif
                        </a>
                    </div>
                </div>
           
            @endif
            <div class="form-group">
                {!! Form::label('category_id',trans('main.provider.category'),array('class'=>'custom_required col-sm-3 control-label')) !!}
                <div class="col-lg-6">
                    {!!Form::select('category_id', $categories, @$provider->category_id, ['class' => 'form-control', 'placeholder' => 'Select', 'id' => 'category_id'])!!}
                   <!--  @if ($errors->has('category_id'))
                    <span class="help-block">
                        <strong>{{ $errors->first('category_id') }}</strong>
                    </span>
                    @endif -->
                </div>
            </div>
            <div class="form-group">
                {!! Form::label('service_id',trans('main.provider.services'),array('class'=>'control-label col-lg-3 custom_required')) !!}
                <div class="col-lg-6">
                    {{ Form::select('service_id[]', @$services, @$service, array('class' => 'populate select2-offscreen selectinput-width', 'multiple' => 'true', 'id' => 'e9')) }}
                 <!--   @if ($errors->has('service_id'))
                        <span class="help-block">
                            <strong>{{ $errors->first('service_id') }}</strong>
                        </span>
                    @endif -->
                </div>
            </div>
            <div>
            </div>
            <div class="form-group">
                {!! Form::label('city',trans('main.provider.city'),array('class'=>'custom_required col-sm-3 control-label')) !!}
                <div class="col-lg-6">
                    {!! Form::select('city', $cities, @$provider->city, ['class' => 'form-control', 'placeholder' => 'Select'])!!}
                   <!--  @if ($errors->has('city'))
                    <span class="help-block">
                        <strong>{{ $errors->first('city') }}</strong>
                    </span>
                    @endif -->
                </div>
            </div>
            <div class="form-group">
                <div class="">
                    {!! Form::label('location_id',trans('main.provider.locality'),array('class'=>'custom_required col-sm-3 control-label')) !!}
                      </div>
                      <div class="col-lg-6">
                    {!!Form::select('location_id', $localities, @$provider->location_id, ['class' => 'form-control', 'placeholder' => 'Select'])!!}
                        <!-- @if ($errors->has('location_id'))
                        <span class="help-block">
                            <strong>{{ $errors->first('location_id') }}</strong>
                        </span>
                        @endif -->
                    </div>
            </div>
            <div class="form-group">
                    {!! Form::label('address',trans('main.provider.address'),array('class'=>'control-label col-sm-3 ')) !!}
                  <div class="col-lg-6">
                      {!! Form::textarea('address', @$provider->address, array('class'=>'form-control', 'placeholder' => __(trans('main.placeholder'),['name' => trans('main.provider.address')]), 'rows'=>'3', 'maxlenght' => '255')) !!}
                  </div>
               </div>
               <div class="form-group">
                    {!! Form::label('short_description',trans('main.provider.short_description'),array('class'=>'control-label col-sm-3 custom_required')) !!}
                  <div class="col-lg-6">
                      {!! Form::textarea('short_description', @$provider->short_description, array('class'=>'form-control', 'placeholder' => __(trans('main.placeholder'),['name' => trans('main.provider.short_description')]), 'rows'=>'3', 'maxlenght' => '255')) !!}
                  <!--   @if ($errors->has('short_description'))
                        <span class="help-block">
                            <strong>{{ $errors->first('short_description') }}</strong>
                        </span>
                    @endif -->
                  </div>
               </div>
                <div class="form-group">
                    {!! Form::label('googlemap_latitude',trans('main.provider.coordinates'),array('class'=>'control-label col-lg-3')) !!}
					<div class=" col-md-3">
                      {!! Form::text('googlemap_latitude', @$provider->googlemap_latitude, array('class'=>'form-control', 'placeholder' => __(trans('main.placeholder'),['name' => trans('main.provider.latitude')]))) !!}
                       <!--  @if ($errors->has('googlemap_latitude'))
                            <span class="help-block">
                                <strong>{{ $errors->first('googlemap_latitude') }}</strong>
                            </span>
                        @endif -->
                        </div>
                        <div class=" col-md-3">
                          {!! Form::text('googlemap_longitude', @$provider->googlemap_longitude, array('class'=>'form-control', 'placeholder' => __(trans('main.placeholder'),['name' => trans('main.provider.longitude')]))) !!}
                          <!-- @if ($errors->has('googlemap_longitude'))
                            <span class="help-block">
                                    <strong>{{ $errors->first('googlemap_longitude') }}</strong>
                                  </span>
                              @endif -->
                    </div>
				</div>
                <div class="form-group">
                    {!! Form::label('status_owner_manager	',trans('main.provider.status_owner_manager'),array('class'=>'control-label col-lg-3 custom_required')) !!}
					<div  class="col-lg-6" >
                        {{ Form::radio('status_owner_manager', 'Owner', false, ['class' => 'field', 'id' => 'status_owner_manager_yes']) }} Yes
                        {{ Form::radio('status_owner_manager', 'Manager', true, ['class' => 'field', 'id' => 'status_owner_manager_no']) }} No
                    </div>
                   <!--  @if ($errors->has('status_owner_manager'))
                        <span class="help-block">
                            <strong>{{ $errors->first('status_owner_manager') }}</strong>
                        </span>
                    @endif -->
				</div>
                <div class="Manager_yes">
                <div class="form-group">
                    {!! Form::label('owner_name',trans('main.provider.owner_name'),array('class'=>'control-label col-lg-3')) !!}
                    <div  class="col-lg-6" >
                          {{ Form::text('owner_name', @$provider->owner_name, ['class' => 'form-control','maxlength' => '100']) }}
                    </div>
                </div>
                <div class="form-group">
                    {!! Form::label('owner_designation',trans('main.provider.owner_designation'),array('class'=>'control-label col-lg-3')) !!}
                    <div  class="col-lg-6">
                          {{ Form::text('owner_designation', @$provider->owner_designation, ['class' => 'form-control','maxlength' => '100']) }}
                    </div>
                </div>
                <div class="form-group">
                    {!! Form::label('owner_phone',trans('main.provider.owner_phone'),array('class'=>'control-label col-lg-3')) !!}
                    <div  class="col-lg-6" >
                          {{ Form::text('owner_phone', @$provider->owner_phone, ['class' => 'form-control','maxlength' => '30', 'onkeypress'=>'checkAlphaNumericWithComma(event)']) }}
                    </div>
                </div>
                </div>
                <div class="form-group">
                    {!! Form::label('working_days ',trans('main.provider.working_days'),array('class'=>'control-label col-sm-3 custom_required')) !!}
                    <div class="col-sm-9 zero">
                        <!--div class="col-sm-2 check4 checkbox">
                            <label class="checkbox-inline">
                                 <input type="checkbox" name="selecctall"  id="selecctall" value="" tabindex="9">
                                 Check All </label>
                         </div-->
                        <div class="col-sm-4 check1 checkbox checkbox_type">
                            <?php //foreach ($working_days as $key => $row): ?>
                                 <!--label class="checkbox-inline"></label-->
                            {{ Form::checkbox('working_days', '1', @$selected_working_days,['class' => 'checkbox_type']) }} <?php echo 'Mon to Fri'; ?>
                            {{-- Form::checkbox('working_days[]', @$key, in_array(@$key, @$selected_working_days), ['class' => 'checkbox_type']) --}}
                            <?php //echo $row; ?>
                            <?php //endforeach; ?>
                         </div>
                         <div class="col-sm-4 check1 checkbox checkbox_type">
                            {{ Form::checkbox('working_saturdays', '2', @$selected_working_saturdays,['class' => 'saturday', 'id' => "WorkingSaturday"]) }} <?php echo 'Saturday'; ?>
                         </div>
                         <div class="col-sm-4 check1 checkbox checkbox_type checkbox_align">
                            {{ Form::checkbox('working_sundays', '3', @$selected_working_sundays,['class' => 'sunday', 'id' => "WorkingSunday"]) }} <?php echo 'Sunday'; ?>
                         </div>
                      <!--   @if ($errors->has('working_days'))
                            <span class="help-block">
                                <strong>{{ $errors->first('working_days') }}</strong>
                            </span>
                        @endif -->
                    </div>
                </div>
                <div class="form-group">
                      {!! Form::label('open_close_hours	',trans('main.provider.open_close_hours'),array('class'=>'control-label col-lg-3 custom_required')) !!}

                      <div class="col-lg-9 zero">
                      <div class="row">
                        <div class="col-md-4 padding_zero">
						  <div class="col-md-6">
                                {!!Form::select('opening_hrs', $opening_hrs, @$provider->opening_hrs, ['class' => 'form-control', 'placeholder' => 'Select'])!!}
                               <!--  @if ($errors->has('opening_hrs'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('opening_hrs') }}</strong>
                                    </span>
                                @endif -->
							</div>
                            <div class="col-md-6">
                                {!!Form::select('closing_hrs', $closing_hrs, @$provider->closing_hrs, ['class' => 'form-control', 'placeholder' => 'Select'])!!}
                               <!--  @if ($errors->has('closing_hrs'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('closing_hrs') }}</strong>
                                    </span>
                                @endif -->
        					</div>
                        </div>
                         <div class="col-md-4 padding_zero" id="WorkingSaturdayHours">
                          <div class="col-md-6 padding_left_zero">
                                {!!Form::select('saturday_opening_hrs', @$opening_hrs, @$provider->saturday_opening_hrs, ['class' => 'form-control', 'placeholder' => 'Select'])!!}
                            </div>
                            <div class="col-md-6">
                                {!!Form::select('saturday_closing_hrs', @$closing_hrs, @$provider->saturday_closing_hrs, ['class' => 'form-control', 'placeholder' => 'Select'])!!}
                            </div>
                        </div>
                         <div class="col-md-4 padding_zero" id="WorkingSundayHours">
                          <div class="col-md-6 padding_left_zero">
                                {!! Form::select('sunday_opening_hrs', @$opening_hrs, @$provider->sunday_opening_hrs, ['class' => 'form-control', 'placeholder' => 'Select']) !!}
                            </div>
                            <div class="col-md-6">
                                {!!Form::select('sunday_closing_hrs', @$closing_hrs, @$provider->sunday_closing_hrs, ['class' => 'form-control', 'placeholder' => 'Select'])!!}
                            </div>
                        </div>
					</div>
                    </div>
				</div>
                    <div class="form-group">
                        {!! Form::label('phone',trans('main.provider.phone'),array('class'=>'control-label col-sm-3 custom_required')) !!}
                            <div class="col-lg-6">
                                {!! Form::text('phone', @$provider->phone, array('class'=>'form-control', 'onkeypress'=>'checkAlphaNumericWithComma(event)', 'placeholder' => __(trans('main.placeholder'),['name' => trans('main.provider.phone')]))) !!}
                              <!--   @if ($errors->has('phone'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('phone') }}</strong>
                                </span>
                                @endif -->
                            </div>
                    </div>
                        <div class="form-group">
                            {!! Form::label('website_link',trans('main.provider.website_link'),array('class'=>'control-label col-sm-3')) !!}
                            <div class="col-lg-6">
                                {!! Form::url('website_link', @$provider->website_link, array('class'=>'form-control', 'placeholder' => __(trans('main.placeholder'),['name' => trans('main.provider.website_link')]))) !!}
                            </div>
                        </div>
                        <div class="prf-contacts sttng">
							<h2> Account Details</h2>
						</div>
                        <div class="form-group">
                                {!! Form::label('email',trans('main.provider.email'),array('class'=>'control-label col-lg-3 custom_required')) !!}
                            <div class="col-lg-6">
                                {!! Form::email('email', @$provider->email, array('class' => 'form-control dropdown-height', 'placeholder' => __(trans('main.placeholder'),['name' => trans('main.provider.email')]))) !!}
                               <!--  @if ($errors->has('email'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                @endif -->
                            </div>
                        </div>
                        <div class="form-group">
                                {!! Form::label('password',trans('main.provider.password'),array('class'=>'control-label col-lg-3 custom_required')) !!}
                            <div class="col-lg-6">
                                {!! Form::password('password', array('class' => 'form-control', 'placeholder' =>  __(trans('main.placeholder'),['name' => trans('main.provider.password')]))) !!}
                               <!--  @if ($errors->has('password'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                @endif -->
                            </div>
                        </div>
            <div class="form-group">
                        {!! Form::label('status',trans('main.status'),array('class'=>'custom_required control-label col-lg-3')) !!}
                      <div class="col-lg-6">
                        {!! Form::select('status',$all_status,@$provider->status, array('class' => 'form-control dropdown-height' )) !!}
                       <!--  @if ($errors->has('status'))
                        <span class="help-block">
                            <strong>{{ $errors->first('status') }}</strong>
                        </span>
                        @endif -->
                    </div>
            </div>

            <div class="form-group">
                <div class="col-lg-offset-3 col-lg-6">
                    <button type="submit" class="btn btn-primary">{!! @$btn !!}</button>
                    <a href="{!! route('main.provider.index') !!}" class="btn btn-default">{!! trans('main.cancel') !!}</a>
                </div>
            </div>
        </div>

</section>

<!--main content end-->
@section('page_js')
<script src="https://ajax.aspnetcdn.com/ajax/jquery.validate/1.11.1/jquery.validate.js"></script>
<script type="text/javascript">
    var token = "{!! csrf_token() !!}";
    $('.Manager_yes').hide();
    $('#WorkingSundayHours').hide();
    $('#WorkingSaturdayHours').hide();
    /*$("#selecctall").change(function () {
        $("input:checkbox").prop('checked', $(this).prop("checked"));
    });*/
    $('#WorkingSaturday').on('change', function(){
        $('#WorkingSaturdayHours').toggle();
    });
    $('#WorkingSunday').on('change', function(){
        $('#WorkingSundayHours').toggle();
    });
    $('#status_owner_manager_yes').on('change', function() {
            $('.Manager_yes').show();
    });
    $('#status_owner_manager_no').on('change', function() {
            $('.Manager_yes').hide();
    });

    // To fetch the Category Service Details
    $("#category_id").on('change', function() {
        var category_id = $("#category_id").val();
        $.ajax({
            type: "POST",
            url: "{{ URL::to('provider/cagetoryservices') }}",
            data: {
                    '_token': token,
                    'id' : category_id,
                },
            success: function (response) {
                $("#e9").html(response);
               // you will get response from your php page (what you echo or print)
            },
            error: function(jqXHR, textStatus, errorThrown) {
               console.log(textStatus, errorThrown);
            }
        });
    });

    //To load the WorkingSaturday value
    var setSaturday = $("#WorkingSaturday").val();
    var boxesSaturday    = $('input[name=working_saturdays]:checked').length;
       if (setSaturday == 2 && boxesSaturday > 0) {
                 $("#WorkingSaturdayHours").show();
            } else {
                $("#WorkingSaturdayHours").hide();
            }
    // To Show and Hide the Open/closing hours for Saturday when click checkbox
    $(function () {
        $("#WorkingSaturday").click(function () {
            var boxes_click  = $('input[name=working_saturdays]:checked').length;
            if (setSaturday == 2 && boxes_click_saturday > 0) {
                 $("#WorkingSaturdayHours").hide();
            } else {
                $("#WorkingSaturdayHours").show();
            }
        });
    });

    // To Load the WorkingSunday value
    var setSunday = $("#WorkingSunday").val();
    var boxesSunday    = $('input[name=working_sundays]:checked').length;
       if (setSunday == 3 && boxesSunday > 0) {
                 $("#WorkingSundayHours").show();
            } else {
                $("#WorkingSundayHours").hide();
            }
    // To Show and Hide the Open/closing hours for Sunday when click checkbox
    $(function () {
        $("#WorkingSunday").click(function () {
            var boxes_click_sunday  = $('input[name=working_sundays]:checked').length;
            if (setSunday == 3 && boxes_click_sunday > 0) {
                 $("#WorkingSundayHours").hide();
            } else {
                $("#WorkingSundayHours").show();
            }
        });
    });


    //To Show the client side validation
     $("#form").validate({
        ignore: ".ignore",
        rules: {
            name_sp:"required",
             logo:"required",
             category_id:"required",
             'service_id[]':'required',
             city:'required',
             location_id:'required',
             short_description:'required',
             status_owner_manager:'required',
             working_days:'required',
             opening_hrs:'required',
             closing_hrs:'required',
              phone:'required',
               email:'required',
                password:'required',

           
                   },
        

        messages:{
            name_sp:"Service Provider Name field is required",
               logo:" The logo field is required" ,
               category_id:" Choose Category field is required",
               'service_id[]':" Choose service field is required",
               city:" Choose City Name is required",
               location_id:" Choose Locality field is required",
               short_description:" The short description field is required",
               status_owner_manager:" The logo field is required",
               working_days:" The logo field is required",
               opening_hrs:" Choose Opening Hourse field is required",
               closing_hrs:" Choose Closing Hourse field is required",
               phone:" Phone Number field is required",
               email:" Email field is required",
               password:" Password field is required",
                            
        },
          
            //To check during tabing itself
            onkeyup: function(element) {
            this.element(element);
            //console.log('onkeyup fired');
            },
            onfocusout: function(element) {
            this.element(element);
            //console.log('onfocusout fired');
            }

  });
</script>
@endsection
