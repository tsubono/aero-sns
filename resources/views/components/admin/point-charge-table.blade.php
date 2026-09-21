@props(['pointCharges', 'showUser' => false])

<div class="overflow-x-auto">
    <table class="w-full text-[13px]">
        <thead>
            <tr class="bg-[#f7fbfc] border-b border-[#e2e6ea]">
                <th class="text-left px-4 py-3 font-semibold text-[#7b8694]">ID</th>
                @if($showUser)
                    <th class="text-left px-4 py-3 font-semibold text-[#7b8694]">ユーザー</th>
                @endif
                <th class="text-right px-4 py-3 font-semibold text-[#7b8694]">金額</th>
                <th class="text-right px-4 py-3 font-semibold text-[#7b8694]">ポイント</th>
                <th class="text-left px-4 py-3 font-semibold text-[#7b8694]">ステータス</th>
                <th class="text-left px-4 py-3 font-semibold text-[#7b8694]">取引ID (Stripe)</th>
                <th class="text-left px-4 py-3 font-semibold text-[#7b8694] w-36">チャージ日時</th>
            </tr>
        </thead>
        <tbody>
            @forelse($pointCharges as $charge)
                <tr class="border-b border-[#eef1f4] hover:bg-[#f9fafb] transition-colors duration-100">
                    <td class="px-4 py-3 text-[#7b8694]">{{ $charge->id }}</td>
                    @if($showUser)
                        <td class="px-4 py-3">
                            @if($charge->user)
                                <a href="{{ route('admin.users.show', $charge->user) }}" class="text-[#178ba6] no-underline hover:underline whitespace-nowrap">{{ $charge->user->name }}</a>
                            @else
                                <span class="text-[#7b8694]">削除済みユーザー</span>
                            @endif
                        </td>
                    @endif
                    <td class="px-4 py-3 text-right font-semibold">¥{{ number_format($charge->amount) }}</td>
                    <td class="px-4 py-3 text-right font-semibold text-[#1fa5c4]">+{{ number_format($charge->point) }} pt</td>
                    <td class="px-4 py-3">
                        @php
                            $statusColor = match($charge->status) {
                                \App\Enums\PointChargeStatus::Completed  => 'bg-[#f0faf6] text-[#2b9e78] border-[#b6e6d4]',
                                \App\Enums\PointChargeStatus::Processing => 'bg-[#fffbea] text-[#b59a00] border-[#ffe58f]',
                                \App\Enums\PointChargeStatus::Failed     => 'bg-[#fff1f0] text-[#cf1322] border-[#ffa39e]',
                            };
                        @endphp
                        <span class="px-2 py-[3px] rounded-full text-[11px] font-bold border whitespace-nowrap {{ $statusColor }}">{{ $charge->status->label() }}</span>
                    </td>
                    <td class="px-4 py-3">
                        @if($charge->transaction_id)
                            <span class="font-mono text-[11px] text-[#4a5566] break-all">{{ $charge->transaction_id }}</span>
                        @else
                            <span class="text-[#c0c8d2]">—</span>
                        @endif
                    </td>
                    <td class="px-4 py-3 text-[#7b8694] whitespace-nowrap">
                        {{ $charge->charged_at?->format('Y/m/d H:i') ?? '—' }}
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="{{ $showUser ? 7 : 6 }}" class="px-4 py-8 text-center text-[#7b8694]">チャージ履歴がありません。</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

@if($pointCharges->hasPages())
    <div class="flex items-center justify-between px-4 py-3 border-t border-[#eef1f4]">
        <span class="text-[12px] text-[#7b8694]">全 {{ $pointCharges->total() }} 件中 {{ $pointCharges->firstItem() }}〜{{ $pointCharges->lastItem() }} 件</span>
        <div class="flex items-center gap-1">
            @if($pointCharges->onFirstPage())
                <span class="px-3 py-1 rounded-[6px] border border-[#e2e6ea] text-[#c0c8d2] text-[12px]">前へ</span>
            @else
                <a href="{{ $pointCharges->previousPageUrl() }}" class="px-3 py-1 rounded-[6px] border border-[#d4dae1] text-[#4a5566] text-[12px] no-underline hover:bg-[#f2f5f7]">前へ</a>
            @endif
            @if($pointCharges->hasMorePages())
                <a href="{{ $pointCharges->nextPageUrl() }}" class="px-3 py-1 rounded-[6px] border border-[#d4dae1] text-[#4a5566] text-[12px] no-underline hover:bg-[#f2f5f7]">次へ</a>
            @else
                <span class="px-3 py-1 rounded-[6px] border border-[#e2e6ea] text-[#c0c8d2] text-[12px]">次へ</span>
            @endif
        </div>
    </div>
@endif
