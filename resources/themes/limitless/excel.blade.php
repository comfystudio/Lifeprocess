<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        {{-- <title>{{$module_title}}</title> --}}
        <title>{{$title}}</title>
        <!-- Stylesheets -->
        <!-- Bootstrap is included in its original form, unaltered -->
        {{-- <link href="{{ public_path("themes/limitless/css/digitalCard.css")}}" rel="stylesheet" type="text/css" /> --}}
        <link href="{{ public_path("themes/limitless/css/all.css")}}" rel="stylesheet" type="text/css" />
        @section('style')
        @show
        <style type="text/css">
          body
            {
                background-color: #fff;
            }
            .table-bordered > tbody > tr > td {
                font-size: 8px !important;
            }
            .table-bordered > thead > tr > th {
                    font-size: 10px !important;
            }
        </style>

    </head>
    <body >
        <!-- Page Container -->
        <div id="page-container">
            <!--  Content -->
            @yield('content')
        </div>
        <!-- END Page Container -->
    </body>
</html>