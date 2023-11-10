@extends('layouts.app')
@section('title', 'Kontak | '.Str::title($global['setting'][0]->content))
@section('content')
<section class="bg-white py-2">
	<div class="container">
		<div class="ayobaca-title">
			<h1>
				<span>Kontak</span>
			</h1>
		</div>
		<div class="row g-2 mb-2">
			<div class="col-lg-8">
				<div class="bg-white rounded-1 shadow-sm p-2 mb-2">
					<label for="address">
						<i class="bx bx-map-alt"></i>
						{{ $global['contact'][0]->title }}
					</label>
					<address id="address" class="bg-light rounded-1 p-2 mb-0">{{ $global['contact'][0]->content }}</address>
				</div>
				<div class="bg-white rounded-1 shadow-sm p-2 mb-2">
					<label for="email">
						<i class="bx bx-phone"></i>
						Telepon
					</label>
					<div class="row">
						@foreach ($global['contact'][3] as $item)
						<div class="col-lg-4">
							<a href="#" class="contact-block rounded-1 p-2">
								<span>{{ $item->title }}</span>
								{{ $item->content }}
							</a>
						</div>
						@endforeach
					</div>
				</div>
				<div class="bg-white rounded-1 shadow-sm p-2 mb-2">
					<label for="email">
						<i class="bx bxl-whatsapp"></i>
						Whatsapp
					</label>
					<div class="row">
						@foreach ($global['contact'][4] as $item)
						<div class="col-lg-4">
							<a href="#" class="contact-block rounded-1 p-2">
								<span>{{ $item->title }}</span>
								{{ $item->content }}
							</a>
						</div>
						@endforeach
					</div>
				</div>
				<div class="bg-white rounded-1 shadow-sm p-2 mb-2">
					<label for="email">
						<i class="bx bx-envelope"></i>
						Email
					</label>
					<div class="row">
						@foreach ($global['contact'][2] as $item)
						<div class="col-lg-4">
							<a href="#" class="contact-block rounded-1 p-2">
								<span>{{ $item->title }}</span>
								{{ $item->content }}
							</a>
						</div>
						@endforeach
					</div>
				</div>
			</div>
			<div class="col-lg-4">
				<div class="top-stick">
					<div class="ad ad-md" data-url="{{ (!empty($ad_1)) ? $ad_1->url : '#' }}">
						@if (!empty($ad_1))
						<img src="{{ url('storage/'.$ad_1->file) }}" alt="{{ $ad_1->url }}">
						@endif
					</div>
				</div>
			</div>
		</div>
		<div class="row g-2">
			<div class="col-lg-8">
				@if ($global['contact'][1]->content!='no-map')
				<div class="ayobaca-map bg-white rounded-1 shadow-sm">
					{!! $global['contact'][1]->content !!}
					<b>{{ $global['contact'][1]->title }}</b>
				</div>
				@endif
			</div>
			<div class="col-lg-4">
				<div class="ad ad-md" data-url="{{ (!empty($ad_2)) ? $ad_2->url : '#' }}">
					@if (!empty($ad_2))
					<img src="{{ url('storage/'.$ad_2->file) }}" alt="{{ $ad_2->url }}">
					@endif
				</div>
			</div>
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