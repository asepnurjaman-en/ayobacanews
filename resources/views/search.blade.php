@extends('layouts.app')
@section('title', "Cari | ".Str::title($global['setting'][0]->content))
@php
    $needle = request()->get('q') ?? '';
@endphp
@section('content')
<section class="py-2">
	<div class="container">
		{{-- Search News --}}
		<div class="row g-2 mb-2">
            <div class="col-lg-8">
                <div class="ayobaca-title">
                    <h1>
                        <i class="icon-feed bg-secondary rounded-1 shadow-sm"></i>
                        <span>Cari</span>
                    </h1>
                </div>
                <div class="bg-white shadow-sm rounded-1">
                    @forelse ($news as $item)
                    <div class="news-list-md p-3">
                        <div class="d-flex">
                            <div class="image me-2">
                                <img src="{{ ($item->file_type=='video') ? "https://img.youtube.com/vi/{$item->file}/hqdefault.jpg" : url('storage/md/'.$item->file) }}" alt="{{ $item->file_source }}" class="img-fluid rounded-top">
                            </div>
                            <div class="desc">
                                <h3>
                                    <a href="{{ route('l.news', $item->slug) }}">{!! highlight($needle, $item->title) !!}</a>
                                </h3>
                                <div class="d-flex align-items-center py-1 small text-muted">
                                    {!! ($item->file_type=='video') ? '<i class="bx bxl-youtube fs-5 me-2"></i>' : null !!}
                                    <span class="me-2">{{ date_stat($item->datetime) }}</span>
                                    <span>{{ $item->author }}</span>
                                </div>
                                <blockquote>{{ $item->description }}</blockquote>
                                @if (count(json_decode($item->tags))>0)
                                <div class="tag my-1">
                                    @foreach (json_decode($item->tags) as $tag)
                                    <a href="{{ route('l.tag', $tag) }}" class="text-primary fw-bold mb-1">#{{ $tag }}</a>
                                    @endforeach
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="empty">Segera rilis</div>
                    @endforelse
                </div>
            </div>
            <div class="col-lg-4">
                @include('layouts.component', ['type'=>'trending'])
            </div>
        </div>
		{{-- \. Search News --}}
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
<meta property="og:title" content="{{ $needle }}">
<meta property="og:description" content="{{ strip_tags($global['setting'][4]->content) }}">
<meta property="og:image" content="{{ url('storage/sm/'.$global['setting'][2]->content) }}">
<!-- Twitter -->
<meta property="twitter:card" content="summary_large_image">
<meta property="twitter:url" content="{{ request()->fullUrl() }}">
<meta property="twitter:title" content="{{ $needle }}">
<meta property="twitter:description" content="{{ strip_tags($global['setting'][4]->content) }}">
<meta property="twitter:image" content="{{ url('storage/sm/'.$global['setting'][2]->content) }}">
@endpush

@push('script')
@endpush