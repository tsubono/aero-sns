<x-layout.app title="マイページ">
<div class="flex-1 w-full box-border max-w-[1180px] mx-auto px-8 pt-5 pb-[110px]">
    <div class="text-[13px] text-[#7b8694] flex gap-2 items-center mb-6">
        <a href="{{ route('top') }}" class="text-[#7b8694] no-underline">ホーム</a>
        <span>/</span>
        <span class="text-[#4a5566]">マイページ</span>
    </div>

    <h1 class="text-[21px] font-bold m-0 mb-[26px] flex items-center gap-[10px]"><span class="w-1 h-[18px] bg-[#1fa5c4] rounded-[2px] inline-block"></span>マイページ</h1>

    <div class="flex items-center justify-between gap-5 px-[26px] py-6 rounded-[14px] bg-[#f2f9fb] border border-[#cfe8ef] mb-[34px] flex-wrap">
        <div class="flex flex-col gap-[6px]">
            <span class="text-[12px] font-semibold text-[#178ba6]">保有ポイント</span>
            <span class="text-[30px] font-extrabold leading-none">{{ number_format(auth()->user()->point) }}<span class="text-[15px] font-semibold text-[#4a5566]"> pt</span></span>
        </div>
        <a href="{{ route('point.index') }}" class="px-6 py-[13px] rounded-[10px] bg-[#1fa5c4] text-white hover:text-white text-[14px] font-bold no-underline transition-colors duration-150 hover:bg-[#178ba6]">ポイントをチャージ</a>
    </div>

    <div class="grid grid-cols-[repeat(auto-fill,minmax(min(240px,calc(50%-9px)),1fr))] gap-[18px] mb-10">
        <a href="{{ route('mypage.profile.index') }}" class="flex items-center gap-[14px] p-5 border border-[#e2e6ea] rounded-[14px] no-underline text-inherit transition-colors duration-150 hover:border-[#1fa5c4] hover:bg-[#f7fbfc]">
            <div class="w-[38px] h-[38px] rounded-[10px] bg-[#f2f9fb] flex items-center justify-center text-[#1fa5c4] flex-none">
                <svg width="19" height="19" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M16 8a4 4 0 1 1-8 0 4 4 0 0 1 8 0M4 20c0-4 3.5-6 8-6s8 2 8 6"/></svg>
            </div>
            <div class="flex flex-col gap-[3px] flex-1">
                <span class="text-[14px] font-semibold">会員情報設定</span>
            </div>
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="#b9c3cd" stroke-width="2"><path d="M9 6l6 6-6 6"/></svg>
        </a>
        <a href="{{ route('mypage.orders.index') }}" class="flex items-center gap-[14px] p-5 border border-[#e2e6ea] rounded-[14px] no-underline text-inherit transition-colors duration-150 hover:border-[#1fa5c4] hover:bg-[#f7fbfc]">
            <div class="w-[38px] h-[38px] rounded-[10px] bg-[#f2f9fb] flex items-center justify-center text-[#1fa5c4] flex-none">
                <svg width="19" height="19" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M4 5h16M4 12h16M4 19h10"/></svg>
            </div>
            <div class="flex flex-col gap-[3px] flex-1">
                <span class="text-[14px] font-semibold">購入履歴</span>
            </div>
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="#b9c3cd" stroke-width="2"><path d="M9 6l6 6-6 6"/></svg>
        </a>
        <a href="{{ route('point.index') }}" class="flex items-center gap-[14px] p-5 border border-[#e2e6ea] rounded-[14px] no-underline text-inherit transition-colors duration-150 hover:border-[#1fa5c4] hover:bg-[#f7fbfc]">
            <div class="w-[38px] h-[38px] rounded-[10px] bg-[#f2f9fb] flex items-center justify-center text-[#1fa5c4] flex-none">
                <svg width="19" height="19" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M12 4v16M4 12h16"/></svg>
            </div>
            <div class="flex flex-col gap-[3px] flex-1">
                <span class="text-[14px] font-semibold">ポイントチャージ</span>
                <span class="text-[12px] text-[#7b8694]">残高 {{ number_format(auth()->user()->point) }} pt</span>
            </div>
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="#b9c3cd" stroke-width="2"><path d="M9 6l6 6-6 6"/></svg>
        </a>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="w-full flex items-center gap-[14px] p-5 border border-[#e2e6ea] rounded-[14px] text-inherit transition-colors duration-150 hover:border-[#1fa5c4] hover:bg-[#f7fbfc] bg-transparent cursor-pointer font-sans text-left">
                <div class="w-[38px] h-[38px] rounded-[10px] bg-[#f2f9fb] flex items-center justify-center text-[#1fa5c4] flex-none">
                    <svg width="19" height="19" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M15 17l5-5-5-5M20 12H9M13 3H5v18h8"/></svg>
                </div>
                <div class="flex flex-col gap-[3px] flex-1">
                    <span class="text-[14px] font-semibold">ログアウト</span>
                </div>
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="#b9c3cd" stroke-width="2"><path d="M9 6l6 6-6 6"/></svg>
            </button>
        </form>
    </div>

    <div class="flex items-center justify-between gap-4 mb-[18px] flex-wrap">
        <h2 class="text-[17px] font-bold m-0">最近購入した商品</h2>
        <a href="{{ route('mypage.orders.index') }}" class="text-[13px] font-semibold no-underline inline-flex items-center gap-[6px]">購入履歴をすべて見る
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 12h14"/><path d="M13 6l6 6-6 6"/></svg>
        </a>
    </div>

    <div class="flex flex-col border border-[#e2e6ea] rounded-[14px] overflow-hidden">
        @forelse($recentOrders as $order)
            @foreach($order->orderDetails as $detail)
                <a href="{{ route('mypage.orders.show', $order) }}" class="flex items-center gap-4 px-[18px] py-4 border-b border-[#eef1f4] no-underline text-inherit transition-colors duration-150 hover:bg-[#f7fbfc]">
                    <div class="w-14 h-14 rounded-[10px] overflow-hidden border border-[#e2e6ea] flex-none">
                        <div class="aero-img-placeholder">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#9fbcc6" stroke-width="1.4"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><path d="M21 15l-5-5L5 21"/></svg>
                        </div>
                    </div>
                    <div class="flex flex-col gap-1 flex-1 min-w-0">
                        <span class="text-[11px] font-semibold text-[#178ba6]">{{ $detail->product->category->name ?? '' }}</span>
                        <span class="text-[14px] font-semibold leading-[1.5]">{{ $detail->product_name }}</span>
                    </div>
                    <div class="flex flex-col items-end gap-1 flex-none">
                        <span class="text-[14px] font-bold">{{ number_format($detail->point) }}<span class="text-[11px] text-[#7b8694] font-medium"> pt</span></span>
                        <span class="text-[12px] text-[#7b8694]">{{ $order->created_at->format('Y/m/d') }}</span>
                    </div>
                </a>
            @endforeach
        @empty
            <div class="px-[18px] py-10 text-center text-[14px] text-[#7b8694]">購入履歴がありません。</div>
        @endforelse
    </div>
</div>
</x-layout.app>
