@extends('layouts.auth')

@section('title', 'Login')
@section('header_text', 'Sign In')

@section('content')
    <form method="post" id="auth-form">
        {{csrf_field()}}
        <div class="form-group has-feedback">
            <input type="email" class="form-control sty1" name="email"  placeholder="Email" value="{{old('email')}}" required>
        </div>
        <div class="form-group has-feedback">
            <input type="password" class="form-control sty1" name="password" placeholder="Password" required>
        </div>
        <div>
            <div class="col-xs-8">
                <div class="checkbox icheck">
                    <label>
                        <input type="checkbox" name="remember">
                        Remember Me </label>
                    <a href="{{ url('password/reset') }}" class="pull-right"><i class="fa fa-lock"></i> Forgot pwd?</a>
                </div>
            </div>
            <!-- /.col -->
            <div class="col-xs-4 m-t-1">
                <div class="col-xs-4 m-t-1">
                    @if(env('RECAPTCHA_ENABLED'))
                        <button type="submit" class="g-recaptcha btn btn-primary btn-block btn-flat"
                                data-sitekey="{{env('RECAPTCHA')}}"
                                data-callback="onSubmit"
                        >Sign In</button>
                    @else
                        <button type="submit" class="btn btn-primary btn-block btn-flat">Sign In</button>
                    @endif
                </div>
            </div>
            <!-- /.col -->
        </div>
    </form>
@endsection
