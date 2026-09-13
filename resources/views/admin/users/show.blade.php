<x-layout.admin-app title="ユーザー詳細">

    <div class="mb-4">
        <a href="{{ route('admin.users.index') }}" class="text-[13px] text-[#7b8694] no-underline hover:text-[#4a5566] flex items-center gap-1">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M15 18l-6-6 6-6"/></svg>
            ユーザー一覧に戻る
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-5 mb-6">

        <div class="bg-white rounded-[12px] border border-[#e2e6ea] p-5 lg:col-span-2">
            <h2 class="text-[14px] font-bold m-0 mb-4 pb-3 border-b border-[#eef1f4]">基本情報</h2>
            <dl class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-[13px]">
                <div class="flex flex-col gap-1">
                    <dt class="text-[11px] font-semibold text-[#7b8694] uppercase tracking-wide">ID</dt>
                    <dd class="font-semibold">{{ $user->id }}</dd>
                </div>
                <div class="flex flex-col gap-1">
                    <dt class="text-[11px] font-semibold text-[#7b8694] uppercase tracking-wide">名前</dt>
                    <dd class="font-semibold">{{ $user->name }}</dd>
                </div>
                <div class="flex flex-col gap-1">
                    <dt class="text-[11px] font-semibold text-[#7b8694] uppercase tracking-wide">メールアドレス</dt>
                    <dd>{{ $user->email }}</dd>
                </div>
                <div class="flex flex-col gap-1">
                    <dt class="text-[11px] font-semibold text-[#7b8694] uppercase tracking-wide">保有ポイント</dt>
                    <dd class="font-bold text-[#1fa5c4]">{{ number_format($user->point) }} pt</dd>
                </div>
                <div class="flex flex-col gap-1">
                    <dt class="text-[11px] font-semibold text-[#7b8694] uppercase tracking-wide">ステータス</dt>
                    <dd>
                        @if($user->status === \App\Enums\UserStatus::Active)
                            <span class="px-2 py-[3px] rounded-full text-[11px] font-bold bg-green-50 text-green-600 border border-green-200">{{ $user->status->label() }}</span>
                        @else
                            <span class="px-2 py-[3px] rounded-full text-[11px] font-bold bg-red-50 text-red-500 border border-red-200">{{ $user->status->label() }}</span>
                        @endif
                    </dd>
                </div>
                <div class="flex flex-col gap-1">
                    <dt class="text-[11px] font-semibold text-[#7b8694] uppercase tracking-wide">登録日</dt>
                    <dd>{{ $user->created_at->format('Y/m/d H:i') }}</dd>
                </div>
            </dl>
        </div>

        <div class="bg-white rounded-[12px] border border-[#e2e6ea] p-5">
            <h2 class="text-[14px] font-bold m-0 mb-4 pb-3 border-b border-[#eef1f4]">ステータス変更</h2>
            <form method="POST" action="{{ route('admin.users.status', $user) }}">
                @csrf
                @method('PUT')
                <div class="flex flex-col gap-3">
                    @foreach(\App\Enums\UserStatus::cases() as $status)
                        <label class="flex items-center gap-2 cursor-pointer text-[13px]">
                            <input type="radio" name="status" value="{{ $status->value }}" {{ $user->status === $status ? 'checked' : '' }} class="accent-[#1fa5c4]">
                            {{ $status->label() }}
                        </label>
                    @endforeach
                    <button type="submit" class="mt-2 px-4 py-[10px] rounded-[8px] bg-[#1fa5c4] text-white text-[13px] font-bold border-0 cursor-pointer font-sans hover:bg-[#178ba6] transition-colors duration-150 whitespace-nowrap">変更を保存</button>
                </div>
            </form>
        </div>

    </div>

    {{-- 購入履歴 --}}
    <div class="bg-white rounded-[12px] border border-[#e2e6ea] overflow-hidden mb-6">
        <div class="px-5 py-4 border-b border-[#eef1f4]">
            <h2 class="text-[14px] font-bold m-0">購入履歴</h2>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-[13px]">
                <thead>
                    <tr class="bg-[#f7fbfc] border-b border-[#e2e6ea]">
                        <th class="text-left px-4 py-3 font-semibold text-[#7b8694]">注文番号</th>
                        <th class="text-left px-4 py-3 font-semibold text-[#7b8694]">商品</th>
                        <th class="text-right px-4 py-3 font-semibold text-[#7b8694] w-28">合計ポイント</th>
                        <th class="text-left px-4 py-3 font-semibold text-[#7b8694] w-24">ステータス</th>
                        <th class="text-left px-4 py-3 font-semibold text-[#7b8694] w-36">注文日</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($orders as $order)
                        <tr class="border-b border-[#eef1f4] hover:bg-[#f9fafb] transition-colors duration-100">
                            <td class="px-4 py-3 font-semibold align-top">{{ $order->order_no }}</td>
                            <td class="px-4 py-3 align-top">
                                @foreach($order->orderDetails as $detail)
                                    <div class="flex items-center gap-2 {{ !$loop->first ? 'mt-1' : '' }}">
                                        @if($detail->product)
                                            <a href="{{ route('admin.products.edit', $detail->product) }}" class="text-[#178ba6] no-underline hover:underline whitespace-nowrap">{{ $detail->product->name }}</a>
                                        @else
                                            <span class="text-[#7b8694]">{{ $detail->product_name ?? '削除済み商品' }}</span>
                                        @endif
                                        <span class="text-[11px] text-[#7b8694]">× {{ $detail->quantity }}</span>
                                    </div>
                                @endforeach
                            </td>
                            <td class="px-4 py-3 text-right font-semibold align-top">{{ number_format($order->total_point) }} pt</td>
                            <td class="px-4 py-3 align-top">
                                <span class="px-2 py-[3px] rounded-full text-[11px] font-bold bg-[#f2f9fb] text-[#178ba6] border border-[#cfe8ef] whitespace-nowrap">{{ $order->status->label() }}</span>
                            </td>
                            <td class="px-4 py-3 text-[#7b8694] align-top whitespace-nowrap">{{ $order->created_at->format('Y/m/d H:i') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-4 py-8 text-center text-[#7b8694]">購入履歴がありません。</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($orders->hasPages())
            <div class="flex items-center justify-between px-4 py-3 border-t border-[#eef1f4]">
                <span class="text-[12px] text-[#7b8694]">全 {{ $orders->total() }} 件中 {{ $orders->firstItem() }}〜{{ $orders->lastItem() }} 件</span>
                <div class="flex items-center gap-1">
                    @if($orders->onFirstPage())
                        <span class="px-3 py-1 rounded-[6px] border border-[#e2e6ea] text-[#c0c8d2] text-[12px]">前へ</span>
                    @else
                        <a href="{{ $orders->previousPageUrl() }}" class="px-3 py-1 rounded-[6px] border border-[#d4dae1] text-[#4a5566] text-[12px] no-underline hover:bg-[#f2f5f7]">前へ</a>
                    @endif
                    @if($orders->hasMorePages())
                        <a href="{{ $orders->nextPageUrl() }}" class="px-3 py-1 rounded-[6px] border border-[#d4dae1] text-[#4a5566] text-[12px] no-underline hover:bg-[#f2f5f7]">次へ</a>
                    @else
                        <span class="px-3 py-1 rounded-[6px] border border-[#e2e6ea] text-[#c0c8d2] text-[12px]">次へ</span>
                    @endif
                </div>
            </div>
        @endif
    </div>

    {{-- ポイントチャージ履歴 --}}
    <div class="bg-white rounded-[12px] border border-[#e2e6ea] overflow-hidden">
        <div class="px-5 py-4 border-b border-[#eef1f4]">
            <h2 class="text-[14px] font-bold m-0">ポイントチャージ履歴</h2>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-[13px]">
                <thead>
                    <tr class="bg-[#f7fbfc] border-b border-[#e2e6ea]">
                        <th class="text-right px-4 py-3 font-semibold text-[#7b8694] w-28">付与ポイント</th>
                        <th class="text-right px-4 py-3 font-semibold text-[#7b8694] w-28">決済金額</th>
                        <th class="text-left px-4 py-3 font-semibold text-[#7b8694] w-28">決済方法</th>
                        <th class="text-left px-4 py-3 font-semibold text-[#7b8694] w-24">ステータス</th>
                        <th class="text-left px-4 py-3 font-semibold text-[#7b8694] w-36">チャージ日時</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($pointCharges as $charge)
                        <tr class="border-b border-[#eef1f4] hover:bg-[#f9fafb] transition-colors duration-100">
                            <td class="px-4 py-3 text-right font-bold text-[#1fa5c4]">+{{ number_format($charge->point) }} pt</td>
                            <td class="px-4 py-3 text-right">¥{{ number_format($charge->amount) }}</td>
                            <td class="px-4 py-3 text-[#7b8694]">{{ $charge->payment_method }}</td>
                            <td class="px-4 py-3">
                                @if($charge->status === \App\Enums\PointChargeStatus::Completed)
                                    <span class="px-2 py-[3px] rounded-full text-[11px] font-bold bg-green-50 text-green-600 border border-green-200 whitespace-nowrap">{{ $charge->status->label() }}</span>
                                @elseif($charge->status === \App\Enums\PointChargeStatus::Failed)
                                    <span class="px-2 py-[3px] rounded-full text-[11px] font-bold bg-red-50 text-red-500 border border-red-200 whitespace-nowrap">{{ $charge->status->label() }}</span>
                                @else
                                    <span class="px-2 py-[3px] rounded-full text-[11px] font-bold bg-yellow-50 text-yellow-600 border border-yellow-200 whitespace-nowrap">{{ $charge->status->label() }}</span>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-[#7b8694] whitespace-nowrap">{{ $charge->charged_at ? $charge->charged_at->format('Y/m/d H:i') : '-' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-4 py-8 text-center text-[#7b8694]">チャージ履歴がありません。</td>
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
    </div>

</x-layout.admin-app>
