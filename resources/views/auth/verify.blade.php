@extends('layouts.app')

@section('begin')

    <!DOCTYPE html>
<html lang="en">
<head>
    <title>Material Admin - Login</title>

    <!-- BEGIN META -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="keywords" content="your,keywords">
    <meta name="description" content="Short explanation about this website">
    <!-- END META -->

    <!-- BEGIN STYLESHEETS -->
    <link href='http://fonts.googleapis.com/css?family=Roboto:300italic,400italic,300,400,500,700,900' rel='stylesheet' type='text/css'/>
    <link type="text/css" rel="stylesheet" href="{{asset('assets/css/theme-default/bootstrap.css?1422792965')}}" />
    <link type="text/css" rel="stylesheet" href="{{asset('assets/css/theme-default/materialadmin.css?1425466319')}}" />
    <link type="text/css" rel="stylesheet" href="{{asset('assets/css/theme-default/font-awesome.min.css?1422529194')}}" />
    <link type="text/css" rel="stylesheet" href="{{asset('assets/css/theme-default/material-design-iconic-font.min.css?1421434286')}}" />
    <!-- END STYLESHEETS -->

    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
<!--[if lt IE 9]>
    <script type="text/javascript" src="{{asset('assets/js/libs/utils/html5shiv.js?1403934957')}}"></script>
    <script type="text/javascript" src="{{asset('assets/js/libs/utils/respond.min.js?1403934956')}}"></script>
    <![endif]-->
</head>

<body class="menubar-hoverable header-fixed ">

@endsection

@section('content')

    <!-- BEGIN LOGIN SECTION -->
    <section class="section-account">
        <div class="img-backdrop" style="height: 100vh;background-image: url({{asset('assets/img/img16.jpg')}})"></div>
        <div class="spacer"></div>
        <div class="card contain-sm style-transparent">
            <div class="card-body">
                <div class="row">
                    <div class="col-sm-12">
                        <br/>
                        <span class="text-lg text-bold text-primary">Подтвердите свою электронную почту</span>
                        <br/><br/>
                            @csrf
                            <div class="form-group">
                                <label>Письмо со ссылкой для подтверждения было отправленно на вашу электронную почту</label>
                            </div>
                            <br/>
                            <div class="row">
                                <div class="col-xs-6 text-left">
                                    <div class="checkbox checkbox-inline checkbox-styled">
                                        <label>
                                        </label>
                                    </div>
                                </div><!--end .col -->
                                <div class="col-xs-6 text-right">
                                    <a href="{{route('verify')}}" class="btn btn-primary btn-raised">Отправить снова</a>
                                </div><!--end .col -->
                            </div><!--end .row -->
                    </div><!--end .col -->
                </div><!--end .row -->
            </div><!--end .card-body -->
        </div><!--end .card -->
    </section>
    <!-- END LOGIN SECTION -->

@endsection

@section('end')

    <!-- BEGIN JAVASCRIPT -->
    <script src="{{asset('assets/js/libs/jquery/jquery-1.11.2.min.js')}}"></script>
    <script src="{{asset('assets/js/libs/jquery/jquery-migrate-1.2.1.min.js')}}"></script>
    <script src="{{asset('assets/js/libs/bootstrap/bootstrap.min.js')}}"></script>
    <script src="{{asset('assets/js/libs/spin.js/spin.min.js')}}"></script>
    <script src="{{asset('assets/js/libs/autosize/jquery.autosize.min.js')}}"></script>
    <script src="{{asset('assets/js/libs/nanoscroller/jquery.nanoscroller.min.js')}}"></script>
    <script src="{{asset('assets/js/core/source/App.js')}}"></script>
    <script src="{{asset('assets/js/core/source/AppNavigation.js')}}"></script>
    <script src="{{asset('assets/js/core/source/AppOffcanvas.js')}}"></script>
    <script src="{{asset('assets/js/core/source/AppCard.js')}}"></script>
    <script src="{{asset('assets/js/core/source/AppForm.js')}}"></script>
    <script src="{{asset('assets/js/core/source/AppNavSearch.js')}}"></script>
    <script src="{{asset('assets/js/core/source/AppVendor.js')}}"></script>
    <script src="{{asset('assets/js/core/demo/Demo.js')}}"></script>
    <!-- END JAVASCRIPT -->

</body>
</html>

@endsection


