@extends($theme)
@section('title', $title)
@section('content')
<div class="content-wrapper">
    <div class="row">
        <div class="col-md-12">
            {!! Form::model($email_template, ['method' => 'PATCH','route' => ['email-template.update', Crypt::encryptString($email_template->id)],'class' => 'form-horizontal']) !!}
                <div class="panel panel-white">
                    <div class="panel-heading">
                        <h3 class="panel-title">View Email Template</h3>
                        <div class="heading-elements">
                            @if(!empty($module_action))
                                <div class="text-right">
                                    @foreach($module_action as $key=>$action)
                                    {!! Html::decode(Html::link(URL::previous(),$action['title'],$action['attributes'])) !!}
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    </div>
                    <div class="panel-body">
                    <div class="form-group">
                         {!! Html::decode(Form::label('subject', trans("comman.subject"), ['class' => 'col-sm-3 control-label'])) !!}
                         <div class="col-sm-6">
                            {!! Form::text('subject', null, ['class' => 'form-control number','placeholder' => trans("comman.subject"),'readonly']) !!}
                        </div>
                    </div>
                    <div class="form-group {{ $errors->has('content') ? 'has-error' : ''}}">
                    {!! Html::decode(Form::label('content', trans("comman.content"). '', ['class' => 'col-sm-3 control-label'])) !!}
                     <div class="col-sm-6">
                     {!!html_entity_decode($email_template->content)!!}
                     </div>
                    </div>
                </div>
            {!! Form::close() !!}

        </div>
    </div>
</div>
@push('scripts')
<script>
    $(document).ready(function() {
        $('#summernote').summernote();
    });
  </script>
@endpush
@endsection