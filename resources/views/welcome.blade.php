<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Laravel</title>

    </head>
    <body>
      <div style="width: 100%; height: 600px;" id="map"></div>


        <script src="https://cdnjs.cloudflare.com/ajax/libs/socket.io/2.3.0/socket.io.js"></script>
        <!-- <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCgbUiGX0mdRPWz0A9N3XGs0bGPxns6FxQ&callback=initMap&libraries=&v=weekly" defer></script> -->
        <script>
            var socket = io('http://376174.msk-ovz.ru:6001');
            socket.on('coordinates', function (data) {
                markers[data.imei].setPosition({lat : data.lat, lng : data.lng});
            });

            let map;

            var markers = {};
            function initMap() {
                map = new google.maps.Map(document.getElementById("map"), {
                    center: { lat: 43.337926, lng: 76.938093 },
                    zoom: 8
                });
                let point;
                @foreach($cars as $car)
                    @php
                        $coordinates = $car->lastCoordinates();
                    @endphp
                    point = new google.maps.LatLng(parseFloat({{$coordinates->lat}}),parseFloat({{$coordinates->lng}}));
                markers['{{$car->imei}}'] = new google.maps.Marker({
                    position: point,
                    map: map,
                    title: '{{$car->title}}'
                });
                @php
                $movings = $car->movings;
                @endphp
                var normalPolyline = new google.maps.Polyline({
                    path: [@foreach($movings as $moving) {lat : {{$moving->lat}}, lng : {{$moving->lng}} }, @endforeach],
                    strokeColor: "#0000FF",
                    strokeOpacity: 0.5,
                    strokeWeight: 2,
                    map: map
                });
                @endforeach
            }


        </script>
    </body>
</html>
