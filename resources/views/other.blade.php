@extends('layouts.app')
@section('title', $info->title.' | '.Str::title($global['setting'][0]->content))
@section('content')
<section class="py-4">
	<div class="container">
		<div class="bg-white rounded-sm p-4">
			<div class="ayobaca-title">
				<h1>
					<span>{{ $info->title }}</span>
				</h1>
			</div>
			{!! $info->content !!}
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
@endpush