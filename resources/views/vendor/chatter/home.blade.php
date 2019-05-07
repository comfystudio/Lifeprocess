@extends(Config::get('chatter.master_file_extend'))

@section(Config::get('chatter.yields.head'))
    <link rel="stylesheet" type="text/css" href="{{ elixir('themes/limitless/fonts/OpenSans/stylesheet.css') }}">
    <link rel="stylesheet" href="{{ elixir('themes/limitless/css/client-all.css') }}">
    <link href="{{ url('/vendor/devdojo/chatter/assets/vendor/spectrum/spectrum.css') }}" rel="stylesheet">
	<link href="{{ url('/vendor/devdojo/chatter/assets/css/chatter.css') }}" rel="stylesheet">
	@if($chatter_editor == 'simplemde')
		<link href="{{ url('/vendor/devdojo/chatter/assets/css/simplemde.min.css') }}" rel="stylesheet">
	@elseif($chatter_editor == 'trumbowyg')
		<link href="{{ url('/vendor/devdojo/chatter/assets/vendor/trumbowyg/ui/trumbowyg.css') }}" rel="stylesheet">
		<style>
			.trumbowyg-box, .trumbowyg-editor {
				margin: 0px auto;
			}
		</style>
	@endif
@stop

<style type="text/css">
.navbar-collapse
{
    padding-right: 0px;
    padding-left: 0px;
}
.navbar
{
    margin-left: 10px;
    width: fit-content;
}
.navbar-toggle
{
    border: 1px solid #f44336;
    background-color: white;
    margin-right: 0px;
    margin-left: 20px;
}
.dropdown-menu hr{
    margin: 0;
}
</style>

@section('content')

<div id="chatter" class="chatter_home">
	@if(config('chatter.errors'))
		@if(Session::has('chatter_alert'))
			<div class="chatter-alert alert alert-{{ Session::get('chatter_alert_type') }}">
				<div class="container">
					<strong><i class="chatter-alert-{{ Session::get('chatter_alert_type') }}"></i> {{ Config::get('chatter.alert_messages.' . Session::get('chatter_alert_type')) }}</strong>
					{{ Session::get('chatter_alert') }}
					<i class="chatter-close"></i>
				</div>
			</div>
			<div class="chatter-alert-spacer"></div>
		@endif

		@if (count($errors) > 0)
			<div class="chatter-alert alert alert-danger">
				<div class="container">
					<p><strong><i class="chatter-alert-danger"></i> @lang('chatter::alert.danger.title')</strong> @lang('chatter::alert.danger.reason.errors')</p>
					<ul>
						@foreach ($errors->all() as $error)
							<li>{{ $error }}</li>
						@endforeach
					</ul>
				</div>
			</div>
		@endif
	@endif

	<div class="page-container">
	    <div class="content" @if(isset($agent_theme) && !empty($agent_theme['colour_4'])) style="background: {{$agent_theme['colour_4']}}"@endif>
	        <div class="container-fluid">
                <div class="row">
                    <div class="col-md-2 col-sm-6">
                        <div class="menu">
                            <nav class="navbar navbar-default">
                            <!-- Brand and toggle get grouped for better mobile display -->

                                <!-- Collect the nav links, forms, and other content for toggling -->
                                <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1" style="padding: 0px;">
                                    <ul class="nav navbar-nav nav-tabs">
                                        <li class="title" @if(isset($agent_theme) && !empty($agent_theme['colour_3'])) style="background: {{$agent_theme['colour_3']}}"@endif>
                                             <span>Main Navigation</span>
                                        </li>
                                        <li class="{{ (Request::segment(2)=='program' ? 'active' : '' ) }}">
                                            <a href="{{ route('client.dashboard') }}">
                                                <img src="{{ asset('themes/limitless/images/client-view/home-button.png') }}" class="icon">
                                                <span class="font-semibold text-black">Program Modules</span>
                                            </a>
                                        </li>
                                        @if(Auth::user()->hasAccess('messages.view'))
                                        <li class="message {{ (Request::segment(1)=='messages' ? 'active' : '' ) }}">
                                            <a href="{{ route('messages.index') }}">
                                                <img src="{{ asset('themes/limitless/images/client-view/speech-bubble.png') }}" class="icon">
                                                <span class="font-semibold text-black">Messages
                                                    {{--@if($unread_counter != '0')--}}
                                                    {{--<i class="font-bold msg">{{ $unread_counter }}</i>--}}
                                                    {{--@endif--}}
                                                </span>
                                            </a>
                                        </li>
                                        @endif

                                        {{--@if(isset($llp_coach) && $llp_coach)--}}
                                        @if($user_credits >= 1)
                                            <li class="{{ (Request::segment(1)=='client_coaching' || Request::segment(1)=='booking' ? 'active' : '' ) }}">
                                                <a href="{{ route('clients.dashboard.coaching') }}">
                                                    <img src="{{ asset('themes/limitless/images/client-view/avatar.png') }}" class="icon">
                                                    <span class="font-semibold text-black">Coaching</span>
                                                </a>
                                            </li>
                                        @endif

                                        @if(Auth::user()->hasAccess('my_lifestory.view'))
                                        <li class="{{ (Request::segment(1)=='mylifestory' ? 'active' : '' ) }}">
                                            <a href="{{ route('mylifestory.index') }}">
                                                <img src="{{ asset('themes/limitless/images/client-view/heart-outline.png') }}" class="icon">
                                                <span class="font-semibold text-black">Life Story</span>
                                            </a>
                                        </li>
                                        @endif
                                        <li>
                                            <a href="{{ route('resource.show') }}">
                                                <img src="{{ asset('themes/limitless/images/client-view/book.png') }}" class="icon">
                                                <span class="font-semibold text-black">Resource Library</span>
                                            </a>
                                        </li>
                                        <li class="message {{ (Request::segment(1)=='forums' ? 'active' : '' ) }}">
                                            <a href="{{ url('/forums') }}">
                                                <img src="{{ asset("images/forum.svg") }}" width="32px" class="icon"/>
                                                <span class="font-semibold text-black">Discussions
                                                </span>
                                            </a>
                                        </li>
                                        <li class="message {{ (Request::segment(1)=='group-meeting' ? 'active' : '' ) }}">
                                            <a href="{{ url('/group-meeting') }}">
                                                <img src="{{ asset("images/group.svg") }}" width="32px" class="icon"/>
                                                <span class="font-semibold text-black">Group Meeting
                                                </span>
                                            </a>
                                        </li>
                                        <li class="message {{ (Request::segment(1)=='contact-admin' ? 'active' : '' ) }}">
                                            <a href="{{ route('messages.contact-admin') }}">
                                                <img src="{{ asset('themes/limitless/images/client-view/paper-plane.png') }}" class="icon">
                                                <span class="font-semibold text-black">Contact Admin
                                                {{--@if($unread_admin_counter != '0')--}}
                                                    {{--<i class="font-bold msg">{{ $unread_admin_counter }}</i>--}}
                                                {{--@endif--}}
                                                </span>
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </nav>
                        </div>
                    </div>

                    <div class="col-md-2 left-column">
                        <!-- SIDEBAR -->
                        <div class="chatter_sidebar">
                            <button class="btn btn-primary" id="new_discussion_btn"><i class="chatter-new"></i> @lang('chatter::messages.discussion.new')</button>
                            <a href="/{{ Config::get('chatter.routes.home') }}"><i class="chatter-bubble"></i> @lang('chatter::messages.discussion.all')</a>
                  {!! $categoriesMenu !!}
                        </div>
                        <!-- END SIDEBAR -->
                    </div>
                    <div class="col-md-8 right-column">
                        <div class="panel">
                            <ul class="discussions">
                                @foreach($discussions as $discussion)
                                    <li>
                                        <a class="discussion_list" href="/{{ Config::get('chatter.routes.home') }}/{{ Config::get('chatter.routes.discussion') }}/{{ $discussion->category->slug }}/{{ $discussion->slug }}">
                                            <div class="chatter_avatar">
                                                @if(Config::get('chatter.user.avatar_image_database_field'))

                                                    <?php $db_field = Config::get('chatter.user.avatar_image_database_field'); ?>

                                                    <!-- If the user db field contains http:// or https:// we don't need to use the relative path to the image assets -->
                                                    @if( (substr($discussion->user->{$db_field}, 0, 7) == 'http://') || (substr($discussion->user->{$db_field}, 0, 8) == 'https://') )
                                                        <img src="{{ $discussion->user->{$db_field}  }}">
                                                    @else
                                                        <img src="{{ Config::get('chatter.user.relative_url_to_image_assets') . $discussion->user->{$db_field}  }}">
                                                    @endif

                                                @else

                                                    <span class="chatter_avatar_circle" style="background-color:#<?= \DevDojo\Chatter\Helpers\ChatterHelper::stringToColorCode($discussion->user->{Config::get('chatter.user.database_field_with_user_name')}) ?>">
                                                        {{ strtoupper(substr($discussion->user->{Config::get('chatter.user.database_field_with_user_name')}, 0, 1)) }}
                                                    </span>

                                                @endif
                                            </div>

                                            <div class="chatter_middle">
                                                <h3 class="chatter_middle_title">{{ $discussion->title }} <div class="chatter_cat" style="background-color:{{ $discussion->category->color }}">{{ $discussion->category->name }}</div></h3>
                                                <span class="chatter_middle_details">@lang('chatter::messages.discussion.posted_by') <span data-href="/user">{{ ucfirst($discussion->user->{Config::get('chatter.user.database_field_with_user_name')}) }} {{ substr(ucfirst($discussion->user->{Config::get('chatter.user.database_field_with_last_name')}),0,1) }}</span> {{ \Carbon\Carbon::createFromTimeStamp(strtotime($discussion->created_at))->diffForHumans() }}</span>
                                                @if($discussion->post[0]->markdown)
                                                    <?php $discussion_body = GrahamCampbell\Markdown\Facades\Markdown::convertToHtml( $discussion->post[0]->body ); ?>
                                                @else
                                                    <?php $discussion_body = $discussion->post[0]->body; ?>
                                                @endif

                                                <p>{{ substr(strip_tags($discussion_body), 0, 200) }}@if(strlen(strip_tags($discussion_body)) > 200){{ '...' }}@endif</p>
                                            </div>

                                            <div class="chatter_right">
                                                <div class="chatter_count"><i class="chatter-bubble"></i> {{ $discussion->postsCount[0]->total }}</div>
                                            </div>

                                            <div class="chatter_clear"></div>
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        </div>

                        <div id="pagination">
                            {{ $discussions->links() }}
                        </div>

                    </div>
                </div>
            </div>
        </div>
	</div>

	<div id="new_discussion">
    	<div class="chatter_loader dark" id="new_discussion_loader">
		    <div></div>
		</div>

    	<form id="chatter_form_editor" action="/{{ Config::get('chatter.routes.home') }}/{{ Config::get('chatter.routes.discussion') }}" method="POST">
        	<div class="row">
	        	<div class="col-md-7">
		        	<!-- TITLE -->
	                <input type="text" class="form-control" id="title" name="title" placeholder="@lang('chatter::messages.editor.title')" value="{{ old('title') }}" >
	            </div>

	            <div class="col-md-4">
		            <!-- CATEGORY -->
					<select id="chatter_category_id" class="form-control" name="chatter_category_id">
						<option value="">@lang('chatter::messages.editor.select')</option>
						@foreach($categories as $category)
							@if(old('chatter_category_id') == $category->id)
								<option value="{{ $category->id }}" selected>{{ $category->name }}</option>
							@elseif(!empty($current_category_id) && $current_category_id == $category->id)
								<option value="{{ $category->id }}" selected>{{ $category->name }}</option>
							@else
								<option value="{{ $category->id }}">{{ $category->name }}</option>
							@endif
						@endforeach
					</select>
		        </div>

		        <div class="col-md-1">
		        	<i class="chatter-close"></i>
		        </div>
	        </div><!-- .row -->

            <!-- BODY -->
        	<div id="editor">
        		@if( $chatter_editor == 'tinymce' || empty($chatter_editor) )
					<label id="tinymce_placeholder">@lang('chatter::messages.editor.tinymce_placeholder')</label>
    				<textarea id="body" class="richText" name="body" placeholder="">{{ old('body') }}</textarea>
    			@elseif($chatter_editor == 'simplemde')
    				<textarea id="simplemde" name="body" placeholder="">{{ old('body') }}</textarea>
				@elseif($chatter_editor == 'trumbowyg')
					<textarea class="trumbowyg" name="body" placeholder="@lang('chatter::messages.editor.tinymce_placeholder')">{{ old('body') }}</textarea>
				@endif
    		</div>

            <input type="hidden" name="_token" id="csrf_token_field" value="{{ csrf_token() }}">

            <div id="new_discussion_footer">
            	<input type='text' id="color" name="color" /><span class="select_color_text">@lang('chatter::messages.editor.select_color_text')</span>
            	<button id="submit_discussion" class="btn btn-success pull-right"><i class="chatter-new"></i> @lang('chatter::messages.discussion.create')</button>
            	<a href="/{{ Config::get('chatter.routes.home') }}" class="btn btn-default pull-right" id="cancel_discussion">@lang('chatter::messages.words.cancel')</a>
            	<div style="clear:both"></div>
            </div>
        </form>

    </div><!-- #new_discussion -->

</div>

@if( $chatter_editor == 'tinymce' || empty($chatter_editor) )
	<input type="hidden" id="chatter_tinymce_toolbar" value="{{ Config::get('chatter.tinymce.toolbar') }}">
	<input type="hidden" id="chatter_tinymce_plugins" value="{{ Config::get('chatter.tinymce.plugins') }}">
@endif
<input type="hidden" id="current_path" value="{{ Request::path() }}">

@endsection

@section(Config::get('chatter.yields.footer'))


@if( $chatter_editor == 'tinymce' || empty($chatter_editor) )
	<script src="{{ url('/vendor/devdojo/chatter/assets/vendor/tinymce/tinymce.min.js') }}"></script>
	<script src="{{ url('/vendor/devdojo/chatter/assets/js/tinymce.js') }}"></script>
	<script>
		var my_tinymce = tinyMCE;
		$('document').ready(function(){
			$('#tinymce_placeholder').click(function(){
				my_tinymce.activeEditor.focus();
			});
		});
	</script>
@elseif($chatter_editor == 'simplemde')
	<script src="{{ url('/vendor/devdojo/chatter/assets/js/simplemde.min.js') }}"></script>
	<script src="{{ url('/vendor/devdojo/chatter/assets/js/chatter_simplemde.js') }}"></script>
@elseif($chatter_editor == 'trumbowyg')
	<script src="{{ url('/vendor/devdojo/chatter/assets/vendor/trumbowyg/trumbowyg.min.js') }}"></script>
	<script src="{{ url('/vendor/devdojo/chatter/assets/vendor/trumbowyg/plugins/preformatted/trumbowyg.preformatted.min.js') }}"></script>
	<script src="{{ url('/vendor/devdojo/chatter/assets/js/trumbowyg.js') }}"></script>
@endif

<script src="{{ url('/vendor/devdojo/chatter/assets/vendor/spectrum/spectrum.js') }}"></script>
<script src="{{ url('/vendor/devdojo/chatter/assets/js/chatter.js') }}"></script>
<script>
	$('document').ready(function(){
		$('.chatter-close, #cancel_discussion').click(function(){
			$('#new_discussion').slideUp();
		});
		$('#new_discussion_btn').click(function(){
			@if(Auth::guest())
				window.location.href = "{{ route('login') }}";
			@else
				$('#new_discussion').slideDown();
				$('#title').focus();
			@endif
		});
		$("#color").spectrum({
		    color: "#333639",
		    preferredFormat: "hex",
		    containerClassName: 'chatter-color-picker',
		    cancelText: '',
    		chooseText: 'close',
		    move: function(color) {
				$("#color").val(color.toHexString());
			}
		});
		@if (count($errors) > 0)
			$('#new_discussion').slideDown();
			$('#title').focus();
		@endif
	});
</script>
@stop