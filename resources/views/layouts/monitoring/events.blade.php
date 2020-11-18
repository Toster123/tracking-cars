@foreach($events as $event) 
    <li class="tile" id="@if($event->isLast)last @else{{$event->_id}}@endif">
        <a class="tile-content ink-reaction" onclick="selectEvent(this)" data-lat="{{$event->lat}}" data-lng="{{$event->lng}}" data-toggle="offcanvas" data-backdrop="false">
            <div class="tile-text">
                @switch($event->type)
                    @case(0)
                    {{$event->title}}
                    <small>подключен ({{date_format($event->created_at, 'd.m.Y H:i')}})</small>
                    @break
                    @case(1)
                    {{$event->title}}
                    <small>отключен или прервана связь ({{date_format($event->created_at, 'd.m.Y H:i')}})</small>
                    @break
                    @case(2)
                    {{$event->title}}
                    <small>вход ({{date_format($event->created_at, 'd.m.Y H:i')}})</small>
                    @break
                    @case(3)
                    {{$event->title}}
                    <small>выход ({{date_format($event->created_at, 'd.m.Y H:i')}})</small>
                    @break
                    @case(4)
                    {{$event->title}}
                    <small>зажигание включено ({{date_format($event->created_at, 'd.m.Y H:i')}})</small>
                    @break
                    @case(5)
                    {{$event->title}}
                    <small>зажигание выключено ({{date_format($event->created_at, 'd.m.Y H:i')}})</small>
                    @break
                    @case(6)
                    {{$event->title}}
                    <small>начал движение ({{date_format($event->created_at, 'd.m.Y H:i')}})</small>
                    @break
                    @case(7) 
                    {{$event->title}}
                    <small>остановился ({{date_format($event->created_at, 'd.m.Y H:i')}})</small>
                    @break
                @endswitch
            </div>
        </a>
    </li>
@endforeach
