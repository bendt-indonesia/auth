@extends('bendt-auth::layouts.auth')

@section('content')
    <p>Enter your Email and instructions will be sent to you! </p>
    @if (session('status'))
        <div class="alert alert-success">
            {{ session('status') }}
        </div>
    @endif
    <form method="post" id="auth-form">
        {{ csrf_field() }}
        <div class="form-group has-feedback">
            <input type="email" class="form-control sty1" placeholder="Password"  name="email" value="{{ old('email') }}" required>
            @if ($errors->has('email'))
                <span class="help-block">
                        <strong>{{ $errors->first('email') }}</strong>
                    </span>
            @endif
        </div>
        <div>
            <div class="col-xs-4 m-t-1">
                @if(env('RECAPTCHA_ENABLED'))
                    <button type="submit" class="g-recaptcha btn btn-primary btn-block btn-flat"
                            data-sitekey="{{env('RECAPTCHA')}}"
                            data-callback="onSubmit"
                    >Send Instruction</button>
                @else
                    <button type="submit" class="btn btn-primary btn-block btn-flat">Reset</button>
                @endif

                <div class="mt-3 text-center">
                    <a href="{{url('login')}}">Back to Login</a>
                </div>
            </div>
            <!-- /.col -->
        </div>
    </form>
@endsection
