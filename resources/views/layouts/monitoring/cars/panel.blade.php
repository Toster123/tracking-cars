<div class="card fixed-bottom-panel comp">
    <div class="card-body no-padding list-results">

        <div class="col-xs-12 col-lg-4 hbox-xs">

            <a id="toast-info" class="btn btn-block btn-raised btn-default-bright ink-reaction">
                <i class="md md-notifications pull-right text-default-dark"></i>Info message
            </a>



        </div>

        <div class="col-xs-12 col-lg-4 hbox-xs">

            <!--если машина выбрана-->
            <div class="header-nav-ul-li">
                <div class="gui-icon"><i class="fa fa-puzzle-piece fa-fw"></i></div><div class="header-nav-ul-li-span">{{$car->title}}</div></div>
            <div class="header-nav-ul-li">
                <div class="gui-icon"><i class="fa fa-puzzle-piece fa-fw"></i></div><div class="header-nav-ul-li-span"><span id="carPanelMinutes">{{number_format((float)(strtotime(now()) - strtotime($car->latest_moving ? $car->latest_moving->created_at : $car->created_at)) / 60, 0, ',', '')}}</span> мин. назад</div></div>
            <div class="header-nav-ul-li">
                <div class="gui-icon"><i class="fa fa-puzzle-piece fa-fw"></i></div><div class="header-nav-ul-li-span">Сигнал: <span id="carPanelSignal">100</span>%</div></div>
            <div class="header-nav-ul-li">
                <div class="gui-icon"><i class="fa fa-puzzle-piece fa-fw"></i></div><div class="header-nav-ul-li-span">Скорость: <span id="carPanelSpeed">{{$car->latest_moving ? $car->latest_moving->speed : 0}}</span> км/ч</div></div>

        </div>
        <div class="col-xs-12 col-lg-4 hbox-xs">

            <!--если машина выбрана-->
            <div class="header-nav-ul-li">
                <div class="gui-icon"><i class="fa fa-puzzle-piece fa-fw"></i></div><div class="header-nav-ul-li-span">Широта: <span id="carPanelLat">{{$car->latest_moving ? $car->latest_moving->lat : 0}}</span></div></div>
            <div class="header-nav-ul-li">
                <div class="gui-icon"><i class="fa fa-puzzle-piece fa-fw"></i></div><div class="header-nav-ul-li-span">Долгота: <span id="carPanelLng">{{$car->latest_moving ? $car->latest_moving->lng : 0}}</span></div></div>
            <div class="header-nav-ul-li">
                <div class="gui-icon"><i class="fa fa-puzzle-piece fa-fw"></i></div><div class="header-nav-ul-li-span"><span id="carPanelAdress">...</span></div></div>

        </div>
    </div>
</div>
