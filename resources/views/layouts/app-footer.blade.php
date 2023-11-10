<footer class="ayobaca-footer bg-white">
    <div class="container">
        <div class="text-center">
            <img src="{{ url('storage/'.$global['setting'][2]->content) }}" alt="Logo" class="img-fluid">
            <div class="py-2">
                <b>{{ $global['setting'][0]->content }}</b>
                <p>{{ $global['contact'][0]->content }}</p>
                <ul class="list-unstyled">
                    @foreach ($global['contact'][3] as $item)
                    <li>
                        <a href="tel:">
                            <i class="icon-phone"></i>
                            <span>{{ $item->content }}</span>
                        </a>
                    </li>
                    @endforeach
                    @foreach ($global['contact'][4] as $item)
                    <li>
                        <a href="https://wa.me/">
                            <i class="bx bxl-whatsapp"></i>
                            <span>{{ $item->content }}</span>
                        </a>
                    </li>
                    @endforeach
                    @foreach ($global['contact'][2] as $item)
                    <li>
                        <a href="mailto:">
                            <i class="icon-envelope"></i>
                            <span>{{ $item->content }}</span>
                        </a>
                    </li>
                    @endforeach
                </ul>
                <ul class="socialize">
                    @foreach ($global['social'][0] as $item)
                    <li>
                        <a href="#" target="_BLANK" class="{{ $item->brand }}" title="{{ $item->title }}">
                            <i class="{{ $item->icon }}"></i>
                        </a>
                    </li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
    <div class="border-top border-bottom py-4">
        <div class="container">
            <div class="row justify-content-center">
                @foreach ($global['category'] as $item)
                <div class="col-lg-2 col-6 text-center">
                    <a href="{{ route('l.category', $item->slug) }}">{{ $item->title }}</a>
                </div>
                @endforeach
            </div>
        </div>
    </div>
    <div class="py-2">
        <div class="container">
            <div class="info-nav text-center text-muted">
                <a href="{{ route('l.profile') }}">Tentang kami</a>
                <a href="{{ route('l.info', 'ketentuan') }}">Term & condition</a>
                <a href="{{ route('l.info', 'kebijakan') }}">Privacy policy</a>
                <a href="{{ route('l.info', 'iklan') }}">Iklan</a>
                <a href="{{ route('l.contact') }}">Hubungi kami</a>
            </div>
        </div>
    </div>
    <div class="bg-light py-3">
        <div class="container">
            <div class="d-block text-center d-lg-flex justify-content-between">
                <span>&copy; <strong>{{ $global['setting'][0]->content }}</strong> 2023. Allrights reserved.</span>
                <span>Developed by <a href="#" target="_BLANK">Inovindo</a></span>
            </div>
        </div>
    </div>
</footer>