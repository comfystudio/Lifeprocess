@extends($theme)
@section('title', $title)
@section('content')
    <div class="content-wrapper">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title">{{trans("comman.emiltemplate")}}</h3>
                <div class="heading-elements">
                    @if(!empty($module_action))
                        <div class="text-right">
                            @foreach($module_action as $key=>$action)
                            {!! Html::decode(Html::link($action['url'],$action['title'],$action['attributes'])) !!}
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
            <div class="panel-body">
                <table class="table table-bordered table-hover no-footer">
                    <thead>
                        <tr>
                            {{-- <th>No.</th> --}}
                            <th>Name</th>
                            <th>Slug</th>
                            <th>Trigger</th>
                            <th>Subject</th>
                            <th style="width: 60px;" class="text-center">{{ trans('comman.action') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if(isset($email_template) && count($email_template) > 0 )
                            @php
                                $counter =1;
                            @endphp
                            @foreach($email_template as $template)
                                <tr>
                                    {{--
                                    <td>
                                    {{ ($email_template->currentPage()-1) * $email_template->perPage()+ $counter++ }}
                                    </td>
                                    --}}
                                    <td>{{$template->template_name}}</td>
                                    <td>{{$template->slug}}</td>
                                    <td>{{$template->trigger}}</td>
                                    <td>{{$template->subject}}</td>
                                    <td class="text-center">
                                        <ul class="icons-list">
                                            <li class="dropdown">
                                                <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                                    <i class="icon-menu9"></i>
                                                </a>
                                                <ul class="dropdown-menu dropdown-menu-right">
                                                    @if(Auth::user()->hasAccess('email_template.update'))
                                                        <li>
                                                            {!! Html::decode(link_to_route('email-template.edit', '<i class="icon-pencil7"></i>Edit', array(Crypt::encryptString($template->id)))) !!}
                                                        </li>
                                                    @endif
                                                    <li>
                                                        {!! Html::decode(link_to_route('email-template.show', '<i class="icon-eye"></i>View', array(Crypt::encryptString($template->id)))) !!}
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
                    {{ $email_template->appends(request()->except('page'))->links('vendor.pagination.bootstrap-custom')}}
                </div>
            </div>
        </div>
    </div>
@endsection