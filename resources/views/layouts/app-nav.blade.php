@php
    $active = $active ?? 0;
@endphp
<header class="ayobaca-header">
    <div class="container-fluid d-flex justify-content-between align-items-center py-2">
        <div class="logo">
            <a href="{{ route('l.home') }}">
                <img src="{{ url('storage/'.$global['setting'][2]->content) }}" alt="Logo" class="img-fluid">
            </a>
        </div>
        <div class="search">
            <form action="{{ route('l.search') }}" method="get">
                <input type="search" name="q" placeholder="Cari apa?" value="{{ request()->get('q') ?? null }}">
                <button type="submit">
                    <i class="icon-magnifier"></i>
                </button>
            </form>
        </div>
    </div>
    <nav>
        <button type="button" class="navLeft shadow-sm hide"><</button>
        <a href="{{ route('l.home') }}" @class(['active' => (Route::currentRouteName()=='l.home')])>
            <i class="icon-book-open"></i>
            <span>Beranda</span>
        </a>
        @foreach ($global['category'] as $item)
        <a href="{{ route('l.category', $item->slug) }}" @class(['active' => ($active==$item->id)])>{{ $item->title }}</a>
        @endforeach
        <a href="{{ route('l.contact') }}" @class(['active' => (Route::currentRouteName()=='l.contact')])>Kontak Kami</a>
    </nav>
</header>
<div class="top-holder"></div>