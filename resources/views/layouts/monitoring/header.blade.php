<!-- BEGIN HEADER-->
<header id="header" >
    <div class="headerbar">
        <!-- Brand and toggle get grouped for better mobile display -->
        <div class="headerbar-left">
            <ul class="header-nav header-nav-options">
                <li class="header-nav-brand" >
                    <div class="brand-holder">
                        <a href="{{route('monitoring')}}">
                            <span class="text-lg text-bold text-primary">Мониторинг</span>
                        </a>
                    </div>
                </li>
                <li>
                    <a class="btn btn-icon-toggle menubar-toggle" data-toggle="menubar" href="javascript:void(0);">
                        <i class="fa fa-bars"></i>
                    </a>
                </li>
            </ul>
        </div>
        <!-- Collect the nav links, forms, and other content for toggling -->
        <div class="headerbar-right">

            <div id="mobileCarPanel"></div><!-- Сюда будет подгружаться скрытая панель для мобилки -->

            <ul class="header-nav header-nav-toggle">
                <li>
                    <a class="btn btn-icon-toggle btn-default" href="#offperiod-search" data-toggle="offcanvas" data-backdrop="false">
                        <i class="fa fa-ellipsis-v"></i>
                    </a>
                </li>
                <li>
                    <a class="btn btn-icon-toggle btn-default" href="#offcanvas-search" data-toggle="offcanvas" data-backdrop="false">
                        <i class="fa fa-ellipsis-v"></i>
                    </a>
                </li>
            </ul><!--end .header-nav-toggle -->
        </div><!--end #header-navbar-collapse -->
    </div>
</header>
<!-- END HEADER-->



{{--<!-- BEGIN HEADER-->--}}
{{--<header id="header" >--}}
{{--    <div class="headerbar">--}}
{{--        <!-- Brand and toggle get grouped for better mobile display -->--}}
{{--        <div class="headerbar-left">--}}
{{--            <ul class="header-nav header-nav-options">--}}
{{--                <li class="header-nav-brand" >--}}
{{--                    <div class="brand-holder">--}}
{{--                        <a href="{{route('monitoring')}}">--}}
{{--                            <span class="text-lg text-bold text-primary">Admin panel</span>--}}
{{--                        </a>--}}
{{--                    </div>--}}
{{--                </li>--}}
{{--                <li>--}}
{{--                    <a class="btn btn-icon-toggle menubar-toggle" data-toggle="menubar" href="javascript:void(0);">--}}
{{--                        <i class="fa fa-bars"></i>--}}
{{--                    </a>--}}
{{--                </li>--}}
{{--            </ul>--}}
{{--        </div>--}}
{{--        <!-- Collect the nav links, forms, and other content for toggling -->--}}
{{--        <div class="headerbar-right comp">--}}
{{--            <ul class="header-nav header-nav-profile">--}}
{{--                <li class="dropdown">--}}
{{--                    <a href="javascript:void(0);" class="dropdown-toggle ink-reaction" data-toggle="dropdown">--}}
{{--                        <img src="assets/img/avatar1.jpg?1403934956" alt="" />--}}
{{--                        <span class="profile-info">--}}
{{--									Name name--}}
{{--									<small>Administrator</small>--}}
{{--								</span>--}}
{{--                    </a>--}}
{{--                    <ul class="dropdown-menu animation-dock">--}}
{{--                        <li class="dropdown-header">Настройка</li>--}}
{{--                        <li><a href="#">Профиль</a></li>--}}
{{--                        <li class="divider"></li>--}}
{{--                        <li><a href="#"><i class="fa fa-fw fa-lock"></i> Вход</a></li>--}}
{{--                        <li><a href="{{route('get:logout')}}"><i class="fa fa-fw fa-power-off text-danger"></i> Выход</a></li>--}}
{{--                    </ul><!--end .dropdown-menu -->--}}
{{--                </li><!--end .dropdown -->--}}
{{--            </ul><!--end .header-nav-profile -->--}}
{{--            <ul class="header-nav header-nav-toggle">--}}
{{--                <li>--}}
{{--                    <a class="btn btn-icon-toggle btn-default" href="#offcanvas-search" data-toggle="offcanvas" data-backdrop="false">--}}
{{--                        <i class="fa fa-ellipsis-v"></i>--}}
{{--                    </a>--}}
{{--                </li>--}}
{{--            </ul><!--end .header-nav-toggle -->--}}
{{--        </div><!--end #header-navbar-collapse -->--}}

{{--        <div id="mobileCarPanel"></div>--}}

{{--    </div>--}}
{{--</header>--}}
{{--<!-- END HEADER-->--}}
