@extends('limitless.login')

@section('title', 'Reset Password')

@section('style')
    <style type="text/css">
        .rc-anchor-center-container {
            display: table;
            height: 75%;
        }
        iframe.rc-anchor-normal{
            height: 60px !important;
            width: 250px !important;
        }
    </style>
@endsection

@section('content')
<!-- Page container -->
<div class="page-container">
    <!-- Page content -->
    <div class="page-content">
        <!-- Main content -->
        <div class="content-wrapper">
            <!-- Password recovery -->
            <form accept-charset="UTF-8" role="form" id="reset_form" method="post" action="{{ route('password.email') }}">
                <div class="panel panel-body login-form">
                    <div class="text-center">
                        {{-- <div class="icon-object border-warning text-warning"><i class="icon-spinner11"></i></div> --}}
                        <div class="label" style="margin: 10px;">
                            <img src="{{ asset("themes/limitless/images/client-view/logo.png") }}" alt="Life Process Alcohol Program">
                        </div>
                        <h5 class="content-group">Password Recovery <small class="display-block">We'll send you instructions by email</small></h5>
                    </div>

                    @include('limitless.partials.notifications')
                    <br>
                    <div class="form-group has-feedback">
                        <input type="text" name="email" class="form-control" placeholder='Enter your registered Email' />
                        <div class="form-control-feedback">
                            <i class="icon-mail5 text-muted"></i>
                        </div>
                        {!! ($errors->has('email') ? $errors->first('email', '<p class="text-danger">:message</p>') : '') !!}
                    </div>
                   {{--  <div class="form-group has-feedback">
                        <div class="form-group col-sm-12 g-recaptcha" style="margin-left: -22px;" {{ $errors->has('g-recaptcha-response') ? 'has-error' : ''}} data-sitekey="6LdhAyMUAAAAADcDTIqrvyia0weYwpgYz-LFta30">
                        </div>
                        <span class="captcha_error error"></span>
                    </div> --}}

                    <input name="_token" value="{{ csrf_token() }}" type="hidden">
                    <button type="submit" class="btn bg-blue btn-block">Reset Password <i class="icon-arrow-right14"></i></button>
                    <br/>
                    {!! Html::decode(link_to(route('login'), '<i class="icon-arrow-left13 "></i> Back to home',array('class' => 'btn bg-teal-400 btn-block'))) !!}
                    {{-- <button type="submit" class="btn bg-blue btn-block"><i class=" icon-arrow-left13 position-right"></i> Back to home</button> --}}
                </div>
            </form>
            <!-- /password recovery -->
        </div>
        <!-- /main content -->
    </div>
    <!-- /page content -->
</div>
<!-- /page container -->
@endsection

@push('scripts')
    <script type="text/javascript">
        jQuery(document).ready(function($) {
            $(".btn-block").on('click',function(e){
                if(grecaptcha.getResponse() == ''){
                    $('.captcha_error').html('please verify captcha');
                    return false;
                }
                return true;
            });
        });
    </script>
@endpush
