<li class="tile" id="">
	<a class="tile-content ink-reaction" data-toggle="offperiod" data-backdrop="false">
		<div class="tile-text">

		<button onclick="tripsADay();" style="width:100%;margin-bottom:15px;" class="btn btn-raised btn-default-light ink-reaction">День</button>
		<button onclick="tripsAWeek();" style="width:100%;margin-bottom:15px;" class="btn btn-raised btn-default-light ink-reaction">Неделя</button>
		<button onclick="tripsAMonth();" style="width:100%;margin-bottom:15px;" class="btn btn-raised btn-default-light ink-reaction">Месяц</button>

                        <div id="travelPeriod">
                            <div class="list-group list-email list-gray">

                                <form id="travelPeriodForm" action="{{route('car.trips', $car->_id)}}" method="POST" class="form">
                                    @csrf
                                    <div class="card">
                                        <div class="card-head style-primary">
                                            <header>поездки: {{$car->title}}</header>
                                        </div>
                                        <div class="card-body">
                                            <div class="form_group">
                                                 <input id="fromDate" name="fromDate" min="{{date('Y-m-d', strtotime(date('Y-m-d') . ' -3 months'))}}" max="{{date('Y-m-d')}}" type="date" class="form-control col-lg-12">

                                                 <input id="upToDate" name="upToDate" min="{{date('Y-m-d', strtotime(date('Y-m-d') . ' -3 months'))}}" max="{{date('Y-m-d')}}" type="date" class="form-control col-lg-12">
                                            </div>
                                            <div class="form_group">
                                                <input id="fromTime" name="fromTime" type="time" class="form-control col-lg-12">

                                                <input id="upToTime" name="upToTime" type="time" class="form-control col-lg-12">
                                            </div>
                                        </div>
                                        <div class="card-actionbar">
                                            <div class="card-actionbar-row checkbox checkbox-styled">
                                                <label>
                                                    <input id="displayUpToCurrentTime" name="displayUpToCurrentTime" onchange="selectDisplayUpToCurrentTime(this)" type="checkbox"><span>до нынешнего времени</span>
                                                </label>
                                            </div>
                                        </div>
                                        <div class="card-actionbar">
                                            <div class="card-actionbar-row">
                                                <button type="submit"
                                                        class="btn btn-flat btn-primary ink-reaction">Отобразить
                                                </button>
                                            </div>
                                        </div>

                                    </div>
                                </form>
                            </div>
                        </div>

                        <div id="travelResult" class="row">
                        </div>


		</div>
	</a>
</li>
