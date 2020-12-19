<div id="trips" class="col-md-12 col-lg-12 height-6 scroll-sm">
    <div class="list-group list-email list-gray">


        <div class="card">
            <div class="card-head style-primary">
                <header>{{$carTitle}}</header>
            </div>
            <div class="card-body">
                <ul class="nav nav-pills nav-stacked nav-icon">

                    @if(!empty($trips->toArray()))
                    <li>
                        <div class="checkbox checkbox-styled">
                            <label>
                                <input type="checkbox" data-type="all" name="trips" onchange="selectAllTripsCheckbox(this)">
                                <span>Выбрать все</span>
                            </label>
                        </div>
                    </li>
                    <div id="tripsList">
                    @foreach($trips as $trip)
                    <li><div class="checkbox checkbox-styled">
                            <label>
                                <input id="{{$trip->_id}}" type="checkbox" data-type="trip" name="trips" onchange="selectTripCheckbox(this)">
                                <span>{{date_format($trip->created_at, 'd.m.Y H:i')}} - {{$trip->ended_at ? date('d.m.Y H:i', (string)$trip->ended_at/1000) : ''}}
                                    @if(!$trip->ended_at)
                                        <span id="notEndedTripTime">В пути</span>
                                    @endif
                                    <span @if(!$trip->ended_at) id="notEndedTripMileage" @endif>{{round($trip->getMileage(), 3)}}</span> Км.
                                </span>
                            </label>
                        </div>
                    </li>
                        @endforeach
                    </div>
                        @endif

                </ul>
            </div>

            <div class="card-actionbar">
                <div class="card-actionbar-row">
                    <button onclick="removeTrips()"
                        class="btn btn-flat btn-primary ink-reaction">Скрыть
                    </button>
                </div>
            </div>

        </div>

    </div>
</div>
