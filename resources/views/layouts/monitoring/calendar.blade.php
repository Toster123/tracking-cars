<li class="tile" id="">
	<a class="tile-content ink-reaction" data-toggle="offperiod" data-backdrop="false">
		<div class="tile-text">

		<ul>
			<li>День</li>
			<li>Неделя</li>
			<li>Месяц</li>
		</ul>

                        <div id="travelPeriod">
                            <div class="list-group list-email list-gray">

                                <form id="travelPeriodForm" action="{{route('car.trips', $car->_id)}}" method="POST" class="form">
                                    @csrf
                                    <div class="card">
                                        <div class="card-head style-primary">
                                            <header>Посмотреть поездки: {{$car->title}}</header>
                                        </div>
                                        <div class="card-body">
                                            <div class="form_group">
                                                 <input id="fromDate" name="fromDate" min="{{date('Y-m-d', strtotime(date('Y-m-d') . ' -3 months'))}}" max="{{date('Y-m-d')}}" type="date" class="col-lg-5">
                                                  <span class="col-lg-2" style="text-align: center"><big>По</big></span>
                                                 <input id="upToDate" name="upToDate" min="{{date('Y-m-d', strtotime(date('Y-m-d') . ' -3 months'))}}" max="{{date('Y-m-d')}}" type="date" class="col-lg-5">
                                            </div>
                                            <div class="form_group">
                                                <input id="fromTime" name="fromTime" type="time" class="col-lg-2">
                                                <span class="col-lg-5" style="text-align: center"></span>
                                                <input id="upToTime" name="upToTime" type="time" class="col-lg-2">
                                            </div>
                                        </div>
                                        <div class="card-actionbar">
                                            <div class="card-actionbar-row checkbox checkbox-styled">
                                                <label>
                                                <input id="displayUpToCurrentTime" name="displayUpToCurrentTime" onchange="selectDisplayUpToCurrentTime(this)" type="checkbox">Отобразить вплоть до нынешнего времени
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

		</div>
	</a>
</li>
