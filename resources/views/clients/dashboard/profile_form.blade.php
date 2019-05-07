
<style type="text/css">
.firstDiv{
    margin-top: 20px;
    margin-left: 20px;
    margin-right: 20px;
}
.secDiv{
    margin-top: 20px;
    margin-bottom: 20px;
}
.brd{
    border: 1px solid #ddd;
    background-color: #fff;
    padding: 0px;
    padding-bottom: 20px;
    padding-right: 20px;
}
.sample_head{
    padding-left: 20px;
}
.mrg_top{
    margin-top: -42px;
}
.send {
    background-color: #82cd49;
    border: 0 none;
    border-radius: 0;
    color: #fff;
    padding: 10px 18px;
}
.secDiv.brd .row {
    margin: 0;
}
.file-upload {
    position: relative;
    display: inline-block;
}

.file-upload__label {
  display: block;
  padding: 1em 2em;
  color: #fff;
  background: #626165;
  border-radius: .4em;
  transition: background .3s;

  &:hover {
     cursor: pointer;
     background: #000;
  }
}

.file-upload__input {
    position: absolute;
    left: 0;
    top: 0;
    right: 0;
    bottom: 0;
    font-size: 1;
    width:0;
    height: 60%;
    opacity: 0;
}
</style>
<div class="brd">
<div class="firstDiv">
    <div class="row no-margin left">
            <div class="msg center-block text-center">
                <div class="col-md-12">
                @if(isset(Auth::user()->image) && !empty(Auth::user()->image))
                    {{Html::image(AppHelper::path('uploads/user/')->size('100x100')->getImageUrl(Auth::user()->image),'User Photo',array("class"=>"img-circle",'id'=>'profile_image','height'=>'100','width'=>'100'))}}
                @else
                    {{Html::image(AppHelper::size('100x100')->getDefaultImage(),'User Photo',array("class"=>"img-circle staff",'id'=>'profile_image','height'=>'100','width'=>'100'))}}
                @endif
                <br>
                </div>
                    {{--   {!! Form::file('image', ['onchange' => 'loadFile(event)', 'accept' => 'image/*','id'=>'upload_profile_pic',"class"=>"inputfile"] ) !!} --}}
              <br>
              <br>
                <div class="file-upload">
                <br>
                <label for="upload" class="file-upload__label">Upload</label>
                <input id="upload" class="file-upload__input" type="file" name="image" id='upload_profile_pic' onchange="loadFile(event)">
                </div>
                <img id="output"/>
                {!! ($errors->has('image') ? $errors->first('image', '<p class="text-danger">:message</p>') : '') !!}
            </div>
    </div>
    <div class="row">
    <div class="col-lg-4 ">
        <div class="form-group {{ $errors->has('first_name') ? 'has-error' : ''}}">
            {!! Html::decode(Form::label('first_name', 'First name:<span class="has-stik">*</span>', ['class' => 'col-sm-12  '])) !!}
            <div class="col-sm-12">
                {!! Form::text('first_name', null, ['class' => 'form-control','placeholder'=> 'First name', 'id' => 'first_name']) !!}
                {!! ($errors->has('first_name') ? $errors->first('first_name', '<p class="text-danger">:message</p>') : '') !!}
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="form-group {{ $errors->has('last_name') ? 'has-error' : ''}}">
            {!! Html::decode(Form::label('last_name', 'Last name:<span class="has-stik">*</span>', ['class' => 'col-sm-12 '])) !!}
            <div class="col-sm-12">
                {!! Form::text('last_name', null, ['class' => 'form-control','placeholder'=>'Last name']) !!}
                {!! ($errors->has('last_name') ? $errors->first('last_name', '<p class="text-danger">:message</p>') : '') !!}
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="form-group {{ $errors->has('middle_name') ? 'has-error' : ''}}">
            {!! Html::decode(Form::label('middle_name', 'Middle name:', ['class' => 'col-sm-12 '])) !!}
            <div class="col-sm-12">
                {!! Form::text('middle_name', null, ['class' => 'form-control','placeholder'=>'Middle name']) !!}
                {!! ($errors->has('middle_name') ? $errors->first('middle_name', '<p class="text-danger">:message</p>') : '') !!}
            </div>
        </div>
    </div>
    </div>
<div class="row">
    <div class="col-lg-4">
        <div class="form-group {{ $errors->has('email') ? 'has-error' : ''}}">
            {!! Html::decode(Form::label('email', 'Email:<span class="has-stik">*</span>', ['class' => 'col-sm-12 '])) !!}
            <div class="col-sm-12">
                {!! Form::text('email', null, ['class' => 'form-control','placeholder'=>'Email', 'id' => 'email', 'readonly' => 'readonly']) !!}
                {!! ($errors->has('email') ? $errors->first('email', '<p class="text-danger">:message</p>') : '') !!}
            </div>
        </div>
    </div>

  {{--   <div class="col-lg-4">
        <div class="form-group {{ $errors->has('mobile_no') ? 'has-error' : ''}}">
            {!! Html::decode(Form::label('mobile_no', 'Mobile No: ', ['class' => ' col-md-12'])) !!}
            <div class="col-md-12">
                {!! Form::text('mobile_no', null, ['class' => 'form-control','placeholder'=> 'Mobile No', 'id' => 'mobile_no']) !!}
                {!! ($errors->has('mobile_no') ? $errors->first('mobile_no', '<p class="text-danger">:message</p>') : '') !!}
            </div>
        </div>
    </div> --}}
   {{--  <div class="col-lg-4">
        <div class="form-group {{ $errors->has('date_of_birth') ? 'has-error' : ''}}">
            {!! Html::decode(Form::label('date_of_birth', trans('comman.date_of_birth') . ': ', ['class' => ' col-md-12'])) !!}
            <div class="col-md-12">
                {!! Form::text('date_of_birth', null, ['class' => 'form-control','placeholder'=> trans('comman.date_of_birth'), 'id' => 'date_of_birth']) !!}
                {!! ($errors->has('date_of_birth') ? $errors->first('date_of_birth', '<p class="text-danger">:message</p>') : '') !!}
            </div>
        </div>
    </div> --}}
{{--      <div class="col-lg-4 ">
        <div class="form-group {{ $errors->has('country_id') ? 'has-error' : ''}}">
        {!!  Html::decode(Form::label('country_id', trans("comman.country"). ':<span class="has-stik">*</span>', ['class' => 'col-md-12'])) !!} --}}
            {{-- @if(Sentinel::getUser()->hasAccess(['countries.create'])) --}}
       {{--          <div class="col-sm-12">

                    {!! Form::select('country_id',array( "" =>trans("comman.select_country"))+$countries, null, ['class' => 'form-control col-md-10']) !!}
                    {!! ($errors->has('country_id') ? $errors->first('country_id', '<p class="text-danger">:message</p>') : '') !!}

                </div> --}}


{{--     </div>
    </div> --}}
    <div class="row">
    <div class="col-lg-4 ">
        <div class="form-group {{ $errors->has('timezone') ? 'has-error' : ''}}">
        {!!  Html::decode(Form::label('timezone', trans("comman.location_timezone"). ':<span class="has-stik">*</span>', ['class' => 'col-md-12'])) !!}
            {{-- @if(Sentinel::getUser()->hasAccess(['countries.create'])) --}}
                <div class="col-sm-12">

                    {!! Form::select('timezone',array( "" =>trans("comman.location_timezone"))+$timezones, null, ['class' => 'form-control col-md-10']) !!}
                    {!! ($errors->has('timezone') ? $errors->first('timezone', '<p class="text-danger">:message</p>') : '') !!}

                </div>


    </div>
    </div>
    </div>
 </div>

{{-- <div class="row">
    <div class="col-lg-4 ">
        <div class="form-group {{ $errors->has('address_line_one') ? 'has-error' : ''}}">
            {!! Html::decode(Form::label('address_line_one', 'Address:<span class="has-stik">*</span>', ['class' => 'col-sm-12  '])) !!}
            <div class="col-sm-12">
                {!! Form::text('address_line_one', null, ['class' => 'form-control','placeholder'=> 'Address Line 1', 'id' => 'address_line_one']) !!}
                {!! ($errors->has('address_line_one') ? $errors->first('address_line_one', '<p class="text-danger">:message</p>') : '') !!}
            </div>
        </div>
    </div>
    <div class="col-lg-4 ">
        <div class="form-group {{ $errors->has('address_line_two') ? 'has-error' : ''}}">
            {!! Html::decode(Form::label('address_line_two', 'Address:', ['class' => 'col-sm-12  '])) !!}
            <div class="col-sm-12">
                {!! Form::text('address_line_two', null, ['class' => 'form-control','placeholder'=> 'Address Line 2', 'id' => 'address_line_two']) !!}
                {!! ($errors->has('address_line_two') ? $errors->first('address_line_two', '<p class="text-danger">:message</p>') : '') !!}
            </div>
        </div>
    </div>
    <div class="col-lg-4 ">
        <div class="form-group {{ $errors->has('address_line_three') ? 'has-error' : ''}}">
            {!! Html::decode(Form::label('address_line_three', 'Address:', ['class' => 'col-sm-12  '])) !!}
            <div class="col-sm-12">
                {!! Form::text('address_line_three', null, ['class' => 'form-control','placeholder'=> 'Address Line 3', 'id' => 'address_line_three']) !!}
                {!! ($errors->has('address_line_three') ? $errors->first('address_line_three', '<p class="text-danger">:message</p>') : '') !!}
            </div>
        </div>
    </div>
    </div> --}}
{{--     <div class="row">
    <div class="col-lg-4 ">
        <div class="form-group {{ $errors->has('country_id') ? 'has-error' : ''}}">
        {!!  Html::decode(Form::label('country_id', trans("comman.country"). ':<span class="has-stik">*</span>', ['class' => 'col-md-12'])) !!}

                <div class="col-sm-12">

                    {!! Form::select('country_id',array( "" =>trans("comman.select_country"))+$countries, null, ['class' => 'form-control col-md-10']) !!}
                    {!! ($errors->has('country_id') ? $errors->first('country_id', '<p class="text-danger">:message</p>') : '') !!}

                </div>


    </div>
    </div>
    <div class="col-lg-4 ">
        <div class="form-group {{ $errors->has('skype_id') ? 'has-error' : ''}}">
            {!! Html::decode(Form::label('skype_id', 'Skype Id:', ['class' => 'col-sm-12  '])) !!}
            <div class="col-sm-12">
                {!! Form::text('skype_id', null, ['class' => 'form-control','placeholder'=> 'Skype Id', 'id' => 'skype_id']) !!}
                {!! ($errors->has('skype_id') ? $errors->first('skype_id', '<p class="text-danger">:message</p>') : '') !!}
            </div>
        </div>
    </div>
    <div class="col-lg-4 ">
        <div class="form-group {{ $errors->has('emergency_contact') ? 'has-error' : ''}}">
            {!! Html::decode(Form::label('emergency_contact', 'Emergency Contact:', ['class' => 'col-sm-12  '])) !!}
            <div class="col-sm-12">
                {!! Form::text('emergency_contact', null, ['class' => 'form-control','placeholder'=> 'Emergency Contact', 'id' => 'emergency_contact']) !!}
                {!! ($errors->has('emergency_contact') ? $errors->first('emergency_contact', '<p class="text-danger">:message</p>') : '') !!}
            </div>
        </div>
    </div>
</div> --}}

</div>
</div>
<div class="">
{{-- <div class="secDiv brd">
        <div>
            <h3 class="sample_head"><b>Payment Details</b></h3>
            <ul class="nav nav-tabs pull-right mrg_top">

              <li role="presentation" class="active"><a href="#">Credit Card</a></li>
              <li role="presentation"><a href="#">Paypal</a></li>

            </ul>
        </div>
        <div class="row">
        <div class="col-lg-4 sample_head">
        <h6><b>Card Type:</b></h6>

        <h6><b>Card Holdername:</b></h6>

        <h6><b>Card Number:</b></h6>
        <p>{{ isset($client['card_number']) ? $client['card_number'] : '' }}</p>
        <h6><b>Card ExpiryDate:</b></h6>
        <p>{{ isset($client['expiry_date']) ? $client['expiry_date'] : '' }}</p>

        <p><a href="{{ route('clients.update_payment') }}" class="font-bold send">Update Card Details</a></p>

        </div>
        <div class="col-lg-4 sample_head">
        <h6><b>Billing Address:</b></h6>

        <!-- <p><a href="{{ route('clients.update_payment') }}" class="send">Edit Address</a></p> -->
        </div>
        <div class="col-lg-4 sample_head"></div>

</div>

</div> --}}
<div class="secDiv brd">
        <div>
            <h3 class="sample_head"><b>Change Password</b></h3>

        </div>
        <div class="row">
        <div class="col-lg-4 sample_head">
        <div class="form-group {{ $errors->has('current_password') ? 'has-error' : ''}}">
                {!! Html::decode(Form::label('current_password', 'Current Password:', ['class' => 'col-sm-12 '])) !!}
                <div class="col-sm-12">
                    {!! Form::password('current_password', ['class' => 'form-control','placeholder'=>'Current Password', 'id' => 'current_password']) !!}
                    {!! ($errors->has('current_password') ? $errors->first('current_password', '<p class="text-danger">:message</p>') : '') !!}
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="form-group {{ $errors->has('new_password') ? 'has-error' : ''}}">
                {!! Html::decode(Form::label('new_password', 'New Password:', ['class' => 'col-sm-12 control-label '])) !!}
                <div class="col-sm-12">
                    {!! Form::password('new_password', ['class' => 'form-control','placeholder'=>'New Password', 'id' => 'new_password']) !!}
                    {!! ($errors->has('new_password') ? $errors->first('new_password', '<p class="text-danger">:message</p>') : '') !!}
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="form-group {{ $errors->has('new_password_confirmation') ? 'has-error' : ''}}">
                {!! Html::decode(Form::label('new_password_confirmation', 'Confirm Password:', ['class' => 'col-sm-12 control-label '])) !!}
                <div class="col-sm-12">
                    {!! Form::password('new_password_confirmation', ['class' => 'form-control','placeholder'=>'Confirm Password', 'id' => 'new_password_confirmation']) !!}
                    {!! ($errors->has('new_password_confirmation') ? $errors->first('new_password_confirmation', '<p class="text-danger">:message</p>') : '') !!}
                </div>
            </div>
        </div>
        <div class="col-lg-4 sample_head"></div>
        <div class="col-lg-4 sample_head"></div>
        <div class="col-lg-4 sample_head">
            {!! Form::submit("Save all changes", ['name' => 'save','class' => 'font-bold send pull-right']) !!}

        </div>


</div>

</div>

@push('scripts')
<script type="text/javascript">
    jQuery('.app > .app-content > .box').append('<div class="overlay ajax-overlay"><i class="fa fa-spinner fa-pulse fa-5x fa-fw"></i></div>');
    var $loading = jQuery('.ajax-overlay').hide();
    jQuery(document).ajaxStart(function () {
        $loading.show();
    }).ajaxStop(function () {
        $loading.hide();
    });
</script>
<script type="text/javascript">
    $('#profile_image').on('click', function() {
        $('#upload_profile_pic').click();
    });
</script>
<script>
  var loadFile = function(event) {
    var output = document.getElementById('profile_image');
    output.src = URL.createObjectURL(event.target.files[0]);
  };
   function readUserURL(input){
        $.imageChanger(input,"staff");
    }
</script>

{!! ajax_fill_dropdown('lang','coach_id',route('ajax.coach')) !!}
@endpush
