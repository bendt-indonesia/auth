<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>@yield('title') | {{env('APP_NAME')}}</title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <meta name="csrf-token" content="{{csrf_token()}}">

    <link href="{{asset('bendt/css/backend.min.css')}}" rel="stylesheet" />

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->

    <script src='https://www.google.com/recaptcha/api.js'></script>
    @include('include.favicons')
</head>
<body class="hold-transition login-page">
<div class="loader-container">
    <ul class='loader'>
        <li></li><li></li><li></li><li></li><li></li><li></li><li></li><li></li><li></li>
    </ul>
</div>
<div class="login-box">
    <div class="login-box-body">
        <h3 class="login-box-msg">@yield('header_text')</h3>
        @if ($errors->any())
            <div class="alert alert-danger alert-dismissable">
                <strong>Error!</strong>
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        @if (session('status'))
            <div class="alert alert-success">
                {{ session('status') }}
            </div>
        @endif
        @yield('content')
    </div>
    <!-- /.login-box-body -->
</div>
<!-- /.login-box -->

<script src="{{ asset('/bendt/js/vendor.min.js') }}"></script>
<script src="{{ asset('/bendt/js/backend.min.js') }}"></script>
<script>
    function onSubmit(token) {
        document.getElementById("auth-form").submit();
    }
</script>
</body>
</html>
