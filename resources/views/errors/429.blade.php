<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="Codeside Academy">
    <meta name="title" content="Codeside Academy">
    

    <meta property="og:type" content="Learning">
    <meta property="og:title" content="{{ get_option('og_title') }}">
    <meta property="og:description" content="{{ get_option('og_description') }}">
    <meta property="og:image" content="{{ getImageFile(get_option('app_logo')) }}">

    <meta name="twitter:card" content="Learning">
    <meta name="twitter:title" content="{{ get_option('og_title') }}">
    <meta name="twitter:description" content="{{ get_option('og_description') }}">
    <meta name="twitter:image" content="{{ getImageFile(get_option('app_logo')) }}">

    <meta name="msapplication-TileImage" content="https://codesideacademy.com/uploads/setting/1665520009-kOjeqaOhVH.png">

    <meta name="msapplication-TileColor" content="rgba(103, 20, 222,.55)">
    <meta name="theme-color" content="#754FFE">

    <title>Codeside Academy</title>

    <!--=======================================
      All Css Style link
    ===========================================-->

    <!-- Bootstrap core CSS -->
    <link href="{{ asset('frontend') }}/assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">

    <link href="{{ asset('frontend') }}/assets/css/jquery-ui.min.css" rel="stylesheet">

    <!-- Font Awesome for this template -->
    <link href="{{ asset('frontend') }}/assets/vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">

    <!-- Custom fonts for this template -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Jost:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('frontend') }}/assets/fonts/feather/feather.css">

    <!-- Animate Css-->
    <link rel="stylesheet" href="{{ asset('frontend') }}/assets/css/animate.min.css">

    <link rel="stylesheet" href="{{ asset('frontend') }}/assets/css/owl.carousel.min.css">
    <link rel="stylesheet" href="{{ asset('frontend') }}/assets/css/owl.theme.default.min.css">

    <link rel="stylesheet" href="{{ asset('frontend') }}/assets/css/venobox.min.css">

    <!-- Custom styles for this template -->
    <link href="{{ asset('frontend') }}/assets/css/style.css" rel="stylesheet">

    <!-- Responsive Css-->
    <link rel="stylesheet" href="{{ asset('frontend') }}/assets/css/responsive.css">

    <!-- FAVICONS -->
    <link rel="icon" href="https://codesideacademy.com/uploads/setting/1665520009-kOjeqaOhVH.png" type="image/png" sizes="16x16">
    <link rel="shortcut icon" href="https://codesideacademy.com/uploads/setting/1665520009-kOjeqaOhVH.png" type="image/x-icon">
    <link rel="shortcut icon" href="https://codesideacademy.com/uploads/setting/1665520009-kOjeqaOhVH.png">

    <!--<link rel="apple-touch-icon-precomposed" type="image/x-icon" href="{{ asset('frontend') }}/assets/img/apple-icon-72x72.png" sizes="72x72" />-->
    <!--<link rel="apple-touch-icon-precomposed" type="image/x-icon" href="{{ asset('frontend') }}/assets/img/apple-icon-114x114.png" sizes="114x114" />-->
    <!--<link rel="apple-touch-icon-precomposed" type="image/x-icon" href="{{ asset('frontend') }}/assets/img/apple-icon-144x144.png" sizes="144x144" />-->
    <!--<link rel="apple-touch-icon-precomposed" type="image/x-icon" href="{{ asset('frontend') }}/assets/img/favicon-16x16.png" />-->

</head>
<body class="bg-page">

@if(get_option('allow_preloader') == 1)
    <!-- Pre Loader Area start -->
    <div id="preloader">
        <div id="preloader_status"><img src="{{getImageFile(get_option('app_preloader'))}}" alt="img" /></div>
    </div>
    <!-- Pre Loader Area End -->
@endif

<!-- FAQ Area Start -->
<section class="error-page-area">
    <div class="container">
        <div class="row">
            <div class="error-content text-center">
                <div class="error-img-wrap">
                    <img src="{{ asset('frontend/assets/img/429.png') }}" alt="error" class="img-fluid">
                </div>
                <h4>Too Many Requests</h4>
                <a href="{{ route('main.index') }}" class="theme-btn theme-button1 theme-button3 mt-3">{{ __('Back to home') }} <i data-feather="arrow-right"></i></a>
            </div>
        </div>
    </div>
</section>
<!-- FAQ Area End -->

<!--=======================================
    All Jquery Script link
===========================================-->
<!-- Bootstrap core JavaScript -->
<script src="{{ asset('frontend') }}/assets/vendor/jquery/jquery-3.6.0.min.js"></script>
<script src="{{ asset('frontend') }}/assets/vendor/jquery/popper.min.js"></script>
<script src="{{ asset('frontend') }}/assets/vendor/bootstrap/js/bootstrap.min.js"></script>

<!-- ==== Plugin JavaScript ==== -->
<script src="{{ asset('frontend') }}/assets/vendor/jquery-easing/jquery.easing.min.js"></script>

<script src="{{ asset('frontend') }}/assets/js/jquery-ui.min.js"></script>

<!--WayPoints JS Script-->
<script src="{{ asset('frontend') }}/assets/js/waypoints.min.js"></script>

<!--Counter Up JS Script-->
<script src="{{ asset('frontend') }}/assets/js/jquery.counterup.min.js"></script>

<script src="{{ asset('frontend') }}/assets/js/owl.carousel.min.js"></script>

<!-- Range Slider -->
<script src="{{ asset('frontend') }}/assets/js/price_range_script.js"></script>

<!--Feather Icon-->
<script src="{{ asset('frontend') }}/assets/js/feather.min.js"></script>

<!--Iconify Icon-->
<script src="{{ asset('common/js/iconify.min.js') }}"></script>

<!--Venobox-->
<script src="{{ asset('frontend') }}/assets/js/venobox.min.js"></script>

<!-- Menu js -->
<script src="{{ asset('frontend') }}/assets/js/menu.js"></script>

<!-- Custom scripts for this template -->
<script src="{{ asset('frontend') }}/assets/js/frontend-custom.js"></script>

</body>

</html>
