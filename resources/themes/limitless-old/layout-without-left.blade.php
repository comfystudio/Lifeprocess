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
        <link rel="stylesheet" href="{{ elixir('themes/limitless/css/all.css') }}">
        @section('style')
        @show
    </head>
    <body>
        <div class="app">
            <div id="content" class="app-content" role="main">
                <div class="box">
                    @include('limitless.partials.header')
                    <div class="col-sm-12">
                        @include('limitless.partials.notifications')
                    </div>
                    <div class="page-container">
                        <!-- Page content -->
                        <div class="page-content">
                            {{-- @include('limitless.partials.left') --}}
                            @yield('content')
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

            jQuery(document).ready(function(){
                jQuery('.sidebar-control.sidebar-main-toggle').hide();
            });
        </script>
        @stack('scripts')
        <!-- footer section -->
        @section('footer')
        @include('limitless.partials.footer')
        @show
    </body>
</html>