@section('style')
<style>
    .font-limit {
        font-size: 14px;
    }
    legend.module{
        margin-bottom: 5px;
    }
    .permission_display{
        /*width:270px;*/
        width: 100%;
        float: left;
        padding: 2.5px 7.5px;
        margin: 1px;
        border: 1px solid #adadad; /*#f39c12; */
    }
    .permission_title:hover{
        cursor:pointer;
    }
    .permission_title{
        font-size:15px;
        /*width: 155px;*/
        display: inline-block;
        min-height: 20px;
        vertical-align: top;
        padding-top: 7px;
    }
    legend.module{
        margin-bottom: 5px;
    }
    .all_lbl{
        font-size: 14px !important;
        width:8%;
        float:left;
        position: relative;
        padding: 0px;
        margin:0px;
        margin-top:7px;
    }
    .all_per_div{
        float:left;
        position: relative;
        min-height: 1px;
        margin-left:5px;
        font-size: 14px;
    }
    .all_permission{
        display:inline-block;        
    }
    .sub_title{
        float: left;
        margin: 0;
        padding: 0 0 0 14px;
        /*width: 160px;*/
        text-transform: capitalize;
    }
    .sub_radio{
        float: left;
        margin: 4px;
        padding: 0;
        width: auto;
    }
    .sub_radio label{
        /*width: 100%;*/
        font-weight: normal;
        margin: 0;
        padding: 0;
    }
    .checker, .checker span, .checker input {
        margin-right: 10px;
    }
    
</style>
@stop

<div class="form-group {{ ($errors->has('role_name')) ? 'has-error' : '' }}">
    {!! Html::decode(Form::label('role_name','Name:<span class="has-stik">*</span>', ['class' => 'col-lg-1 control-label'])) !!}
    <div class="col-lg-9">
        {!! Form::text('role_name', null, ['class' => 'form-control text-capitalize','id'=>'role_name','autofocus'=>'true', 'autocomplete' => 'off']) !!}
        {!! ($errors->has('role_name') ? $errors->first('role_name', '<p class="text-danger">:message</p>') : '') !!}
    </div>
</div>
<div class="form-group {{ ($errors->has('slug')) ? 'has-error' : '' }}">
    {!! Html::decode(Form::label('slug','Slug:<span class="has-stik">*</span>', ['class' => 'col-lg-1 control-label'])) !!}
    <div class="col-lg-9">
        {!! Form::text('slug', null, ['class' => 'form-control','id'=>'slug', 'readonly']) !!}
        {!! ($errors->has('slug') ? $errors->first('slug', '<p class="text-danger">:message</p>') : '') !!}
    </div>
</div>
<hr/>
<h5>Permissions</h5>

<?php 
    $flag_row = 0; 
    //for view 4 permissions in single row 
    $tmp = 1; 
    //Tmp variable for all Permission
?>
<div class="row">
    @foreach($all_permission as $module_name => $module_wise_array)
        
        @if($flag_row % 3 == 0)
            </div><div class="row">
        @endif
        <?php $flag_row++;  ?>
    <div class="col-md-4 " style="padding: 0 5px;">
        <div class="permission_display" style="margin-bottom: 5px;">
            
                <span data-id="{{str_replace(array('.',' ','/'), '_', $module_name)}}" class="permission_title text-capitalize col-md-9">{{str_replace('_', ' ',$module_name)}}
                &nbsp;&nbsp;&nbsp;<i class="fa fa-lg fa-caret-down pull-right"></i></span>
                {{--  Check In All permission Allow/Deny Selected or not End --}}
                @php
                    $true_occurs = 0;
                    if(isset($users_permission)) {                
                        $stored_permission = array_get($users_permission, $module_name) ? : [] ;
                        foreach ($stored_permission as $key => $value) { // value will be True / False
                            if($value)
                                $true_occurs++;
                        }
                    }
                @endphp
                <div class="all_permission parent_permission ">
                    <div class="checkbox all_per_div">
                        <label class="font-limit" for="task_assign_sms">
                        <input value="yes" type="checkbox" name="task_assign_sms" class="all_allow parent_alw styled" data-allow="{{$tmp}}_alw" {{ (count($module_wise_array) == $true_occurs ? ' checked="checked"' : '') }}>
                        <i class="indigo"></i> Allow
                      </label>
                    </div>
                </div>
                
            <div class="sub_permission sub_{{str_replace(array('.',' ','/'), '_', $module_name)}}" style="display:none; width: 100%;">
                @foreach($module_wise_array as $module_permission_name => $module_permission_value)
                    {{-- <div class="text-capitalize col-sm-9 ">
                        <strong>
                            <small>{{ str_replace("_", " ", $module_permission_value['label']) }}</small>
                        </strong>
                    </div> --}}
                    <label class="sub_title col-md-8">{{ str_replace("_", " ", $module_permission_value['label']) }}</label>           
                    <div class="sub_radio chec">
                        <input value="yes" type="checkbox" class="{{$tmp}}_alw child_alw" name="permission[{{$module_name}}][{{$module_permission_value['permission']}}]"
                        {{ (isset($users_permission) && array_get($users_permission, $module_name . '.' . $module_permission_value['label'])) ? "checked= 'checked' ": '' }}> 
                         <label for="permission[{{$module_name}}][{{$module_permission_value['permission']}}]"> Allow </label> 
                        {{ Form::hidden("hdn_permission[" . $module_name . "][" . $module_permission_value['permission'] . "]", '1') }}
                        
                    </div>
                @endforeach
            </div>
            
        </div>
    </div>
    <?php $tmp++; ?>
    @endforeach
</div>
    
<div class="clearfix"></div>
<br>