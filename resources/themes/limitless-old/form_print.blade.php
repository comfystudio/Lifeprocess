<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
        <link rel="manifest" href="/favicon/manifest.json">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>@yield('title')</title>
        <link rel="stylesheet" href="{{ elixir('themes/limitless/css/all.css') }}">
    </head>
    <body>
        <div class="app">
            <div id="content" class="app-content" role="main">
                <div class="box">
                    <div class="col-sm-12">
                        @include('limitless.partials.notifications')
                    </div>
                    <div class="page-container">
                        <!-- Page content -->
                        <div class="page-content">
                            @yield('content')
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @yield('add_button')
        <script type="text/javascript" src="/qz/js/dependencies/rsvp-3.1.0.min.js"></script>
        <script type="text/javascript" src="/qz/js/dependencies/sha-256.min.js"></script>
        <script type="text/javascript" src="/qz/js/qz-tray.js"></script>
        <script src="/build/themes/limitless/js/jquery-1.10.2.js"></script>        
        <script type="text/javascript">
var cancel_btn = '{{trans("comman.cancel")}}';
var localization_current = '{{Localization::getCurrentLocale()}}';
var please_confirm = '{{trans("comman.please_confirm")}}';
var ok_btn = '{{trans("comman.ok")}}';
var delete_msg = '{{trans("comman.delete_msg")}}';
var permission = '{{trans("comman.delete_msg")}}';
var rejectStatus_msg = '{{trans("comman.rejectStatus_msg")}}';
        </script>
        @stack('scripts')
        <!-- footer section -->
        @section('footer')
        @include('limitless.partials.footer')
        @show
    </body>
</html>