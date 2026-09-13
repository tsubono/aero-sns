@props(['order'])

<div class="border border-[#e2e6ea] rounded-[14px] overflow-hidden">
    <div class="flex items-center justify-between flex-wrap border-b border-[#e2e6ea] bg-[#f7fbfc] px-[18px] py-[14px] gap-4">
        <div class="flex items-center flex-wrap gap-[18px]">
            <div class="flex flex-col gap-0.5">
                <span class="text-[11px] text-[#7b8694]">注文日</span>
                <span class="text-[13px] font-semibold">{{ $order->created_at->format('Y/m/d') }}</span>
            </div>
            <div class="flex flex-col gap-0.5">
                <span class="text-[11px] text-[#7b8694]">注文番号</span>
                <span class="text-[13px] font-semibold">{{ $order->order_no }}</span>
            </div>
            <div class="flex flex-col gap-0.5">
                <span class="text-[11px] text-[#7b8694]">合計</span>
                <span class="text-[13px] font-bold">{{ number_format($order->total_point) }}<span class="text-[11px] text-[#7b8694] font-medium"> pt</span></span>
            </div>
        </div>
        <a href="{{ route('mypage.orders.show', $order) }}" class="text-[13px] font-semibold no-underline border border-[#d4dae1] rounded-[9px] text-[#4a5566] bg-white transition-colors duration-150 hover:bg-[#f2f5f7] hover:border-[#1fa5c4] hover:text-[#178ba6] px-4 py-[9px]">詳細を見る</a>
    </div>
    @foreach($order->orderDetails as $detail)
        <div class="flex items-center gap-4 px-[18px] py-4 border-b border-[#eef1f4]">
            <div class="w-14 h-14 rounded-[10px] overflow-hidden border border-[#e2e6ea] flex-none">
                @if($detail->product?->thumbnail_path)
                    <img src="{{ '/storage/' . $detail->product->thumbnail_path }}" alt="{{ $detail->product_name }}" class="w-full h-full object-cover">
                @else
                    <div class="aero-img-placeholder">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#9fbcc6" stroke-width="1.4"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><path d="M21 15l-5-5L5 21"/></svg>
                    </div>
                @endif
            </div>
            <div class="flex flex-col flex-1 min-w-0 gap-1">
                <span class="text-[11px] font-semibold text-[#178ba6]">{{ $detail->product->category->name ?? '' }}</span>
                @if($detail->product)
                    <a href="{{ route('products.show', $detail->product) }}" class="text-[14px] font-semibold leading-[1.5] truncate text-[#1a1f27] no-underline hover:text-[#1fa5c4] transition-colors">{{ $detail->product_name }}</a>
                @else
                    <span class="text-[14px] font-semibold leading-[1.5] truncate">{{ $detail->product_name }}</span>
                @endif
            </div>
            <span class="text-[14px] font-bold flex-none">{{ number_format($detail->point) }}<span class="text-[11px] text-[#7b8694] font-medium"> pt</span></span>
        </div>
    @endforeach
</div>
