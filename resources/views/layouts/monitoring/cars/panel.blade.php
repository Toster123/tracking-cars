<div class="card fixed-bottom-panel comp">
    <div class="card-body no-padding list-results">

        <div class="col-xs-12 col-lg-3 hbox-xs">

			<div class="info-widget main x-box-item info-widget-default simple-scroll webkit-scroll" id="maininfo-1107">
			<div class="widget-item"><p class="main-title">{{$car->title}}</p>
                <div class="gray block"><span id="carPanelConnectionIndicator" class="connection_status @if($car->signal > 0) active @else offline @endif"></span><span id="carPanelConnectionStatus">@if($car->signal > 0) На связи @else Отключен @endif</span></div>
			<div class="gray block selectable" id="ext-gen25750">ID: {{$car->imei}}</div>
			</div>
			</div>

			<!--
            <a id="toast-info" class="btn btn-block btn-raised btn-default-bright ink-reaction">
                <i class="md md-notifications pull-right text-default-dark"></i>Info message
            </a>
			-->

        </div>

        <div class="col-xs-12 col-lg-3 hbox-xs">

            <!--если машина выбрана-->
            <div class="header-nav-ul-li">
                <div class="gui-icon"></div><div class="header-nav-ul-li-span"><span id="carPanelMinutes">{{number_format((float)(strtotime(now()) - strtotime($car->latest_moving ? $car->latest_moving->created_at : $car->created_at)) / 60, 0, ',', '')}}</span> мин. назад</div></div>
            <div class="header-nav-ul-li">
                <div class="gui-icon"></div><div class="header-nav-ul-li-span">Сигнал: <span id="carPanelSignal">{{$car->signal}}</span>%</div></div>
            <div class="header-nav-ul-li">
                <div class="gui-icon"></div><div class="header-nav-ul-li-span">Скорость: <span id="carPanelSpeed">{{$car->latest_moving && $car->is_driving ? $car->latest_moving->speed : 0}}</span> км/ч</div></div>


        </div>
        <div class="col-xs-12 col-lg-3 hbox-xs">

            <!--если машина выбрана-->
            <div class="header-nav-ul-li">
                <div class="gui-icon"></div><div class="header-nav-ul-li-span">Широта: <span id="carPanelLat">{{$car->latest_moving ? $car->latest_moving->lat : 0}}</span></div></div>
            <div class="header-nav-ul-li">
                <div class="gui-icon"></div><div class="header-nav-ul-li-span">Долгота: <span id="carPanelLng">{{$car->latest_moving ? $car->latest_moving->lng : 0}}</span></div></div>
            <div class="header-nav-ul-li">
                <div class="gui-icon"></div><div class="header-nav-ul-li-span"><span id="carPanelAdress">...</span></div></div>

        </div>
    </div>
</div>
