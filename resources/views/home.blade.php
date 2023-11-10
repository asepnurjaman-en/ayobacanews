@extends('layouts.app')
@section('title', Str::title($global['setting'][0]->content))
@section('content')
<section class="py-2">
	<div class="container">
		{{-- Head News --}}
		<div class="row g-2 mb-2">
			<div class="col-lg-9">
				<div class="row flex-lg-row-reverse g-2">
					<div class="col-lg-8">
						@if (!empty($category[0]))							
						@forelse ($category[0]->articles->take(1) as $item)
						<div class="news-box-lg bg-white overflow-hidden shadow-sm rounded-1">
							<div class="image skeleton">
								<img src="{{ ($item->file_type=='video') ? "https://img.youtube.com/vi/{$item->file}/hqdefault.jpg" : url('storage/'.$item->file) }}" alt="{{ $item->file_source }}" class="img-fluid rounded-top d-none" loading="lazy">
								<label class="ayobaca-label rounded-1">
									<i @style('background-color:'.$item->article_category->color)></i>
									<span>{{ $item->article_category->title }}</span>
								</label>
							</div>
							<div class="desc p-3">
								<h2>
									<a href="{{ route('l.news', $item->slug) }}">{{ $item->title }}</a>
								</h2>
								<div class="py-1 small text-muted">
									<span class="me-2">{{ date_stat($item->datetime) }}</span>
									<span>{{ $item->author }}</span>
								</div>
								<blockquote>{{ $item->description }}</blockquote>
								<div class="button">
									<a href="{{ route('l.news', $item->slug) }}">
										<span>baca selengkapnya</span>
										<i class="icon-arrow-down"></i>
									</a>
								</div>
							</div>
						</div>
						@empty
						<div class="empty">Segera rilis</div>
						@endforelse
						@endif
						<div class="top-stick">
							<div class="ad ad-md" data-url="{{ (!empty($ad_1)) ? $ad_1->url : '#' }}">
								@if (!empty($ad_1))
								<img src="{{ url('storage/'.$ad_1->file) }}" alt="{{ $ad_1->url }}">
								@endif
							</div>
						</div>
					</div>
					<div class="col-lg-4">
						@include('layouts.component', ['type'=>'trending'])
					</div>
				</div>
			</div>
			<div class="col-lg-3">
				<div class="weather-info text-white d-flex align-items-center rounded shadow-sm p-3 mb-2">
					<div class="weather-info-animation">
						<canvas id="animated-icon" width="90" height="90"></canvas>
					</div>
					<div class="weather-info-text px-3">
						<small>Cuaca hari ini di </small>
						<span class="location fs-5"></span>
						<div class="temperature fs-4 fw-bolder">
							<span class="temp-celsius">°C</span>
						</div>
					</div>
				</div>
				<div class="ad ad-md" data-url="{{ (!empty($ad_2)) ? $ad_2->url : '#' }}">
					@if (!empty($ad_2))
					<img src="{{ url('storage/'.$ad_2->file) }}" alt="{{ $ad_2->url }}">
					@endif
				</div>
				@include('layouts.component', ['type'=>'highlight'])
			</div>
		</div>
		{{-- \. Head News --}}
		<div class="row g-2">
			{{-- Left News --}}
			<div class="col-lg-3">
				@if (!empty($category[1]))
				@forelse ($category[1]->articles->take(1) as $item)
				<div class="news-box-md bg-white shadow-sm rounded-sm mb-2">
					<div class="image skeleton">
						<img src="{{ ($item->file_type=='video') ? "https://img.youtube.com/vi/{$item->file}/hqdefault.jpg" : url('storage/sm/'.$item->file) }}" alt="{{ $item->file_source }}" class="img-fluid rounded-top d-none" loading="lazy">
						<label class="ayobaca-label rounded-1">
							<i @style('background-color:'.$item->article_category->color)></i>
							<span>{{ $item->article_category->title }}</span>
						</label>
					</div>
					<div class="desc p-2">
						<h3>
							<a href="{{ route('l.news', $item->slug) }}">{{ $item->title }}</a>
						</h3>
					</div>
					<div class="d-flex justify-content-between p-2 small text-muted">
						<span>{{ date_stat($item->datetime) }}</span>
						<span>{{ $item->author }}</span>
					</div>
				</div>
				@empty
				<div class="empty">Segera rilis</div>
				@endforelse
				@endif
				<div class="ad ad-md" data-url="{{ (!empty($ad_3)) ? $ad_3->url : '#' }}">
					@if (!empty($ad_3))
					<img src="{{ url('storage/'.$ad_3->file) }}" alt="{{ $ad_3->url }}">
					@endif
				</div>
				<div class="top-stick">
					@if (!empty($category[1]))
					<div class="bg-white shadow-sm rounded-1 mb-2">
						@forelse ($category[1]->articles->skip(1)->take(5) as $item)
						<div class="news-box-sm p-2 lh-sm border-bottom">
							<a href="{{ route('l.news', $item->slug) }}">{{ $item->title }}</a>
							<div class="d-flex justify-content-between pt-2 small text-muted">
								<div>
									<span class="fw-bold">{{ $item->article_category->title }} &dash;</span>
									<span>{{ date_stat($item->datetime) }}</span>
								</div>
								<span>{{ $item->author }}</span>
							</div>
						</div>
						@empty
						<div class="empty">Segera rilis</div>
						@endforelse
					</div>
					<a href="{{ route('l.category', $category[1]->slug) }}" class="d-flex align-items-center justify-content-between text-decoration-none redBaca text-white rounded-1 shadow-sm px-2 py-1">
						<span>Lihat semua</span>
						<i class="icon-arrow-right"></i>
					</a>
					@endif
				</div>
			</div>
			{{-- \. Left News --}}
			{{-- Center News --}}
			<div class="col-lg-3">
				@if (!empty($category[2]))
				@forelse ($category[2]->articles->take(1) as $item)
				<div class="news-box-md bg-white shadow-sm rounded-sm mb-2 mb-lg-0">
					<div class="image skeleton">
						<img src="{{ ($item->file_type=='video') ? "https://img.youtube.com/vi/{$item->file}/hqdefault.jpg" : url('storage/sm/'.$item->file) }}" alt="{{ $item->file_source }}" class="img-fluid rounded-top d-none" loading="lazy">
						<label class="ayobaca-label rounded-1">
							<i @style('background-color:'.$item->article_category->color)></i>
							<span>{{ $item->article_category->title }}</span>
						</label>
					</div>
					<div class="desc p-2">
						<h3>
							<a href="{{ route('l.news', $item->slug) }}">{{ $item->title }}</a>
						</h3>
					</div>
					<div class="d-flex justify-content-between p-2 small text-muted">
						<span>{{ date_stat($item->datetime) }}</span>
						<span>{{ $item->author }}</span>
					</div>
				</div>
				@empty
				<div class="empty">Segera rilis</div>
				@endforelse
				<div class="ad ad-md" data-url="{{ (!empty($ad_4)) ? $ad_4->url : '#' }}">
					@if (!empty($ad_4))
					<img src="{{ url('storage/'.$ad_4->file) }}" alt="{{ $ad_4->url }}">
					@endif
				</div>
				@endif
				<div class="top-stick">
					@if (!empty($category[2]))
					<div class="bg-white shadow-sm rounded-1 mb-2 mb-lg-0">
						@forelse ($category[2]->articles->skip(1)->take(5) as $item)
						<div class="news-box-sm p-2 lh-sm border-bottom">
							<a href="{{ route('l.news', $item->slug) }}">{{ $item->title }}</a>
							<div class="d-flex justify-content-between pt-2 small text-muted">
								<div>
									<span class="fw-bold">{{ $item->article_category->title }} &dash;</span>
									<span>{{ date_stat($item->datetime) }}</span>
								</div>
								<span>{{ $item->author }}</span>
							</div>
						</div>
						@empty
						<div class="empty">Segera rilis</div>
						@endforelse
					</div>
					<a href="{{ route('l.category', $category[2]->slug) }}" class="d-flex align-items-center justify-content-between text-decoration-none redBaca text-white rounded-1 shadow-sm px-2 py-1">
						<span>Lihat semua</span>
						<i class="icon-arrow-right"></i>
					</a>
					@endif
				</div>
			</div>
			<div class="col-lg-3">
				@if (!empty($category[3]))
				@forelse ($category[3]->articles->take(1) as $item)
				<div class="news-box-md bg-white shadow-sm rounded-sm mb-2">
					<div class="image skeleton">
						<img src="{{ ($item->file_type=='video') ? "https://img.youtube.com/vi/{$item->file}/hqdefault.jpg" : url('storage/sm/'.$item->file) }}" alt="{{ $item->file_source }}" class="img-fluid rounded-top d-none" loading="lazy">
						<label class="ayobaca-label rounded-1">
							<i @style('background-color:'.$item->article_category->color)></i>
							<span>{{ $item->article_category->title }}</span>
						</label>
					</div>
					<div class="desc p-2">
						<h3>
							<a href="{{ route('l.news', $item->slug) }}">{{ $item->title }}</a>
						</h3>
					</div>
					<div class="d-flex justify-content-between p-2 small text-muted">
						<span>{{ date_stat($item->datetime) }}</span>
						<span>{{ $item->author }}</span>
					</div>
				</div>
				@empty
				<div class="empty">Segera rilis</div>
				@endforelse
				@endif
				<div class="top-stick">
					@if (!empty($category[3]))
					<div class="bg-white shadow-sm rounded-1 mb-2">
						@forelse ($category[3]->articles->skip(1)->take(5) as $item)
						<div class="news-box-sm p-2 lh-sm border-bottom">
							<a href="{{ route('l.news', $item->slug) }}">{{ $item->title }}</a>
							<div class="d-flex justify-content-between pt-2 small text-muted">
								<div>
									<span class="fw-bold">{{ $item->article_category->title }} &dash;</span>
									<span>{{ date_stat($item->datetime) }}</span>
								</div>
								<span>{{ $item->author }}</span>
							</div>
						</div>
						@empty
						<div class="empty">Segera rilis</div>
						@endforelse
					</div>
					<a href="{{ route('l.category', $category[3]->slug) }}" class="d-flex align-items-center justify-content-between text-decoration-none redBaca text-white rounded-1 shadow-sm px-2 py-1">
						<span>Lihat semua</span>
						<i class="icon-arrow-right"></i>
					</a>
					@endif
				</div>
			</div>
			{{-- \. Center News --}}
			{{-- Right News --}}
			<div class="col-lg-3">
				<div class="ad ad-md" data-url="{{ (!empty($ad_5)) ? $ad_5->url : '#' }}">
					@if (!empty($ad_5))
					<img src="{{ url('storage/'.$ad_5->file) }}" alt="{{ $ad_5->url }}">
					@endif
				</div>
				<div>
					@if (!empty($category[4]))
					@forelse ($category[4]->articles->take(1) as $item)
					<div class="news-box-md bg-white shadow-sm rounded-sm mb-2">
						<div class="desc p-2">
							<h3>
								<a href="{{ route('l.news', $item->slug) }}">{{ $item->title }}</a>
							</h3>
						</div>
						<div class="d-flex justify-content-between px-2 small text-muted">
							<span>{{ date_stat($item->datetime) }}</span>
							<span>{{ $item->author }}</span>
						</div>
						<div class="image skeleton">
							<img src="{{ ($item->file_type=='video') ? "https://img.youtube.com/vi/{$item->file}/hqdefault.jpg" : url('storage/sm/'.$item->file) }}" alt="{{ $item->file_source }}" class="img-fluid d-none" loading="lazy">
							<label class="ayobaca-label rounded-1">
								<i @style('background-color:'.$item->article_category->color)></i>
								<span>{{ $item->article_category->title }}</span>
							</label>
						</div>
						<div class="desc p-2">
							<blockquote>{{ $item->description }}</blockquote>
						</div>
					</div>
					@empty
					<div class="empty">Segera rilis</div>
					@endforelse
					<a href="{{ route('l.category', $category[4]->slug) }}" class="d-flex align-items-center justify-content-between text-decoration-none redBaca text-white rounded-1 shadow-sm px-2 py-1">
						<span>Lihat semua</span>
						<i class="icon-arrow-right"></i>
					</a>
					@endif
				</div>
				<div class="ad ad-md" data-url="{{ (!empty($ad_6)) ? $ad_6->url : '#' }}">
					@if (!empty($ad_6))
					<img src="{{ url('storage/'.$ad_6->file) }}" alt="{{ $ad_6->url }}">
					@endif
				</div>
				<div class="top-stick">
					@if (!empty($category[4]))
					@forelse ($category[4]->articles->take(1) as $item)
					<div class="news-box-md bg-white shadow-sm rounded-sm mb-2">
						<div class="desc p-2">
							<h3>
								<a href="{{ route('l.news', $item->slug) }}">{{ $item->title }}</a>
							</h3>
						</div>
						<div class="d-flex justify-content-between px-2 small text-muted">
							<span>{{ date_stat($item->datetime) }}</span>
							<span>{{ $item->author }}</span>
						</div>
						<div class="image skeleton">
							<img src="{{ ($item->file_type=='video') ? "https://img.youtube.com/vi/{$item->file}/hqdefault.jpg" : url('storage/sm/'.$item->file) }}" alt="{{ $item->file_source }}" class="img-fluid d-none" loading="lazy">
							<label class="ayobaca-label rounded-1">
								<i @style('background-color:'.$item->article_category->color)></i>
								<span>{{ $item->article_category->title }}</span>
							</label>
						</div>
						<div class="desc p-2">
							<blockquote>{{ $item->description }}</blockquote>
						</div>
					</div>
					@empty
					<div class="empty">Segera rilis</div>
					@endforelse
					<a href="{{ route('l.category', $category[4]->slug) }}" class="d-flex align-items-center justify-content-between text-decoration-none redBaca text-white rounded-1 shadow-sm px-2 py-1">
						<span>Lihat semua</span>
						<i class="icon-arrow-right"></i>
					</a>
					@endif
				</div>
			</div>
			{{-- \. Right News --}}
		</div>
	</div>
</section>
@endsection

@push('style')
@endpush

@push('running-text')
@include('layouts.component', ['type'=>'running-text'])
@endpush

@push('meta')
<meta name="description" content="{{ strip_tags($global['setting'][4]->content) }}">
<meta property="og:type" content="article">
<meta property="og:url" content="{{ request()->fullUrl() }}">
<meta property="og:title" content="{{ $global['setting'][0]->content }}">
<meta property="og:description" content="{{ strip_tags($global['setting'][4]->content) }}">
<meta property="og:image" content="{{ url('storage/sm/'.$global['setting'][2]->content) }}">
<!-- Twitter -->
<meta property="twitter:card" content="summary_large_image">
<meta property="twitter:url" content="{{ request()->fullUrl() }}">
<meta property="twitter:title" content="{{ $global['setting'][0]->content }}">
<meta property="twitter:description" content="{{ strip_tags($global['setting'][4]->content) }}">
<meta property="twitter:image" content="{{ url('storage/sm/'.$global['setting'][2]->content) }}">
@endpush

@push('script')
<script src="https://rawgit.com/darkskyapp/skycons/master/skycons.js"></script>
<script>
	var weatherBG = $(".weather-info");
	// inspired by the Design and code by Ayo Isaiah
	//Skycons

	let skycons = new Skycons({color: "#ffffff"});
		skycons.add("animated-icon", Skycons.CLEAR_DAY);
		skycons.play();
	weatherBG.css('background-image', "url('{{ url('img/weather/CLEAR_DAY.jpg') }}')");

	//Some Global variables
	var longitude, latitude, timeHour, timeFull;
	//Function to update weather information

	function updateWeather(json) {
		longitude = json.coord.lon;
		latitude = json.coord.lat;
		//AJAX request
		let geoJSON = $.getJSON("https://secure.geonames.org/timezoneJSON?lat=" + latitude + "&lng=" + longitude + "&username=ayoisaiah").then(function(timezone) {
			var rawTimeZone = JSON.stringify(timezone);
			var parsedTimeZone = JSON.parse(rawTimeZone);
			var dateTime = parsedTimeZone.time;
			timeFull = dateTime.substr(11);
			$(".local-time").html(timeFull); //Update local time
			timeHour = dateTime.substr(-5, 2);
			//Update Weather parameters and location
			$(".weather-condition").html(json.weather[0].description);
			var temp = [
				(json.main.temp - 273.15).toFixed(0) + "°C",
				(1.8 * (json.main.temp - 273.15) + 32).toFixed(0) + "°F"
			];
			$(".temp-celsius").html(temp[0]);
			$(".location").html(json.name);

			//Update Weather animation based on the returned weather description

			var weather = json.weather[0].description;
			if (weather.indexOf("rain") >= 0) {
				skycons.set("animated-icon", Skycons.RAIN);
				weatherBG.css('background-image', "url('{{ url('img/weather/RAIN.jpg') }}')");
			} else if (weather.indexOf("sunny") >= 0) {
				skycons.set("animated-icon", Skycons.CLEAR_DAY);
				weatherBG.css('background-image', "url('{{ url('img/weather/CLEAR_DAY.jpg') }}')");
			} else if (weather.indexOf("clear") >= 0) {
				if (timeHour >= 7 && timeHour < 20) {
					skycons.set("animated-icon", Skycons.CLEAR_DAY);
					weatherBG.css('background-image', "url('{{ url('img/weather/CLEAR_DAY.jpg') }}')");
				} else {
					skycons.set("animated-icon", Skycons.CLEAR_NIGHT);
					weatherBG.css('background-image', "url('{{ url('img/weather/CLEAR_NIGHT.jpg') }}')");
				}
			} else if (weather.indexOf("cloud") >= 0) {
				if (timeHour >= 7 && timeHour < 20) {
					skycons.set("animated-icon", Skycons.PARTLY_CLOUDY_DAY);
					weatherBG.css('background-image', "url('{{ url('img/weather/PARTLY_CLOUDY_DAY.jpg') }}')");
				} else {
					skycons.set("animated-icon", Skycons.PARTLY_CLOUDY_NIGHT);
					weatherBG.css('background-image', "url('{{ url('img/weather/PARTLY_CLOUDY_NIGHT.jpg') }}')");
				}
			} else if (weather.indexOf("thunderstorm") >= 0) {
				skycons.set("animated-icon", Skycons.SLEET);
					weatherBG.css('background-image', "url('{{ url('img/weather/SLEET.jpg') }}')");
			} else if (weather.indexOf("snow") >= 0) {
				skycons.set("animated-icon", Skycons.SNOW);
					weatherBG.css('background-image', "url('{{ url('img/weather/SNOW.jpg') }}')");
			}
		});
	}
	if (navigator.geolocation) {
		window.onload = function() {
			var currentPosition;
			function getCurrentLocation(position) {
				currentPosition = position;
				latitude = currentPosition.coords.latitude;
				longitude = currentPosition.coords.longitude;

				$.getJSON(
					"https://api.openweathermap.org/data/2.5/weather?lat=" + latitude + "&lon=" + longitude + "&APPID=4e5a45260e98e770e7377f516bc22d75",
					function(data) {
						var rawJson = JSON.stringify(data);
						var json = JSON.parse(rawJson);
						updateWeather(json); //Update Weather parameters
					}
				);
			}
			navigator.geolocation.getCurrentPosition(getCurrentLocation);
		};
	} else {
		alert("Geolocation is not supported by your browser, download the latest Chrome or Firefox to use this app");
	}
</script>
@endpush