<x-layout.admin-app title="売上管理">

    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
        <div class="bg-white rounded-[12px] border border-[#e2e6ea] p-5">
            <p class="text-[11px] font-semibold text-[#7b8694] m-0 mb-2">総注文件数</p>
            <p class="text-[26px] font-extrabold text-[#1a1f27] m-0">{{ number_format($totalOrders) }}<span class="text-[13px] font-semibold text-[#7b8694] ml-1">件</span></p>
        </div>
        <div class="bg-white rounded-[12px] border border-[#e2e6ea] p-5">
            <p class="text-[11px] font-semibold text-[#7b8694] m-0 mb-2">総消費ポイント</p>
            <p class="text-[26px] font-extrabold text-[#1fa5c4] m-0">{{ number_format($totalPoints) }}<span class="text-[13px] font-semibold text-[#7b8694] ml-1">pt</span></p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-5 mb-6">

        <div class="bg-white rounded-[12px] border border-[#e2e6ea] overflow-hidden">
            <div class="px-5 py-4 border-b border-[#eef1f4]">
                <h2 class="text-[14px] font-bold m-0">月別売上（直近12ヶ月）</h2>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-[13px]">
                    <thead>
                        <tr class="bg-[#f7fbfc] border-b border-[#e2e6ea]">
                            <th class="text-left px-4 py-3 font-semibold text-[#7b8694]">月</th>
                            <th class="text-right px-4 py-3 font-semibold text-[#7b8694]">注文件数</th>
                            <th class="text-right px-4 py-3 font-semibold text-[#7b8694]">消費ポイント</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($monthlySales as $sale)
                            <tr class="border-b border-[#eef1f4]">
                                <td class="px-4 py-3 font-semibold">{{ $sale->month }}</td>
                                <td class="px-4 py-3 text-right">{{ number_format($sale->order_count) }} 件</td>
                                <td class="px-4 py-3 text-right font-semibold text-[#1fa5c4]">{{ number_format($sale->monthly_point) }} pt</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="px-4 py-8 text-center text-[#7b8694]">データがありません。</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="bg-white rounded-[12px] border border-[#e2e6ea] overflow-hidden">
            <div class="px-5 py-4 border-b border-[#eef1f4]">
                <h2 class="text-[14px] font-bold m-0">カテゴリ別売上</h2>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-[13px]">
                    <thead>
                        <tr class="bg-[#f7fbfc] border-b border-[#e2e6ea]">
                            <th class="text-left px-4 py-3 font-semibold text-[#7b8694]">カテゴリ</th>
                            <th class="text-right px-4 py-3 font-semibold text-[#7b8694]">注文件数</th>
                            <th class="text-right px-4 py-3 font-semibold text-[#7b8694]">消費ポイント</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($categoryRanking as $item)
                            <tr class="border-b border-[#eef1f4]">
                                <td class="px-4 py-3 font-semibold">{{ $item->category_name }}</td>
                                <td class="px-4 py-3 text-right">{{ number_format($item->order_count) }} 件</td>
                                <td class="px-4 py-3 text-right font-semibold text-[#1fa5c4]">{{ number_format($item->total_point) }} pt</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="px-4 py-8 text-center text-[#7b8694]">データがありません。</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>

    <div class="bg-white rounded-[12px] border border-[#e2e6ea] overflow-hidden">
        <div class="px-5 py-4 border-b border-[#eef1f4]">
            <h2 class="text-[14px] font-bold m-0">最近の注文</h2>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-[13px]">
                <thead>
                    <tr class="bg-[#f7fbfc] border-b border-[#e2e6ea]">
                        <th class="text-left px-4 py-3 font-semibold text-[#7b8694]">注文番号</th>
                        <th class="text-left px-4 py-3 font-semibold text-[#7b8694]">ユーザー</th>
                        <th class="text-left px-4 py-3 font-semibold text-[#7b8694]">商品</th>
                        <th class="text-right px-4 py-3 font-semibold text-[#7b8694] w-28">合計ポイント</th>
                        <th class="text-left px-4 py-3 font-semibold text-[#7b8694] w-24">ステータス</th>
                        <th class="text-left px-4 py-3 font-semibold text-[#7b8694] w-36">注文日</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recentOrders as $order)
                        <tr class="border-b border-[#eef1f4] hover:bg-[#f9fafb] transition-colors duration-100">
                            <td class="px-4 py-3 font-semibold align-top whitespace-nowrap">{{ $order->order_no }}</td>
                            <td class="px-4 py-3 align-top">
                                @if($order->user)
                                    <a href="{{ route('admin.users.show', $order->user) }}" class="text-[#178ba6] no-underline hover:underline whitespace-nowrap">{{ $order->user->name }}</a>
                                @else
                                    <span class="text-[#7b8694]">-</span>
                                @endif
                            </td>
                            <td class="px-4 py-3 align-top">
                                @foreach($order->orderDetails as $detail)
                                    <div class="flex items-center gap-1 {{ !$loop->first ? 'mt-1' : '' }}">
                                        @if($detail->product)
                                            <a href="{{ route('admin.products.edit', $detail->product) }}" class="text-[#178ba6] no-underline hover:underline">{{ $detail->product->name }}</a>
                                        @else
                                            <span class="text-[#7b8694]">削除済み商品</span>
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
                            <td colspan="6" class="px-4 py-8 text-center text-[#7b8694]">注文がありません。</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($recentOrders->hasPages())
            <div class="flex items-center justify-between px-4 py-3 border-t border-[#eef1f4]">
                <span class="text-[12px] text-[#7b8694]">全 {{ $recentOrders->total() }} 件中 {{ $recentOrders->firstItem() }}〜{{ $recentOrders->lastItem() }} 件</span>
                <div class="flex items-center gap-1">
                    @if($recentOrders->onFirstPage())
                        <span class="px-3 py-1 rounded-[6px] border border-[#e2e6ea] text-[#c0c8d2] text-[12px]">前へ</span>
                    @else
                        <a href="{{ $recentOrders->previousPageUrl() }}" class="px-3 py-1 rounded-[6px] border border-[#d4dae1] text-[#4a5566] text-[12px] no-underline hover:bg-[#f2f5f7]">前へ</a>
                    @endif
                    @if($recentOrders->hasMorePages())
                        <a href="{{ $recentOrders->nextPageUrl() }}" class="px-3 py-1 rounded-[6px] border border-[#d4dae1] text-[#4a5566] text-[12px] no-underline hover:bg-[#f2f5f7]">次へ</a>
                    @else
                        <span class="px-3 py-1 rounded-[6px] border border-[#e2e6ea] text-[#c0c8d2] text-[12px]">次へ</span>
                    @endif
                </div>
            </div>
        @endif
    </div>

</x-layout.admin-app>
