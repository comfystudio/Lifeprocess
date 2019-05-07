<table>
	<thead>
		
	</thead>

	<tbody>

@if(!empty($RevenueReport) && count($RevenueReport) > 0)

 {{-- Year report --}}
            @if((isset($group_by) && $group_by=='year') || !isset($group_by))

                        @foreach($RevenueReport as $report_key=>$report_value)

                             @php $total_payment_received = 0;$total_payment_to_coach = 0;$total_net_revenue = 0; @endphp


                                    <div class="page-title"> <b>{{$report_key}}</b></div>

                                    <table class="table table-bordered table-hover no-footer">

                                         <tbody>

                                                  <tr class="info">
                                                                <th scope="row" >Sales</th>
                                                                <th scope="row" >Amount</th>
                                                                <th scope="row">Ratio (%)</th>
                                                   </tr>

                                                  <tr>
                                                             <td>Subscription Payments</td>
                                                             <td>{{  isset($report_value['total_sub_fee']) ? number_format($report_value['total_sub_fee'],2) : number_format(0,2) }}</td>
                                                             <td>80</td>
                                                  </tr>

                                                   <tr>
                                                             <td> Coaching Session </td>
                                                             <td>{{ isset($report_value['total_scedule']) ? number_format($report_value['total_scedule'],2) : number_format(0,2) }}</td>
                                                             <td>20</td>
                                                   </tr>


                                                <tr class="info">
                                                               <th scope="row">Total Sales</th>

                                                                @php
                                                                        $total_payment_received = isset($report_value['total_sub_fee']) ? $report_value['total_sub_fee'] : 0;
                                                                        $total_payment_received += isset($report_value['total_scedule']) ? $report_value['total_scedule'] : 0;
                                                                @endphp

                                                               <th scope="row" >{{ number_format($total_payment_received,2)  }}</th>
                                                               <th scope="row">100</th>
                                                 </tr>

                                        </tbody>

                                    </table>


                                <br>

                                <table class="table table-bordered table-hover no-footer">

                                             <tbody>

                                                          <tr class="info">
                                                                        <th scope="row" >Cost Of Sales</th>
                                                                        <th scope="row" >Amount</th>
                                                                        <th scope="row">Ratio (%)</th>
                                                           </tr>

                                                          <tr>
                                                                        <td> Modules Feedback </td>
                                                                        <td>{{ isset($report_value['total_module_completed']) ? number_format($report_value['total_module_completed'],2) :  number_format(0,2) }}</td>
                                                                        <td>20</td>
                                                          </tr>

                                                           <tr>
                                                                        <td> Coaching Session </td>
                                                                        <td>{{ isset($report_value['total_pay_scedule']) ? number_format($report_value['total_pay_scedule'],2) :  number_format(0,2) }}</td>
                                                                        <td>25</td>
                                                           </tr>

                                                            <tr class="info">
                                                                         <th scope="row">Total for Cost of Sales</th>

                                                                             @php
                                                                                $total_payment_to_coach = isset($report_value['total_module_completed']) ? $report_value['total_module_completed'] : 0;
                                                                                $total_payment_to_coach += isset($report_value['total_pay_scedule']) ? $report_value['total_pay_scedule'] : 0;
                                                                            @endphp


                                                                         <th scope="row">{{number_format($total_payment_to_coach,2)}}</th>
                                                                         <th scope="row">45</th>
                                                            </tr>

                                            </tbody>
                                </table>


                                <br>

                                    <table class="table table-bordered table-hover no-footer">

                                             <tbody>

                                                      <tr class="info">
                                                                          <th scope="row" > Gross Profit </th>

                                                                                 @php
                                                                                    $total_net_revenue= $total_payment_received;
                                                                                    $total_net_revenue -= $total_payment_to_coach;
                                                                                 @endphp

                                                                          <th scope="row"> {{number_format($total_net_revenue,2)}} </th>
                                                                          <th scope="row">55</th>
                                                      </tr>

                                              </tbody>

                                    </table>


                        @endforeach


            @endif

@endif

 {{-- End Year report --}}		







	</tbody>


</table>