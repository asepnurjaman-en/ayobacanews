@extends('layouts.app')
@section('title', $category->title." | ".Str::title($global['setting'][0]->content))
@section('content')
<section class="py-2">
	<div class="container">
		{{-- Head News --}}
		<div class="row g-2 mb-2">
			<div class="col-lg-9">
				<div class="row g-2">
					<div class="col-lg-8">
						<div class="ayobaca-title">
							<h1>
								<i class="icon-feed rounded-1 shadow-sm" @style('background-color:'.$category->color)></i>
								<span>{{ $category->title }}</span>
							</h1>
						</div>
						<div class="ad ad-md" data-url="{{ (!empty($ad_1)) ? $ad_1->url : '#' }}">
							@if (!empty($ad_1))
							<img src="{{ url('storage/'.$ad_1->file) }}" alt="{{ $ad_1->url }}">
							@endif
						</div>
						@forelse ($category->articles->take(1) as $item)
						<div class="news-box-lg bg-white overflow-hidden shadow-sm rounded-1">
							@if ($item->file_type=='video')
							<div class="video">
								<iframe src="https://www.youtube-nocookie.com/embed/{{$item->file}}" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen></iframe>
							</div>
							@elseif ($item->file_type=='image')
							<div class="image skeleton">
								<img src="{{ url('storage/'.$item->file) }}" alt="{{ $item->file_source }}" class="img-fluid rounded-top d-none" loading="lazy">
							</div>
							@endif
							<div class="desc p-3">
								<h2>
									<a href="{{ route('l.news', $item->slug) }}">{{ $item->title }}</a>
								</h2>
								<div class="d-flex align-items-center py-1 small text-muted">
									{!! ($item->file_type=='video') ? '<i class="bx bxl-youtube fs-5 me-2"></i>' : null !!}
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
						<div class="ad ad-md" data-url="{{ (!empty($ad_3)) ? $ad_3->url : '#' }}">
							@if (!empty($ad_3))
							<img src="{{ url('storage/'.$ad_3->file) }}" alt="{{ $ad_3->url }}">
							@endif
						</div>
					</div>
					<div class="col-lg-4">
						@include('layouts.component', ['type'=>'trending'])
					</div>
				</div>
			</div>
			<div class="col-lg-3">
				<div class="ad ad-md top-stick" data-url="{{ (!empty($ad_2)) ? $ad_2->url : '#' }}">
					@if (!empty($ad_2))
					<img src="{{ url('storage/'.$ad_2->file) }}" alt="{{ $ad_2->url }}">
					@endif
				</div>
			</div>
		</div>
		{{-- \. Head News --}}
		{{-- All News --}}
		<div class="row g-2 mb-2">
			<div class="col-lg-8">
				<div class="load-news bg-white shadow-sm rounded-1">
					{{-- @forelse ($news as $item)
					<div class="news-list-md p-3">
						<div class="d-flex">
							<div class="image me-2 skeleton">
								<img src="{{ ($item->file_type=='video') ? "https://img.youtube.com/vi/{$item->file}/hqdefault.jpg" : url('storage/md/'.$item->file) }}" alt="{{ $item->file_source }}" class="img-fluid rounded-top d-none" loading="lazy">
							</div>
							<div class="desc">
								<h3>
									<a href="{{ route('l.news', $item->slug) }}">{{ $item->title }}</a>
								</h3>
								<div class="d-flex align-items-center py-1 small text-muted">
									{!! ($item->file_type=='video') ? '<i class="bx bxl-youtube fs-5 me-2"></i>' : null !!}
									<span class="me-2">{{ date_stat($item->datetime) }}</span>
									<span>{{ $item->author }}</span>
								</div>
								<blockquote>{{ $item->description }}</blockquote>
							</div>
						</div>
					</div>
					@empty
					<div class="empty">Segera rilis</div>
					@endforelse --}}
				</div>
			</div>
			<div class="col-lg-4">
				<div class="ad ad-md" data-url="{{ (!empty($ad_4)) ? $ad_4->url : '#' }}">
					@if (!empty($ad_4))
					<img src="{{ url('storage/'.$ad_4->file) }}" alt="{{ $ad_4->url }}">
					@endif
				</div>
				@include('layouts.component', ['type'=>'highlight'])
			</div>
		</div>
		{{-- \. All News --}}
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
<meta property="og:title" content="{{ $category->title }}">
<meta property="og:description" content="{{ strip_tags($global['setting'][4]->content) }}">
<meta property="og:image" content="{{ url('storage/sm/'.$global['setting'][2]->content) }}">
<!-- Twitter -->
<meta property="twitter:card" content="summary_large_image">
<meta property="twitter:url" content="{{ request()->fullUrl() }}">
<meta property="twitter:title" content="{{ $category->title }}">
<meta property="twitter:description" content="{{ strip_tags($global['setting'][4]->content) }}">
<meta property="twitter:image" content="{{ url('storage/sm/'.$global['setting'][2]->content) }}">
@endpush

@push('script')
<script>
	var page = 1;
	load_news(page);
	$(window).scroll(function() {
		if (($(window).scrollTop() + $(window).height() + 320) >= $(document).height()) {
			page++;
			load_news(page);
		}
		// console.log(`${$(window).scrollTop() + $(window).height() + 320} >= ${$(document).height()}`);
	});
	function load_news(page) {
		$.ajax({
			url : `{{ route('l.category.load', $category->id) }}`,
			type: 'get',
			data: {
				page: page
			},
			dataType: 'html',
		}).done(function(response) {
			$(".load-news").append(response);
		}).fail(function(q,w,e) {
			console.log(q.responseText);
		});
	}
</script>
@endpush