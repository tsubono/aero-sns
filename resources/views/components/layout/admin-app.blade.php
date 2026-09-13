@props(['title' => ''])
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ? $title . ' | ' : '' }}管理画面 | {{ config('app.name', 'AERO SNS') }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+JP:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
<div class="font-sans bg-[#f4f6f8] text-[#1a1f27] min-h-screen flex">

    <aside id="admin-sidebar" class="fixed inset-y-0 left-0 z-30 w-60 bg-[#1a1f27] flex flex-col -translate-x-full md:translate-x-0 transition-transform duration-200">
        <div class="flex items-center px-6 py-5 border-b border-white/10">
            <a href="{{ route('admin.products.index') }}" class="text-[18px] font-extrabold tracking-wide text-white no-underline hover:text-white">AERO <span class="text-[#1fa5c4]">Admin</span></a>
        </div>
        <nav class="flex-1 py-4 flex flex-col gap-1 px-3 overflow-y-auto">
            <a href="{{ route('admin.products.index') }}" class="flex items-center gap-3 px-3 py-[10px] rounded-[8px] text-[13px] font-semibold no-underline transition-colors duration-150 {{ request()->routeIs('admin.products.*') ? 'bg-[#1fa5c4] text-white' : 'text-white/70 hover:bg-white/10 hover:text-white' }}">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="3" width="20" height="14" rx="2"/><path d="M8 21h8M12 17v4"/></svg>
                商品管理
            </a>
            <a href="{{ route('admin.users.index') }}" class="flex items-center gap-3 px-3 py-[10px] rounded-[8px] text-[13px] font-semibold no-underline transition-colors duration-150 {{ request()->routeIs('admin.users.*') ? 'bg-[#1fa5c4] text-white' : 'text-white/70 hover:bg-white/10 hover:text-white' }}">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M16 8a4 4 0 1 1-8 0 4 4 0 0 1 8 0M4 20c0-4 3.5-6 8-6s8 2 8 6"/></svg>
                ユーザー管理
            </a>
            <a href="{{ route('admin.sales.index') }}" class="flex items-center gap-3 px-3 py-[10px] rounded-[8px] text-[13px] font-semibold no-underline transition-colors duration-150 {{ request()->routeIs('admin.sales.*') ? 'bg-[#1fa5c4] text-white' : 'text-white/70 hover:bg-white/10 hover:text-white' }}">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 3v18h18"/><path d="M7 16l4-4 4 4 4-8"/></svg>
                売上管理
            </a>
        </nav>
        <div class="px-3 pb-5 border-t border-white/10 pt-4">
            <span class="block text-[11px] text-white/40 px-3 mb-2">{{ auth('admin')->user()->name ?? '' }}</span>
            <form method="POST" action="{{ route('admin.logout') }}">
                @csrf
                <button type="submit" class="w-full flex items-center gap-3 px-3 py-[10px] rounded-[8px] text-[13px] font-semibold text-white/70 hover:bg-white/10 hover:text-white bg-transparent border-0 cursor-pointer font-sans transition-colors duration-150">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M15 17l5-5-5-5M20 12H9M13 3H5v18h8"/></svg>
                    ログアウト
                </button>
            </form>
        </div>
    </aside>

    <div id="admin-overlay" class="fixed inset-0 z-20 bg-black/50 hidden md:hidden" onclick="toggleAdminSidebar()"></div>

    <div class="flex-1 flex flex-col min-w-0 md:ml-60">
        <header class="sticky top-0 z-10 flex items-center gap-4 px-6 py-[14px] bg-white border-b border-[#e2e6ea]">
            <button class="md:hidden p-1 text-[#4a5566] bg-transparent border-0 cursor-pointer" onclick="toggleAdminSidebar()" aria-label="メニュー">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 12h18M3 6h18M3 18h18"/></svg>
            </button>
            <h1 class="text-[15px] font-bold text-[#1a1f27] flex-1">{{ $title }}</h1>
        </header>

        <main class="flex-1 p-6">
            @if(session('success'))
                <div class="mb-5 px-4 py-3 rounded-[8px] bg-green-50 border border-green-200 text-green-700 text-[13px] font-semibold">{{ session('success') }}</div>
            @endif
            @if(session('error'))
                <div class="mb-5 px-4 py-3 rounded-[8px] bg-red-50 border border-red-200 text-red-600 text-[13px] font-semibold">{{ session('error') }}</div>
            @endif
            {{ $slot }}
        </main>
    </div>
</div>
<script>
function toggleAdminSidebar() {
    document.getElementById('admin-sidebar').classList.toggle('-translate-x-full');
    document.getElementById('admin-overlay').classList.toggle('hidden');
}
</script>
</body>
</html>
