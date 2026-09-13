<x-layout.app title="カート">
<div class="flex-1 w-full box-border max-w-[1080px] mx-auto px-8 pt-5 pb-[110px]">
    <div class="text-[13px] text-[#7b8694] flex gap-2 items-center mb-6">
        <a href="{{ route('top') }}" class="text-[#7b8694] no-underline">ホーム</a>
        <span>/</span>
        <span class="text-[#4a5566]">カート</span>
    </div>

    <h1 class="text-[26px] font-bold m-0 mb-[30px] flex items-center gap-[10px]"><span class="w-1 h-[22px] bg-[#1fa5c4] rounded-[2px] inline-block"></span>カート</h1>

    @if(session('success'))
        <div class="mb-6 px-4 py-3 rounded-[10px] bg-[rgba(43,158,120,0.08)] border border-[rgba(43,158,120,0.25)] text-[14px] text-[#2b9e78] font-semibold">{{ session('success') }}</div>
    @endif

    @if($cartItems->count() > 0)
        <div class="grid grid-cols-[minmax(320px,1.7fr)_minmax(280px,1fr)] gap-10 items-start">
            <div class="min-w-0 flex flex-col gap-[14px]">
                @foreach($cartItems as $cartItem)
                    <div class="flex gap-[18px] p-5 border border-[#e2e6ea] rounded-[14px] bg-white items-center flex-wrap">
                        <div class="w-24 h-24 flex-none rounded-[10px] overflow-hidden bg-[#f2f5f7] border border-[#e2e6ea]">
                            @if($cartItem->product->thumbnail_path)
                                <img src="{{ '/storage/' . $cartItem->product->thumbnail_path }}" alt="{{ $cartItem->product->name }}" class="w-full h-full object-cover">
                            @else
                                <div class="aero-img-placeholder">
                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#9fbcc6" stroke-width="1.5"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><path d="M21 15l-5-5L5 21"/></svg>
                                </div>
                            @endif
                        </div>
                        <div class="flex-1 min-w-[180px] flex flex-col gap-[14px]">
                            <div class="flex items-start justify-between gap-4 flex-wrap">
                                <div class="flex flex-col gap-[5px] min-w-0">
                                    <span class="text-[11px] font-semibold text-[#178ba6]">{{ $cartItem->product->category->name ?? '' }}</span>
                                    <a href="{{ route('products.show', $cartItem->product) }}" class="text-[15px] font-semibold text-[#1a1f27] no-underline leading-[1.5]">{{ $cartItem->product->name }}</a>
                                </div>
                                <div class="text-[17px] font-bold whitespace-nowrap">{{ number_format($cartItem->product->point * $cartItem->quantity) }}<span class="text-[12px] text-[#7b8694] font-medium"> pt</span></div>
                            </div>
                            <div class="flex items-center justify-between gap-4 flex-wrap">
                                <form method="POST" action="{{ route('cart.update', $cartItem) }}" class="flex items-center">
                                    @csrf
                                    @method('PATCH')
                                    <div class="flex items-center border border-[#d4dae1] rounded-[8px] overflow-hidden">
                                        <button type="submit" name="delta" value="-1" class="w-[34px] h-[34px] border-0 bg-transparent text-[#4a5566] text-[16px] cursor-pointer font-sans transition-colors duration-150 hover:bg-[#f2f5f7]" aria-label="減らす">−</button>
                                        <div class="w-[42px] text-center text-[14px] font-semibold border-l border-r border-[#e2e6ea] leading-[34px]">{{ $cartItem->quantity }}</div>
                                        <button type="submit" name="delta" value="1" class="w-[34px] h-[34px] border-0 bg-transparent text-[#4a5566] text-[16px] cursor-pointer font-sans transition-colors duration-150 hover:bg-[#f2f5f7]" aria-label="増やす">＋</button>
                                    </div>
                                </form>
                                <form method="POST" action="{{ route('cart.destroy', $cartItem) }}">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="inline-flex items-center gap-[6px] border-0 bg-transparent text-[#99a3af] text-[13px] cursor-pointer font-sans px-1 py-[6px] transition-colors duration-150 hover:text-[#c94a4a]">
                                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9"><path d="M3 6h18"/><path d="M8 6V4h8v2"/><path d="M5 6l1 15h12l1-15"/></svg>
                                        削除
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="min-w-0 sticky top-24 border border-[#e2e6ea] rounded-[14px] p-6 flex flex-col gap-4 bg-white shadow-[0_10px_30px_rgba(26,31,39,0.05)]">
                <div class="text-[15px] font-bold">お支払い内容</div>
                <div class="flex justify-between text-[14px] text-[#4a5566]">
                    <span>商品点数</span><span class="text-[#1a1f27] font-semibold">{{ $totalQty }} 点</span>
                </div>
                <div class="h-px bg-[#eef1f4]"></div>
                <div class="flex justify-between items-baseline">
                    <span class="text-[14px] text-[#4a5566]">合計</span>
                    <span class="text-[26px] font-extrabold">{{ number_format($totalPoint) }}<span class="text-[14px] text-[#7b8694] font-medium"> pt</span></span>
                </div>
                <div class="flex justify-between text-[13px] text-[#7b8694]">
                    <span>保有ポイント</span><span>{{ number_format(auth()->user()->point) }} pt</span>
                </div>
                <a href="{{ route('order.index') }}" class="mt-1 px-6 py-[15px] rounded-[10px] bg-[#1fa5c4] text-white hover:text-white no-underline text-[15px] font-bold text-center transition-colors duration-150 hover:bg-[#178ba6]">注文手続きへ進む</a>
                <a href="{{ route('products.index') }}" class="text-[13px] text-[#7b8694] no-underline text-center">買い物を続ける</a>
            </div>
        </div>
    @else
        <div class="py-[90px] text-center flex flex-col items-center gap-[18px]">
            <svg width="42" height="42" viewBox="0 0 24 24" fill="none" stroke="#c8d0d9" stroke-width="1.6"><circle cx="9" cy="21" r="1"/><circle cx="19" cy="21" r="1"/><path d="M1 1h3l2.4 12.4a2 2 0 0 0 2 1.6h9.2a2 2 0 0 0 2-1.6L21 6H6"/></svg>
            <div class="text-[15px] text-[#7b8694]">カートに商品がありません。</div>
            <a href="{{ route('products.index') }}" class="px-7 py-[13px] rounded-[10px] bg-[#1fa5c4] text-white hover:text-white no-underline text-[14px] font-bold hover:bg-[#178ba6]">商品を探す</a>
        </div>
    @endif
</div>
</x-layout.app>
