@props(['title' => '', 'centerLogo' => false])
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ? $title . ' | ' : '' }}{{ config('app.name', 'AERO SNS') }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+JP:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
<div class="font-sans bg-white text-[#1a1f27] min-h-screen flex flex-col">

    <header class="flex items-center {{ $centerLogo ? 'justify-center' : 'justify-between' }} gap-6 py-[18px] px-8 border-b border-[#e2e6ea]">
        <a href="{{ route('top') }}" class="text-[20px] font-extrabold tracking-[0.04em] text-[#1a1f27] no-underline">AERO <span class="text-[#1fa5c4]">SNS</span></a>
    </header>

    {{ $slot }}

    <x-layout.footer />
</div>
</body>
</html>
