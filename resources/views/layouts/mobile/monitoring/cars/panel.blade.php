<div class="headerbar-right mob">
<div class="header-nav header-nav-ul">

    <div class="btn demo-icon-hover" style="position:absolute;right:0;top:0;">
        <i class="md md-close" onclick="unselectCar();"></i>
    </div>

    <!--если машина выбрана-->
    <div class="header-nav-ul-li">
        <div class="gui-icon"><i class="fa fa-puzzle-piece fa-fw"></i></div><div class="header-nav-ul-li-span">{{$car->title}}</div></div>
    <div class="header-nav-ul-li">
        <div class="gui-icon"><i class="fa fa-puzzle-piece fa-fw"></i></div><div class="header-nav-ul-li-span"><span id="mobileCarPanelMinutes">{{number_format((float)(strtotime(now()) - strtotime($car->latest_moving ? $car->latest_moving->created_at : $car->created_at)) / 60, 0, ',', '')}}</span> мин. назад</div></div>
    <div class="header-nav-ul-li">
        <div class="gui-icon"><i class="fa fa-puzzle-piece fa-fw"></i></div><div class="header-nav-ul-li-span">Сигнал: <span id="mobileCarPanelSignal">100</span>%</div></div>
    <div class="header-nav-ul-li">
        <div class="gui-icon"><i class="fa fa-puzzle-piece fa-fw"></i></div><div class="header-nav-ul-li-span">Скорость: <span id="mobileCarPanelSpeed">{{$car->latest_moving ? $car->latest_moving->speed : 0}}</span>км/ч</div></div>
</div>
</div>

{{--<div class="headerbar-right mob">--}}
{{--    <div class="header-nav header-nav-ul">--}}

{{--        <div class="btn demo-icon-hover" style="position:absolute;right:0;top:0;">--}}
{{--            <i class="md md-close" onclick="unselectCar();"></i>--}}
{{--        </div>--}}

{{--        <!--если машина выбрана-->--}}
{{--        <div class="header-nav-ul-li">--}}
{{--            <div class="gui-icon"><i class="fa fa-puzzle-piece fa-fw"></i></div><div class="header-nav-ul-li-span">{{$car->title}}</div></div>--}}
{{--        <div class="header-nav-ul-li">--}}
{{--            <div class="gui-icon"><i class="fa fa-puzzle-piece fa-fw"></i></div><div class="header-nav-ul-li-span">28 сек. назад</div></div>--}}
{{--        <div class="header-nav-ul-li">--}}
{{--            <div class="gui-icon"><i class="fa fa-puzzle-piece fa-fw"></i></div><div class="header-nav-ul-li-span">Сигнал: <span id="carPanelSignal">100</span>%</div></div>--}}
{{--        <div class="header-nav-ul-li">--}}
{{--            <div class="gui-icon"><i class="fa fa-puzzle-piece fa-fw"></i></div><div class="header-nav-ul-li-span">Скорость: <span id="carPanelSpeed">{{$car->latest_moving->speed}}</span>км/ч</div></div>--}}
{{--    </div>--}}
{{--    <ul class="header-nav header-nav-toggle">--}}
{{--        <li>--}}
{{--            <a class="btn btn-icon-toggle btn-default" href="#offperiod-search" data-toggle="offcanvas" data-backdrop="false">--}}
{{--                <i class="fa fa-ellipsis-v"></i>--}}
{{--            </a>--}}
{{--        </li>--}}
{{--        <li>--}}
{{--            <a class="btn btn-icon-toggle btn-default" href="#offcanvas-search" data-toggle="offcanvas" data-backdrop="false">--}}
{{--                <i class="fa fa-ellipsis-v"></i>--}}
{{--            </a>--}}
{{--        </li>--}}
{{--    </ul><!--end .header-nav-toggle -->--}}
{{--</div>--}}
