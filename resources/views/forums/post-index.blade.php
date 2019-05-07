@extends($theme)

@section('title', $title)

@section('content')
<div class="content-wrapper">
    <div class="panel panel-default">
        <div class="panel-heading">
            <h3 class="panel-title">Forum Posts</h3>
        </div>
    </div>
    <div class="panel panel-default">
        <div class="panel-heading bg-white">
         <div class="row">
                <div class="col-md-6">
                    <h5 class="panel-title">{{ trans('comman.posts') }}</h5>
                </div>
                <div class="heading-elements col-md-4">
                    <div class="pull-right">
                        <a href="/forum-posts/create" class="btn bg-success btn-add btn-labeled heading-btn" title="Add New"><b><i class="icon-diff-added"></i></b> Add Post</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="panel-body">
            <table class="table table-bordered table-hover no-footer">
                <thead>
                    <tr>
                        <th>Text</th>
                        <th>Topic</th>
                        <th>Created By</th>
                        <th width="80px" style="width: 80px;" class="text-center">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @if(isset($posts) && count($posts) > 0)
                        @foreach($posts as $data)
                            <tr>

                                <td> {{str_limit($data->body, 100, '...')}}</td>
                                <td>
                                    @if(isset($data->ChatterDiscussion) && count($data->ChatterDiscussion) > 0)
                                        {{str_limit($data->ChatterDiscussion->title, 50, '...') }}
                                    @endif
                                </td>
                                <td> {{ $data->User->name }}</td>
                                <td class="text-center">
                                    <ul class="icons-list">
                                        <li class="dropdown">
                                            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                                <i class="icon-menu9"></i>
                                            </a>
                                            <ul class="dropdown-menu dropdown-menu-right">
                                                <li>
                                                    <a href="/forum-posts/edit/{{$data->id}}" data-toggle="tooltip" title="Edit Category" ><i class="icon-pencil7"></i>Edit</a>
                                                </li>

                                                <li>
                                                    <a href="/forum-posts/delete/{{$data->id}}" data-toggle="tooltip" title="Delete Category" data-method="delete" data-modal-text=" Post?" class="action_confirm text-danger-600" title="Delete"><i class="icon-trash"></i>Delete</a>
                                                    {{--<a href="http://lifeprocess.localhost" data-method="delete" data-modal-text=" User?" class="action_confirm text-danger-600" title="Delete"><i class="icon-trash"></i>Delete</a>--}}
                                                </li>
                                            </ul>
                                        </li>
                                    </ul>
                                </td>

                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="5">No data found.</td>
                        </tr>
                    @endif
                </tbody>
            </table>
            <div class="pagination-wraper">
                {{ $posts->appends(request()->except('page'))->links('vendor.pagination.bootstrap-custom')}}
            </div>
        </div>
    </div>
</div>

@endsection