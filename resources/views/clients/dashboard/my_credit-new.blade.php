@extends($theme)
@section('title', $title)
@section('content')
<style type="text/css">
    form {
        margin-bottom: 0;
    }
    .table-bordered > tbody > tr > th, .table-bordered > tbody > tr > td {
        padding: 5px 15px;
    }
</style>
@if(Auth::user()->user_type == 'client')
    <div class="purchase-credit">
        <div class="tab-title">
            <h1>{{ $client_module_title }}</h1>
            <p>
                Enrich your Life Process Program experience with 1-1 coaching.<br>
                Stock up and save with credits that never expire.
            </p>
        </div>
        <section id="credits-box">
            <ul class="options-box">
                @if(isset($credits_package) && count($credits_package) > 0)
                    @foreach($credits_package as $package)
                        {!! Form::open( array('route' => 'client.myCredits.purchase','class'=>'form-horizontal','role'=>"form",'id'=>'client_purchase_credits_'.$package->credit)) !!}
                            <li class="credit-list-item featured-pack">
                                <div class="credit-row-container">
                                    <a class="credit-row" href="#">
                                        <div class="descriptor">
                                            <div class="credits-name">
                                                <div class="text font-semibold">
                                                    <span>
                                                        <strong class="font-bold">
                                                            {{ $package->credit }}
                                                        </strong> credits
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="show-price shortname-currency">
                                            <div class="show-price-everything">
                                                <div class="show-price-combined">
                                                    <span id="credit_{{ $package->credit }}" class="font-bold" >${{ number_format($package->price ,2) * $package->credit }}</span>
                                                    @if($package->credit > 1)
                                                        <div class="savings font-semibold">${{ number_format($package->price ,2) }}/credit</div>
                                                    @endif
                                                    {!! Form::hidden('credits_amount', '') !!}
                                                </div>
                                                <div class="buy-now font-semibold" id="{{ $package->credit }}" onclick="submit_toPurchase(this.id)">
                                                    <span>Buy credits</span>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                            </li>
                            <!-- end ngRepeat: pack in creditPacks -->
                        {!! Form::close() !!}
                    @endforeach
                @else
                    <h6>No credit package found.</h6>
                @endif
            </ul>
        </section>
        <div class="how-credit-work">
            <h5 class="font-bold">How do credits work</h5>
            <p>Credits are Life Process Program's very own currency. Purchase credits and use them to book sessions with your coach.</p>
        </div>
    </div>
@endif
@push('scripts')
    {{-- resources/themes/limitless/assets/js/plugins/forms/inputs/touchspin.min.js --}}
    <script src="{{ asset("themes/limitless/js/touchspin.min.js")}}" type="text/javascript"></script>
    <script type="text/javascript">
        // jQuery(document).ready(function(){
        //     $("select[name='credits']").select2({
        //         minimumResultsForSearch: Infinity
        //     });
        //     jQuery('select[name="credits"]').trigger('change');
        // });

        // function calculate_amount(credits) {
        //     var total_cost = jQuery('#credit_' + credits).html();
        //     jQuery('#total_cost').html('$' + parseFloat(total_cost).toFixed(2));
        // }
        function submit_toPurchase(credits) {
            var total_cost = jQuery('#credit_' + credits).html().split('$');
            jQuery('input[name="credits_amount"]').val(parseFloat(total_cost[1]).toFixed(2));
            jQuery('#client_purchase_credits_' + credits).submit();
        }
    </script>
@endpush
@endsection