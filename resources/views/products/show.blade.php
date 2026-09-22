<x-layout.app :title="$product->name">
<div class="flex-1 w-full box-border max-w-[1180px] mx-auto px-8 pt-5 pb-[100px]">
    <div class="text-[13px] text-[#7b8694] flex gap-2 items-center mb-7 flex-wrap">
        <a href="{{ route('top') }}" class="text-[#7b8694] no-underline">ホーム</a>
        <span>/</span>
        <a href="{{ route('products.index', ['category_id' => $product->category_id]) }}" class="text-[#7b8694] no-underline">{{ $product->category->name ?? '' }}</a>
        <span>/</span>
        <span class="text-[#4a5566]">{{ $product->name }}</span>
    </div>

    <div class="grid grid-cols-[repeat(auto-fit,minmax(340px,1fr))] gap-12 mb-[72px]">

        <div class="flex flex-col gap-[14px] min-w-0">
            <div class="w-full aspect-square rounded-2xl overflow-hidden bg-[#f2f5f7] border border-[#e2e6ea]">
                @if($product->thumbnail_path)
                    <img src="{{ '/storage/' . $product->thumbnail_path }}" alt="{{ $product->name }}" class="w-full h-full object-cover">
                @else
                    <div class="aero-img-placeholder-lg">
                        <svg width="52" height="52" viewBox="0 0 24 24" fill="none" stroke="#9fbcc6" stroke-width="1.2"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><path d="M21 15l-5-5L5 21"/></svg>
                    </div>
                @endif
            </div>
        </div>

        <div class="flex flex-col gap-5 min-w-0">
            <div>
                @if($product->category)
                    <div class="flex gap-2 mb-3">
                        <span class="text-[12px] font-semibold text-[#178ba6] bg-[rgba(31,165,196,0.1)] border border-[rgba(31,165,196,0.35)] px-[10px] py-1 rounded-full">{{ $product->category->name }}</span>
                    </div>
                @endif
                <h1 class="text-[28px] font-bold leading-[1.4] m-0 mb-[14px]">{{ $product->name }}</h1>
                <div class="flex items-baseline gap-[6px]">
                    <span class="text-[34px] font-extrabold text-[#1a1f27]">{{ number_format($product->point) }}</span>
                    <span class="text-[15px] text-[#7b8694]">pt</span>
                </div>
            </div>

            <div class="flex items-center gap-4 flex-wrap">
                @if($product->stock > 0)
                    <div class="flex items-center gap-2">
                        <span class="text-[13px] text-[#4a5566] font-medium">数量</span>
                        <div class="flex items-center border border-[#d4dae1] rounded-[8px] overflow-hidden bg-white">
                            <button type="button" onclick="changeQty(-1)" class="w-[38px] h-10 border-0 bg-transparent text-[#4a5566] text-[18px] cursor-pointer font-sans flex items-center justify-center transition-colors duration-150 hover:bg-[#f2f5f7]" aria-label="減らす">−</button>
                            <input type="number" id="qty-input" value="1" min="1" max="{{ $product->stock }}" class="w-12 text-center text-[15px] font-semibold text-[#1a1f27] border-l border-r border-[#e2e6ea] h-10 border-t-0 border-b-0 outline-none bg-white" readonly>
                            <button type="button" onclick="changeQty(1)" class="w-[38px] h-10 border-0 bg-transparent text-[#4a5566] text-[18px] cursor-pointer font-sans flex items-center justify-center transition-colors duration-150 hover:bg-[#f2f5f7]" aria-label="増やす">＋</button>
                        </div>
                    </div>
                    <div class="flex items-center gap-[6px] text-[13px] text-[#2b7d63]">
                        <span class="w-[7px] h-[7px] rounded-full bg-[#2b9e78] inline-block"></span>
                        在庫 {{ $product->stock }} 点
                    </div>
                @else
                    <div class="flex items-center gap-[6px] text-[13px] text-[#9ba7b4]">
                        <span class="w-[7px] h-[7px] rounded-full bg-[#c8d0d8] inline-block"></span>
                        在庫なし
                    </div>
                @endif
            </div>

            <div class="flex items-baseline gap-2 px-[18px] py-[14px] bg-[#f2f5f7] rounded-[10px]">
                <span class="text-[13px] text-[#4a5566]">合計</span>
                <span id="total-display" class="text-[24px] font-extrabold text-[#1a1f27] ml-auto">{{ number_format($product->point) }}</span>
                <span class="text-[14px] text-[#7b8694]">pt</span>
            </div>

            @if($product->stock > 0)
                <div class="flex gap-3 flex-wrap">
                    <form method="POST" action="{{ route('cart.store') }}" class="flex-1 min-w-[180px]" onsubmit="syncQty(this)">
                        @csrf
                        <input type="hidden" name="product_id" value="{{ $product->id }}">
                        <input type="hidden" name="quantity" value="1">
                        <button type="submit" class="w-full px-6 py-[15px] rounded-[10px] border border-[#2b9e78] bg-[#2b9e78] text-white text-[15px] font-bold cursor-pointer font-sans flex items-center justify-center gap-2 transition-colors duration-150 hover:bg-[#248665] hover:border-[#248665]">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="9" cy="21" r="1"/><circle cx="19" cy="21" r="1"/><path d="M1 1h3l2.4 12.4a2 2 0 0 0 2 1.6h9.2a2 2 0 0 0 2-1.6L21 6H6"/></svg>
                            カートに入れる
                        </button>
                    </form>
                    <form id="buy-now-form" method="POST" action="{{ route('order.buy-now') }}">
                        @csrf
                        <input type="hidden" name="product_id" value="{{ $product->id }}">
                        <input type="hidden" id="buy-now-quantity" name="quantity" value="1">
                        <button
                            type="button"
                            onclick="openBuyNowModal()"
                            class="w-full px-6 py-[15px] rounded-[10px] border-0 bg-[#1fa5c4] text-white text-[15px] font-bold cursor-pointer font-sans flex items-center justify-center gap-2 transition-colors duration-150 hover:bg-[#178ba6]"
                        >
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><path d="M13 2L3 14h7l-1 8 11-14h-7l1-6z"/></svg>
                            今すぐ購入
                        </button>
                    </form>
                </div>
            @else
                <div class="flex gap-3 flex-wrap">
                    <button type="button" disabled class="flex-1 min-w-[180px] px-6 py-[15px] rounded-[10px] border border-[#d4dae1] bg-[#f2f5f7] text-[#b0bac5] text-[15px] font-bold cursor-not-allowed font-sans flex items-center justify-center gap-2">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="9" cy="21" r="1"/><circle cx="19" cy="21" r="1"/><path d="M1 1h3l2.4 12.4a2 2 0 0 0 2 1.6h9.2a2 2 0 0 0 2-1.6L21 6H6"/></svg>
                        カートに入れる
                    </button>
                    <button type="button" disabled class="flex-1 min-w-[180px] px-6 py-[15px] rounded-[10px] border-0 bg-[#f2f5f7] text-[#b0bac5] text-[15px] font-bold cursor-not-allowed font-sans flex items-center justify-center gap-2">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><path d="M13 2L3 14h7l-1 8 11-14h-7l1-6z"/></svg>
                        今すぐ購入
                    </button>
                </div>
                <p class="text-[13px] font-semibold text-[#b0bac5] text-center m-0">この商品は現在売り切れです</p>
            @endif

            <div class="mt-2 px-5 py-[18px] bg-white border border-[#e2e6ea] rounded-[12px] flex flex-col gap-3">
                @if($product->category)
                    <div class="flex justify-between text-[13px]">
                        <span class="text-[#7b8694]">商品カテゴリ</span>
                        <span class="text-[#1a1f27] font-medium">{{ $product->category->name }}</span>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <div class="grid grid-cols-[repeat(auto-fit,minmax(340px,1fr))] gap-12">
        @if($product->description)
            <section>
                <h2 class="text-[16px] font-bold m-0 mb-[18px] pb-[10px] border-b border-[#e2e6ea] flex items-center gap-[10px] tracking-[0.02em]"><span class="w-1 h-4 bg-[#1fa5c4] rounded-[2px] inline-block"></span>商品内容</h2>
                <p class="text-[15px] leading-[2] text-[#3d4757] whitespace-pre-line m-0">{{ $product->description }}</p>
            </section>
        @endif
    </div>
</div>

{{-- 今すぐ購入 確認モーダル --}}
<div id="buy-now-modal" class="hidden fixed inset-0 z-50 flex items-center justify-center px-4" style="background: rgba(0,0,0,0.45);">
    <div class="bg-white rounded-[16px] w-full max-w-[420px] p-8 shadow-xl flex flex-col gap-5">
        <div class="flex flex-col gap-2">
            <h3 class="text-[18px] font-bold m-0 text-[#1a1f27]">購入の確認</h3>
            <p class="text-[14px] font-semibold text-[#1a1f27] m-0">{{ $product->name }}</p>
        </div>
        <div class="flex flex-col gap-[10px] px-4 py-[14px] bg-[#f7fbfc] rounded-[10px] border border-[#e2e6ea]">
            <div class="flex justify-between text-[13px]">
                <span class="text-[#7b8694]">単価</span>
                <span class="font-semibold">{{ number_format($product->point) }} pt</span>
            </div>
            <div class="flex justify-between text-[13px]">
                <span class="text-[#7b8694]">数量</span>
                <span id="modal-qty" class="font-semibold">1</span>
            </div>
            <div class="h-px bg-[#e2e6ea]"></div>
            <div class="flex justify-between">
                <span class="text-[14px] font-bold">合計</span>
                <span id="modal-total" class="text-[18px] font-extrabold text-[#1a1f27]">{{ number_format($product->point) }}<span class="text-[12px] text-[#7b8694] font-medium"> pt</span></span>
            </div>
        </div>
        <p class="text-[12px] text-[#7b8694] m-0">この操作はポイントを消費します。購入後のキャンセルはできません。</p>
        <div class="flex flex-col gap-[10px]">
            <button
                type="button"
                onclick="submitBuyNow()"
                class="w-full py-[13px] rounded-[10px] bg-[#1fa5c4] text-white text-[14px] font-bold border-0 cursor-pointer font-sans transition-colors hover:bg-[#178ba6]"
            >購入する</button>
            <button
                type="button"
                onclick="closeBuyNowModal()"
                class="w-full py-[13px] rounded-[10px] border border-[#d4dae1] text-[#4a5566] text-[14px] font-semibold bg-white cursor-pointer font-sans transition-colors hover:bg-[#f2f5f7]"
            >キャンセル</button>
        </div>
    </div>
</div>

<script>
var unitPrice = {{ $product->point }};
var maxStock  = {{ $product->stock }};
var userPoint = @auth {{ (int) auth()->user()->point }} @else null @endauth;
var pointIndexUrl = "{{ route('point.index') }}";

function changeQty(delta) {
    var input = document.getElementById('qty-input');
    var next = Math.max(1, Math.min(maxStock, parseInt(input.value) + delta));
    input.value = next;
    document.getElementById('total-display').textContent = (unitPrice * next).toLocaleString();
}

function syncQty(form) {
    form.querySelector('input[name="quantity"]').value = document.getElementById('qty-input').value;
}

function openBuyNowModal() {
    var qty = parseInt(document.getElementById('qty-input').value) || 1;
    document.getElementById('modal-qty').textContent = qty;
    document.getElementById('modal-total').innerHTML = (unitPrice * qty).toLocaleString() + '<span class="text-[12px] text-[#7b8694] font-medium"> pt</span>';
    document.getElementById('buy-now-modal').classList.remove('hidden');
}

function closeBuyNowModal() {
    document.getElementById('buy-now-modal').classList.add('hidden');
}

function submitBuyNow() {
    var qty = parseInt(document.getElementById('qty-input').value) || 1;
    if (userPoint !== null && userPoint < unitPrice * qty) {
        window.location.href = pointIndexUrl + '?insufficient=1';
        return;
    }
    document.getElementById('buy-now-quantity').value = qty;
    document.getElementById('buy-now-form').submit();
}

document.getElementById('buy-now-modal').addEventListener('click', function(e) {
    if (e.target === this) closeBuyNowModal();
});
</script>
</x-layout.app>
