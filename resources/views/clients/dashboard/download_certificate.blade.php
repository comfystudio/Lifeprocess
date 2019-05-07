@extends($theme)
@section('title', 'Download title')
@section('content')
<link href="{{ public_path("themes/limitless/fonts/perpetua/stylesheet.css")}}" rel="stylesheet" type="text/css" />
<link href="{{ public_path("themes/limitless/fonts/times-new-roman/stylesheet.css")}}" rel="stylesheet" type="text/css" />
<style type="text/css">
/*    form {
        margin-bottom: 0;
    }
    body{
        background-color: #FFF;
    }*/
.life-pdf{
    background-image: url({{public_path("/themes/limitless/images/11.png")}});
    background-size: 100% 100%;
    background-repeat: no-repeat;
    padding-bottom: 45px;
}
.life-pdf .logo-img img{
    width: 180px;
    margin-top: 68px;
}
.life-pdf .content .main-title{
    font-size: 32px;
    margin: 0 auto;
    font-family: 'perpetuaregular';
    letter-spacing: 11px;
}
.life-pdf .content .hr-title{
    max-width: 620px;
    margin: 0 auto;
}
.life-pdf .content .sub-title{
    margin-top: 5px;
    margin-bottom: 7px;
    font-family: 'perpetuaregular';
    font-size: 22px;
}
.life-pdf .content .head-name{
    font-size: 35px;
    margin: 0 auto;
    font-family: 'perpetuaregular';
    letter-spacing: 7px;
    color: #005D05;
}
.life-pdf .content .head-hr{
    max-width: 250px;
    margin: 0 auto;
}
.life-pdf .content .head-sub-title{
    margin-top: 5px;
    margin-bottom: 0px;
    font-family: 'perpetuaregular';
    font-size: 22px;
}
.life-pdf .content .life-name{
    font-size: 35px;
    margin: 0 auto;
    font-family: 'perpetuaregular';
    letter-spacing: 7px;
    color: #005D05;
    margin-bottom: 0px;
}
.life-pdf .content .granted{
    margin-bottom: 0px;
    font-family: 'perpetuaregular';
    font-size: 22px;
}
.life-pdf .content .granted span{
    font-size: 35px;
}
.life-pdf .content .module{
    font-family: 'times_new_romanregular';
    font-weight: bold;
    font-size: 18px;
}
.life-pdf .content .module-border{
    border-bottom: 1px solid;
    max-width: 160px;
    margin: 0 auto;
}
.life-pdf .content .hr-module{
    max-width: 465px;
}
 .life-pdf .content .module-list{
    max-width: 500px;
    margin: 0 auto;
    padding-top: 20px;
    padding-bottom: 20px;
    font-family: 'times_new_romanregular';
    font-size: 17px;
    padding-bottom: 10px;
}
.life-pdf .content .module-list-content{
    text-align: left;
    width: 50%!important;
    float: left;
}
.life-pdf .content .module-list-content p{
    margin-bottom: 0px;
}
.life-pdf .content .life-pro{
    font-family: 'perpetuaregular';
    font-size: 25px;
    margin-bottom: 0px;
    display: inline-block;
    margin-bottom: 5px;
}
</style>
{{-- <div class="row">
        <div class="panel panel-default border-top-warning">
            <div class="panel-body">
            Certificate of  Completion
            Certificate  is awarded to
            {{$user_name}}

            {{$program_name}}


            </div>
        </div>
</div> --}}
<div class="main-pdf">
        <div class="life-pdf text-center">
            <div class="life-border">
                <div class="logo-img">
                    {{-- <img src="life-process.png"> --}}
                    <img src="{{ public_path("themes/limitless/images/life-process.png") }}"/>
                </div>
                <div class="content">
                    <div class="text-uppercase main-title">certificate of completion</div>
                    <hr class="hr-title">
                    <div class="sub-title">This certificate is awarded to</div>
                    <div class="text-uppercase head-name">{{$user_name}}</div>
                    <hr class="head-hr">
                    <div class="head-sub-title">to certify that they have completed the</div>
                    <div class="text-uppercase life-name">Life Process {{$program_name}} program</div>
                    <div class="granted">Granted: {{$date}} <span></span> </div>
                    <div class="text-capitalize module">Modules completed:</div>
                    <div class="module-border"></div>
                        <div class="row module-list">
                        @for($i=0;$i<count($client->program->modules);$i++)
                         <div class="col-md-6 module-list-content">
                            Module {{ $client->program->modules[$i]['module_no']}} - {{ $client->program->modules[$i]['module_title']}}
                         </div>
                        @endfor

                        </div>
                        <hr class="hr-module">
                        <div class="text-uppercase life-pro">Life process program</div>
                </div>
            </div>
        </div>
    </div>
@endsection