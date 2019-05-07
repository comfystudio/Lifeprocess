@extends('limitless.login')

@section('title', 'Reset Password')

@section('content')

<!-- Page container -->
<div class="page-container">
    <!-- Page content -->
    <div class="page-content">
        <!-- Main content -->
        <div class="content-wrapper">
            <!-- Password recovery -->
            <form accept-charset="UTF-8" role="form" method="post" action="{{ route('password.reset.attempt') }}">
                <div class="panel panel-body login-form" style="width: 345px;">
                    <div class="text-center">
                        {{-- <div class="icon-object border-warning text-warning"><i class="icon-spinner11"></i></div> --}}
                        <div class="label" style="margin: 10px;">
                            <img src="{{ asset("themes/limitless/images/client-view/logo.png") }}" alt="Life Process Alcohol Program">
                        </div>
                        <h5 class="content-group">Reset Password {{-- <small class="display-block">We'll send you instructions in email</small> --}}</h5>
                    </div>
                    @include('limitless.partials.notifications')
                    @if (session('status'))
                        <div class="alert alert-success">
                            {{ session('status') }}
                        </div>
                    @endif
                    <br>
                    <div class="form-group has-feedback">
                        <input type="text" name="email" class="form-control" placeholder='Enter your registered Email' value="{{ $email or old('email') }}" />
                        <div class="form-control-feedback">
                            <i class="icon-mail5 text-muted"></i>
                        </div>
                        {!! ($errors->has('email') ? $errors->first('email', '<p class="text-danger">:message</p>') : '') !!}
                    </div>
                    <div class="form-group has-feedback">
                        <input type="password" name="password" class="form-control" placeholder='Password' />
                        <div class="form-control-feedback">
                            <i class="icon-user-lock text-muted"></i>
                        </div>
                        {!! ($errors->has('password') ? $errors->first('password', '<p class="text-danger">:message</p>') : '') !!}
                    </div>
                    <div class="form-group has-feedback">
                        <input type="password" name="password_confirmation" class="form-control" placeholder='Confirm Password' />
                        <div class="form-control-feedback">
                            <i class="icon-user-lock text-muted"></i>
                        </div>
                        {!! ($errors->has('password_confirmation') ? $errors->first('password_confirmation', '<p class="text-danger">:message</p>') : '') !!}
                    </div>

                    <input type="hidden" name="token" value="{{ $token }}">
                    <input name="_token" value="{{ csrf_token() }}" type="hidden">
                    <button type="submit" class="btn bg-blue btn-block">Reset Password <i class="icon-arrow-right14"></i></button>
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
