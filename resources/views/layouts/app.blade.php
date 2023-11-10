<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@yield('title')</title>
    <meta name="theme-color" content="{{ $global['setting'][3]->content }}">
    <meta name="keywords" content="{{ $global['setting'][5]->content }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/simple-line-icons/2.5.5/css/simple-line-icons.min.css">
    @stack('meta')
    {{-- <link rel="stylesheet" href="{{ asset('build/assets/app-e559bf45.css') }}"> --}}
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
    @stack('style')
</head>
<body>
    @stack('running-text')
    @include('layouts.app-nav')
    @yield('content')
    <script src="{{ asset('node_modules/jquery/dist/jquery.min.js') }}"></script>
    {{-- <script src="{{ asset('build/assets/app-662fcc1c.js') }}" type="module"></script> --}}
    @include('layouts.app-footer')
    @stack('script')
</body>
</html>