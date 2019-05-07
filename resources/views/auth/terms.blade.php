@extends('limitless.login')
@section('content')
    <div class="panel panel-white">
        <div class="panel-body">
            @if(isset($page))
                {!!html_entity_decode($page->content)!!}
            @else
                Static page terms-conditon not found
            @endif
        </div>
    </div>
@endsection