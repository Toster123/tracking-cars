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

    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- END META -->

    <!-- BEGIN STYLESHEETS -->
    <link href='http://fonts.googleapis.com/css?family=Roboto:300italic,400italic,300,400,500,700,900' rel='stylesheet' type='text/css'/>
    <link type="text/css" rel="stylesheet" href="{{asset('assets/css/theme-default/bootstrap.css?1422792965')}}" />
    <link type="text/css" rel="stylesheet" href="{{asset('assets/css/theme-default/materialadmin.css?1425466319')}}" />
    <link type="text/css" rel="stylesheet" href="{{asset('assets/css/theme-default/font-awesome.min.css?1422529194')}}" />
    <link type="text/css" rel="stylesheet" href="{{asset('assets/css/theme-default/material-design-iconic-font.min.css?1421434286')}}" />
	<link type="text/css" rel="stylesheet" href="{{asset('assets/css/theme-default/libs/toastr/toastr.css?1425466569')}}" />
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
                    <!-- BEGIN BASIC MAP -->
                    <div id="pageContent" class="col-md-12">
                        <div class="card" style="height:100vh;margin-bottom:0px;">
                            <div class="card-body no-padding">
                                <div id="basic-map" class="border-gray height-10" style="height:calc(100vh - 30px);"></div>
                            </div>
                        </div><!--end .card -->

                        <!-- TRIPS -->

						<div style="position:absolute;bottom:30px;width:100%;" id="carPanel"></div><!-- Сюда будет подгружаться панель для машины -->
						<div style="position:absolute;top:64px;height:35px;padding:5px 0;width:100%;background:#ffffff;border-bottom: 1px solid black">

							<div class="col-md-2">
							<span style="color:#000;float:left;margin: 0 15px 0 0;">Карта: </span>
								<ul style="
    list-style: none;">
								<li style="
    float: left;
	margin: 0 5px 0 0;
    width: 20px;
    color: #000;"><a href="javascript:map.setMapTypeId('roadmap');">1</a></li>
								<li style="
    float: left;
	margin: 0 5px 0 0;
    width: 20px;
    color: #000;"><a href="javascript:map.setMapTypeId('hybrid');">2</a></li>
								<li style="
    float: left;
	margin: 0 5px 0 0;
    width: 20px;
    color: #000;"><a href="javascript:map.setMapTypeId('terrain');">3</a></li>
								</ul>
							</div>
							<div class="col-md-2">
								<input id="placeSearch" style="
    line-height: 20px;
    height: 100%;
    width: 100%;
    float: left;" type="text" placeholder="Поиск..." />
							</div>

						</div>

                    </div><!--end .col -->
                    <!-- END BASIC MAP -->
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
                <li class="gui-folder expanded">
                    <a>
                        <div class="gui-icon"><i class="md md-computer"></i></div>
                        <span class="title">Машины</span>
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
                                        <li><a class="checkbox-styled"><label><input checked type="checkbox" onchange="selectGroupCheckbox(this);" data-type="group" name="{{$group->_id}}"></label><label><span class="title">Выбрать все</span></label></a></li>
                                    @endif

                                    @foreach($group->cars as $car)
                                        <li><a class="checkbox-styled"><label><input checked type="checkbox" onchange="selectCarCheckbox(this);" data-type="car" data-imei="{{$car->imei}}" name="{{$group->_id}}"></label><label onclick="selectCar('{{$car->_id}}');" style="cursor: pointer"><span class="title">{{$car->title}}</span></label></a></li>
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
                                            <li><a class="checkbox-styled"><label><input checked type="checkbox" onchange="selectGroupCheckbox(this);" data-type="group" name="nothing"></label><label><span class="title">Выбрать все</span></label></a></li>

                                        @foreach($cars as $car)
                                            <li><a class="checkbox-styled"><label><input checked type="checkbox" onchange="selectCarCheckbox(this);" data-type="car" data-imei="{{$car->imei}}" name="nothing"></label><label onclick="selectCar('{{$car->_id}}');" style="cursor: pointer"><span class="title">{{$car->title}}</span></label></a></li>
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

                <ul hidden="true" class="list" id="eventsCar"></ul>

            </div><!--end .offcanvas-body -->
        </div><!--end .offcanvas-pane -->
        <!-- END OFFCANVAS SEARCH -->

    </div><!--end .offcanvas-->
    <!-- END OFFCANVAS RIGHT -->

    <!-- BEGIN ONPERIOD RIGHT -->
    <div class="offperiod">

        <!-- BEGIN OFFCANVAS SEARCH -->
        <div id="offperiod-search" class="offcanvas-pane width-8">
            <div class="offcanvas-head">
                <header class="text-primary">Поездки</header>
                <div class="offcanvas-tools">
                    <a class="btn btn-icon-toggle btn-default-light pull-right" data-dismiss="offcanvas">
                        <i class="md md-close"></i>
                    </a>
                </div>
            </div>
            <div class="onperiod-body no-padding">
                <ul class="list" id="calendar">
                    <li class="tile"><div class="tile-content"><div class="tile-text"><small>Выберите машину, чтобы посмотреть ее поездки</small></div></div></li>
                </ul>
            </div><!--end .offcanvas-body -->
        </div><!--end .onperiod-pane -->
        <!-- END ONPERIOD SEARCH -->

    </div><!--end .offcanvas-->
    <!-- END OFFCANVAS RIGHT -->

</div><!--end #base-->
<!-- END BASE -->
@endsection

@section('end')
<!-- BEGIN JAVASCRIPT -->
<script src="{{asset('assets/js/libs/jquery/jquery-1.11.2.min.js')}}"></script>
<script src="{{asset('assets/js/libs/jquery/jquery-migrate-1.2.1.min.js')}}"></script>
<script src="{{asset('assets/js/libs/bootstrap/bootstrap.min.js')}}"></script>
<script src="{{asset('assets/js/libs/spin.js/spin.min.js')}}"></script>
<script src="{{asset('assets/js/libs/autosize/jquery.autosize.min.js')}}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/socket.io/2.3.0/socket.io.js"></script>
<script src="https://unpkg.com/@google/markerclustererplus@5.1.0/dist/markerclustererplus.min.js"></script>
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCgbUiGX0mdRPWz0A9N3XGs0bGPxns6FxQ&callback=initMap&libraries=geometry,places&v=weekly" defer></script>
<!-- <script src="assets/js/libs/gmaps/gmaps.js"></script> -->
<script src="{{asset('assets/js/libs/nanoscroller/jquery.nanoscroller.min.js')}}"></script>
<script src="{{asset('assets/js/core/source/App.js')}}"></script>
<script src="{{asset('assets/js/core/source/AppNavigation.js')}}"></script>
<script src="{{asset('assets/js/core/source/AppOffcanvas.js')}}"></script>
<script src="{{asset('assets/js/core/source/AppCard.js')}}"></script>
<script src="{{asset('assets/js/core/source/AppForm.js')}}"></script>
<script src="{{asset('assets/js/core/source/AppNavSearch.js')}}"></script>
<script src="{{asset('assets/js/core/source/AppVendor.js')}}"></script>
<script src="{{asset('assets/js/core/demo/Demo.js')}}"></script>
<script src="{{asset('assets/js/core/demo/DemoPageMaps.js')}}"></script>

<script src="{{asset('assets/js/libs/toastr/toastr.js')}}"></script>
<script src="{{asset('assets/js/core/demo/DemoUIMessages.js')}}"></script>
<script type="text/javascript">
    //для post запросов
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    //---

    var socket = io('http://376174.msk-ovz.ru:6001');
    //обработка событий сокетов в конце


    //---вспомогательные функции---

    function randomHex() {
        let output = '';
        for (let i = 0; i < 6; ++i) {
            output += (Math.floor(Math.random() * 16)).toString(16);
        }
        return output;
    }

    //для поиска улиц
    function placeChanged() {
        let place = autocomplete.getPlace();
        if (place.geometry) {
            map.panTo(place.geometry.location);
            map.setZoom(15);
        }
    }

    //для карты

    function selectCar (id) {
        $.ajax({
            url : 'http://376174.msk-ovz.ru/monitoring/cars/' + id,
            type : 'GET',
            datatype : 'json',
            success : function (data) {
                data = JSON.parse(data);
                if (data.car && data.car.latest_moving) {

                    selectedCar = data.car._id;

                    removeTrips();

                    document.getElementById('carPanel').innerHTML = data.panel;
                    document.getElementById('mobileCarPanel').innerHTML = data.mobile_panel;

                    document.getElementById('basic-map').setAttribute('style','height: calc(100vh - 145px); position: relative; overflow: hidden;');

                    map.setZoom(17);
                    map.panTo({'lat' : parseFloat(data.car.latest_moving.lat), 'lng' : parseFloat(data.car.latest_moving.lng)});

                    document.getElementById('events').hidden = true;

                    document.getElementById('eventsCar').innerHTML = data.events;
                    document.getElementById('eventsCar').hidden = false;

                    document.getElementById('calendar').innerHTML = data.calendar;

                    selectedCarInterval = setInterval(function () {
                        let minutes = Number(document.getElementById('carPanelMinutes').innerHTML);
                        minutes++;
                        document.getElementById('carPanelMinutes').innerHTML = minutes;
                        document.getElementById('mobileCarPanelMinutes').innerHTML = minutes;
                    }, 60000);

                    // geocoder.geocode({'latLng' : {'lat' : parseFloat(data.car.latest_moving.lat), 'lng' : parseFloat(data.car.latest_moving.lng)}}, function (res, st) {
                    //     console.log(res, st);
                    // });
                    $.ajax({
                            'url': 'https://geocode-maps.yandex.ru/1.x/?apikey=69281f91-22b2-45bd-b749-d46b51d6311a&format=json&geocode=' + parseFloat(data.car.latest_moving.lng) + ',' + parseFloat(data.car.latest_moving.lat),
                            'type': 'GET',
                            success: function (res) {
                                document.getElementById('carPanelAdress').innerHTML = res.response.GeoObjectCollection.featureMember[0].GeoObject.name;
                            }
                        }
                    );
                }
            },
        });
    }

    function unselectCar (id) {
        selectedCar = false;
        removeTrips();

        document.getElementById('carPanel').innerHTML = '';
        document.getElementById('mobileCarPanel').innerHTML = '';

        document.getElementById('basic-map').setAttribute('style','height: calc(100vh - 30px); position: relative; overflow: hidden;');

        document.getElementById('eventsCar').innerHTML = '';
        document.getElementById('eventsCar').hidden = true;

        document.getElementById('events').hidden = false;

        selectedCarInterval = false;

        document.getElementById('calendar').innerHTML = '<li class="tile"><div class="tile-content"><div class="tile-text"><small>Выберите машину, чтобы посмотреть ее поездки</small></div></div></li>';
    }

    //для панели слева

    function selectGroupCheckbox (el) {
        var cars = $('input[type="checkbox"][name="' + el.getAttribute('name') + '"]').prop('checked', el.checked);

        for (let key in indexes[el.getAttribute('name')]) {
            if (el.checked) {
                markerCluster.addMarker(markers[indexes[el.getAttribute('name')][key]]);
            } else {
                markerCluster.removeMarker(markers[indexes[el.getAttribute('name')][key]]);
            }
        }
    }

    function selectCarCheckbox (el) {
        var group = $('input[type="checkbox"][data-type="group"][name="' + el.getAttribute('name') + '"]');

        if (el.checked) {
            var cars = $('input[type="checkbox"][data-type="car"][name="' + el.getAttribute('name') + '"]');

            let isAllChecked = true;
            for (let i = 0; i < cars.length; i++) {
                if (!cars[i].checked) {
                    isAllChecked = false;
                    break;
                }
            }
            if (isAllChecked) {
                group.prop('checked', true);
            }
        } else {
            group.prop('checked', false);
        }

        if (el.checked) {
            markerCluster.addMarker(markers[indexes[el.getAttribute('name')][el.getAttribute('data-imei')]]);
        } else {
            markerCluster.removeMarker(markers[indexes[el.getAttribute('name')][el.getAttribute('data-imei')]]);
        }

    }

    //для поездок

    function selectDisplayUpToCurrentTime (el) {
        if (el.checked) {
            document.getElementById('upToDate').value = '';
            document.getElementById('upToDate').disabled = true;
            document.getElementById('upToTime').value = '';
            document.getElementById('upToTime').disabled = true;
        } else {
            document.getElementById('upToDate').disabled = false;
            document.getElementById('upToTime').disabled = false;
        }
    }

    function tripsADay () {
        let date = new Date;
        date.setDate(date.getDate() - 1);
        document.getElementById('fromDate').value = date.getFullYear() + '-' + (date.getMonth() + 1) + '-' + date.getDate();
        document.getElementById('fromTime').value = date.getHours() + ':' + date.getMinutes();
        document.getElementById('displayUpToCurrentTime').checked = true;
        selectDisplayUpToCurrentTime(document.getElementById('displayUpToCurrentTime'));
    }
    function tripsAWeek () {
        let date = new Date;
        date.setDate(date.getDate() - 7);
        document.getElementById('fromDate').value = date.getFullYear() + '-' + (date.getMonth() + 1) + '-' + date.getDate();
        document.getElementById('fromTime').value = date.getHours() + ':' + date.getMinutes();
        document.getElementById('displayUpToCurrentTime').checked = true;
        selectDisplayUpToCurrentTime(document.getElementById('displayUpToCurrentTime'));
    }
    function tripsAMonth () {
        let date = new Date;
        document.getElementById('fromDate').value = date.getFullYear() + '-' + date.getMonth() + '-' + date.getDate();
        document.getElementById('fromTime').value = date.getHours() + ':' + date.getMinutes();
        document.getElementById('displayUpToCurrentTime').checked = true;
        selectDisplayUpToCurrentTime(document.getElementById('displayUpToCurrentTime'));
    }

    $(document).on('submit', '#travelPeriodForm', function (e) {
        e.preventDefault();
        if (document.getElementById('fromDate').value &&
            (document.getElementById('upToDate').value || document.getElementById('displayUpToCurrentTime').checked)) {

            if (document.getElementById('fromTime').value == '' || document.getElementById('fromTime').value.length < 5) {
                document.getElementById('fromTime').value = '00:00';
            }
            if ((document.getElementById('upToTime').value == '' || document.getElementById('upToTime').value.length < 5) && !document.getElementById('displayUpToCurrentTime').checked) {
                document.getElementById('upToTime').value = '23:59';
            }
            let data = $(this).serialize();
            $.ajax({
                url: document.getElementById('travelPeriodForm').action,
                type: 'POST',
                datatype: 'json',
                data: data,
                success: function (data) {
                    if (data) {
                        removeTrips();

                        data = JSON.parse(data);

                        $('#travelResult').append(data.view);

                        let color;
                        for (let key in data.path) {
                            color = "#" + randomHex();
                            trips[key] = new google.maps.Polyline({
                                path: data.path[key],
                                icons: [{
                                    icon: {
                                        path: 'M -1,-2 0,-0.7 1,-2 0,2.5 z',
                                        strokeColor : '#000',
                                        strokeWeight : 1.5,
                                        fillColor: color,
                                        fillOpacity: 1,
                                        scale: 6,
                                        rotation : 180,
                                    },
                                    offset: '100%',
                                    repeat: '180px',
                                }],
                                strokeColor: color,
                                strokeWeight: 9,
                                strokeOpacity: 0.5,
                                map: map
                            });

                            google.maps.event.addListener(trips[key], 'click', function (event) {
                                let coordinates = event.latLng;
                                let needle = {coordinates: {lat: 0, lng: 0}};
                                let distance;

                                trips[key].getPath().forEach(function (point, index) {
                                    distance = google.maps.geometry.spherical.computeDistanceBetween(coordinates, point);
                                    if (!needle['minDistance'] || needle['minDistance'] > distance) {
                                        needle['minDistance'] = distance;
                                        needle['index'] = index;
                                        needle['coordinates'] = {lat: point.lat(), lng: point.lng()};
                                    }
                                });

                                $.ajax({
                                    url: 'http://376174.msk-ovz.ru/monitoring/trips/' + key + '/moving',
                                    type: 'POST',
                                    datatype: 'json',
                                    data: {trip_id : key, lat : needle['coordinates']['lat'], lng : needle['coordinates']['lng']},
                                    success: function (data) {
                                        if (data) {
                                            let date = new Date(data.created_at),
                                                mounth = date.getMonth() + 1,
                                                days = date.getDate(),
                                                hours = date.getHours(),
                                                minutes = date.getMinutes();
                                            if (mounth.toString().length < 2) mounth = '0' + mounth;
                                            if (days.toString().length < 2) days = '0' + days;
                                            if (hours.toString().length < 2) hours = '0' + hours;
                                            if (minutes.toString().length < 2) minutes = '0' + minutes;

                                            date = days + '.' + mounth + '.' + date.getFullYear() + ' ' + hours + ':' + minutes;

                                            tripInfowindow.close();
                                            tripInfowindow.setPosition({lat: parseFloat(data.lat), lng: parseFloat(data.lng)});
                                            tripInfowindow.setContent(
                                                '<p><strong>Время:</strong> ' + date + '</p>' +
                                                '<p><strong>Скорость:</strong> ' + data.speed + 'км/час</p>' +
                                                '<p><strong>Широта:</strong> ' + data.lat + '</p>' +
                                                '<p><strong>Долгота:</strong> ' + data.lng + '</p>'
                                            );
                                            tripInfowindow.open(map);
                                        }
                                    }
                                });

                            });

                            trips[key].setVisible(false);
                        }

                        isTripsShowing = true;
                        isTripsShowingUpToCurrentTime = document.getElementById('displayUpToCurrentTime').checked;
                    }
                }
            });
        }
    });

    function selectAllTripsCheckbox (el) {
        $('input[type="checkbox"][name="' + el.getAttribute('name') + '"]').prop('checked', el.checked);

        for (let key in trips) {
            trips[key].setVisible(el.checked);
        }
    }

    function selectTripCheckbox (el) {
        let selectAllCheckbox = $('input[type="checkbox"][data-type="all"][name="' + el.getAttribute('name') + '"]');

        if (el.checked) {
            let tripsCheckboxes = $('input[type="checkbox"][data-type="trip"][name="' + el.getAttribute('name') + '"]');

            let isAllChecked = true;
            for (let i = 0; i < tripsCheckboxes.length; i++) {
                if (!tripsCheckboxes[i].checked) {
                    isAllChecked = false;
                    break;
                }
            }
            if (isAllChecked) {
                selectAllCheckbox.prop('checked', true);
            }
        } else {
            selectAllCheckbox.prop('checked', false);
        }
        trips[el.getAttribute('id')].setVisible(el.checked);
    }

    function removeTrips () {
        if (document.getElementById('trips')) {
            document.getElementById('trips').remove();
        }

        for(key in trips) {
            trips[key].setMap(null);
        }
        trips = {};
        isTripsShowing = false;
        isTripsShowingUpToCurrentTime = false;
    }

    //для событий

    function selectEvent (el) {
        if (el.getAttribute('data-lat') && el.getAttribute('data-lng')) {
            eventInfowindow.close();
            eventInfowindow.setPosition(new google.maps.LatLng(parseFloat(el.getAttribute('data-lat')), parseFloat(el.getAttribute('data-lng'))));
            eventInfowindow.setContent(el.innerHTML);
            eventInfowindow.open(map);
            map.setZoom(17);
            map.panTo(new google.maps.LatLng(parseFloat(el.getAttribute('data-lat')), parseFloat(el.getAttribute('data-lng'))));
        }
    }

    //подгрузка
    let is_loading;
    $(document).bind('scrollend', '.nano-content', function (e) {
        is_loading = selectedCar ? $('#eventsCar').children().last().attr('id') == 'last ' : $('#events').children().last().attr('id') == 'last ';
        if (!is_loading) {
            is_loading = true;

            if (selectedCar) {
                $.ajax({
                    url: 'http://376174.msk-ovz.ru/monitoring/cars/' + selectedCar + '/events/more',
                    method: 'POST',
                    data: {'id' : $('#eventsCar').children().last().attr('id')},
                    datatype: 'html',
                    success: function (data) {
                        $('#eventsCar').append(data);
                        if ($('#eventsCar').children().last().attr('id') !== 'last ') {
                            is_loading = false;
                        }
                    }
                });
            } else {
                $.ajax({
                    url: '{{route('events.more')}}',
                    method: 'POST',
                    data: {'id' : $('#events').children().last().attr('id')},
                    datatype: 'html',
                    success: function (data) {
                        $('#events').append(data);
                        if ($('#events').children().last().attr('id') !== 'last ') {
                            is_loading = false;
                        }
                    }
                });
            }

        }
    });
    //---

    //---карта---
    let map;
    let eventInfowindow;
    let tripInfowindow;
    var selectedCar = false;
    var isTripsShowing = false;
    var isTripsShowingUpToCurrentTime = false;
    var trips = {};
    var markers = [];
    var indexes = {};
    let selectedCarInterval;
    let geocoder;
    let autocomplete;
    let places;
    var markerCluster;
    function initMap() {
        map = new google.maps.Map(document.getElementById("basic-map"), {
            center: {lat: 43.337926, lng: 76.938093},
            zoom: 11
        });
        let point;
        let color;
        let markerImage;
        let i = 0;
        @foreach($groups as $group)
            indexes['{{$group->_id}}'] = {};

        color = '{{$group->color}}';
        if (color[0] !== '#') {
            color = '#' + color;
        }


        @foreach($group->cars as $car)
            @php
                $coordinates = $car->lastCoordinates() ?: (Object)['lat' => 43.337926, 'lng' => 76.938093, 'course' => 0];
            @endphp
            point = {lat: parseFloat({{$coordinates->lat}}), lng: parseFloat({{$coordinates->lng}})};
        markers[i] = new google.maps.Marker({
            position: point,
            title: '{{$car->title}}',
            icon: {
                path: 'M -1.5,-2.9 0,-1.2 1.5,-2.9 0,2.9 z',
                strokeColor: '#000',
                strokeWeight: 1.5,
                fillColor: color,
                fillOpacity: 1,
                scale: 6,
                rotation: Number('{{(int)$coordinates->course > 180 ? $coordinates->course - 180 : $coordinates->course + 180}}')
            }
        });
        indexes['{{$group->_id}}']['{{$car->imei}}'] = i;
        markers[i].addListener('click', function () {
            selectCar('{{$car->_id}}');
        });
        i++;
        @endforeach
            @endforeach


            @if(!empty($cars->toArray()))
            indexes['nothing'] = {};

        @foreach($cars as $car)
            @php
                $coordinates = $car->lastCoordinates() ?: (Object)['lat' => 43.337926, 'lng' => 76.938093, 'course' => 0];
            @endphp
            point = new google.maps.LatLng(parseFloat({{$coordinates->lat}}), parseFloat({{$coordinates->lng}}));
        markers[i] = new google.maps.Marker({
            position: point,
            title: '{{$car->title}}',
            icon: {
                path: 'M -1.5,-2.9 0,-1.2 1.5,-2.9 0,2.9 z',
                strokeColor: '#000',
                strokeWeight: 1.5,
                fillColor: '#FE7569',
                fillOpacity: 1,
                scale: 6,
                rotation: Number('{{(int)$coordinates->course > 180 ? $coordinates->course - 180 : $coordinates->course + 180}}')
            }
        });
        indexes['nothing']['{{$car->imei}}'] = i;
        markers[i].addListener('click', function () {
            selectCar('{{$car->_id}}');
        });
        i++;
        @endforeach
            @endif

            markerCluster = new MarkerClusterer(map, markers,
            {imagePath: 'https://developers.google.com/maps/documentation/javascript/examples/markerclusterer/m'});


        eventInfowindow = new google.maps.InfoWindow({});
        tripInfowindow = new google.maps.InfoWindow({});
        geocoder = new google.maps.Geocoder();
        places = new google.maps.places.PlacesService(map);
        autocomplete = new google.maps.places.Autocomplete(
            document.getElementById('placeSearch'), {
            });
        autocomplete.addListener('place_changed', placeChanged);
    };
    //---

    //---обработка событий сокетов---

    socket.on('moving', function (data) {
        console.log(data);
        let marker;
        if (data.group_id && data.group_id !== null) {
            marker = markers[indexes[data.group_id][data.imei]];
        } else {
            marker = markers[indexes['nothing'][data.imei]];
        }
        marker.setIcon({
            path: 'M -1.8,-3.2 0,-1.5 1.8,-3.2 0,3.2 z',
            strokeColor : '#000',
            strokeWeight : 1.5,
            fillColor: marker.icon.fillColor,
            fillOpacity: 1,
            scale: 6,
            rotation: Number(data.course) > 180 ? Number(data.course) - 180 : Number(data.course) + 180
        });
        marker.setPosition({lat : data.lat, lng : data.lng});

        if (selectedCar == data.car_id) {
            document.getElementById('carPanelMinutes').innerHTML = 0;
            document.getElementById('mobileCarPanelMinutes').innerHTML = 0;

            document.getElementById('carPanelLat').innerHTML = data.lat;
            document.getElementById('carPanelSpeed').innerHTML = data.speed;
            document.getElementById('mobileCarPanelSpeed').innerHTML = data.speed;
            document.getElementById('carPanelSignal').innerHTML = 100;
            document.getElementById('mobileCarPanelSignal').innerHTML = 100;
            document.getElementById('carPanelLng').innerHTML = data.lng;

            geocoder.geocode({'latLng' : {'lat' : parseFloat(data.lat), 'lng' : parseFloat(data.lng)}}, function (res, st) {
                console.log(res, st);
            });

            if (isTripsShowing && trips) {
                let mileage = Number(document.getElementById('notEndedTripMileage').innerHTML);
                if (mileage instanceof Number) {
                    document.getElementById('notEndedTripMileage').innerHTML = Math.round(mileage + Number(data.mileage), 3);
                }
                if (isTripsShowingUpToCurrentTime) {
                    trips[Object.keys(trips)[0]].getPath().push(new google.maps.LatLng(parseFloat(data.lat),parseFloat(data.lng)));
                }
            }
        }
    });

    socket.on('tripEnded', function (data) {
        console.log('tr ended');
        if (selectedCar == data.car_id && isTripsShowing && trips && document.getElementById('notEndedTripTime') && document.getElementById('notEndedTripMileage')) {

        }
        let date = new Date(data.ended_at),
            mounth = date.getMonth() + 1,
            days = date.getDate(),
            hours = date.getHours(),
            minutes = date.getMinutes();
        if (mounth.toString().length < 2) mounth = '0' + mounth;
        if (days.toString().length < 2) days = '0' + days;
        if (hours.toString().length < 2) hours = '0' + hours;
        if (minutes.toString().length < 2) minutes = '0' + minutes;

        date = days + '.' + mounth + '.' + date.getFullYear() + ' ' + hours + ':' + minutes;

        document.getElementById('notEndedTripTime').innerHTML = date;
        document.getElementById('notEndedTripTime').removeAttribute('id');

        let mileage = Number(document.getElementById('notEndedTripMileage').innerHTML);
        if (mileage instanceof Number) {
            document.getElementById('notEndedTripMileage').innerHTML = mileage + Number(data.mileage);
        }
        document.getElementById('notEndedTripMileage').removeAttribute('id');
    });

    socket.on('newTrip', function (data) {
        console.log('n tr');
        if (selectedCar == data.car_id && isTripsShowing && trips && isTripsShowingUpToCurrentTime) {

        }
        let date = new Date(data.created_at),
            mounth = date.getMonth() + 1,
            days = date.getDate(),
            hours = date.getHours(),
            minutes = date.getMinutes();
        if (mounth.toString().length < 2) mounth = '0' + mounth;
        if (days.toString().length < 2) days = '0' + days;
        if (hours.toString().length < 2) hours = '0' + hours;
        if (minutes.toString().length < 2) minutes = '0' + minutes;

        date = days + '.' + mounth + '.' + date.getFullYear() + ' ' + hours + ':' + minutes;

        $('#tripsList').prepend('<li><div class="checkbox checkbox-styled">\n' +
            '                            <label>\n' +
            '                                <input id="' + data._id + '" type="checkbox" data-type="trip" name="trips" onchange="selectTripCheckbox(this)">\n' +
            '                                <span>' + date + ' - <span id="notEndedTripTime">В пути</span><span id="notEndedTripMileage">0</span> Км.\n' +
            '                                </span>\n' +
            '                            </label>\n' +
            '                        </div>\n' +
            '                    </li>');

        Object.assign({[data._id] : new google.maps.Polyline({
                path: [],
                icons: [{
                    icon: {
                        path: google.maps.SymbolPath.FORWARD_CLOSED_ARROW,
                        rotation: 180
                    },
                    offset: '100%',
                    repeat: '80px',
                }],
                strokeColor: "#0000FF",
                strokeOpacity: 0.5,
                strokeWeight: 2,
                map: map
            })
        }, trips);
    });

    socket.on('event', function (data) {
        console.log('event');
        let date = new Date(data.created_at),
            mounth = date.getMonth() + 1,
            days = date.getDate(),
            hours = date.getHours(),
            minutes = date.getMinutes();
        if (mounth.toString().length < 2) mounth = '0' + mounth;
        if (days.toString().length < 2) days = '0' + days;
        if (hours.toString().length < 2) hours = '0' + hours;
        if (minutes.toString().length < 2) minutes = '0' + minutes;

        date = days + '.' + mounth + '.' + date.getFullYear() + ' ' + hours + ':' + minutes;

        let html = '';
        let description;
        switch (data.type) {
            case 0 :
                if (selectedCar == data.car_id) {
                    document.getElementById('carPanelSignal').innerHTML = 100;
                    document.getElementById('mobileCarPanelSignal').innerHTML = 100;
                    document.getElementById('carPanelConnectionIndicator').classList.add('active');
                    document.getElementById('carPanelConnectionIndicator').classList.remove('offline');
                    document.getElementById('carPanelConnectionStatus').innerHTML = 'На связи';
                }
                description = 'подключен';
                html += '<li class="tile">\n' +
                    '        <a class="tile-content ink-reaction" onclick="selectEvent(this)" date-lat="' + data.lat + '" date-lng="' + data.lng + '" data-toggle="offcanvas" data-backdrop="false">\n' +
                    '            <div class="tile-text">\n' +
                    '                                    ' + data.title + '\n' +
                    '                    <small>' + description + ' (' + date + ')</small>\n' +
                    '                                </div>\n' +
                    '        </a>\n' +
                    '    </li>';
                break;
            case 1 :
                if (selectedCar == data.car_id) {
                    document.getElementById('carPanelSignal').innerHTML = 0;
                    document.getElementById('mobileCarPanelSignal').innerHTML = 0;
                    document.getElementById('carPanelSpeed').innerHTML = 0;
                    document.getElementById('mobileCarPanelSpeed').innerHTML = 0;
                    document.getElementById('carPanelConnectionIndicator').classList.add('offline');
                    document.getElementById('carPanelConnectionIndicator').classList.remove('active');
                    document.getElementById('carPanelConnectionStatus').innerHTML = 'Отключен';
                }
                description = 'отключен или прервана связь';
                html += '<li class="tile">\n' +
                    '        <a class="tile-content ink-reaction" onclick="selectEvent(this)" date-lat="' + data.lat + '" date-lng="' + data.lng + '" data-toggle="offcanvas" data-backdrop="false">\n' +
                    '            <div class="tile-text">\n' +
                    '                                    ' + data.title + '\n' +
                    '                    <small>' + description + ' (' + date + ')</small>\n' +
                    '                                </div>\n' +
                    '        </a>\n' +
                    '    </li>';
                break;
            case 2 :

                description = 'вход';
                html += '<li class="tile">\n' +
                    '        <a class="tile-content ink-reaction" data-toggle="offcanvas" data-backdrop="false">\n' +
                    '            <div class="tile-text">\n' +
                    '                                    ' + data.title + '\n' +
                    '                    <small>' + description + ' (' + date + ')</small>\n' +
                    '                                </div>\n' +
                    '        </a>\n' +
                    '    </li>';
                break;
            case 3 :
                description = 'выход';
                html += '<li class="tile">\n' +
                    '        <a class="tile-content ink-reaction" data-toggle="offcanvas" data-backdrop="false">\n' +
                    '            <div class="tile-text">\n' +
                    '                                    ' + data.title + '\n' +
                    '                    <small>' + description + ' (' + date + ')</small>\n' +
                    '                                </div>\n' +
                    '        </a>\n' +
                    '    </li>';
                break;
            case 4 :
                description = 'зажигание включено';
                html += '<li class="tile">\n' +
                    '        <a class="tile-content ink-reaction" onclick="selectEvent(this)" date-lat="' + data.lat + '" date-lng="' + data.lng + '" data-toggle="offcanvas" data-backdrop="false">\n' +
                    '            <div class="tile-text">\n' +
                    '                                    ' + data.title + '\n' +
                    '                    <small>' + description + ' (' + date + ')</small>\n' +
                    '                                </div>\n' +
                    '        </a>\n' +
                    '    </li>';
                break;
            case 5 :
                if (selectedCar == data.car_id) {
                    document.getElementById('carPanelSpeed').innerHTML = 0;
                    document.getElementById('mobileCarPanelSpeed').innerHTML = 0;
                }
                description = 'зажигание выключено';
                html += '<li class="tile">\n' +
                    '        <a class="tile-content ink-reaction" onclick="selectEvent(this)" date-lat="' + data.lat + '" date-lng="' + data.lng + '" data-toggle="offcanvas" data-backdrop="false">\n' +
                    '            <div class="tile-text">\n' +
                    '                                    ' + data.title + '\n' +
                    '                    <small>' + description + ' (' + date + ')</small>\n' +
                    '                                </div>\n' +
                    '        </a>\n' +
                    '    </li>';
                break;
            case 6 :
                description = 'начал движение';
                html += '<li class="tile">\n' +
                    '        <a class="tile-content ink-reaction" onclick="selectEvent(this)" date-lat="' + data.lat + '" date-lng="' + data.lng + '" data-toggle="offcanvas" data-backdrop="false">\n' +
                    '            <div class="tile-text">\n' +
                    '                                    ' + data.title + '\n' +
                    '                    <small>' + description + ' (' + date + ')</small>\n' +
                    '                                </div>\n' +
                    '        </a>\n' +
                    '    </li>';
                break;
            case 7 :
                if (selectedCar == data.car_id) {
                    document.getElementById('carPanelSpeed').innerHTML = 0;
                    document.getElementById('mobileCarPanelSpeed').innerHTML = 0;
                }
                description = 'остановился';
                html += '<li class="tile">\n' +
                    '        <a class="tile-content ink-reaction" onclick="selectEvent(this)" date-lat="' + data.lat + '" date-lng="' + data.lng + '" data-toggle="offcanvas" data-backdrop="false">\n' +
                    '            <div class="tile-text">\n' +
                    '                                    ' + data.title + '\n' +
                    '                    <small>' + description + ' (' + date + ')</small>\n' +
                    '                                </div>\n' +
                    '        </a>\n' +
                    '    </li>';
                break;
        }
        $('#events').prepend(html);
        if (selectedCar == data.car_id) {
            $('#eventsCar').prepend(html);
        }

        if (selectedCar) {
            if (selectedCar == data.car_id) {
                toastr.info(data.title, description);
            }
        } else {
            toastr.info(data.title, description);
        }
    });
</script>
<!-- END JAVASCRIPT -->

</body>
</html>

@endsection
