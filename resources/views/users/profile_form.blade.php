<div class="row">
    <div class="col-lg-4">
        <div class="form-group {{ $errors->has('first_name') ? 'has-error' : ''}}">
            {!! Html::decode(Form::label('first_name', 'First name:<span class="has-stik">*</span>', ['class' => 'control-label col-sm-12'])) !!}
            <div class="col-sm-12">
                {!! Form::text('first_name', null, ['class' => 'form-control','placeholder'=>'First name', 'id' => 'first_name']) !!}
                {!! ($errors->has('first_name') ? $errors->first('first_name', '<p class="text-danger">:message</p>') : '') !!}
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="form-group {{ $errors->has('last_name') ? 'has-error' : ''}}">
            {!! Html::decode(Form::label('last_name', 'Last name:<span class="has-stik">*</span>', ['class' => 'control-label col-sm-12'])) !!}
            <div class="col-sm-12">
                {!! Form::text('last_name', null, ['class' => 'form-control','placeholder'=>'Last name']) !!}
                {!! ($errors->has('last_name') ? $errors->first('last_name', '<p class="text-danger">:message</p>') : '') !!}
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="form-group {{ $errors->has('middle_name') ? 'has-error' : ''}}">
            {!! Html::decode(Form::label('middle_name', 'Middle name:<span class="has-stik">*</span>', ['class' => 'control-label col-sm-12'])) !!}
            <div class="col-sm-12">
                {!! Form::text('middle_name', null, ['class' => 'form-control','placeholder'=>'Middle name', 'id' => 'middle_name']) !!}
                {!! ($errors->has('middle_name') ? $errors->first('middle_name', '<p class="text-danger">:message</p>') : '') !!}
            </div>
        </div>
    </div>
</div>
<div class="row">    
    <div class="col-lg-4">
        <div class="form-group {{ $errors->has('mobile_no') ? 'has-error' : ''}}">
            {!! Html::decode(Form::label('mobile_no', 'Mobile No.:<span class="has-stik">*</span>', ['class' => 'control-label col-sm-12'])) !!}
            <div class="col-sm-12">
                {!! Form::text('mobile_no', null, ['class' => 'form-control','placeholder'=>'Mobile No.', 'id' => 'mobile_no']) !!}
                {!! ($errors->has('mobile_no') ? $errors->first('mobile_no', '<p class="text-danger">:message</p>') : '') !!}
            </div>
        </div>
    </div>
    <div class="col-lg-4 {{ $errors->has('country_id') ? 'has-error' : ''}}">
        {!!  Html::decode(Form::label('country_id', trans("comman.country"). ':<span class="has-stik">*</span>', ['class' => 'control-label'])) !!}
            {{-- @if(Sentinel::getUser()->hasAccess(['countries.create'])) --}}
                <div class="input-group">
                    {!! Form::select('country_id',array( "" =>trans("comman.select_country"))+$countries, null, ['class' => 'select-size-sm']) !!}
                    <span class="input-group-addon"><a class="new_country_popup"><i class="icon-plus3"></i></a></span>
                </div>
                {!! ($errors->has('country_id') ? $errors->first('country_id', '<p class="text-danger">:message</p>') : '') !!}
           {{--  @else
                {!! Form::select('country_id',array(""=>"")+$countries, null, ['class' => 'select-size-sm','data-placeholder' => trans("comman.select_country")]) !!}
            @endif --}}
    </div>
    <div class="col-lg-4">
        {!! Html::decode(Form::label('state', trans("comman.state").':', ['class' => 'control-label'])) !!}
        {!! Form::text('state', null, ['class' => 'form-control','placeholder' => trans("comman.state")]) !!}
        {!! ($errors->has('state') ? $errors->first('state', '<p class="text-danger">:message</p>') : '') !!}
    </div>
</div>
<div class="row">
    <div class="col-lg-4">
        <div class="form-group {{ $errors->has('username') ? 'has-error' : ''}}">
            {!! Html::decode(Form::label('username', trans('comman.username') . ':<span class="has-stik">*</span>', ['class' => 'control-label col-sm-12'])) !!}
            <div class="col-sm-12">
                {!! Form::text('username', null, ['class' => 'form-control','placeholder'=> trans('comman.username'), 'id' => 'username', (trim($user->username) != '') ? 'readonly="readonly"' : '']) !!}
                {!! ($errors->has('username') ? $errors->first('username', '<p class="text-danger">:message</p>') : '') !!}
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-4">
        <div class="form-group">
            {!! Html::decode(Form::label('address_line_one', 'Address:', ['class' => 'col-sm-12 control-label '])) !!}
            <div class="col-sm-12">
                {!! Form::text('address_line_one', null, ['class' => 'form-control','placeholder'=>'Address Line 1', 'id' => 'address_line_one']) !!}
            </div>
        </div>
        <div class="form-group">
            <div class="col-sm-12">
                {!! Form::text('address_line_two', null, ['class' => 'form-control','placeholder'=>'Address Line 2', 'id' => 'address_line_two']) !!}
            </div>
        </div>
        <div class="form-group">
            <div class="col-sm-12">
                {!! Form::text('address_line_three', null, ['class' => 'form-control','placeholder'=>'Address Line 2', 'id' => 'address_line_three']) !!}
            </div>
        </div>
    </div>
    <div class="col-lg-8">
        <div class="form-group {{ $errors->has('image') ? 'has-error' : ''}}">
            {!! Html::decode(Form::label('image', 'Upload Profile Photo:', ['class' => 'control-label col-sm-12'])) !!}
            <div class="col-sm-6">
                {{-- {!! Form::text('image', null, ['class' => 'form-control','placeholder'=>'Photo', 'id' => 'image']) !!} --}}
                {!! Form::file('image', ['onchange' => 'readUserURL(this)', 'accept' => 'image/*']) !!}
                {{-- <input type="file" name="image" onchange="readUserURL(this)" accept="image/*"> --}}
                {!! ($errors->has('image') ? $errors->first('image', '<p class="text-danger">:message</p>') : '') !!}
            </div>
            <div class="col-sm-6">
                @if(isset($user->image) && !empty($user->image))
                    {{Html::image(AppHelper::path('uploads/user/')->size('100x100')->getImageUrl($user->image),'User Photo',array("class"=>"img-circle",'id'=>'staff','height'=>'100','width'=>'100'))}}
                @else
                    {{Html::image(AppHelper::size('100x100')->getDefaultImage(),'User Photo',array("class"=>"img-circle staff",'id'=>'staff','height'=>'100','width'=>'100'))}}
                @endif
            </div>
        </div>
    </div>
</div>

<fieldset>
    <legend><h5 class="panel-title">Account Setting</h5> </legend>
</fieldset>
<div class="row">
    <div class="col-md-4">
        <strong>E-mail: </strong> <u>{{ $user->email }}</u>
    </div>
    <div class="col-md-4">
        <div class="form-group {{ $errors->has('terms_and_condition') ? 'has-error' : ''}}">
            {{-- {!! Html::decode(Form::label('terms_and_condition', trans('comman.accept') . ' ' . trans('comman.terms_and_condition') . ':', ['class' => 'col-sm-4 control-label '])) !!} --}}
            <div class="col-sm-12">
                <label class="checkbox-inline">
                    {!! Form::checkbox('terms_and_condition', 'yes', old('terms_and_condition', $user->terms_and_condition) == 'yes' ? 'checked="checked"' : '', ['class' => 'styled', 'id' => 'terms_and_condition', 'style' => '']) !!}
                    {{ trans('comman.accept') . ' ' . trans('comman.terms_and_condition') }}<span class="has-stik">*</span>
                </label>
                {!! ($errors->has('terms_and_condition') ? $errors->first('terms_and_condition', '<p class="text-danger">:message</p>') : '') !!}
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-lg-4">
        <div class="form-group {{ $errors->has('current_password') ? 'has-error' : ''}}">
            {!! Html::decode(Form::label('current_password', 'Current Password:', ['class' => 'col-sm-12 control-label '])) !!}
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
</div>