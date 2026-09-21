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
    @if(session('error'))
        <div class="mb-6 px-4 py-3 rounded-[10px] bg-[rgba(207,19,34,0.06)] border border-[rgba(207,19,34,0.2)] text-[14px] text-[#cf1322] font-semibold">{{ session('error') }}</div>
    @endif

    <div class="flex items-center justify-between gap-5 px-[26px] py-5 rounded-[14px] bg-[#f2f9fb] border border-[#cfe8ef] mb-8">
        <div class="flex flex-col gap-[5px]">
            <span class="text-[12px] font-semibold text-[#178ba6]">現在の保有ポイント</span>
            <span class="text-[28px] font-extrabold leading-none">{{ number_format(auth()->user()->point) }}<span class="text-[14px] font-semibold text-[#4a5566]"> pt</span></span>
        </div>
    </div>

    {{-- submitはJSで実行 --}}
    <form id="charge-form">
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

        {{-- お支払い方法 --}}
        <div class="border border-[#e2e6ea] rounded-[14px] p-5 flex flex-col gap-[14px] mb-8">
            <h3 class="text-[14px] font-bold m-0">お支払い方法</h3>

            @if($savedCards)
                @foreach($savedCards as $card)
                    <div class="flex items-center gap-2">
                        <label class="flex-1 flex items-center gap-[14px] p-4 border rounded-[12px] cursor-pointer has-[:checked]:border-[#1fa5c4] has-[:checked]:bg-[rgba(31,165,196,0.04)] border-[#e2e6ea]">
                            <input type="radio" name="payment_type" value="saved_{{ $card->id }}"
                                data-pm-id="{{ $card->id }}"
                                onchange="selectPaymentType(this)"
                                class="sr-only peer">
                            <span class="w-5 h-5 rounded-full border-2 border-[#e2e6ea] peer-checked:border-[6px] peer-checked:border-[#1fa5c4] flex-none box-border transition-all duration-150"></span>
                            <div class="flex-1">
                                <div class="text-[14px] font-bold">
                                    {{ strtoupper($card->card->brand) }} ****{{ $card->card->last4 }}
                                </div>
                                <div class="text-[12px] text-[#7b8694] mt-[3px]">
                                    有効期限 {{ str_pad($card->card->exp_month, 2, '0', STR_PAD_LEFT) }}/{{ $card->card->exp_year }}
                                </div>
                            </div>
                        </label>
                        <button type="button"
                            onclick="openDeleteModal('{{ route('point.payment-methods.destroy', $card->id) }}', '{{ strtoupper($card->card->brand) }} ****{{ $card->card->last4 }}')"
                            class="flex-none p-2 rounded-[8px] text-[#7b8694] hover:text-[#cf1322] hover:bg-[#fff1f0] transition-colors duration-150 bg-transparent border-0 cursor-pointer"
                            title="カードを削除">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/><path d="M10 11v6M14 11v6"/><path d="M9 6V4a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v2"/></svg>
                        </button>
                    </div>
                @endforeach
                <label class="flex items-center gap-[14px] p-4 border rounded-[12px] cursor-pointer has-[:checked]:border-[#1fa5c4] has-[:checked]:bg-[rgba(31,165,196,0.04)] border-[#e2e6ea]">
                    <input type="radio" name="payment_type" value="new_card"
                        onchange="selectPaymentType(this)"
                        class="sr-only peer">
                    <span class="w-5 h-5 rounded-full border-2 border-[#e2e6ea] peer-checked:border-[6px] peer-checked:border-[#1fa5c4] flex-none box-border transition-all duration-150"></span>
                    <span class="text-[14px] font-semibold">新しいカードを使用する</span>
                </label>
            @else
                {{-- カード未登録: 常に新規入力 --}}
                <input type="hidden" name="payment_type" id="payment-type-hidden" value="new_card">
            @endif

            {{-- Stripeパラメータ --}}
            <div id="new-card-section" class="{{ $savedCards ? 'hidden' : '' }}">
                <div id="card-element" class="p-4 border border-[#cfd7df] rounded-[10px] bg-white min-h-[44px]"></div>
                <div id="card-errors" class="mt-2 text-[13px] text-[#cf1322]" role="alert"></div>
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

        <div id="charge-error" class="hidden mb-4 px-4 py-3 rounded-[10px] bg-[#cf1322] text-white text-[14px] font-semibold flex items-center gap-3">
            <svg class="flex-none" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
            <span id="charge-error-text"></span>
        </div>

        <button type="submit" id="charge-btn" disabled
            class="w-full py-[15px] rounded-[10px] text-white text-[15px] font-bold text-center transition-colors duration-150 border-0 font-sans bg-[#c8d0d9] cursor-not-allowed">
            ポイントをチャージする
        </button>
    </form>
</div>

<x-confirm-modal id="delete-card-modal" title="カードを削除しますか？">
    <p id="delete-modal-card" class="text-[13px] text-[#7b8694] m-0"></p>
    <div class="flex gap-3">
        <button type="button" onclick="closeModal('delete-card-modal')"
            class="flex-1 py-[11px] rounded-[10px] border border-[#d4dae1] text-[#4a5566] text-[14px] font-semibold bg-white hover:bg-[#f2f5f7] transition-colors duration-150 cursor-pointer font-sans">
            キャンセル
        </button>
        <button type="button" onclick="submitDeleteForm()"
            class="flex-1 py-[11px] rounded-[10px] bg-[#cf1322] text-white text-[14px] font-bold border-0 hover:bg-[#a50e1a] transition-colors duration-150 cursor-pointer font-sans">
            削除する
        </button>
    </div>
</x-confirm-modal>

<form id="delete-card-form" method="POST">
    @csrf
    @method('DELETE')
</form>

<script src="https://js.stripe.com/v3/"></script>
<script>
    var balance = {{ auth()->user()->point }};
    var selectedPlan = null;
    var selectedPmId = null; // 保存済みカードのpayment_method_id
    var stripe = Stripe('{{ config('services.stripe.key') }}');
    var elements = stripe.elements();
    var hasSavedCards = {{ $savedCards ? 'true' : 'false' }};

    var cardElement = elements.create('card', {
        style: {
            base: {
                fontSize: '15px',
                color: '#1a1f27',
                fontFamily: '"Noto Sans JP", sans-serif',
                '::placeholder': {color: '#aab4be'},
            },
            invalid: {color: '#cf1322'},
        },
        hidePostalCode: true,
    });

    if (!hasSavedCards) {
        cardElement.mount('#card-element');
    }

    cardElement.on('change', function (event) {
        document.getElementById('card-errors').textContent = event.error ? event.error.message : '';
    });

    function updateSummary(radio) {
        selectedPlan = {
            id: parseInt(radio.value),
            pt: parseInt(radio.dataset.pt),
            price: parseInt(radio.dataset.price)
        };
        document.getElementById('summary-price').textContent = '¥ ' + selectedPlan.price.toLocaleString();
        document.getElementById('summary-pt').textContent = selectedPlan.pt.toLocaleString() + ' pt';
        document.getElementById('summary-after').textContent = (balance + selectedPlan.pt).toLocaleString() + ' pt';
        refreshBtn();
    }

    function selectPaymentType(radio) {
        var isNew = (radio.value === 'new_card');
        document.getElementById('new-card-section').classList.toggle('hidden', !isNew);

        if (isNew) {
            cardElement.mount('#card-element');
            selectedPmId = null;
        } else {
            selectedPmId = radio.dataset.pmId;
        }

        refreshBtn();
    }

    function refreshBtn() {
        var planSelected = !!selectedPlan;
        var pmSelected = hasSavedCards
            ? (selectedPmId !== null || document.querySelector('input[name="payment_type"]:checked')?.value === 'new_card')
            : true;
        var btn = document.getElementById('charge-btn');
        if (planSelected && pmSelected) {
            btn.disabled = false;
            btn.classList.remove('bg-[#c8d0d9]', 'cursor-not-allowed');
            btn.classList.add('bg-[#1fa5c4]', 'hover:bg-[#178ba6]', 'cursor-pointer');
        } else {
            btn.disabled = true;
            btn.classList.add('bg-[#c8d0d9]', 'cursor-not-allowed');
            btn.classList.remove('bg-[#1fa5c4]', 'hover:bg-[#178ba6]', 'cursor-pointer');
        }
    }

    // 保存済みカードがある場合、最初の選択状態でrefreshBtn
    @if($savedCards)
    (function () {
        var checked = document.querySelector('input[name="payment_type"]:checked');
        if (checked) selectPaymentType(checked);
    })();
    @endif

    // フォーム送信処理
    document.getElementById('charge-form').addEventListener('submit', async function (e) {
        e.preventDefault();

        if (!selectedPlan) return;

        var btn = document.getElementById('charge-btn');
        var errDiv = document.getElementById('charge-error');
        btn.disabled = true;
        btn.textContent = '処理中...';
        errDiv.classList.add('hidden');

        var csrfToken = document.querySelector('input[name="_token"]').value;

        // 1. AjaxでPaymentIntent作成
        var intentRes = await fetch('{{ route('point.intent') }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
            },
            body: JSON.stringify({plan_id: selectedPlan.id}),
        });

        var intentData = await intentRes.json();

        if (!intentRes.ok || intentData.error) {
            showError(intentData.error || '決済処理の開始に失敗しました。');
            return;
        }

        // 2. Stripe決済確認
        var confirmParams = {};
        if (selectedPmId) {
            confirmParams = {payment_method: selectedPmId};
        } else {
            confirmParams = {payment_method: {card: cardElement}};
        }

        var {paymentIntent, error} = await stripe.confirmCardPayment(
            intentData.client_secret,
            confirmParams
        );

        if (error) {
            showError(error.message);
            return;
        }

        if (paymentIntent.status !== 'succeeded') {
            showError('決済が完了しませんでした。');
            return;
        }

        // 3. バックエンドで確定処理
        var form = document.createElement('form');
        form.method = 'POST';
        form.action = '{{ route('point.store') }}';
        form.innerHTML =
            '<input type="hidden" name="_token" value="' + csrfToken + '">' +
            '<input type="hidden" name="plan_id" value="' + selectedPlan.id + '">' +
            '<input type="hidden" name="payment_intent_id" value="' + paymentIntent.id + '">';
        document.body.appendChild(form);
        form.submit();
    });

    function openDeleteModal(action, cardLabel) {
        document.getElementById('delete-card-form').action = action;
        document.getElementById('delete-modal-card').textContent = cardLabel;
        openModal('delete-card-modal');
    }

    function submitDeleteForm() {
        document.getElementById('delete-card-form').submit();
    }

    function showError(msg) {
        var btn = document.getElementById('charge-btn');
        var errDiv = document.getElementById('charge-error');
        btn.disabled = false;
        btn.textContent = 'ポイントをチャージする';
        btn.classList.remove('bg-[#c8d0d9]', 'cursor-not-allowed');
        btn.classList.add('bg-[#1fa5c4]', 'hover:bg-[#178ba6]', 'cursor-pointer');
        document.getElementById('charge-error-text').textContent = msg;
        errDiv.classList.remove('hidden');
    }
</script>
</x-layout.app>
