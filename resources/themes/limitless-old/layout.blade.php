<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
        <link rel="manifest" href="/favicon/manifest.json">
        <link rel="apple-touch-icon" sizes="57x57" href="/favicon/apple-icon-57x57.png">
        <link rel="apple-touch-icon" sizes="60x60" href="/favicon/apple-icon-60x60.png">
        <link rel="apple-touch-icon" sizes="72x72" href="/favicon/apple-icon-72x72.png">
        <link rel="apple-touch-icon" sizes="76x76" href="/favicon/apple-icon-76x76.png">
        <link rel="apple-touch-icon" sizes="114x114" href="/favicon/apple-icon-114x114.png">
        <link rel="apple-touch-icon" sizes="120x120" href="/favicon/apple-icon-120x120.png">
        <link rel="apple-touch-icon" sizes="144x144" href="/favicon/apple-icon-144x144.png">
        <link rel="apple-touch-icon" sizes="152x152" href="/favicon/apple-icon-152x152.png">
        <link rel="apple-touch-icon" sizes="180x180" href="/favicon/apple-icon-180x180.png">
        <link rel="icon" type="image/png" sizes="192x192"  href="/favicon/android-icon-192x192.png">
        <link rel="icon" type="image/png" sizes="32x32" href="/favicon/favicon-32x32.png">
        <link rel="icon" type="image/png" sizes="96x96" href="/favicon/favicon-96x96.png">
        <link rel="icon" type="image/png" sizes="16x16" href="/favicon/favicon-16x16.png">
        <meta name="msapplication-TileColor" content="#ffffff">
        <meta name="msapplication-TileImage" content="/favicon/ms-icon-144x144.png">
        <meta name="theme-color" content="#ffffff">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>@yield('title')</title>
        <link rel="stylesheet" type="text/css" href="{{ elixir('themes/limitless/fonts/OpenSans/stylesheet.css') }}">
        <link rel="stylesheet" href="{{ elixir('themes/limitless/css/all.css') }}">
        {{-- <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css"> --}}
        <style type="text/css">
            .navbar-default.navbar-fixed-bottom {
                z-index: auto;
            }
            .panel-heading .col-sm-9 {
                padding-left: 0px;
            }
        </style>
        @section('style')
        @show
    </head>
    <body class="sidebar-opposite-visible">
        <div class="app">
            <div id="content" class="app-content" role="main">
                <div class="box">
                    @include('limitless.partials.header')
                    <div class="col-sm-12 notifications-wrapper sub_header">
                        @include('limitless.partials.notifications')
                    </div>
                    @if(Auth::user()->user_type == 'coach')
                    <div class="page-container coach-bg">
                    @else
                    <div class="page-container admin-bg">
                    @endif
                        <!-- Page content -->
                        <div class="page-content">
                            @include('limitless.partials.left')
                            @yield('content')
                            @if(Auth::user()->user_type == 'client' || Auth::user()->user_type == 'coach' && Request::segment(1)!='messages' && Request::segment(1)!='week' && Request::segment(1)!='adjust_schedule' && Request::segment(1)!='free_session')
                                @include('limitless.partials.right')
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @yield('add_button')
        <script src="{{ elixir('themes/limitless/js/async.js') }}"></script>
        <script type="text/javascript">
        	var cancel_btn='{{trans("comman.cancel")}}';
            var please_confirm='{{trans("comman.please_confirm")}}';
            var ok_btn='{{trans("comman.ok")}}';
            var delete_msg='{{trans("comman.delete_msg")}}';
            var permission = '{{trans("comman.delete_msg")}}';
            var rejectStatus_msg = '{{trans("comman.rejectStatus_msg")}}';
        </script>
        <script>
            $(document).ready(function() {
                $('.summernote').summernote({
                    defaultFontName: 'open_sansregular',
                    fontNames: ['Arial','open_sansregular']
                });
            });
        </script>
        @stack('scripts')
        <!-- footer section -->
        @section('footer')
        {{-- @include('limitless.partials.footer') --}}
        @show
    </body>
</html>