<style type="text/css">
  .table > thead > tr > th, .table > tbody > tr > th, .table > tfoot > tr > th, .table > thead > tr > td, .table > tbody > tr > td, .table > tfoot > tr > td
  {
    padding: 10px 5px;
  }
  .col-md-1
  {
    width:6.5%;
  }
  .table > tbody > tr > th
  {
    font-size: 10px;
  }
</style>
<div class="panel panel-default">
  <div class="panel-heading">
    <h5 class="panel-title"><b>{{ $title }}</b></h5>
    <div class="heading-elements">
      <a href="{!! route('financialreportpdf', request()->all()) !!}" type="button" >  {{Html::image(AppHelper::path('images/')->getImageUrl('pdf.svg'),'pdf ',array('id'=>'friend','height'=>'35px','width'=>'65px','style'=>'margin: 0px -18px 0 0;'))}}  </a>

      <a href="{!! route('financialreportxls', request()->all()) !!}" type="button" >  {{Html::image(AppHelper::path('images/')->getImageUrl('xls.svg'),'xls',array('id'=>'friend','height'=>'35px','width'=>'65px','style'=>'margin: 0px -18px 0 0;'))}}  </a>

      <a href="{!! route('financialreportcsv', request()->all()) !!}" type="button" >  {{Html::image(AppHelper::path('images/')->getImageUrl('csv.svg'),'help a friend',array('id'=>'friend','height'=>'35px','width'=>'65px','style'=>'margin: 0px -18px 0 0;'))}}  </a>

      <a href="#" id="print" onclick="javascript:window.print();" type="button" >  {{Html::image(AppHelper::path('images/')->getImageUrl('print.svg'),'help a friend',array('id'=>'friend','height'=>'35px','width'=>'65px','style'=>'margin: 0px -18px 0 0;'))}}  </a>
    </div>
  </div>


  <div class="panel-body">

    @if(!empty($RevenueReport) && count($RevenueReport) > 0)

      {{-- Year report --}}
      @if((isset($group_by) && $group_by=='year') || !isset($group_by))
        <div class="col-sm-3">
          <table class="table table-bordered table-hover no-footer">
            <tbody>
              <tr class="info">
                <th scope="">Sales</th>
              </tr>
              <tr>
                <th scope="row" >Subscription Payments</th>
              </tr>
              <tr>
                <th scope="row">Coaching Session</th>
              </tr>
              <tr class="info">
                <th scope="row" style="width: 242px;">Total Sales</th>
              </tr>
            </tbody>
          </table>
          <br>
          <table class="table table-bordered table-hover no-footer">
            <tbody>
              <tr class="info">
                <th scope="">Sales</th>
              </tr>
              <tr>
                <th scope="row">Modules Feedback</th>
              </tr>
              <tr>
                <th scope="row">Coaching Session</th>
              </tr>
              <tr class="info">
                <th scope="row" style="width: 242px;">Total Sales</th>
              </tr>
            </tbody>
          </table>
          <br>
          <table class="table table-bordered table-hover no-footer">
            <tbody>
              <tr class="info">
                <th scope="" >Gross Profit</th>
              </tr>
            </tbody>
          </table>
        </div>
        @foreach($RevenueReport as $report_key=>$report_value)

          @php
            $now = Carbon\Carbon::now();
            $year = $now->format('Y');
            $year=$year-3;
          @endphp

          @if($report_key>$year)
            @php

            $total_payment_received = 0;$total_payment_to_coach = 0;$total_net_revenue = 0;

            /* First Table Calculation */

            $total_sub_fee = isset($report_value['total_sub_fee']) ? $report_value['total_sub_fee'] : 0;
            $total_scedule = isset($report_value['total_scedule']) ? $report_value['total_scedule'] : 0;
            $total_payment_received = $total_sub_fee + $total_scedule;

            /* Second Table Calculation */
            $total_module_completed = isset($report_value['total_module_completed']) ? $report_value['total_module_completed'] : 0;
            $total_pay_scedule = isset($report_value['total_pay_scedule']) ? $report_value['total_pay_scedule'] : 0;
            $total_payment_to_coach = $total_module_completed + $total_pay_scedule;


            if($total_payment_received == 0)
            {
              $ratio_of_subscription_payments = 0;
              $ratio_of_coaching_session = 0;
              $total_ratio_of_sales =  0;

              if($total_payment_to_coach ==0)
              {
                $ratio_of_total_module_completed = 0;
                $ratio_of_total_pay_scedule = 0;
                $total_ratio_of_coast_sales = 0;
              }
              else{

                $ratio_of_total_module_completed = ($total_module_completed * 100) / $total_payment_to_coach;
                $ratio_of_total_pay_scedule = ($total_pay_scedule * 100) / $total_payment_to_coach;
                $total_ratio_of_coast_sales =  $ratio_of_total_module_completed + $ratio_of_total_pay_scedule;
              }

            }
            else{
              $ratio_of_subscription_payments = ($total_sub_fee * 100) / $total_payment_received;
              $ratio_of_coaching_session = ($total_scedule * 100) / $total_payment_received;
              $total_ratio_of_sales =  $ratio_of_subscription_payments + $ratio_of_coaching_session;

              /* For Second Table Ratio */
              $ratio_of_total_module_completed = ($total_module_completed * 100) / $total_payment_received;
              $ratio_of_total_pay_scedule = ($total_pay_scedule * 100) / $total_payment_received;
              $total_ratio_of_coast_sales =  $ratio_of_total_module_completed + $ratio_of_total_pay_scedule;
            }

            @endphp

            <div class="col-sm-3">
              <table class="table table-bordered table-hover no-footer">
                <tbody>
                  <tr class="info">
                    <th scope="row" ><b>{{$report_key}}</b></th>
                  </tr>
                  <tr>
                    <th>{{  number_format($total_sub_fee,2) }}</th>
                  </tr>
                  <tr>
                    <th>{{ number_format($total_scedule,2) }}</th>
                  </tr>
                  <tr class="info" style="text-align: left;">
                    <th scope="row" >{{ number_format($total_payment_received,2)  }}</th>
                  </tr>
                </tbody>
              </table>
              <br>
              <table class="table table-bordered table-hover no-footer">
                <tbody>
                  <tr class="info">
                    <th><b>{{$report_key}}</b></th>
                  </tr>
                  <tr>
                    <th>{{ number_format($total_module_completed,2) }}</th>
                  </tr>
                  <tr>
                    <th>{{ number_format($total_pay_scedule,2) }}</th>
                  </tr>
                  <tr class="info">
                    <th scope="row">{{ number_format($total_payment_to_coach,2) }}</th>
                  </tr>
                </tbody>
              </table>
              <br>
              @php
              $total_net_revenue= $total_payment_received;
              $total_net_revenue -= $total_payment_to_coach;

              $total_net_ratio = $total_ratio_of_sales;
              $total_net_ratio -= $total_ratio_of_coast_sales;

              @endphp
              <table class="table table-bordered table-hover no-footer">
                <tbody>
                  <tr class="info">
                    <th scope="row"> {{number_format($total_net_revenue,2)}} </th>
                  </tr>
                </tbody>
              </table>
            </div>
          @endif
        @endforeach
      @endif
    @endif

    {{-- End Year report --}}

    {{-- Start Day report --}}

      @if(isset($group_by) && $group_by=='day')

        @php $count = 0;  @endphp

        @foreach($RevenueReport as $day_key=>$day_value)

        @php
          $count++;
          $total_payment_received = 0;$total_payment_to_coach = 0;$total_net_revenue = 0;

          /* First Table Calculation */

          $total_sub_fee = isset($day_value['total_sub_fee']) ? $day_value['total_sub_fee'] : 0;
          $total_scedule = isset($day_value['total_scedule']) ? $day_value['total_scedule'] : 0;
          $total_payment_received = $total_sub_fee + $total_scedule;

          /* Second Table Calculation */
          $total_module_completed = isset($day_value['total_module_completed']) ? $day_value['total_module_completed'] : 0;
          $total_pay_scedule = isset($day_value['total_pay_scedule']) ? $day_value['total_pay_scedule'] : 0;
          $total_payment_to_coach = $total_module_completed + $total_pay_scedule;


          if($total_payment_received == 0)
          {
            $ratio_of_subscription_payments = 0;
            $ratio_of_coaching_session = 0;
            $total_ratio_of_sales =  0;

            if($total_payment_to_coach ==0)
            {
              $ratio_of_total_module_completed = 0;
              $ratio_of_total_pay_scedule = 0;
              $total_ratio_of_coast_sales = 0;
            }
            else{

              $ratio_of_total_module_completed = ($total_module_completed * 100) / $total_payment_to_coach;
              $ratio_of_total_pay_scedule = ($total_pay_scedule * 100) / $total_payment_to_coach;
              $total_ratio_of_coast_sales =  $ratio_of_total_module_completed + $ratio_of_total_pay_scedule;
            }

          }
          else{
            $ratio_of_subscription_payments = ($total_sub_fee * 100) / $total_payment_received;
            $ratio_of_coaching_session = ($total_scedule * 100) / $total_payment_received;
            $total_ratio_of_sales =  $ratio_of_subscription_payments + $ratio_of_coaching_session;

            /* For Second Table Ratio */
            $ratio_of_total_module_completed = ($total_module_completed * 100) / $total_payment_received;
            $ratio_of_total_pay_scedule = ($total_pay_scedule * 100) / $total_payment_received;
            $total_ratio_of_coast_sales =  $ratio_of_total_module_completed + $ratio_of_total_pay_scedule;

          }

          @endphp

                @if(isset($day_key))
                    <div class="page-title"> <b>{{Carbon\Carbon::createFromFormat('d-m-Y',$day_key)->format('m/d/Y')}}</b></div>
                @else
                    <div class="page-title"></div>
                @endif


                    <table class="table table-bordered table-hover no-footer">

                        <tbody>

                          <tr class="info">
                      <th scope="row" >Sales</th>
                      <th scope="row" >Subscription Payments</th>
                      <th scope="row">Coaching Session</th>
                      <th scope="row" style="width: 242px;">Total Sales</th>
                    </tr>

                    <tr>
                      <td>Amount</td>
                      <td>{{  number_format($total_sub_fee,2) }}</td>
                      <td>{{ number_format($total_scedule,2) }}</td>
                      <th scope="row" >{{ number_format($total_payment_received,2)  }}</th>
                    </tr>

                  <tr>
                      <td> Ratio (%) </td>
                      {{-- <td>{{ number_format($total_scedule,2) }}</td> --}}
                      <td>{{ number_format($ratio_of_subscription_payments,2) }}</td>
                      <td>{{ number_format($ratio_of_coaching_session,2) }}</td>
                      <th scope="row">{{ number_format($total_ratio_of_sales,2)  }}</th>
                    </tr>

                        </tbody>

                    </table>


                    <table class="table table-bordered table-hover no-footer" style="margin-top:10px;">

                        <tbody>

                            <tr class="info">
                      <th scope="row" >Sales</th>
                      <th scope="row" style="width: 323px;">Modules Feedback</th>
                      <th scope="row">Coaching Session</th>
                      <th scope="row">Total Cost of Sales</th>
                    </tr>

                  <tr>
                    <td>Amount</td>
                    <td>{{ number_format($total_module_completed,2) }}</td>
                    <td>{{ number_format($total_pay_scedule,2) }}</td>
                    <th scope="row">{{ number_format($total_payment_to_coach,2) }}</th>
                  </tr>

                  <tr>
                    <td> Ratio (%) </td>
                    <td>{{ number_format($ratio_of_total_module_completed,2) }}</td>
                    <td> {{  number_format($ratio_of_total_pay_scedule,2)  }}</td>
                    <th scope="row"> {{  number_format($total_ratio_of_coast_sales,2)  }} </th>
                  </tr>

                        </tbody>

                    </table>


                    <table class="table table-bordered table-hover no-footer" style="margin-top:10px; @if($count%2==0) page-break-after: always; @endif">

                        <tbody>

                            <tr class="info">
                                  <th scope="row" > Gross Profit </th>
                                  @php
                                  $total_net_revenue= $total_payment_received;
                                  $total_net_revenue -= $total_payment_to_coach;

                                  $total_net_ratio = $total_ratio_of_sales;
                                  $total_net_ratio -= $total_ratio_of_coast_sales;
                                  @endphp

                                  <th scope="row"> {{ number_format($total_net_revenue,2) }} </th>
                                  <th scope="row">{{ number_format($total_net_ratio,2) }}</th>
                            </tr>

                        </tbody>

                    </table>

            @endforeach

        @endif
    {{-- End Day report --}}

    {{-- Start Month report --}}

    @if(isset($group_by) && $group_by=='month')
            @php $cou = 0; @endphp
      <div class="col-md-2 col-sm-2">
          <table class="table table-bordered table-hover no-footer">
            <tbody>
              <tr class="info">
                <th scope="">Sales</th>
              </tr>
              <tr>
                <th scope="row" >Subscription Payments</th>
              </tr>
              <tr>
                <th scope="row">Coaching Session</th>
              </tr>
              <tr class="info">
                <th scope="row" style="width: 242px;">Total Sales</th>
              </tr>
            </tbody>
          </table>
          <br>
          <table class="table table-bordered table-hover no-footer">
            <tbody>
              <tr class="info">
                <th scope="">Sales</th>
              </tr>
              <tr>
                <th scope="row">Modules Feedback</th>
              </tr>
              <tr>
                <th scope="row">Coaching Session</th>
              </tr>
              <tr class="info">
                <th scope="row" style="width: 242px;">Total Sales</th>
              </tr>
            </tbody>
          </table>
          <br>
          <table class="table table-bordered table-hover no-footer">
            <tbody>
              <tr class="info">
                <th scope="" >Gross Profit</th>
              </tr>
            </tbody>
          </table>
      </div>
            @foreach(config('srtpl.months') as $mnth_key=>$Month_value)

                @php  $cou++; @endphp

                @if(array_key_exists($mnth_key,$RevenueReport))

                    @php

                      $total_payment_received = 0;$total_payment_to_coach = 0;$total_net_revenue = 0;

                      /* First Table Calculation */
                      $total_sub_fee = isset($RevenueReport[$mnth_key]['total_sub_fee']) ? $RevenueReport[$mnth_key]['total_sub_fee'] : 0;
                      $total_scedule = isset($RevenueReport[$mnth_key]['total_scedule']) ? $RevenueReport[$mnth_key]['total_scedule'] : 0;
                      $total_payment_received = $total_sub_fee + $total_scedule;

                      /* Second Table Calculation */
                      $total_module_completed = isset($RevenueReport[$mnth_key]['total_module_completed']) ? $RevenueReport[$mnth_key]['total_module_completed'] : 0;
                      $total_pay_scedule = isset($RevenueReport[$mnth_key]['total_pay_scedule']) ? $RevenueReport[$mnth_key]['total_pay_scedule'] : 0;
                      $total_payment_to_coach = $total_module_completed + $total_pay_scedule;


                      if($total_payment_received == 0)
                      {
                        $ratio_of_subscription_payments = 0;
                        $ratio_of_coaching_session = 0;
                        $total_ratio_of_sales =  0;

                        if($total_payment_to_coach ==0)
                        {
                          $ratio_of_total_module_completed = 0;
                          $ratio_of_total_pay_scedule = 0;
                          $total_ratio_of_coast_sales = 0;
                        }
                        else{

                          $ratio_of_total_module_completed = ($total_module_completed * 100) / $total_payment_to_coach;
                          $ratio_of_total_pay_scedule = ($total_pay_scedule * 100) / $total_payment_to_coach;
                          $total_ratio_of_coast_sales =  $ratio_of_total_module_completed + $ratio_of_total_pay_scedule;
                        }

                      }
                      else{
                        $ratio_of_subscription_payments = ($total_sub_fee * 100) / $total_payment_received;
                        $ratio_of_coaching_session = ($total_scedule * 100) / $total_payment_received;
                        $total_ratio_of_sales =  $ratio_of_subscription_payments + $ratio_of_coaching_session;

                        /* For Second Table Ratio */
                        $ratio_of_total_module_completed = ($total_module_completed * 100) / $total_payment_received;
                        $ratio_of_total_pay_scedule = ($total_pay_scedule * 100) / $total_payment_received;
                        $total_ratio_of_coast_sales =  $ratio_of_total_module_completed + $ratio_of_total_pay_scedule;
                      }

                    @endphp
            <div class="col-md-1 col-sm-1">
              <table class="table table-bordered table-hover no-footer">
                <tbody>
                  <tr class="info">
                    <th scope="row" ><b>{{$Month_value}}</b></th>
                  </tr>
                  <tr>
                    <th>{{  number_format($total_sub_fee,2) }}</th>
                  </tr>
                  <tr>
                    <th>{{ number_format($total_scedule,2) }}</th>
                  </tr>
                  <tr class="info" style="text-align: left;">
                    <th scope="row" >{{ number_format($total_payment_received,2)  }}</th>
                  </tr>
                </tbody>
              </table>
              <br>
              <table class="table table-bordered table-hover no-footer">
                <tbody>
                  <tr class="info">
                    <th><b>{{$Month_value}}</b></th>
                  </tr>
                  <tr>
                    <th>{{ number_format($total_module_completed,2) }}</th>
                  </tr>
                  <tr>
                    <th>{{  number_format($ratio_of_total_pay_scedule,2)  }}</th>
                  </tr>
                  <tr class="info">
                    <th scope="row">{{ number_format($total_payment_to_coach,2) }}</th>
                  </tr>
                </tbody>
              </table>
              <br>
               @php
                                                      $total_net_revenue= $total_payment_received;
                                                      $total_net_revenue -= $total_payment_to_coach;

                                                      $total_net_ratio = $total_ratio_of_sales;
                                                      $total_net_ratio -= $total_ratio_of_coast_sales;
                                                      @endphp
              <table class="table table-bordered table-hover no-footer">
                <tbody>
                  <tr class="info">
                    <th scope="row"> {{number_format($total_net_revenue,2)}} </th>
                  </tr>
                </tbody>
              </table>
            </div>

                @else

            <div class="col-md-1 col-sm-1">
              <table class="table table-bordered table-hover no-footer">
                <tbody>
                  <tr class="info">
                    <th scope="row" ><b>{{$Month_value}}</b></th>
                  </tr>
                  <tr>
                    <th>0.00</th>
                  </tr>
                  <tr>
                    <th>0.00</th>
                  </tr>
                  <tr class="info" style="text-align: left;">
                    <th scope="row" >0.00</th>
                  </tr>
                </tbody>
              </table>
              <br>
              <table class="table table-bordered table-hover no-footer">
                <tbody>
                  <tr class="info">
                    <th><b>{{$Month_value}}</b></th>
                  </tr>
                  <tr>
                    <th>0.00</th>
                  </tr>
                  <tr>
                    <th>0.00</th>
                  </tr>
                  <tr class="info">
                    <th scope="row">0.00</th>
                  </tr>
                </tbody>
              </table>
              <br>

              <table class="table table-bordered table-hover no-footer">
                <tbody>
                  <tr class="info">
                    <th scope="row">0.00 </th>
                  </tr>
                </tbody>
              </table>
            </div>


                @endif

            @endforeach

        @endif

    {{-- End Month report --}}

