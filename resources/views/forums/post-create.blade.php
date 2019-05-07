@extends($theme)
@section('title', $title)
<style type="text/css">
    div.panel-body > div.proof{
        padding-bottom:10px;
    }
    hr {
        margin: 0 !important;
    }
</style>
@section('content')
<div class="content-wrapper">
    <div class="panel panel-white">
        <div class="panel-heading">
            <h3 class="panel-title">@if(isset($post)) Edit @else Add @endif Post</h3>
            <div class="heading-elements">
                <div class="text-right">
                    <a href="{{url('/forum-posts')}}" class="btn bg-blue btn-labeled heading-btn" title="Back"><b><i class="icon-arrow-left52"></i></b> Back</a>
                </div>
            </div>
        </div>
        <div class="panel-body">
            <form action="/forum-posts/{{isset($post) ? 'edit/'.$post->id : 'create'}}" method="POST" class="form-horizontal form-bordered">
                {{ csrf_field() }}

                <div class="row">
                    <div class="col-lg-6 {{ $errors->has('chatter_discussion_id') ? 'has-error' : ''}}">
                        <div class="form-group">
                            <label for="chatter_discussion_id" class="col-sm-4 control-label ">Topic <span class="has-stik"> *</span></label>
                            <div class="col-sm-8">
                                <div class="input-group">
                                    <select class="select-size" name="chatter_discussion_id" data-placeholder="Select Topic...">
                                        @foreach($discussionList as $key => $discussions)
                                            <option value="{{$key}}"
                                                @if(old('chatter_discussion_id') && $key == old('chatter_discussion_id'))
                                                    selected = "selected"
                                                @elseif(isset($post['chatter_discussion_id']) && $key == $post['chatter_discussion_id'])
                                                    selected = "selected"
                                                @endif
                                            >
                                                {{$discussions}}
                                            </option>
                                        @endforeach
                                    </select>
                                    @if($errors->has('chatter_discussion_id') == 1)<p class="text-danger">{{$errors->first('chatter_discussion_id')}}</p> @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-12">
                        <div class="form-group {{ $errors->has('body') ? 'has-error' : ''}}">
                        {!! Html::decode(Form::label('body', trans("comman.text"). ':<span class="has-stik">*</span>', ['class' => 'col-sm-2 control-label'])) !!}
                            <div class="col-sm-8">
                                @if(isset($post))
                                    {!! Form::textarea('body', $post["body"], ['class' => 'form-control','rows' => '10','id' =>'summernote']) !!}
                                @else
                                    {!! Form::textarea('body', null, ['class' => 'form-control','rows' => '10','id' =>'summernote']) !!}
                                @endif
                                 {!! ($errors->has('body') ? $errors->first('body', '<p class="text-danger">:message</p>') : '') !!}
                             </div>
                        </div>
                    </div>
                </div>

                <br>
                <div class="form-group">
                    <div class="col-sm-offset-3 col-sm-4 text-center">
                        <input name="save" class="btn btn-primary" type="submit" value="Save">
                        <a href="{{url('/forum-posts')}}" class="btn btn-warning">Cancel</a>
                    </div>
                </div>

            </form>
        </div>
    </div>
</div>
@push('scripts')

<script type="text/javascript">

    jQuery('.select-size-sm').select2();
    jQuery('.select-size').select2({ width: '500px' });

    $(document).ready(function() {
        $('#summernote').summernote();
    });
</script>

@endpush

@stop