@extends($theme)
@section('title', $title)
@section('content')

<div class="row">
    <div class="col-md-12">
       <div class="panel panel-white">
            <div class="panel-heading">
                <div class="col-sm-9"><h5 class="panel-title"> {{ $module_title }} </h5></div>               
                <div class="clearfix"></div>
            </div>
            <div class="panel-body">
                <div class="form-group">
                    {!! Html::decode(Form::label('note', trans("comman.notes"). ':', ['class' => 'col-sm-2 control-label'])) !!}
                    <div class="col-sm-4">
                        <div class="col-sm-7">
                            {{ $coach_note->note }}
                        </div>
                    </div>
                </div> 
            </div>            
        </div>
    </div>
</div>
@endsection