@extends($theme)

@section('title', $title)

@section('content')
<div class="content-wrapper">
    <div class="panel panel-default">
        <div class="panel-heading">
            <h3 class="panel-title">Forum Threads</h3>
        </div>
    </div>
    <div class="panel panel-default">
        <div class="panel-heading bg-white">
         <div class="row">
                <div class="col-md-6">
                    <h5 class="panel-title">{{ trans('comman.threads') }}</h5>
                </div>
                <div class="heading-elements col-md-4">
                    <div class="pull-right">
                        <a href="/forum-topics/create" class="btn bg-success btn-add btn-labeled heading-btn" title="Add New"><b><i class="icon-diff-added"></i></b> Add Thread</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="panel-body">
            <table class="table table-bordered table-hover no-footer">
                <thead>
                    <tr>
                        <th>Title</th>
                        <th>Category</th>
                        <th>Created By</th>
                        <th>Number of Post</th>
                        <th>Colour</th>
                        <th width="80px" style="width: 80px;" class="text-center">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @if(isset($topics) && count($topics) > 0)
                        @foreach($topics as $data)
                            <tr>
                                <td> {{ $data->title }} </td>
                                <td> {{ $data->ChatterCategory->name }} </td>
                                <td> {{ $data->User->name }}</td>
                                <td> {{ count($data->ChatterPost) }}</td>
                                <td style="color:{{ $data->color}};"> {{ $data->color }} </td>
                                <td class="text-center">
                                    <ul class="icons-list">
                                        <li class="dropdown">
                                            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                                <i class="icon-menu9"></i>
                                            </a>
                                            <ul class="dropdown-menu dropdown-menu-right">
                                                <li>
                                                    <a href="/forum-topics/edit/{{$data->id}}" data-toggle="tooltip" title="Edit Category" ><i class="icon-pencil7"></i>Edit</a>
                                                </li>

                                                <li>
                                                    <a href="/forum-topics/delete/{{$data->id}}" data-toggle="tooltip" title="Delete Category" data-method="delete" data-modal-text=" Topic?" class="action_confirm text-danger-600" title="Delete"><i class="icon-trash"></i>Delete</a>
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
                {{ $topics->appends(request()->except('page'))->links('vendor.pagination.bootstrap-custom')}}
            </div>
        </div>
    </div>
</div>

@endsection