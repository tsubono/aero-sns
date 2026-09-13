<x-layout.app title="ポイントチャージ">
<div class="flex-1 w-full box-border max-w-[760px] mx-auto px-8 pt-5 pb-[110px]">
    <div class="text-[13px] text-[#7b8694] flex gap-2 items-center mb-6">
        <a href="{{ route('top') }}" class="text-[#7b8694] no-underline">ホーム</a>
        <span>/</span>
        <a href="{{ route('mypage') }}" class="text-[#7b8694] no-underline">マイページ</a>
        <span>/</span>
        <span class="text-[#4a5566]">ポイントチャージ</span>
    </div>

    <h1 class="text-[21px] font-bold m-0 mb-[26px] flex items-center gap-[10px]"><span class="w-1 h-[18px] bg-[#1fa5c4] rounded-[2px] inline-block"></span>ポイントチャージ</h1>

    @if(session('success'))
        <div class="mb-6 px-4 py-3 rounded-[10px] bg-[rgba(43,158,120,0.08)] border border-[rgba(43,158,120,0.25)] text-[14px] text-[#2b9e78] font-semibold">{{ session('success') }}</div>
    @endif

    <div class="flex items-center justify-between gap-5 px-[26px] py-5 rounded-[14px] bg-[#f2f9fb] border border-[#cfe8ef] mb-8">
        <div class="flex flex-col gap-[5px]">
            <span class="text-[12px] font-semibold text-[#178ba6]">現在の保有ポイント</span>
            <span class="text-[28px] font-extrabold leading-none">{{ number_format(auth()->user()->point) }}<span class="text-[14px] font-semibold text-[#4a5566]"> pt</span></span>
        </div>
    </div>

    <form method="POST" action="{{ route('point.store') }}" id="charge-form">
        @csrf

        <h2 class="text-[16px] font-bold m-0 mb-4">チャージ金額を選択</h2>

        <div class="grid grid-cols-[repeat(auto-fill,minmax(min(180px,calc(50%-9px)),1fr))] gap-[12px] mb-8">
            @foreach($pointPlans as $plan)
                <label class="block cursor-pointer">
                    <input type="radio" name="plan_id" value="{{ $plan['id'] }}" data-pt="{{ $plan['point'] }}" data-price="{{ $plan['amount'] }}"
                        {{ old('plan_id') == $plan['id'] ? 'checked' : '' }}
                        onchange="updateSummary(this)"
                        class="sr-only peer">
                    <div class="flex flex-col items-center gap-[6px] p-5 border-2 rounded-[14px] transition-all duration-150 peer-checked:border-[#1fa5c4] peer-checked:bg-[rgba(31,165,196,0.05)] peer-checked:ring-2 peer-checked:ring-[#1fa5c4] peer-checked:ring-opacity-30 border-[#e2e6ea] bg-white hover:border-[#1fa5c4]">
                        <span class="text-[11px] font-semibold text-[#7b8694]">¥ {{ number_format($plan['amount']) }}</span>
                        <span class="text-[26px] font-extrabold leading-none">{{ number_format($plan['point']) }}</span>
                        <span class="text-[13px] font-semibold text-[#4a5566]">pt</span>
                    </div>
                </label>
            @endforeach
        </div>

        <div class="border border-[#e2e6ea] rounded-[14px] p-5 flex flex-col gap-[14px] mb-8">
            <h3 class="text-[14px] font-bold m-0">お支払い方法</h3>
            <div class="flex items-center gap-[14px] p-4 border border-[#1fa5c4] bg-[rgba(31,165,196,0.04)] rounded-[12px]">
                <span class="w-5 h-5 rounded-full border-[6px] border-[#1fa5c4] box-border flex-none"></span>
                <div class="flex-1">
                    <div class="text-[14px] font-bold">クレジットカード（VISA ****1234）</div>
                    <div class="text-[12px] text-[#7b8694] mt-[3px]">登録済みカード</div>
                </div>
            </div>
        </div>

        <div class="border border-[#e2e6ea] rounded-[14px] p-5 flex flex-col gap-[12px] mb-8">
            <div class="flex justify-between text-[14px]">
                <span class="text-[#7b8694]">チャージ金額</span>
                <span id="summary-price" class="font-semibold">未選択</span>
            </div>
            <div class="flex justify-between text-[14px]">
                <span class="text-[#7b8694]">付与ポイント</span>
                <span id="summary-pt" class="font-semibold text-[#1fa5c4]">—</span>
            </div>
            <div class="h-px bg-[#eef1f4]"></div>
            <div class="flex justify-between items-baseline">
                <span class="text-[14px] font-bold">チャージ後の残高</span>
                <span id="summary-after" class="text-[20px] font-extrabold text-[#1fa5c4]">{{ number_format(auth()->user()->point) }} pt</span>
            </div>
        </div>

        <button type="submit" id="charge-btn" disabled
            class="w-full py-[15px] rounded-[10px] text-white text-[15px] font-bold text-center transition-colors duration-150 border-0 font-sans bg-[#c8d0d9] cursor-not-allowed">
            ポイントをチャージする
        </button>
    </form>
</div>
<script>
var balance = {{ auth()->user()->point }};

function updateSummary(radio) {
    var pt    = parseInt(radio.dataset.pt);
    var price = parseInt(radio.dataset.price);
    document.getElementById('summary-price').textContent = '¥ ' + price.toLocaleString();
    document.getElementById('summary-pt').textContent    = pt.toLocaleString() + ' pt';
    document.getElementById('summary-after').textContent = (balance + pt).toLocaleString() + ' pt';
    var btn = document.getElementById('charge-btn');
    btn.disabled = false;
    btn.classList.remove('bg-[#c8d0d9]', 'cursor-not-allowed');
    btn.classList.add('bg-[#1fa5c4]', 'hover:bg-[#178ba6]', 'cursor-pointer');
}
</script>
</x-layout.app>
