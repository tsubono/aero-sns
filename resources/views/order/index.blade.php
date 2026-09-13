<x-layout.app title="注文確認">
<div class="flex-1 w-full box-border max-w-[900px] mx-auto px-8 pt-10 pb-[110px]">

    <div class="flex items-center gap-[10px] mb-10 text-[12px] font-semibold flex-wrap">
        <span class="inline-flex items-center gap-[7px] text-[#7b8694]"><span class="w-5 h-5 rounded-full bg-[#e6ebef] text-[#7b8694] inline-flex items-center justify-center text-[11px]">1</span>カート</span>
        <span class="w-[26px] h-px bg-[#d4dae1]"></span>
        <span class="inline-flex items-center gap-[7px] text-[#1fa5c4]"><span class="w-5 h-5 rounded-full bg-[#1fa5c4] text-white inline-flex items-center justify-center text-[11px]">2</span>注文確認</span>
        <span class="w-[26px] h-px bg-[#d4dae1]"></span>
        <span class="inline-flex items-center gap-[7px] text-[#a3adb9]"><span class="w-5 h-5 rounded-full bg-[#f2f5f7] text-[#a3adb9] inline-flex items-center justify-center text-[11px]">3</span>完了</span>
    </div>

    <h1 class="text-[26px] font-bold m-0 mb-8 flex items-center gap-[10px]"><span class="w-1 h-[22px] bg-[#1fa5c4] rounded-[2px] inline-block"></span>注文内容の確認</h1>

    @if(session('error'))
        <div class="mb-6 px-4 py-3 rounded-[10px] bg-[rgba(201,74,74,0.08)] border border-[rgba(201,74,74,0.25)] text-[14px] text-[#c94a4a] font-semibold">{{ session('error') }}</div>
    @endif

    <section class="mb-10">
        <h2 class="text-[15px] font-bold m-0 mb-4 pb-[10px] border-b border-[#e2e6ea]">ご注文商品</h2>
        <div class="flex flex-col">
            @foreach($cartItems as $cartItem)
                <div class="flex gap-4 py-4 border-b border-[#eef1f4] items-center flex-wrap">
                    <div class="w-[68px] h-[68px] flex-none rounded-[9px] overflow-hidden bg-[#f2f5f7] border border-[#e2e6ea]">
                        @if($cartItem->product->thumbnail_path)
                            <img src="{{ '/storage/' . $cartItem->product->thumbnail_path }}" alt="{{ $cartItem->product->name }}" class="w-full h-full object-cover">
                        @else
                            <div class="aero-img-placeholder">
                                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#9fbcc6" stroke-width="1.5"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><path d="M21 15l-5-5L5 21"/></svg>
                            </div>
                        @endif
                    </div>
                    <div class="flex-1 min-w-[160px] flex flex-col gap-1">
                        <span class="text-[14px] font-semibold leading-[1.5]">{{ $cartItem->product->name }}</span>
                        <span class="text-[12px] text-[#7b8694]">数量 {{ $cartItem->quantity }}</span>
                    </div>
                    <div class="text-[15px] font-bold min-w-[90px] text-right">{{ number_format($cartItem->product->point * $cartItem->quantity) }}<span class="text-[12px] text-[#7b8694] font-medium"> pt</span></div>
                </div>
            @endforeach
        </div>
    </section>

    <section class="mb-10">
        <h2 class="text-[15px] font-bold m-0 mb-4 pb-[10px] border-b border-[#e2e6ea]">お支払い方法</h2>
        <div class="border border-[#1fa5c4] bg-[rgba(31,165,196,0.05)] rounded-[12px] p-5 flex items-center gap-[14px] flex-wrap">
            <span class="w-5 h-5 rounded-full border-[6px] border-[#1fa5c4] box-border flex-none"></span>
            <div class="flex-1 min-w-[160px]">
                <div class="text-[15px] font-bold">ポイント決済</div>
            </div>
            <a href="{{ route('point.index') }}" class="text-[13px] font-semibold no-underline whitespace-nowrap">ポイントをチャージ</a>
        </div>
    </section>

    <section class="border border-[#e2e6ea] rounded-[14px] p-6 flex flex-col gap-[14px] mb-8">
        <div class="flex justify-between text-[14px] text-[#4a5566]">
            <span>商品合計（{{ $totalQty }} 点）</span><span class="text-[#1a1f27] font-semibold">{{ number_format($totalPoint) }} pt</span>
        </div>
        <div class="h-px bg-[#eef1f4]"></div>
        <div class="flex justify-between items-baseline">
            <span class="text-[15px] font-bold">お支払いポイント</span>
            <span class="text-[28px] font-extrabold text-[#1fa5c4]">{{ number_format($totalPoint) }}<span class="text-[14px] text-[#7b8694] font-medium"> pt</span></span>
        </div>
        <div class="h-px bg-[#eef1f4]"></div>
        <div class="flex justify-between text-[13px] text-[#7b8694]">
            <span>保有ポイント</span><span>{{ number_format($userPoint) }} pt</span>
        </div>
        <div class="flex justify-between text-[13px] {{ $userPoint >= $totalPoint ? 'text-[#7b8694]' : 'text-[#c94a4a]' }}">
            <span>決済後の残高</span><span class="font-semibold">{{ number_format($userPoint - $totalPoint) }} pt</span>
        </div>
    </section>

    <form method="POST" action="{{ route('order.store') }}">
        @csrf
        <div class="flex gap-[14px] flex-wrap">
            <a href="{{ route('cart.index') }}" class="px-7 py-[15px] rounded-[10px] border border-[#d4dae1] bg-white text-[#4a5566] no-underline text-[15px] font-semibold transition-colors duration-150 hover:bg-[#f2f5f7]">カートに戻る</a>
            <button type="submit" class="flex-1 min-w-[220px] px-7 py-[15px] rounded-[10px] bg-[#1fa5c4] text-white text-[15px] font-bold text-center transition-colors duration-150 hover:bg-[#178ba6] border-0 cursor-pointer font-sans">ポイントで注文を確定する</button>
        </div>
    </form>
</div>
</x-layout.app>
