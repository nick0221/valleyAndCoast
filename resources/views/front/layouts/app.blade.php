<!-- resources/views/layouts/app.blade.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="Valley and Coast Hotel">
    <meta name="author" content="Valley and Coast Hotel">
    <link rel="icon" href="{{ asset('images/favicon.ico') }}">
    <link href="https://fonts.googleapis.com/css?family=Poppins:100,200,300,400,500,600,700,800,900&display=swap" rel="stylesheet">

    <title>@yield('title')</title>

    <!-- Bootstrap core CSS -->
    <link rel="stylesheet" href="{{ asset('template/vendor/bootstrap/css/bootstrap.min.css') }}">

    <!-- Additional CSS Files -->
    <link rel="stylesheet" href="{{ asset('template/assets/css/fontawesome.css') }}">
    <link rel="stylesheet" href="{{ asset('template/assets/css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('template/assets/css/owl.css') }}">

</head>
<body>

<!-- ***** Preloader Start ***** -->
{{--<div id="preloader">--}}
{{--    <div class="jumper">--}}
{{--        <div></div>--}}
{{--        <div></div>--}}
{{--        <div></div>--}}
{{--    </div>--}}
{{--</div>--}}
<!-- ***** Preloader End ***** -->
@include('front.partials.header')
<div class="content">
    @yield('content')
</div>

<footer>
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="inner-content">
                    <p>Copyright © 2023 V&C HRS </p>
                </div>
            </div>
        </div>
    </div>
</footer>
@include('front.partials.footer')
</body>
</html>
