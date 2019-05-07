<div class="panel panel-default">
    <div class="panel-heading bg-white">
            <div class="row">
                <div class="col-md-6">
                    <h5 class="panel-title">{{ $title }}</h5>
                </div>
                <div class="heading-elements col-md-4">
                     <div class="pull-right">
                     <a  href=" {!! route('referfriendreportpdf', request()->all()) !!}" type="button" class="btn bg-info btn-labeled heading-btn"><b><i class="fa fa-file-pdf-o"></i></b>{{ trans('comman.saveaspdf')}}</a>
                     </div>
                    @if(!empty($module_action))
                        <div class="pull-right">
                            @foreach($module_action as $key=>$action)
                            {!! Html::decode(Html::link($action['url'],$action['title'],$action['attributes'])) !!}
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
    </div>
    <div class="panel-body">
        <table class="table table-bordered table-hover no-footer">
            <thead>
                @if(request()->get('group_by') == 'none' || request()->get('group_by') == null)
                     <tr>
                            <th class="text-center">Sr.no</th>
                            <th class="text-center">Your Friend Email</th>
                            <th class="text-center">Used Name</th>
                            <th class="text-center">Send Date with time</th>
                    </tr>
                @elseif(request()->get('group_by') == 'user')
                    <tr>
                            <th class="text-center">Sr.no</th>
                            <th class="text-center">First Name </th>
                            <th class="text-center">Last Name</th>
                            <th class="text-center">Email</th>
                            <th class="text-center">Total Refer Friends</th>
                    </tr>
                @elseif(request()->get('group_by') == 'role')
                    <tr>
                        <th class="text-center">Sr.no</th>
                        <th class="text-center">Role</th>
                        <th class="text-center">Total Refer Friends</th>
                    </tr>
                @endif
            </thead>
            <tbody>
                {{-- all User Report--}}
                @if(!empty($ReferFriendReport) && count($ReferFriendReport) > 0)
                    @foreach($ReferFriendReport as $report)
                    @if(request()->get('group_by') == 'none' || request()->get('group_by') == null)
                        <tr class="text-center">
                           <td>{{$count++}}</td>
                           <td>{{$report->friends_email}}</td>
                           <td>{{$report->use_your_name}}</td>
                           <td>
                                @if($timezone != null)
                                    {{$report->created_at->timezone($timezone)->format('m/d/Y H:i')}}
                                @else
                                    {{$report->created_at->format('m/d/Y H:i')}}
                                @endif
                            </td>
                        </tr>
                    @elseif(request()->get('group_by') == 'user')
                        <tr class="text-center">
                           <td>{{$count++}}</td>
                           <td>{{$report->user->first_name}}</td>
                           <td>{{$report->user->last_name}}</td>
                           <td>{{$report->user->email}}</td>
                           <td>{{$report->total}}</td>
                        </tr>
                    @elseif(request()->get('group_by') == 'role')
                        <tr class="text-center">
                            <td>{{$count++}}</td>
                            <td>{{$report->user_type}}</td>
                            <td>{{$report->total_refer_friend}}</td>
                        </tr>
                    @endif
                    @endforeach
                @else
                 <tr>
                    <td colspan="5" align="center">{{trans('comman.noData')}}</td>
                </tr>
                @endif
            </tbody>
        </table>
    </div>
</div>