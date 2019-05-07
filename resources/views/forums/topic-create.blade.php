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
            <h3 class="panel-title">@if(isset($topic)) Edit @else Add @endif Thread</h3>
            <div class="heading-elements">
                <div class="text-right">
                    <a href="{{url('/forum-topics')}}" class="btn bg-blue btn-labeled heading-btn" title="Back"><b><i class="icon-arrow-left52"></i></b> Back</a>
                </div>
            </div>
        </div>
        <div class="panel-body">
            <form action="/forum-topics/{{isset($topic) ? 'edit/'.$topic->id : 'create'}}" method="POST" class="form-horizontal form-bordered">
                {{ csrf_field() }}

                <div class="row">
                    <div class="col-lg-6">
                        <div class="form-group {{ $errors->has('title') ? 'has-error' : ''}}">
                            <label for="title" class="col-sm-4 control-label ">Title<span class="has-stik"> *</span></label>
                            <div class="col-sm-8">
                                <input class="form-control" placeholder="Title" id="title" name="title" type="text" value="@if(old('title')){{old('title')}} @elseif(isset($topic->title)){{$topic->title}}@endif">
                                @if($errors->has('title') == 1)<p class="text-danger">{{$errors->first('title')}}</p> @endif
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="form-group {{ $errors->has('color') ? 'has-error' : ''}}">
                            <label for="color" class="col-sm-4 control-label ">Colour</label>

                            <div class="col-sm-8">
                                <input class="form-control" placeholder="Colour" id="color" name="color" type="text" value="@if(old('color')){{old('color')}} @elseif(isset($topic->color)){{$topic->color}}@endif">
                                @if($errors->has('color') == 1)<p class="text-danger">{{$errors->first('color')}}</p> @endif
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-6 {{ $errors->has('chatter_category_id') ? 'has-error' : ''}}">
                        <div class="form-group">
                            <label for="chatter_category_id" class="col-sm-4 control-label ">Category <span class="has-stik"> *</span></label>
                            <div class="input-group">
                                <div class="col-sm-8">
                                    <select class="select-size" name="chatter_category_id" data-placeholder="Select Parent...">
                                        @foreach($categoryList as $key => $categories)
                                            <option value="{{$key}}"
                                                @if(old('parent_id') && $key == old('chatter_category_id'))
                                                    selected = "selected"
                                                @elseif(isset($category['chatter_category_id']) && $key == $category['chatter_category_id'])
                                                    selected = "selected"
                                                @endif
                                            >
                                                {{$categories}}
                                            </option>
                                        @endforeach
                                    </select>
                                    @if($errors->has('chatter_category_id') == 1)<p class="text-danger">{{$errors->first('chatter_category_id')}}</p> @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{--@include('users.form')--}}
                <br>
                <div class="form-group">
                    <div class="col-sm-offset-3 col-sm-4 text-center">
                        <input name="save" class="btn btn-primary" type="submit" value="Save">
                        {{--<input name="save_exit" class="btn btn-primary" type="submit" value="Save &amp; Exit">--}}
                        <a href="{{url('/forum-topics')}}" class="btn btn-warning">Cancel</a>
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

</script>

@endpush

@stop