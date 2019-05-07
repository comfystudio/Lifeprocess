@extends($theme)
@section('content')
<div class="col-md-8 col-md-offset-2">
	<div class="panel panel-success">
		<div class="panel-heading">
			<h6 class="panel-title">Thank you<a class="heading-elements-toggle"><i class="icon-more"></i></a></h6>
		</div>
		<div class="panel-body">
			@if(isset($page))
                @if(Auth::user()->user_type == 'user' && Auth::user()->hasAccess('pages.update'))
                    <div class="pull-right">{!! Html::decode(link_to_route('pages.edit', '<i class="icon-pencil7"></i>', array($page->id,'_url'=> Request::path()))) !!}</div>
                @endif
                {!!html_entity_decode($page->content)!!}
            @else
                Static page thank-you-page-content not found
            @endif
			<center>{!! link_to(URL::full(), "Redisplay Form",array('class' => 'btn btn-primary ')) !!}</center>
		</div>
	</div>
</div>
@endsection