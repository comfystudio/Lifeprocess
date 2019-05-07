<div class="panel panel-default">
        <div class="panel-heading">
            <h5 class="panel-title"><b>{{ $title }}</b></h5>
            <div class="heading-elements">
                <!--<a  href=" {!! route('financialreportpdf', request()->all()) !!}" type="button" class="btn bg-info btn-labeled heading-btn"><b><i class="fa fa-file-pdf-o"></i></b>{{ trans('comman.saveaspdf')}}</a> -->


                <a href="{!! route('financialreportpdf', request()->all()) !!}" type="button" >  {{Html::image(AppHelper::path('images/')->getImageUrl('pdf.png'),'help a friend',array('id'=>'friend','height'=>'40px','width'=>'60px','style'=>'margin: 0px -18px 0 0;'))}}  </a>

                <a href="{!! route('financialreportxls', request()->all()) !!}" type="button" >  {{Html::image(AppHelper::path('images/')->getImageUrl('xls.png'),'help a friend',array('id'=>'friend','height'=>'40px','width'=>'60px','style'=>'margin: 0 -20px 0 0;'))}}   </a>

                 <a href="{!! route('financialreportcsv', request()->all()) !!}" type="button" >  {{Html::image(AppHelper::path('images/')->getImageUrl('csv.png'),'help a friend',array('id'=>'friend','height'=>'40px','width'=>'60px','style'=>'margin: 0 -15px 0 0;'))}}   </a>

                   <a href="{!! route('financialreportprint', request()->all()) !!}" type="button" >  {{Html::image(AppHelper::path('images/')->getImageUrl('print.png'),'help a friend',array('id'=>'friend','height'=>'40px','width'=>'60px'))}}   </a>


            </div>
        </div>
        <div class="panel-body">
            <table class="table table-bordered table-hover no-footer">
                <thead>
                    <tr>
                        <th colspan="2"></th>
                        <th colspan="3" class="text-center">Payment Received</th>
                        <th colspan="3" class="text-center">Payments to Coaches</th>
                        <th colspan="3" class="text-center">Net Revenue (Payment Received minus Payment to Coaches)</th>
                    </tr>
                    <tr>
                        <th style="width: 50px;" class="text-center">{{ trans('comman.no') }}</th>
                         @if((isset($group_by) && $group_by=='year') || !isset($group_by))
                            <th class="text-center">{{trans("comman.year")}}</th>
                        @endif
                        @if(isset($group_by) && $group_by=='month')
                            <th class="text-center">{{trans("comman.month")}}</th>
                        @endif
                        @if(isset($group_by) && $group_by=='day')
                            <th class="text-center">{{trans("comman.day")}}</th>
                        @endif
                        <th class="text-center"> Subscription Payments </th>
                        <th class="text-center"> Coaching Sessions </th>
                        <th class="text-center">Total</th>
                        <th class="text-center">Module Completions</th>
                        <th class="text-center">Coaching Sessions</th>
                        <th class="text-center">Total</th>
                        <th class="text-center">Subscription Payments</th>
                        <th class="text-center">Coaching Sessions</th>
                        <th class="text-center">Total</th>
                    </tr>
                </thead>
                <tbody>
                @if(!empty($RevenueReport) && count($RevenueReport) > 0)
                {{-- Year report --}}
                        @if((isset($group_by) && $group_by=='year') || !isset($group_by))
                            @foreach($RevenueReport as $report_key=>$report_value)
                            @php $total_payment_received = 0;$total_payment_to_coach = 0;$total_net_revenue = 0; @endphp
                                <tr class="text-center">
                                    <td> {{$count++}} </td>
                                    <td>{{$report_key}}</td>
                                    <td>
                                        {{isset($report_value['total_sub_fee']) ? number_format($report_value['total_sub_fee'],2) : number_format(0,2)}}
                                    </td>
                                    <td>
                                        {{isset($report_value['total_scedule']) ? number_format($report_value['total_scedule'],2) : number_format(0,2)}}
                                    </td>
                                    <td>
                                        @php
                                            $total_payment_received = isset($report_value['total_sub_fee']) ? $report_value['total_sub_fee'] : 0;
                                            $total_payment_received += isset($report_value['total_scedule']) ? $report_value['total_scedule'] : 0;
                                        @endphp
                                        {{number_format($total_payment_received,2)}}
                                    </td>
                                    <td>
                                        {{isset($report_value['total_module_completed']) ? number_format($report_value['total_module_completed'],2) : number_format(0,2)}}
                                    </td>
                                    <td>
                                        {{isset($report_value['total_pay_scedule']) ? number_format($report_value['total_pay_scedule'],2) : number_format(0,2)}}
                                    </td>
                                    <td>
                                        @php
                                            $total_payment_to_coach = isset($report_value['total_module_completed']) ? $report_value['total_module_completed'] : 0;
                                            $total_payment_to_coach += isset($report_value['total_pay_scedule']) ? $report_value['total_pay_scedule'] : 0;
                                        @endphp
                                        {{number_format($total_payment_to_coach,2)}}
                                    </td>
                                    <td>
                                        {{number_format($total_payment_received,2)}}
                                    </td>
                                    <td>
                                        {{number_format($total_payment_to_coach,2)}}
                                    </td>
                                    <td>
                                       @php
                                        $total_net_revenue= $total_payment_received;
                                        $total_net_revenue -= $total_payment_to_coach;
                                     @endphp
                                        {{number_format($total_net_revenue,2)}}
                                    </td>
                                </tr>
                            @endforeach
                        @endif
                        {{-- end year report --}}
                        {{-- Month report --}}
                    {{-- @if(!empty($RevenueReport) && count($RevenueReport) > 0) --}}
                        @if(isset($group_by) && $group_by=='month')
                            @foreach(config('srtpl.months') as $mnth_key=>$Month_value)
                            @php $total_payment_received = 0;$total_payment_to_coach = 0;$total_net_revenue = 0; @endphp
                            <tr class="text-center">
                                <td> {{$count++}} </td>
                                <td>{{$Month_value}}</td>
                                @if(array_key_exists($mnth_key,$RevenueReport))
                                <td>{{isset($RevenueReport[$mnth_key]['total_sub_fee']) ? number_format($RevenueReport[$mnth_key]['total_sub_fee'],2) : number_format(0,2) }}</td>
                                <td>{{isset($RevenueReport[$mnth_key]['total_scedule']) ? number_format($RevenueReport[$mnth_key]['total_scedule'],2) : number_format(0,2) }}</td>
                                <td>
                                    @php
                                        $total_payment_received = isset($RevenueReport[$mnth_key]['total_sub_fee']) ? $RevenueReport[$mnth_key]['total_sub_fee'] : 0;
                                        $total_payment_received += isset($RevenueReport[$mnth_key]['total_scedule']) ? $RevenueReport[$mnth_key]['total_scedule'] : 0;
                                    @endphp
                                    {{number_format($total_payment_received,2)}}
                                </td>
                                <td>{{isset($RevenueReport[$mnth_key]['total_module_completed']) ? number_format($RevenueReport[$mnth_key]['total_module_completed'],2) : number_format(0,2) }}</td>
                                <td>{{isset($RevenueReport[$mnth_key]['total_pay_scedule']) ? number_format($RevenueReport[$mnth_key]['total_pay_scedule'],2) : number_format(0,2) }}</td>
                                <td>
                                    @php
                                    $total_payment_to_coach = isset($RevenueReport[$mnth_key]['total_module_completed']) ? $RevenueReport[$mnth_key]['total_module_completed'] : 0;
                                        $total_payment_to_coach += isset($RevenueReport[$mnth_key]['total_pay_scedule']) ? $RevenueReport[$mnth_key]['total_pay_scedule'] : 0;
                                    @endphp
                                    {{number_format($total_payment_to_coach,2)}}
                                </td>
                                <td>{{number_format($total_payment_received,2)}}</td>
                                <td>{{number_format($total_payment_to_coach,2)}}</td>
                                <td>
                                    @php
                                        $total_net_revenue= $total_payment_received;
                                        $total_net_revenue -= $total_payment_to_coach;
                                     @endphp
                                     {{number_format($total_net_revenue,2)}}
                                </td>
                                @else
                                    <td>0.00</td>
                                    <td>0.00</td>
                                    <td>0.00</td>
                                    <td>0.00</td>
                                    <td>0.00</td>
                                    <td>0.00</td>
                                    <td>0.00</td>
                                    <td>0.00</td>
                                    <td>0.00</td>
                                @endif
                            </tr>
                            @endforeach
                        @endif
                        {{-- Month report end --}}
                        {{-- Day report --}}
                        @if(isset($group_by) && $group_by=='day')
                            @foreach($RevenueReport as $day_key=>$day_value)
                            @php $total_payment_received = 0;$total_payment_to_coach = 0;$total_net_revenue = 0; @endphp
                                <tr class="text-center">
                                        <td>{{$count++}}</td>
                                        @if(isset($day_key))
                                        <td style="width:10%;">{{Carbon\Carbon::createFromFormat('d-m-Y',$day_key)->format('m/d/Y')}}</td>
                                        @else
                                        <td style="width:10%;">

                                        </td>
                                        @endif
                                        <td>{{isset($day_value['total_sub_fee']) ? number_format($day_value['total_sub_fee'],2) : number_format(0,2)}}</td>
                                        <td>{{isset($day_value['total_scedule']) ? number_format($day_value['total_scedule'],2) : number_format(0,2)}}</td>
                                        <td>
                                            @php
                                                $total_payment_received = isset($day_value['total_sub_fee']) ? $day_value['total_sub_fee'] : 0;
                                                $total_payment_received += isset($day_value['total_scedule']) ? $day_value['total_scedule'] : 0;
                                            @endphp
                                            {{number_format($total_payment_received,2)}}
                                        </td>
                                        <td>
                                            {{isset($day_value['total_module_completed']) ? number_format($day_value['total_module_completed'],2) : number_format(0,2)}}
                                        </td>
                                        <td>
                                            {{isset($day_value['total_pay_scedule']) ? number_format($day_value['total_pay_scedule'],2) : number_format(0,2)}}
                                        </td>
                                        <td>
                                            @php
                                                $total_payment_to_coach = isset($day_value['total_module_completed']) ? $day_value['total_module_completed'] : 0;
                                                $total_payment_to_coach += isset($day_value['total_pay_scedule']) ? $day_value['total_pay_scedule'] : 0;
                                            @endphp
                                            {{number_format($total_payment_to_coach,2)}}
                                        </td>
                                        <td>
                                            {{number_format($total_payment_received,2)}}
                                        </td>
                                        <td>
                                            {{number_format($total_payment_to_coach,2)}}
                                        </td>
                                        <td>
                                            @php
                                                $total_net_revenue= $total_payment_received;
                                                $total_net_revenue -= $total_payment_to_coach;
                                            @endphp
                                            {{number_format($total_net_revenue,2)}}
                                        </td>
                                </tr>
                            @endforeach
                        @endif
                        {{-- Day Report end --}}
                        @else
                            <tr>
                                <td colspan="13" align="center">{{trans('comman.noData')}}</td>
                            </tr>
                        @endif
                </tbody>
            </table>
        </div>
    </div>