<div class="panel panel-default">
    <div class="panel-heading bg-white">
              <div class="row">
                <div class="col-md-6">
                    <h5 class="panel-title">{{ $title }}</h5>
                </div>
                <div class="heading-elements col-md-4">
                        <div class="pull-right">
                            <a  href=" {!! route('signupreportpdf', request()->all()) !!}" type="button" class="btn bg-info btn-labeled heading-btn"><b><i class="fa fa-file-pdf-o"></i></b>{{ trans('comman.saveaspdf')}}</a>
                        </div>
                </div>
            </div>
    </div>
    <div class="panel-body">
        <table class="table table-bordered table-hover no-footer">
            <thead>

            @if(request()->get('group_by') == null || request()->get('group_by') == 'role')
                <tr>
                        <th class="text-center">Role</th>
                        <th class="text-center">Total New Signup</th>
                        <th class="text-center">Total Failed Payments</th>
                        <th class="text-center">Cancellations</th>
                        <th class="text-center">Total number of live/active</th>
                </tr>
             @elseif(request()->get('group_by') == 'user')
                        <th class="text-center">Client Name</th>
                        <th class="text-center">New Signup</th>
                        <th class="text-center">Total Failed Payments</th>
                        <th class="text-center">Cancellations</th>
                        <th class="text-center">Status</th>
             @endif
            </thead>
            <tbody>
                {{-- all User Report--}}
                @if(!empty($SignupReport) && count($SignupReport) > 0)

                    @if(request()->get('group_by') == null || request()->get('group_by') == 'role')
                        @foreach($SignupReport as $report_key=>$report_value)
                        <tr class="text-center">
                            <td>
                                {{-- {!! Html::decode(link_to_route('signupreport',ucfirst($report_key), ['group_by' =>$report_key])) !!} --}}
                                @if(ucfirst($report_key)=='Agent') Client Manager @else
                                {{ucfirst($report_key)}} @endif
                            </td>
                            <td>
                                {{isset($report_value['new_signup']) ? number_format($report_value['new_signup']) : number_format(0)}}
                           </td>
                           <td>
                                {{isset($report_value['failed_transaction']) ? number_format($report_value['failed_transaction']) : number_format(0)}}
                           </td>
                           <td>
                               {{isset($report_value['cancel_transaction']) ? number_format($report_value['cancel_transaction']) : number_format(0)}}
                           </td>
                           <td>
                                {{isset($report_value['total']) ? number_format($report_value['total']) : number_format(0)}}
                           </td>
                        </tr>
                        @endforeach
                        @elseif(request()->get('group_by') == 'user')
                        @foreach($SignupReport as $client)
                        @php
                            $yesterday = Carbon\Carbon::now()->subDays(1)->format('Y-m-d');
                            $today = Carbon\Carbon::now()->format('Y-m-d');
                        @endphp
                        <tr class="text-center">
                            <td>{{$client['name']}}</td>
                            <td>
                            @if(Carbon\Carbon::createFromFormat('Y-m-d H:i:s',$client['created_at'])->format('Y-m-d') == $today)
                            <label class="label label-success">
                                Yes
                            </label>
                            @else
                            <label class="label label-danger">
                                No
                            </label>
                            @endif
                            </td>
                            <td>
                                @if(isset($client['fail_transaction']))
                                    {{count($client['fail_transaction'])}}
                                @else
                                    {{0}}
                                @endif
                            </td>
                            <td>
                                @if(isset($client['cancel_transaction']))
                                    {{count($client['cancel_transaction'])}}
                                @else
                                    {{0}}
                                @endif
                            </td>
                            <td>
                                @php
                                    $class = 'label-danger';
                                    if($client['status'] == 'active') {
                                            $class = 'label-success';
                                        }
                                @endphp
                                <label class="label {{ $class}}">
                                    {{ trans('comman.' . $client['status']) }}
                                </label>
                            </td>
                        </tr>
                        @endforeach
                    @endif
                @else
                 <tr>
                    <td colspan="5" align="center">{{trans('comman.noData')}}</td>
                </tr>
                @endif
            </tbody>
        </table>
    </div>
</div>