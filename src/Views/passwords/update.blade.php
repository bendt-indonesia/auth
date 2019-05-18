@extends('bendt-auth::layouts.auth')

@section('content')
    <form method="POST" action="{{ route('password.update') }}">
        {{ csrf_field() }}
        <div class="card card-login card-hidden">
            <div class="header text-center">
                <h3 class="title">Update Password</h3>
            </div>
            <div class="content">
                @if (Session::has('success'))
                    <div class="alert alert-success">{!! Session::get('success') !!}</div>
                @endif
                @if (Session::has('failure'))
                    <div class="alert alert-danger">{!! Session::get('failure') !!}</div>
                @endif

                <div class="form-group {{ $errors->has('old') ? 'has-error' : '' }}">
                    <label for="old">Current Password <star>*</star></label>
                    <input id="old" type="old" class="form-control input-no-border" name="old" value="{{ old('old') }}" required autofocus>
                    @if ($errors->has('old'))
                        <span class="help-block">
                        <strong>{{ $errors->first('old') }}</strong>
                    </span>
                    @endif
                </div>

                <div class="form-group {{ $errors->has('password') ? 'has-error' : '' }}">
                    <label>Password <star>*</star></label>
                    <input id="password" type="password" class="form-control input-no-border" name="password" required>
                    @if ($errors->has('password'))
                        <span class="help-block">
                            <strong>{{ $errors->first('password') }}</strong>
                        </span>
                    @endif
                </div>

                <div class="form-group {{ $errors->has('password_confirmation') ? 'has-error' : '' }}">
                    <label for="password-confirm">Password Confirm <star>*</star></label>
                    <input id="password-confirm" type="password" class="form-control input-no-border" name="password_confirmation" required>
                    @if ($errors->has('password_confirmation'))
                        <span class="help-block">
                        <strong>{{ $errors->first('password_confirmation') }}</strong>
                    </span>
                    @endif
                </div>
            </div>
            <div class="text-center">
                <button type="submit" class="btn btn-rose btn-wd btn-lg">Submit</button>
            </div>
        </div>
    </form>
@endsection
