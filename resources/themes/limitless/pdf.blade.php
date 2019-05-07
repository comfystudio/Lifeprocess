<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        {{-- <title>{{$module_title}}</title> --}}
        <title>{{$title}}</title>
        <!-- Stylesheets -->
        <!-- Bootstrap is included in its original form, unaltered -->
        <link href="{{ public_path("themes/limitless/css/all.css")}}" rel="stylesheet" type="text/css" />
      {{--   <link rel="stylesheet" href="{{ elixir('themes/limitless/css/all.css') }}"> --}}
        @section('style')
        @show

<style>

body{
    background: #FFF;
}

#page-container
{
    background-color: #FFF !important;
}

/* Table classes */

.table{
    margin-top: 10px;
}

.table > tbody  > tr > td:nth-child(2)
{
    padding:15px;
}

.table > tbody  > tr.info >  th
{
    background-color: #E7F3FC !important ;
}

.table > tbody  > tr >  th
{
    font-size: 12px;
    width: 33%;
}

.table > tbody  > tr >  td
{
    font-size: 11px !important;
    width: 33%;
    padding: 14px 0 15px 20px !important;
}
.table > tbody  > tr >  td:first-child {
    font-size: 13px !important;
}
.page-title {
    padding: 15px 35px 17px 0 !important;
}
.col-sm-3{
    width: 25%;
    float:left;
}
.col-sm-2
{
     width: 20%;
    float:left;
}
.col-sm-1
{
    width: 10%;
    float:left;
}
.heading-elements
{
    display: none;
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