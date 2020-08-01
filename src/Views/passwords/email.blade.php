@extends('bendt-auth::layouts.auth')

@section('content')
    <h3>Reset Password</h3>
    <hr>
    <p>Please enter your Email</p>
    @if (session('status'))
        <div class="alert alert-success">
            {{ session('status') }}
        </div>
    @endif
    <form method="post" action="/password/email">
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
    </form>
@endsection
