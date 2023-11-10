@extends('layouts.app')
@section('title', $read->title." | ".Str::title($global['setting'][0]->content))
@php
	use Carbon\Carbon;
	$read_content = explode('<!-- pagebreak -->', $read->content);
@endphp
@section('content')
<section class="bg-white py-2">
	<div class="container">
		<div class="row g-2">
			<div class="col-lg-8">
				<div class="ad ad-md" data-url="{{ (!empty($ad_1)) ? $ad_1->url : '#' }}">
					@if (!empty($ad_1))
					<img src="{{ url('storage/'.$ad_1->file) }}" alt="{{ $ad_1->url }}">
					@endif
				</div>
				<div class="news-detail bg-white mb-2">
					<div class="title py-3">
						<h1>{{ $read->title }}</h1>
						<div class="border-top py-2">
							<span>{{ Carbon::parse($read->date)->isoFormat('dddd, DD MMMM Y | HH:mm'); }}</span>
							&mdash;
							<label class="ayobaca-label border rounded-1">
								<i @style('background-color:'.$read->article_category->color)></i>
								<span>{{ $read->article_category->title }}</span>
							</label>
						</div>
					</div>
					@if ($read->file_type=='video')
					<div class="video">
						<iframe src="https://www.youtube-nocookie.com/embed/{{$read->file}}?autoplay=1" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen></iframe>
					</div>
					@elseif ($read->file_type=='image')
					<div class="image">
						<img src="{{ url('storage/'.$read->file) }}" alt="{{ $read->title }}" class="img-fluid">
					</div>
					@endif
					<div class="desc py-3">
						<div class="text-muted text-center small">{{ $read->file_source }}</div>
						<div class="text-center py-2">
							<span><b class="fw-normal text-muted">Penulis:</b> {{ $read->author }}</span> |
							<span><b class="fw-normal text-muted">Editor:</b> {{ $read->user->name }}</span>
						</div>
						<div class="has-dropcap">
							@foreach ($read_content as $key => $content)
							<div id="news-section{{ $key }}" @class(['news-section mb-2', 'd-none' => ($key!=0)])>
								{!! $content !!}
							</div>
							@endforeach
						</div>
					</div>
					@if (count($read_content)>1)
					<div class="paging rounded-4 mb-2">
						<label class="fw-normal fs-5 me-2">Halaman</label>
						@for ($i = 0; $i < count($read_content); $i++)
						<a href="#news-section{{ $i }}" @class(['rounded-2', 'active' => ($i==0)])>{{ $i+1 }}</a>
						@endfor
					</div>
					@endif
					<div class="d-flex py-3">
						<label class="fw-normal fs-5 me-2">Bagikan</label>
						<!-- AddToAny BEGIN -->
						<div class="a2a_kit a2a_kit_size_32 a2a_default_style">
							{{-- <a class="a2a_dd" href="https://www.addtoany.com/share"></a> --}}
							<a class="a2a_button_facebook"></a>
							<a class="a2a_button_line"></a>
							<a class="a2a_button_telegram"></a>
							<a class="a2a_button_twitter"></a>
							<a class="a2a_button_whatsapp"></a>
							<a class="a2a_button_copy_link"></a>
						</div>
						<script async src="https://static.addtoany.com/menu/page.js"></script>
						<!-- AddToAny END -->
					</div>
					@if (count(json_decode($read->tags))>0)
					<div class="tag">
						<label class="fw-normal fs-5 me-2">Tag</label>
						@foreach (json_decode($read->tags) as $tag)
						<a href="{{ route('l.tag', $tag) }}" class="text-primary fw-bold border rounded mb-1">#{{ $tag }}</a>
						@endforeach
					</div>
					@endif
				</div>
			</div>
			<div class="col-lg-4">
				<div class="ad ad-md" data-url="{{ (!empty($ad_2)) ? $ad_2->url : '#' }}">
					@if (!empty($ad_2))
					<img src="{{ url('storage/'.$ad_2->file) }}" alt="{{ $ad_2->url }}">
					@endif
				</div>
				@include('layouts.component', ['type'=>'trending'])
			</div>
		</div>
		<div class="row g-2">
			<div class="col-lg-8">
				@if (count($related)>0)
				<div class="mb-3">
					<h3 class="fw-bolder mb-2">Berita terkait</h3>
					<div class="row">
						@forelse ($related as $item)
						<div class="col-lg-6">
							<div class="news-box-sm border-bottom">
								<div class="desc py-2">
									<a href="{{ route('l.news', $item->slug) }}">{{ $item->title }}</a>
								</div>
							</div>
						</div>
						@empty
							
						@endforelse
					</div>
				</div>
				@endif
				<div class="ad ad-md" data-url="{{ (!empty($ad_3)) ? $ad_3->url : '#' }}">
					@if (!empty($ad_3))
					<img src="{{ url('storage/'.$ad_3->file) }}" alt="{{ $ad_3->url }}">
					@endif
				</div>
			</div>
			<div class="col-lg-4">
				@include('layouts.component', ['type'=>'highlight'])
			</div>
		</div>
		<div class="row g-2">
			<div class="col-lg-8">
				<div class="news-comment rounded-4 mt-3 p-4">
					<div class="d-flex justify-content-between mb-2">
						<h4 class="d-flex align-items-center">
							<i class="icon-speech me-2"></i>
							<span>Komentar</span>
						</h4>
						@if (Auth::check() && Auth::user()->role=='reader-2')
						<span class="fw-bold">{{ Auth::user()->name }}</span>
						@else
						<button type="button" class="bg-dark text-white rounded small border-0 px-3" data-bs-toggle="modal" data-bs-target="#login">Masuk</button>
						@endif
					</div>
					<div class="bg-white rounded shadow-sm">
						<form action="{{ route('l.news-comment', $read->slug) }}" class="send-comment" method="post">
							@csrf
							<textarea name="comment" id="comment" class="rounded-4 counting-input" placeholder="Tulis komentar" maxlength="500" rows="3">{{ old('comment') ?? null }}</textarea>
							<sup aria-label="comment" class="text-danger px-3"></sup>
							<div class="d-flex align-items-center justify-content-between pt-1 pb-3 px-3">
								<span class="text-muted"><b class="counting">0</b>/500 karakter</span>
								<button type="submit" class="d-flex align-items-center rounded-pill">
									<span class="me-2">Kirim</span>
									<i class="icon-paper-plane"></i>
								</button>
							</div>
						</form>
					</div>
					<ul class="news-comment-list"></ul>
				</div>
			</div>
			<div class="col-lg-4">
				<div class="top-stick mt-3">
					<div class="ad ad-md" data-url="{{ (!empty($ad_4)) ? $ad_4->url : '#' }}">
						@if (!empty($ad_4))
						<img src="{{ url('storage/'.$ad_4->file) }}" alt="{{ $ad_4->url }}">
						@endif
					</div>
				</div>
			</div>
		</div>
	</div>
</section>
@endsection

@push('style')
<style>
	.news-section img {
		width: 100%;
	}
</style>
@endpush

@push('running-text')
@include('layouts.component', ['type'=>'running-text'])
<div class="modal fade" id="login" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="loginLabel" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered">
		<div class="modal-content">
			<div class="modal-body">
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
				<div class="text-center">
					<p>Silahkan masuk untuk mengirim komentar.</p>
					<a href="{{ '/auth/redirect' }}" class="login-with-google-btn">
                        <span>Google</span>
                    </a>
				</div>
			</div>
		</div>
	</div>
</div>
@endpush

@push('meta')
<meta name="description" content="{{ strip_tags($read->description) }}">
<meta property="og:type" content="article">
<meta property="og:url" content="{{ request()->fullUrl() }}">
<meta property="og:title" content="{{ $read->title }}">
<meta property="og:description" content="{{ strip_tags($read->description) }}">
<meta property="og:image" content="{{ url('storage/sm/'.$read->file) }}">
<!-- Twitter -->
<meta property="twitter:card" content="summary_large_image">
<meta property="twitter:url" content="{{ request()->fullUrl() }}">
<meta property="twitter:title" content="{{ $read->title }}">
<meta property="twitter:description" content="{{ strip_tags($read->description) }}">
<meta property="twitter:image" content="{{ url('storage/sm/'.$read->file) }}">
@endpush

@push('script')
<script>
	$(".paging a").on('click', function(e) {
		e.preventDefault();
		$([document.documentElement, document.body]).animate({
			scrollTop: $(".has-dropcap").offset().top - 150
		});
		if ($(this).hasClass('active')) {
			return false;
		} else {
			$(".news-section").addClass('d-none');
			$($(this).attr('href')).removeClass('d-none');
			$(".paging a").removeClass('active');
			$(this).addClass('active');
		}
	});

	function fetchComment() {
        $.ajax({
            url : '{{ route('l.news-comment-show', $read->slug) }}',
            type: 'get',
            success: function(response) {
                updateComment(response);
            }
        });
    }
	function updateComment(data) {
        var display = '';
        data.forEach(function(item) {
			var created_at = new Date(item.created_at),
				status = (item.publish=='draft') ? 'hold' : null;
            display += `<li class="${status}">`;
            display += `<div class="image">`;
			display += `<img src="${item.user.avatar}" alt="${item.user.name}">`;
			display += `</div>`;
			display += `<div class="desc">`;
			display += `<b>${item.user.name}</b>`;
			display += `<blockquote>${item.comment}</blockquote>`;
			display += `<small class="text-muted me-2">${created_at.getDate()}/${(created_at.getMonth() + 1)}/${created_at.getFullYear()}</small>`;
			display += `</div>`;
			display += `</li>`;
        });
        $(".news-comment-list").html(display);
    }
    fetchComment();

	$(".send-comment").on('submit', function(e) {
		e.preventDefault();
		let action = $(this).attr('action'),
			submit = $("button[type=submit]");
		$.ajax({
			type: 'post',
			url : action,
			data: $(this).serialize(),
			dataType: 'json',
			error: function(e) {
				submit.children('span').text('Kirim');
				$("[aria-label=comment]").text(e.responseJSON.message);
			},
			beforeSend: function() {
				$("[aria-label=comment]").text(null);
				submit.children('span').text('Mengirim..');
			},
			success: function(response) {
				submit.children('span').text('Kirim');
				if (response.status=='sent') {
					fetchComment();
					$(".send-comment")[0].reset();
					$(".counting").text('0');
				} else if (response.status=='auth') {
					$("#login").modal('show');
				}
			}
		})
	});
	
</script>
@endpush