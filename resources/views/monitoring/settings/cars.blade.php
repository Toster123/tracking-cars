@extends('layouts.app')

@section('begin')
    <!DOCTYPE html>
<html lang="en">
<head>
    <title>Monitoring</title>

    <!-- BEGIN META -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="keywords" content="your,keywords">
    <meta name="description" content="Short explanation about this website">
    <!-- END META -->

    <!-- BEGIN STYLESHEETS -->
    <link href='http://fonts.googleapis.com/css?family=Roboto:300italic,400italic,300,400,500,700,900' rel='stylesheet' type='text/css'/>
    <link type="text/css" rel="stylesheet" href="{{ asset('assets/css/theme-default/bootstrap.css?1422792965') }}" />
    <link type="text/css" rel="stylesheet" href="{{ asset('assets/css/theme-default/materialadmin.css?1425466319') }}" />
    <link type="text/css" rel="stylesheet" href="{{ asset('assets/css/theme-default/font-awesome.min.css?1422529194') }}" />
    <link type="text/css" rel="stylesheet" href="{{ asset('assets/css/theme-default/material-design-iconic-font.min.css?1421434286') }}" />
    <!-- END STYLESHEETS -->

    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
    <script type="text/javascript" src="{{ asset('assets/js/libs/utils/html5shiv.js?1403934957') }}"></script>
    <script type="text/javascript" src="{{ asset('assets/js/libs/utils/respond.min.js?1403934956') }}"></script>
    <![endif]-->
</head>
<body class="menubar-hoverable header-fixed ">
@endsection

@section('content')

@include('layouts.monitoring.header')


    <!-- BEGIN BASE-->
    <div id="base">

        <!-- BEGIN OFFCANVAS LEFT -->
        <div class="offcanvas">
        </div><!--end .offcanvas-->
        <!-- END OFFCANVAS LEFT -->

        <!-- BEGIN CONTENT-->
        <div id="content">
            <section>
                <div class="section-body">
                    <div class="row">

                        <!--SEARCH-->
                        <form class="navbar-search margin-bottom-xxl" role="search">
                            <div class="form-group">
                                <input id="search" type="text" class="form-control" name="search" placeholder="Поиск по названию">
                            </div>
                            <button type="submit" class="btn btn-icon-toggle ink-reaction"><i class="fa fa-search"></i></button>
                        </form>


                        <!--FORM WITH CHECKBOXES START-->
                        <form id="formGlobal" method="POST" class="form" action="">
                            @csrf

                        <!-- BEGIN INBOX NAV -->
                        <div class="col-sm-4 col-md-3 col-lg-2">


                            <ul id="cars" class="nav nav-pills nav-stacked nav-icon">

                                @foreach($groups as $group)
                                    <li id="{{$group->_id}}" onclick="selectCar(this)" data-type="group" data-title="{{$group->title}}" data-color="{{$group->color}}" data-owner="@if(!is_null($group->user_id)){{$group->user_id}}@else{{'nothing'}}@endif"><a class="checkbox checkbox-styled"><big>{{$group->title}}</big></a></li>
                                @foreach($group->cars as $car)
                                        <li id="{{$car->_id}}" onclick="selectCar(this)" data-type="car" data-title="{{$car->title}}" data-group="{{$group->_id}}" data-owner="@if(!is_null($group->user_id)){{$group->user_id}}@else{{'nothing'}}@endif"><a class="checkbox checkbox-styled">
                                                <label>
                                                    <input type="checkbox" name="cars[]" value="{{$car->_id}}" onchange="selectCheckbox(this)">
                                                    <span>{{$car->title}}</span>
                                                </label>
                                            </a></li>
                                    @endforeach
                                @endforeach

                                @if(!is_null($cars))
                                        <li><a class="checkbox checkbox-styled"><big>Без группы</big></a></li>
                                    @foreach($cars as $car)
                                            <li id="{{$car->_id}}" onclick="selectCar(this)" data-type="car" data-title="{{$car->title}}" data-group="@if(!is_null($car->group)){{$car->group->_id}}@else{{'nothing'}}@endif" data-owner="@if(!is_null($car->user_id)){{$car->user_id}}@else{{'nothing'}}@endif" ><a class="checkbox checkbox-styled">
                                                    <label>
                                                        <input type="checkbox" name="cars[]" value="{{$car->_id}}" onchange="selectCheckbox(this)">
                                                        <span>{{$car->title}}</span>
                                                    </label>
                                                </a></li>
                                    @endforeach
                                    @endif

                            </ul>
                        </div><!--end . -->
                        <!-- END INBOX NAV -->


                        <div id="form" hidden="true" class="col-sm-8 col-md-9 col-lg-3">
                            <div class="text-divider visible-xs"><span>Группы</span></div>
                            <div class="row">

                                <!-- BEGIN INBOX EMAIL LIST -->
                                <div class="col-lg-12">
                                    <div class="list-group list-email list-gray">



                                        <!--FORM EDITING START-->
                                        <div class="card">
                                                <div class="card-head style-primary">
                                                    <header id="formHeader"></header>
                                                </div>
                                                <div id="formTitle" class="card-body">
                                                    <div class="form-group">
                                                        <label for="first">Наименование</label>
                                                        <input id="formTitleInput" name="title" class="form-control" type="text" />
                                                    </div>
                                                </div>
                                                <div id="formColor" class="card-body">
                                                    <div class="form-group">
                                                        <label for="second">Цвет</label>
                                                        <input id="formColorInput" name="color" class="form-control" type="text" />
                                                    </div>
                                                </div>
                                                <div class="card-body">
                                                    <div id="formGroup" class="form-group">
                                                        <label for="select1">Группа</label>
                                                        <select id="formGroupSelect" name="group" class="form-control" required="" aria-required="true">
                                                            <option value="nothing">Без группы</option>
                                                            <option id="formGroupUnchanged" hidden="true" value="unchanged">Без изменений</option>
                                                            @foreach($groups as $group)
                                                                <option value="{{$group->_id}}">{{$group->title}}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    @php
                                                    $UserController = new \App\Http\Controllers\UserController();
                                                    @endphp
                                                    @if($UserController->user->role == $UserController->roles['admin'] && !empty($users))
                                                    <div id="formOwner" class="form-group">
                                                        <label for="select1">Владелец</label>
                                                            <select id="formOwnerSelect" name="owner" class="form-control" required="" aria-required="true">
                                                                <option value="nothing">Без влаельца</option>
                                                                <option id="formOwnerUnchanged" hidden="true" value="unchanged">Без изменений</option>
                                                                @foreach($users as $user)
                                                                    <option value="{{$user->_id}}">{{$user->name}}</option>
                                                                @endforeach
                                                            </select>
                                                    </div>
                                                    @endif
                                                    <div class="card-actionbar">
                                                        <div class="card-actionbar-row">
                                                                <a onclick="deleteSelected();"
                                                                    style="float: left" class="btn btn-flat btn-primary ink-reaction">Удалить
                                                                </a>
                                                            <button type="submit" class="btn btn-flat btn-primary ink-reaction">Сохранить</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        <!--FORM EDITING END-->



                                    </div><!--end .list-group -->
                                </div><!--end .col -->


                            </div><!--end .row -->
                        </div><!--end .col -->
                        </form>
                        <!--FORM WITH CHECKBOXES END-->

                        <div>
                        <!--FORM ADD GROUP START-->
                        <div class="col-sm-8 col-md-9 col-lg-3">
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="list-group list-email list-gray">


                                        <form class="form" method="POST" action="{{route('settings.create.group')}}">
                                            @csrf
                                            <div class="card">
                                                <div class="card-head style-primary">
                                                    <header>Добавить группу</header>
                                                </div>
                                                <div class="card-body">
                                                    <div class="form-group">
                                                        <input class="form-control" name="title" id="title" type="text"/>
                                                        <label for="title">Наименование группы</label>
                                                    </div>
                                                    <div class="form-group">
                                                        <input class="form-control" name="color" id="color" type="text"/>
                                                        <label for="color">Цвет</label>
                                                    </div>
                                                </div>
                                                <div class="card-actionbar">
                                                    <div class="card-actionbar-row">
                                                        <button type="submit"
                                                                class="btn btn-flat btn-primary ink-reaction">Добавить
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </form>


                                    </div>
                                </div>
                            </div>
                        </div>
                        <!--FORM ADD GROUP END-->

                        <!--FORM ADD CAR START-->
                        <div class="col-sm-8 col-md-9 col-lg-3">
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="list-group list-email list-gray">


                                        <form class="form" method="POST" action="{{route('settings.create.car')}}">
                                            @csrf
                                            <div class="card">
                                                <div class="card-head style-primary">
                                                    <header>Добавить машину</header>
                                                </div>
                                                <div class="card-body">
                                                    <div class="form-group">
                                                        <input class="form-control" name="title" id="title" type="text"/>
                                                        <label for="title">Наименование машины</label>
                                                    </div>
                                                    <div class="form-group">
                                                        <input class="form-control" name="imei" id="imei" type="text"/>
                                                        <label for="imei">Id маячка</label>
                                                    </div>
                                                </div>
                                                <div class="card-actionbar">
                                                    <div class="card-actionbar-row">
                                                        <button type="submit"
                                                                class="btn btn-flat btn-primary ink-reaction">Добавить
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </form>


                                    </div>
                                </div>
                            </div>
                        </div>
                        <!--FORM ADD CAR END-->
                        </div>

                    </div><!--end .row -->
                </div><!--end .section-body -->
            </section>
        </div><!--end #content-->
        <!-- END CONTENT -->

        <!-- BEGIN MENUBAR-->
        <div id="menubar" class="menubar-inverse ">
            <div class="menubar-fixed-panel">
                <div>
                    <a class="btn btn-icon-toggle btn-default menubar-toggle" data-toggle="menubar" href="javascript:void(0);">
                        <i class="fa fa-bars"></i>
                    </a>
                </div>
                <div class="expanded">
                    <a href="html/dashboards/dashboard.html">
                        <span class="text-lg text-bold text-primary ">MATERIAL&nbsp;ADMIN</span>
                    </a>
                </div>
            </div>
            <div class="menubar-scroll-panel">

                <!-- BEGIN MAIN MENU -->
                <ul id="main-menu" class="gui-controls">

                    <!-- BEGIN PAGES -->
                    <li class="gui-folder">
                        <a>
                            <div class="gui-icon"><i class="md md-computer"></i></div>
                            <span class="title">Мониторинг</span>
                        </a>
                        <!--start submenu -->
                        <ul>

                            @foreach($groups as $group)
                                <li class="gui-folder">
                                    <a href="javascript:void(0);">
                                        <span class="title">{{$group->title}}</span>
                                    </a>
                                    <!--start submenu -->
                                    <ul>
                                        @if(!empty($group->cars->toArray()))
                                            <li><a class="checkbox-styled"><label><input checked type="checkbox"></label><label><span class="title">Выбрать все</span></label></a></li>
                                        @endif

                                        @foreach($group->cars as $car)
                                            <li><a class="checkbox-styled"><label><input checked type="checkbox"></label><label style="cursor: pointer"><span class="title">{{$car->title}}</span></label></a></li>
                                        @endforeach

                                    </ul><!--end /submenu -->
                                </li><!--end /menu-li -->
                            @endforeach

                            @if(!empty($cars->toArray()))
                                <li class="gui-folder">
                                    <a href="javascript:void(0);">
                                        <span class="title">Без группы</span>
                                    </a>
                                    <!--start submenu -->
                                    <ul>
                                        <li><a class="checkbox-styled"><label><input checked type="checkbox"></label><label><span class="title">Выбрать все</span></label></a></li>

                                        @foreach($cars as $car)
                                            <li><a class="checkbox-styled"><label><input checked type="checkbox"></label><label style="cursor: pointer"><span class="title">{{$car->title}}</span></label></a></li>
                                        @endforeach

                                    </ul><!--end /submenu -->
                                </li><!--end /menu-li -->
                            @endif


                        </ul><!--end /submenu -->
                    </li><!--end /menu-li -->
                    <!-- END FORMS -->

                    <!-- BEGIN PAGES -->
                    <li class="gui-folder">
                        <a>
                            <div class="gui-icon"><i class="fa fa-puzzle-piece fa-fw"></i></div>
                            <span class="title">Настройки</span>
                        </a>
                        <!--start submenu -->
                        <ul>
                            <li><a href="{{route('settings.cars')}}"><span class="title">Настройки маячков</span></a></li>
                        </ul><!--end /submenu -->
                        <!-- END FORMS -->
                    </li>
                    <li class="gui-folder">
                        <a>
                            <div class="gui-icon"><i class="fa fa-puzzle-piece fa-fw"></i></div>
                            <span class="title">Пользователь</span>
                        </a>
                        <ul>
                            <li><a href="{{route('get:logout')}}"><span class="title">Выход</span></a></li>
                        </ul>
                    </li>

                </ul><!--end .main-menu -->
                <!-- END MAIN MENU -->

                <div class="menubar-foot-panel">
                    <small class="no-linebreak hidden-folded">
                        <span class="opacity-75">Copyright &copy; 2020</span> <strong>Distudio</strong>
                    </small>
                </div>
            </div><!--end .menubar-scroll-panel-->
        </div><!--end #menubar-->
        <!-- END MENUBAR -->

        <!-- BEGIN OFFCANVAS RIGHT -->
        <div class="offcanvas">

            <!-- BEGIN OFFCANVAS SEARCH -->
            <div id="offcanvas-search" class="offcanvas-pane width-8">
                <div class="offcanvas-head">
                    <header class="text-primary">Список действий</header>
                    <div class="offcanvas-tools">
                        <a class="btn btn-icon-toggle btn-default-light pull-right" data-dismiss="offcanvas">
                            <i class="md md-close"></i>
                        </a>
                    </div>
                </div>
                <div class="offcanvas-body no-padding">
                    <ul class="list" id="events">

                        @include('layouts.monitoring.events')

                    </ul>
                </div><!--end .offcanvas-body -->
            </div><!--end .offcanvas-pane -->
            <!-- END OFFCANVAS SEARCH -->

        </div><!--end .offcanvas-->
        <!-- END OFFCANVAS RIGHT -->

    </div><!--end #base-->
    <!-- END BASE -->

@endsection

@section('end')
    <!-- BEGIN JAVASCRIPT -->
    <script src="{{ asset('assets/js/libs/jquery/jquery-1.11.2.min.js') }}"></script>
    <script src="{{ asset('assets/js/libs/jquery/jquery-migrate-1.2.1.min.js') }}"></script>
    <script src="{{ asset('assets/js/libs/bootstrap/bootstrap.min.js') }}"></script>
    <script src="{{ asset('assets/js/libs/spin.js/spin.min.js') }}"></script>
    <script src="{{ asset('assets/js/libs/autosize/jquery.autosize.min.js') }}"></script>
    <script src="{{ asset('assets/js/libs/nanoscroller/jquery.nanoscroller.min.js') }}"></script>
    <script src="{{ asset('assets/js/core/source/App.js') }}"></script>
    <script src="{{ asset('assets/js/core/source/AppNavigation.js') }}"></script>
    <script src="{{ asset('assets/js/core/source/AppOffcanvas.js') }}"></script>
    <script src="{{ asset('assets/js/core/source/AppCard.js') }}"></script>
    <script src="{{ asset('assets/js/core/source/AppForm.js') }}"></script>
    <script src="{{ asset('assets/js/core/source/AppNavSearch.js') }}"></script>
    <script src="{{ asset('assets/js/core/source/AppVendor.js') }}"></script>
    <script src="{{ asset('assets/js/core/demo/Demo.js') }}"></script>
    <script src="{{ asset('assets/js/core/demo/DemoPageMaps.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-3-typeahead/4.0.1/bootstrap3-typeahead.min.js"></script>
    <script type="text/javascript">
        var countOfCheckedCars = [];

        function selectCar (el) {
            var actives = document.getElementById('cars').getElementsByClassName('active');
            if (actives[0]) {
                for (let i = 0; actives.length > i; i++) {
                    actives[i].classList.remove('active');
                }
            }
            el.classList.add('active');

            document.getElementById('formTitleInput').value = el.getAttribute('data-title');
            document.getElementById('formHeader').innerHTML = el.getAttribute('data-title');

            document.getElementById('formGroupUnchanged').hidden = true;
            @if($UserController->user->role == $UserController->roles['admin'])
            document.getElementById('formOwnerUnchanged').hidden = true;
            document.getElementById('formOwnerSelect').value = el.getAttribute('data-owner');
            @endif
            document.getElementById('formGroupSelect').value = el.getAttribute('data-group');
            if (el.getAttribute('data-type') == 'car') {
                document.getElementById('formGroup').hidden = false;
                document.getElementById('formColor').hidden = true;
                document.getElementById('formTitleInput').disabled = false;

                document.getElementById('formGlobal').action = 'http://376174.msk-ovz.ru/monitoring/settings/cars/' + el.getAttribute('id') + '/edit';
            } else {
                if (el.getAttribute('data-type') == 'group') {
                    document.getElementById('formColor').hidden = false;
                    document.getElementById('formGroup').hidden = true;
                    document.getElementById('formTitleInput').disabled = false;
                    document.getElementById('formColorInput').value = el.getAttribute('data-color');
                    document.getElementById('formGlobal').action = 'http://376174.msk-ovz.ru/monitoring/settings/groups/' + el.getAttribute('id') + '/edit';
                }
            }
            document.getElementById('form').hidden = false;
        }

        function deleteSelected () {
            let action = document.getElementById('formGlobal').action.split('/');
            action[action.length - 1] = 'delete';
            document.getElementById('formGlobal').action = action.join('/');
            document.getElementById('formGlobal').submit();
        }

        function selectCheckbox (el) {
            document.getElementById('formColor').hidden = true;
            document.getElementById('formGroup').hidden = false;
            document.getElementById('formGlobal').action = '{{route("settings.edit.cars")}}';

            var actives = document.getElementById('cars').getElementsByClassName('active');
            if (actives[0]) {
                for (let i = 0; actives.length > i; i++) {
                    actives[i].classList.remove('active');
                }
            }

            if (el.checked) {
                el = el.parentNode.parentNode.parentNode;

                countOfCheckedCars.push(el.getAttribute('id'));

                if (countOfCheckedCars.length == 1) {
                    document.getElementById('formGlobal').action = 'http://376174.msk-ovz.ru/monitoring/settings/cars/' + el.getAttribute('id') + '/edit';

                    document.getElementById('formHeader').innerHTML = el.getAttribute('data-title');
                    document.getElementById('formTitleInput').value = el.getAttribute('data-title');
                    document.getElementById('formGroupSelect').value = el.getAttribute('data-group');
                    @if($UserController->user->role == $UserController->roles['admin'])
                    document.getElementById('formOwnerSelect').value = el.getAttribute('data-owner');
                    document.getElementById('formOwnerUnchanged').hidden = true;
                    @endif
                    document.getElementById('formGroupUnchanged').hidden = true;
                } else {
                    if (countOfCheckedCars.length > 1) {
                        document.getElementById('formGlobal').action = '{{route("settings.edit.cars")}}';

                        document.getElementById('formTitleInput').value = '';
                        document.getElementById('formTitleInput').disabled = true;
                        document.getElementById('formHeader').innerHTML = 'Выбранно: ' + countOfCheckedCars.length;
                        document.getElementById('formGroupUnchanged').hidden = false;
                        @if($UserController->user->role == $UserController->roles['admin'])
                        document.getElementById('formOwnerUnchanged').hidden = false;
                        document.getElementById('formOwnerSelect').value = 'unchanged';
                        @endif
                        document.getElementById('formGroupSelect').value = 'unchanged';
                    }

                }


            } else {
                countOfCheckedCars.splice(countOfCheckedCars.indexOf(el.parentNode.parentNode.parentNode.getAttribute('id')), 1);
                el = document.getElementById(countOfCheckedCars[0]);
                if (countOfCheckedCars.length == 0) {
                    document.getElementById('form').hidden = true;
                } else {
                if (countOfCheckedCars.length == 1) {
                    document.getElementById('formGlobal').action = 'http://376174.msk-ovz.ru/monitoring/settings/cars/' + el.getAttribute('id') + '/edit';
                    document.getElementById('formHeader').innerHTML = el.getAttribute('data-title');
                    document.getElementById('formTitleInput').value = el.getAttribute('data-title');
                    document.getElementById('formTitleInput').disabled = false;
                    document.getElementById('formGroupUnchanged').hidden = true;
                    @if($UserController->user->role == $UserController->roles['admin'])
                    document.getElementById('formOwnerUnchanged').hidden = true;
                    document.getElementById('formOwnerSelect').value = el.getAttribute('data-owner');
                    @endif
                    document.getElementById('formGroupSelect').value = el.getAttribute('data-group');
                } else {
                    if (countOfCheckedCars.length > 1) {
                        document.getElementById('formGlobal').action = '{{route("settings.edit.cars")}}';

                        document.getElementById('formHeader').innerHTML = 'Выбранно: ' + countOfCheckedCars.length;
                        document.getElementById('formGroupSelect').value = 'unchanged';
                        @if($UserController->user->role == $UserController->roles['admin'])
                        document.getElementById('formOwnerSelect').value = 'unchanged';
                        document.getElementById('formOwnerUnchanged').hidden = false;
                        @endif
                        document.getElementById('formGroupUnchanged').hidden = false;
                    }
                }
            }
            }

        }


        //--search---
        $('#search').typeahead({
            source: function (search, process) {
                return $.get("{{route('settings.cars.search')}}", { search: search }, function (data) {
                    return process(data);
                });
            }
        });

    </script>
    <!-- END JAVASCRIPT -->

</body>
</html>

@endsection
