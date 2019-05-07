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
            <h3 class="panel-title">@if(isset($category)) Edit @else Add @endif Category</h3>
            <div class="heading-elements">
                <div class="text-right">
                    <a href="{{url('/forum-categories')}}" class="btn bg-blue btn-labeled heading-btn" title="Back"><b><i class="icon-arrow-left52"></i></b> Back</a>
                </div>
            </div>
        </div>
        <div class="panel-body">
            <form action="/forum-categories/{{isset($category) ? 'edit/'.$category->id : 'create'}}" method="POST" class="form-horizontal form-bordered">
                {{ csrf_field() }}

                <div class="row">
                    <div class="col-lg-6">
                        <div class="form-group {{ $errors->has('name') ? 'has-error' : ''}}">
                            <label for="name" class="col-sm-4 control-label ">Category Name<span class="has-stik"> *</span></label>
                            <div class="col-sm-8">
                                <input class="form-control" placeholder="Category Name" id="name" name="name" type="text" value="@if(old('name')){{old('name')}} @elseif(isset($category->name)){{$category->name}}@endif">
                                @if($errors->has('name') == 1)<p class="text-danger">{{$errors->first('name')}}</p> @endif
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="form-group {{ $errors->has('color') ? 'has-error' : ''}}">
                            <label for="color" class="col-sm-4 control-label ">Category Colour<span class="has-stik"> *</span></label>

                            <div class="col-sm-8">
                                <input class="form-control" placeholder="Category Colour" id="color" name="color" type="text" value="@if(old('color')){{old('color')}} @elseif(isset($category->color)){{$category->color}}@endif">
                                @if($errors->has('color') == 1)<p class="text-danger">{{$errors->first('color')}}</p> @endif
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-6 {{ $errors->has('parent_id') ? 'has-error' : ''}}">
                        <div class="form-group">
                            <label for="parent_id" class="col-sm-4 control-label ">Parent Category</label>
                            <div class="input-group">
                                <div class="col-sm-8">
                                    <select class="select-size" name="parent_id" data-placeholder="Select Parent...">
                                        <option value="0" selected>--No Parent--</option>
                                        @foreach($categoryList as $key => $categories)
                                            <option value="{{$key}}"
                                                @if(old('parent_id') && $key == old('parent_id'))
                                                    selected = "selected"
                                                @elseif(isset($category['parent_id']) && $key == $category['parent_id'])
                                                    selected = "selected"
                                                @endif
                                            >
                                                {{$categories}}
                                            </option>
                                        @endforeach
                                    </select>
                                    @if($errors->has('parent_id') == 1)<p class="text-danger">{{$errors->first('parent_id')}}</p> @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="form-group {{ $errors->has('order') ? 'has-error' : ''}}">
                            <label for="order" class="col-sm-4 control-label ">Category Order<span class="has-stik"> *</span></label>
                            <div class="col-sm-8">
                                <input class="form-control" placeholder="Category Order" id="order" name="order" type="text" value="@if(old('order')){{old('order')}} @elseif(isset($category->order)){{$category->order}}@endif">
                                @if($errors->has('order') == 1)<p class="text-danger">{{$errors->first('order')}}</p> @endif
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
                        <a href="{{url('/forum-categories')}}" class="btn btn-warning">Cancel</a>
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