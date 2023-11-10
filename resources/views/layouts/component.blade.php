@if ($type=='running-text')
<div class="top-pick">
	<div class="label">Top picks</div>
	<div class="running-text">
		<marquee direction="" onmouseover="this.stop();" onmouseout="this.start();">
			@foreach ($running as $item)
			<a href="{{ route('l.news', $item->article->slug) }}" class="running-news">{{ $item->article->title }}</a>
			@endforeach
		</marquee>
	</div>
</div>
@elseif ($type=='highlight')
<div class="bg-white shadow-sm rounded-1 top-stick">
	<div class="p-2 border-bottom">
		<h4 class="mb-0">Highlight</h4>
	</div>
	@forelse ($highlight as $item)
	<div class="news-box-sm p-2 lh-sm border-bottom">
		<div class="d-flex align-items-center">
			<div class="image">
				<img src="{{ ($item->article->file_type=='video') ? "https://img.youtube.com/vi/{$item->article->file}/hqdefault.jpg" : url('storage/md/'.$item->article->file) }}" alt="{{ $item->article->file_source }}" class="rounded-sm shadow-sm img-fluid">
			</div>
			<div class="desc">
				<a href="{{ route('l.news', $item->article->slug) }}">{{ $item->article->title }}</a>
			</div>
		</div>
		<div class="d-flex justify-content-between pt-2 small text-muted">
			<div class="d-flex align-items-center">
				{!! ($item->article->file_type=='video') ? '<i class="bx bxl-youtube fs-5 me-2"></i>' : null !!}
				<span class="fw-bold me-1">{{ $item->article->article_category->title }} &dash;</span>
				<span>{{ date_stat($item->article->datetime) }}</span>
			</div>
			<span>{{ $item->article->author }}</span>
		</div>
	</div>
	@empty
	<div class="empty">Segera rilis</div>
	@endforelse
</div>
@elseif ($type=='trending')
<div class="bg-white shadow-sm rounded-1 top-stick mb-2">
	<div class="p-2 border-bottom">
		<h4 class="mb-0"><b>Top</b> Trending</h4>
	</div>
	@forelse ($trending as $item)
	<div class="news-box-sm p-2 lh-sm border-bottom">
		<a href="{{ route('l.news', $item->slug) }}">{{ $item->title }}</a>
		<div class="d-flex justify-content-between pt-2 small text-muted">
			<div class="d-flex align-items-center">
				{!! ($item->file_type=='video') ? '<i class="bx bxl-youtube fs-5 me-2"></i>' : null !!}
				<span class="fw-bold me-1">{{ $item->article_category->title }} &dash; </span>
				<span>{{ date_stat($item->datetime) }}</span>
			</div>
			<span>{{ $item->author }}</span>
		</div>
	</div>
	@empty
	<div class="empty">Segera rilis</div>
	@endforelse
</div>
@endif