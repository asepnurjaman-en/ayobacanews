@extends('layouts.app')
@section('title', $info->title.' | '.Str::title($global['setting'][0]->content))
@section('content')
<section class="bg-white py-4">
	<div class="container">
		<div class="p-4">
			<div class="ayobaca-title">
				<h1>
					<span>{{ $info->title }}</span>
				</h1>
			</div>
			<div class="row g-2 flex-row-reverse">
				<div class="col-12 col-lg-5">
					@if ($info->file_type=='image')
					<div class="rounded-sm shadow-sm p-2">
						<img src="{{ url('storage/'.$info->file) }}" class="img-fluid">
					</div>
					@elseif ($info->file_type=='video')
					<div class="iframe rounded-sm shadow-sm p-2">
						<iframe src="https://www.youtube.com/embed/{{ $info->file }}" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen></iframe>
					</div>
					@endif
				</div>
				<div class="col-12 col-lg-7">{!! $info->content !!}</div>
			</div>
		</div>
	</div>
</section>
@endsection

@push('style')
<style>
	.iframe iframe {
		width: 100%;
		margin: 0;
		line-height: 1;
		aspect-ratio: 16/9; 
	}
</style>
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
@endpush