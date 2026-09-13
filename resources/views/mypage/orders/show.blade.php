<x-layout.app title="購入履歴詳細">
<div class="flex-1 w-full box-border max-w-[820px] mx-auto px-8 pt-5 pb-[110px]">
    <div class="text-[13px] text-[#7b8694] flex gap-2 items-center mb-6 flex-wrap">
        <a href="{{ route('top') }}" class="text-[#7b8694] no-underline">ホーム</a>
        <span>/</span>
        <a href="{{ route('mypage') }}" class="text-[#7b8694] no-underline">マイページ</a>
        <span>/</span>
        <a href="{{ route('mypage.orders.index') }}" class="text-[#7b8694] no-underline">購入履歴</a>
        <span>/</span>
        <span class="text-[#4a5566]">注文詳細</span>
    </div>

    <h1 class="text-[21px] font-bold m-0 mb-[26px] flex items-center gap-[10px]"><span class="w-1 h-[18px] bg-[#1fa5c4] rounded-[2px] inline-block"></span>注文詳細</h1>

    <div class="border border-[#e2e6ea] rounded-[14px] p-[22px] mb-[18px] grid grid-cols-2 sm:grid-cols-4 gap-5">
        <div class="flex flex-col gap-[5px]">
            <span class="text-[12px] text-[#7b8694]">注文番号</span>
            <span class="text-[14px] font-bold">{{ $order->order_no }}</span>
        </div>
        <div class="flex flex-col gap-[5px]">
            <span class="text-[12px] text-[#7b8694]">注文日時</span>
            <span class="text-[14px] font-semibold">{{ $order->created_at->format('Y/m/d H:i') }}</span>
        </div>
        <div class="flex flex-col gap-[5px]">
            <span class="text-[12px] text-[#7b8694]">ステータス</span>
            <span class="text-[13px] font-bold text-[#178ba6] bg-[#f2f9fb] border border-[#cfe8ef] rounded-full px-3 py-1 self-start">決済完了</span>
        </div>
        <div class="flex flex-col gap-[5px]">
            <span class="text-[12px] text-[#7b8694]">お支払い方法</span>
            <span class="text-[14px] font-semibold">ポイント払い</span>
        </div>
    </div>

    <h2 class="text-[15px] font-bold m-0 mt-[30px] mb-[14px]">購入商品</h2>

    <div class="border border-[#e2e6ea] rounded-[14px] overflow-hidden mb-[26px]">
        @foreach($order->orderDetails as $detail)
            <div class="p-[18px] border-b border-[#eef1f4] flex flex-col gap-4 last:border-0">
                <div class="flex items-center gap-4">
                    <div class="w-16 h-16 rounded-[10px] overflow-hidden border border-[#e2e6ea] flex-none">
                        <div class="aero-img-placeholder">
                            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#9fbcc6" stroke-width="1.4"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><path d="M21 15l-5-5L5 21"/></svg>
                        </div>
                    </div>
                    <div class="flex flex-col gap-[5px] flex-1 min-w-0">
                        <span class="text-[11px] font-semibold text-[#178ba6]">{{ $detail->product->category->name ?? '' }}</span>
                        @if($detail->product)
                            <a href="{{ route('products.show', $detail->product) }}" class="text-[15px] font-semibold leading-[1.5] text-[#1a1f27] no-underline hover:text-[#1fa5c4] transition-colors">{{ $detail->product_name }}</a>
                        @else
                            <span class="text-[15px] font-semibold leading-[1.5]">{{ $detail->product_name }}</span>
                        @endif
                    </div>
                    <span class="text-[15px] font-bold flex-none">{{ number_format($detail->point * $detail->quantity) }}<span class="text-[11px] text-[#7b8694] font-medium"> pt</span></span>
                </div>

                @if($detail->productAccounts->isNotEmpty())
                    <div class="flex flex-col gap-[10px]">
                        @foreach($detail->productAccounts as $accountIndex => $account)
                            <div class="border border-[#e2e6ea] rounded-[10px] overflow-hidden">
                                <div class="flex items-center justify-between px-[14px] py-[10px] bg-[#f7fbfc] border-b border-[#eef1f4]">
                                    <span class="text-[12px] font-semibold text-[#4a5566]">アカウント {{ $accountIndex + 1 }}</span>
                                    <button
                                        class="text-[12px] font-bold text-white bg-[#1fa5c4] border-0 px-[14px] py-[7px] rounded-[7px] cursor-pointer transition-colors duration-150 hover:bg-[#178ba6] font-sans"
                                        onclick="copyText(this, {{ json_encode($account->account_text) }})"
                                    >一括コピー</button>
                                </div>
                                @foreach($account->parsedInfo() as $field)
                                    <div class="flex items-center gap-3 px-[14px] py-[11px] border-b border-[#eef1f4] bg-white last:border-0">
                                        <span class="text-[12px] font-semibold text-[#7b8694] flex-none w-[150px]">{{ $field['label'] }}</span>
                                        <span class="text-[13px] text-[#1a1f27] flex-1 min-w-0 overflow-hidden text-ellipsis whitespace-nowrap">{{ $field['value'] }}</span>
                                        <button
                                            class="flex-none flex items-center justify-center w-[30px] h-[30px] rounded-[8px] border border-[#d4dae1] text-[#4a5566] bg-white cursor-pointer transition-colors duration-150 hover:bg-[#f2f9fb] hover:border-[#1fa5c4] hover:text-[#1fa5c4] font-sans"
                                            onclick="copyText(this, {{ json_encode($field['value']) }}, '{{ $loop->index }}')"
                                            data-default-icon="true"
                                        >
                                            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><rect x="9" y="9" width="11" height="11" rx="2"/><path d="M15 5.5A2 2 0 0 0 13 4H6a2 2 0 0 0-2 2v7a2 2 0 0 0 1.5 1.9"/></svg>
                                        </button>
                                    </div>
                                @endforeach
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        @endforeach
    </div>

    <div class="px-[22px] py-5 rounded-[14px] border border-[#e2e6ea] flex flex-col gap-3 mb-[26px]">
        <div class="flex items-center justify-between text-[14px] text-[#4a5566]">
            <span>商品小計（{{ $order->orderDetails->sum('quantity') }}点）</span>
            <span class="font-semibold text-[#1a1f27]">{{ number_format($order->orderDetails->sum(fn($d) => $d->point * $d->quantity)) }} pt</span>
        </div>
        <div class="h-px bg-[#e2e6ea]"></div>
        <div class="flex items-center justify-between">
            <span class="text-[15px] font-bold">お支払い合計</span>
            <span class="text-[21px] font-extrabold">{{ number_format($order->total_point) }}<span class="text-[13px] font-semibold text-[#4a5566]"> pt</span></span>
        </div>
        <div class="flex items-center justify-between text-[13px] text-[#7b8694]">
            <span>決済後のポイント残高</span>
            <span>{{ number_format(auth()->user()->point) }} pt</span>
        </div>
    </div>

    <a href="{{ route('mypage.orders.index') }}" class="block text-center px-0 py-[15px] rounded-[10px] bg-white border border-[#d4dae1] text-[#4a5566] text-[14px] font-semibold no-underline transition-colors duration-150 hover:bg-[#f2f5f7] hover:border-[#b9c3cd]">購入履歴に戻る</a>
</div>
<script>
function copyText(btn, text, fieldIndex) {
    navigator.clipboard.writeText(text).then(function() {
        if (btn.dataset.defaultIcon) {
            btn.innerHTML = '<svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="#1fa5c4" stroke-width="2.5"><path d="M20 6L9 17l-5-5"/></svg>';
            setTimeout(function() {
                btn.innerHTML = '<svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><rect x="9" y="9" width="11" height="11" rx="2"/><path d="M15 5.5A2 2 0 0 0 13 4H6a2 2 0 0 0-2 2v7a2 2 0 0 0 1.5 1.9"/></svg>';
            }, 1500);
        } else {
            var orig = btn.textContent;
            btn.textContent = 'コピー完了';
            setTimeout(function() { btn.textContent = orig; }, 1500);
        }
    });
}
</script>
</x-layout.app>
