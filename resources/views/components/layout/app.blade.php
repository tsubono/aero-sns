@props(['title' => ''])
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
    <header class="sticky top-0 z-20 flex items-center justify-between gap-6 py-[18px] px-8 bg-white/90 backdrop-blur-[10px] border-b border-[#e2e6ea] flex-wrap">
        <a href="{{ route('top') }}" class="text-[20px] font-extrabold tracking-[0.04em] text-[#1a1f27] no-underline">AERO <span class="text-[#1fa5c4]">SNS</span></a>
        <div class="flex items-center gap-[18px]">
            @if($userPoint !== null)
                <a href="{{ route('point.index') }}" class="flex items-center gap-[6px] px-[13px] py-[7px] rounded-full bg-[rgba(31,165,196,0.1)] hover:bg-[rgba(31,165,196,0.18)] transition-colors duration-150 no-underline" aria-label="保有ポイント">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="#1fa5c4" stroke="none"><polygon points="12,2 15.09,8.26 22,9.27 17,14.14 18.18,21.02 12,17.77 5.82,21.02 7,14.14 2,9.27 8.91,8.26"/></svg>
                    <span class="text-[15px] font-bold text-[#1fa5c4] leading-none">{{ number_format($userPoint) }}<span class="text-[13px] font-semibold"> pt</span></span>
                </a>
            @endif
            <div class="relative">
                <a href="#" id="user-menu-btn" onclick="toggleUserMenu(event)" class="text-[#4a5566] flex items-center" aria-label="マイページ">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><circle cx="12" cy="8" r="4"/><path d="M4 20c0-4 3.5-6 8-6s8 2 8 6"/></svg>
                </a>
                <div id="user-menu" class="hidden absolute top-8 right-0 bg-white border border-[#e2e6ea] rounded-[10px] p-[6px] flex flex-col min-w-[160px] shadow-[0_12px_28px_rgba(26,31,39,0.12)] z-30">
                    @auth
                        <a href="{{ route('mypage') }}" class="text-[#4a5566] no-underline text-[14px] px-3 py-[9px] rounded-[6px] hover:bg-[#f2f5f7]">マイページ</a>
                        <a href="{{ route('point.index') }}" class="text-[#4a5566] no-underline text-[14px] px-3 py-[9px] rounded-[6px] hover:bg-[#f2f5f7]">ポイントチャージ</a>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="w-full text-left text-[#4a5566] text-[14px] px-3 py-[9px] rounded-[6px] bg-transparent border-0 cursor-pointer font-sans hover:bg-[#f2f5f7]">ログアウト</button>
                        </form>
                    @else
                        <a href="{{ route('login') }}" class="text-[#4a5566] no-underline text-[14px] px-3 py-[9px] rounded-[6px] hover:bg-[#f2f5f7]">ログイン</a>
                        <a href="{{ route('register') }}" class="text-[#4a5566] no-underline text-[14px] px-3 py-[9px] rounded-[6px] hover:bg-[#f2f5f7]">新規登録</a>
                    @endauth
                </div>
            </div>
            <a href="{{ route('cart.index') }}" class="text-[#4a5566] relative flex items-center" aria-label="カート">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><circle cx="9" cy="21" r="1"/><circle cx="19" cy="21" r="1"/><path d="M1 1h3l2.4 12.4a2 2 0 0 0 2 1.6h9.2a2 2 0 0 0 2-1.6L21 6H6"/></svg>
                @if($cartCount > 0)
                    <span class="absolute -top-[6px] -right-[8px] bg-[#1fa5c4] text-white text-[10px] font-bold w-4 h-4 rounded-full flex items-center justify-center">{{ $cartCount }}</span>
                @endif
            </a>
        </div>
    </header>

    {{ $slot }}

    <x-layout.footer />
</div>
<script>
function toggleUserMenu(e) {
    e.preventDefault();
    document.getElementById('user-menu').classList.toggle('hidden');
}
document.addEventListener('click', function(e) {
    const btn = document.getElementById('user-menu-btn');
    const menu = document.getElementById('user-menu');
    if (menu && btn && !btn.contains(e.target) && !menu.contains(e.target)) {
        menu.classList.add('hidden');
    }
});
</script>
</body>
</html>
