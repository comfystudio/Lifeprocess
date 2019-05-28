@extends($theme)
@section('title', $title)
<style type="text/css">
    div.panel-body > div.proof{
        padding-bottom:10px;
    }
    hr {
        margin: 0 !important;
    }
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
@section('content')
<div class="tab-content">
    <div class="tab-pane fade in active">
        <div class="col-md-12">
        
            <form accept-charset="UTF-8" method="post" action="/client/add-read-only-coach" autocomplete="off" class ='form-horizontal'>
                <input name="_token" value="{{ csrf_token() }}" type="hidden"/>
            
                <div class="tab-title">
                    <h1 class="no-margin">Revoke Read Only Coach</h1>
                </div>

                <div class="client-msg">
                    <div class="brd">
                        <div class="firstDiv">
                            <div class="row">
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <label>Current Read Only Coach:</label>
                                        <input type="hidden" name="revoke" value="1">
                                        {{--{!! Html::decode(Form::label('first_name', 'First name:<span class="has-stik">*</span>', ['class' => 'col-sm-12  '])) !!}--}}
                                        <div class="col-sm-12">
                                            <input class="form-control pull-left" type="text" disabled="disabled" value="{{$client->invite_coach}}">
                                            {{--{!! Form::text('first_name', null, ['class' => 'form-control','placeholder'=> 'First name', 'id' => 'first_name']) !!}--}}
                                            {{--{!! ($errors->has('first_name') ? $errors->first('first_name', '<p class="text-danger">:message</p>') : '') !!}--}}
                                        </div>
                                    </div>
                                </div>
                            <div class="row">
                                <div class="col-lg-4 sample_head"></div>
                                <div class="col-lg-4 sample_head"></div>
                                <div class="col-lg-4 sample_head">
                                    {!! Form::submit("Revoke Coach", ['name' => 'save','class' => 'font-bold send pull-right']) !!}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@push('scripts')

<script type="text/javascript">
    $(document).ready(function() {
    });   
</script>
@endpush
{{-- Popup File --}}
@include('users.popup')
@endsection
