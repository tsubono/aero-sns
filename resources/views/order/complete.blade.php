<x-layout.app title="注文完了">
<div class="flex-1 max-w-[720px] mx-auto px-8 pt-10 pb-[110px] w-full box-border">

    <div class="flex items-center gap-[10px] mb-14 text-[12px] font-semibold flex-wrap">
        <span class="inline-flex items-center gap-[7px] text-[#7b8694]"><span class="w-5 h-5 rounded-full bg-[#e6ebef] text-[#7b8694] inline-flex items-center justify-center text-[11px]">1</span>カート</span>
        <span class="w-[26px] h-px bg-[#d4dae1]"></span>
        <span class="inline-flex items-center gap-[7px] text-[#7b8694]"><span class="w-5 h-5 rounded-full bg-[#e6ebef] text-[#7b8694] inline-flex items-center justify-center text-[11px]">2</span>注文確認</span>
        <span class="w-[26px] h-px bg-[#d4dae1]"></span>
        <span class="inline-flex items-center gap-[7px] text-[#1fa5c4]"><span class="w-5 h-5 rounded-full bg-[#1fa5c4] text-white inline-flex items-center justify-center text-[11px]">3</span>完了</span>
    </div>

    <div class="flex flex-col items-center text-center gap-5 mb-12">
        <div class="w-[68px] h-[68px] rounded-full bg-[rgba(43,158,120,0.1)] flex items-center justify-center">
            <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="#2b9e78" stroke-width="2.4"><path d="M20 6L9 17l-5-5"/></svg>
        </div>
        <h1 class="text-[28px] font-extrabold m-0 tracking-[-0.01em]">ご注文が完了しました</h1>
        <p class="text-[15px] leading-[1.9] text-[#4a5566] m-0 max-w-[440px]">ご購入いただいた商品は、注文履歴からいつでもダウンロードいただけます。</p>
    </div>

    @if($order)
        <div class="border border-[#e2e6ea] rounded-[14px] p-6 flex flex-col gap-[14px] mb-9">
            <div class="flex justify-between text-[13px]">
                <span class="text-[#7b8694]">注文番号</span><span class="font-semibold">{{ $order->order_no }}</span>
            </div>
            <div class="h-px bg-[#eef1f4]"></div>
            <div class="flex justify-between text-[13px]">
                <span class="text-[#7b8694]">お支払い方法</span><span class="font-semibold">ポイント決済</span>
            </div>
            <div class="h-px bg-[#eef1f4]"></div>
            <div class="flex justify-between items-baseline">
                <span class="text-[13px] text-[#7b8694]">お支払いポイント</span>
                <span class="text-[20px] font-extrabold">{{ number_format($order->total_point) }}<span class="text-[13px] text-[#7b8694] font-medium"> pt</span></span>
            </div>
            <div class="flex justify-between text-[13px]">
                <span class="text-[#7b8694]">残高</span><span class="text-[#4a5566] font-semibold">{{ number_format(auth()->user()->point) }} pt</span>
            </div>
        </div>
    @endif

    <div class="flex flex-col gap-3">
        <a href="{{ route('mypage.orders.index') }}" class="py-4 px-7 rounded-[10px] bg-[#1fa5c4] text-white hover:text-white no-underline text-[15px] font-bold text-center inline-flex items-center justify-center gap-[9px] transition-colors duration-150 hover:bg-[#178ba6]">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 5h16"/><path d="M4 12h16"/><path d="M4 19h10"/></svg>
            注文履歴を見る
        </a>
        <a href="{{ route('products.index') }}" class="py-[15px] px-7 rounded-[10px] border border-[#d4dae1] bg-white text-[#4a5566] no-underline text-[14px] font-semibold text-center transition-colors duration-150 hover:bg-[#f2f5f7]">買い物を続ける</a>
    </div>
</div>
</x-layout.app>
