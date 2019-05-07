@extends($theme)
<style>
    .heading-elements {
    right: 25px !important;
    margin-top: -13px !important;
}
</style>
@section('title', $title)
@section('content')

@if(Auth::user()->user_type=='client')
<div class="tab-content">
    <div class="tab-pane fade in active" id="coach">
        <div class="tab-title">
            <h1 class="no-margin">
                    FAQ's
                    <div class="faq-question">
                        <h6>
                            {{ Form::open(array('route' => 'faqs.index','method'=>'GET','class'=>'form-filter','role'=>"form")) }}
                            {!! Form::text('question', Request::get('question',null), ['class' => 'col-md-9 margin-right','placeholder'=> 'Type your question here' ]) !!}
                            {!! Form::submit(trans('comman.search'), ['name' => 'search','class' => 'btn btn-xs btn-primary', 'style'=>'margin-left:10px;']) !!}
                        </h6>
                        {!! Form::close() !!}
                    </div>
            </h1>
            <div id="lifestory">
            </div>
        </div>
    </div>
</div>
@elseif(Auth::user()->user_type=='coach')
<div class="content-wrapper">
    <div class="row">
        <div class="col-md-12">

            <div class="panel panel-white">
                <div class="panel-heading">
                    <h5 class="panel-title">FAQ's</h5>
                </div>


                 <div class="faq-question">
                        <h6>
                            {{ Form::open(array('route' => 'faqs.index','method'=>'GET','class'=>'form-filter','role'=>"form")) }}
                            {!! Form::text('question', Request::get('question',null), ['class' => 'col-md-5 ','placeholder'=> 'Type your question here' ]) !!}
                            {!! Form::submit(trans('comman.search'), ['name' => 'search','class' => 'btn btn-xs btn-primary', 'style'=>'margin-left:30px;']) !!}
                        </h6>
                        {!! Form::close() !!}
                    </div>
            </div>
        </div>


@else
<div class="content-wrapper">
    <div class="panel panel-default">
        <div class="panel-heading">
            <h3 class="panel-title">
            FAQ's

                 {!! link_to_route('faqs.create', 'Create FAQ', [], ['class' => 'btn btn-xs btn-primary pull-right', 'style' => 'color: #FFF;']) !!}

                       <span class="pull-right spacer-10">&nbsp;</span>
                        {{ Form::open(array('route' => 'faqs.index','method'=>'GET','class'=>'form-filter pull-right','role'=>"form","style"=>'')) }}

                         <h6 style="margin-top: 0px; padding: 0px;">

                        {!! Form::submit(trans('comman.search'), ['name' => 'search','class' => 'btn btn btn-xs btn-primary']) !!}

                        {!! Form::text('question', Request::get('question',null), ['class' => ' faq-question','placeholder'=> 'Type your question here' ]) !!}

                        </h6>

                {!! Form::close() !!}

            </h3>
        </div>

    </div>


@endif


            @if(isset($faqs) && count($faqs) > 0 )
                        @foreach($faqs as $faq)
                            @php
                                $count = ++$counter;
                            @endphp

                            <div class="col-md-12" style="">
                                    <div class="panel panel-white">
                                                <div class="panel-heading">
                                                    <h6 class="panel-title">
                                                        <a class="collapsed" data-toggle="collapse" href="#question{{ $count }}" aria-expanded="false">
                                                            <i class="icon-help position-left text-slate"></i> {{ $faq->question }}
                                                        </a>
                                                        <i class="fa fa-angle-down "  style="float: right;"></i>
                                                         @if(Auth::user()->hasAnyAccess(['faqs.update','faqs.delete']))
                                                            <div class="heading-elements">
                                                            @if(Auth::user()->hasAccess('faqs.update'))
                                                            {!! Html::decode(link_to_route('faqs.edit', '<i class="icon-pencil7"></i>', array($faq->id))) !!}
                                                            @endif
                                                            @if(Auth::user()->hasAccess('faqs.delete'))
                                                            {!! Html::decode(link_to_route('faqs.destroy', '<i class="icon-trash"></i>', array($faq->id), ['data-method' => 'delete', 'data-modal-text' => ' FAQ?', 'class' => 'action_confirm text-danger-600', 'title' => 'Delete'])) !!}
                                                            @endif
                                                            </div>
                                                        @endif

                                                    </h6>
                                                </div>
                                                <div id="question{{$count}}" class="panel-collapse collapse" aria-expanded="false" style="height: 0px;">
                                                    <div class="panel-body">
                                                        {!! $faq->answer !!}
                                                    </div>
                                                </div>
                                    </div>
                        </div>
                        @endforeach
            @else
                                No data found.
            @endif

</div>
</div>
<style type="text/css">.form-group
{
    margin-bottom: 0px;
}</style>
@endsection