<x-layout.admin-app title="ポイントチャージ履歴">

    <div class="grid grid-cols-2 md:grid-cols-3 gap-4 mb-6">
        <div class="bg-white rounded-[12px] border border-[#e2e6ea] p-5">
            <p class="text-[11px] font-semibold text-[#7b8694] m-0 mb-2">総チャージ件数</p>
            <p class="text-[26px] font-extrabold text-[#1a1f27] m-0">{{ number_format($pointCharges->total()) }}<span class="text-[13px] font-semibold text-[#7b8694] ml-1">件</span></p>
        </div>
        <div class="bg-white rounded-[12px] border border-[#e2e6ea] p-5">
            <p class="text-[11px] font-semibold text-[#7b8694] m-0 mb-2">総売上金額</p>
            <p class="text-[26px] font-extrabold text-[#1a1f27] m-0">¥{{ number_format($totalAmount) }}</p>
        </div>
        <div class="bg-white rounded-[12px] border border-[#e2e6ea] p-5">
            <p class="text-[11px] font-semibold text-[#7b8694] m-0 mb-2">総付与ポイント</p>
            <p class="text-[26px] font-extrabold text-[#1fa5c4] m-0">{{ number_format($totalPoint) }}<span class="text-[13px] font-semibold text-[#7b8694] ml-1">pt</span></p>
        </div>
    </div>

    <div class="bg-white rounded-[12px] border border-[#e2e6ea] overflow-hidden">
        <div class="px-5 py-4 border-b border-[#eef1f4]">
            <h2 class="text-[14px] font-bold m-0">チャージ履歴</h2>
        </div>
        <x-admin.point-charge-table :point-charges="$pointCharges" :show-user="true" />
    </div>

</x-layout.admin-app>
