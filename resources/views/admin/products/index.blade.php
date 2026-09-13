<x-layout.admin-app title="商品管理">

    <div class="flex flex-wrap items-center justify-between gap-3 mb-5">
        <form method="GET" action="{{ route('admin.products.index') }}" class="flex flex-wrap gap-2 flex-1">
            <input type="text" name="keyword" value="{{ request('keyword') }}" placeholder="商品名で検索" class="aero-input w-[200px]">
            <select name="category_id" class="aero-input w-[160px]">
                <option value="">カテゴリ：全て</option>
                @foreach($categories as $category)
                    <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                @endforeach
            </select>
            <button type="submit" class="px-4 py-[10px] rounded-[8px] bg-[#4a5566] text-white text-[13px] font-semibold border-0 cursor-pointer font-sans hover:bg-[#374151] transition-colors duration-150">絞り込む</button>
            @if(request('keyword') || request('category_id'))
                <a href="{{ route('admin.products.index') }}" class="px-4 py-[10px] rounded-[8px] border border-[#d4dae1] bg-white text-[#4a5566] text-[13px] font-semibold no-underline hover:bg-[#f2f5f7] transition-colors duration-150">クリア</a>
            @endif
        </form>
        <a href="{{ route('admin.products.create') }}" class="px-5 py-[10px] rounded-[8px] bg-[#1fa5c4] text-white hover:text-white text-[13px] font-semibold no-underline hover:bg-[#178ba6] transition-colors duration-150 flex items-center gap-2 flex-none">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M12 5v14M5 12h14"/></svg>
            商品を追加
        </a>
    </div>

    <div class="bg-white rounded-[12px] border border-[#e2e6ea] overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-[13px]">
                <thead>
                    <tr class="bg-[#f7fbfc] border-b border-[#e2e6ea]">
                        <th class="text-left px-4 py-3 font-semibold text-[#7b8694] w-12">ID</th>
                        <th class="text-left px-4 py-3 font-semibold text-[#7b8694]">商品名</th>
                        <th class="text-left px-4 py-3 font-semibold text-[#7b8694] w-28">カテゴリ</th>
                        <th class="text-right px-4 py-3 font-semibold text-[#7b8694] w-24">ポイント</th>
                        <th class="text-right px-4 py-3 font-semibold text-[#7b8694] w-20">在庫</th>
                        <th class="text-left px-4 py-3 font-semibold text-[#7b8694] w-36">登録日</th>
                        <th class="text-right px-4 py-3 font-semibold text-[#7b8694] w-28">操作</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($products as $product)
                        <tr class="border-b border-[#eef1f4] hover:bg-[#f9fafb] transition-colors duration-100">
                            <td class="px-4 py-3 text-[#7b8694]">{{ $product->id }}</td>
                            <td class="px-4 py-3 font-semibold">{{ $product->name }}</td>
                            <td class="px-4 py-3 text-[#7b8694]">{{ $product->category->name ?? '-' }}</td>
                            <td class="px-4 py-3 text-right font-semibold">{{ number_format($product->point) }} pt</td>
                            <td class="px-4 py-3 text-right {{ $product->stock === 0 ? 'text-red-500 font-bold' : '' }}">{{ $product->stock }}</td>
                            <td class="px-4 py-3 text-[#7b8694]">{{ $product->created_at->format('Y/m/d') }}</td>
                            <td class="px-4 py-3 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('admin.products.edit', $product) }}" class="px-3 py-1 rounded-[6px] border border-[#d4dae1] text-[#4a5566] text-[12px] font-semibold no-underline hover:bg-[#f2f5f7] transition-colors duration-150 whitespace-nowrap">編集</a>
                                    @if($product->order_details_count > 0)
                                        <button type="button" disabled title="注文が紐づいているため削除できません" class="px-3 py-1 rounded-[6px] border border-[#e2e6ea] text-[#c0c8d2] text-[12px] font-semibold bg-[#f7f9fa] cursor-not-allowed font-sans whitespace-nowrap">削除</button>
                                    @else
                                        <form method="POST" action="{{ route('admin.products.destroy', $product) }}" onsubmit="return confirm('削除してもよいですか？')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="px-3 py-1 rounded-[6px] border border-red-200 text-red-500 text-[12px] font-semibold bg-transparent cursor-pointer font-sans hover:bg-red-50 transition-colors duration-150 whitespace-nowrap">削除</button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-4 py-10 text-center text-[#7b8694]">商品が登録されていません。</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($products->hasPages())
            <div class="flex items-center justify-between px-4 py-3 border-t border-[#eef1f4]">
                <span class="text-[12px] text-[#7b8694]">全 {{ $products->total() }} 件中 {{ $products->firstItem() }}〜{{ $products->lastItem() }} 件</span>
                <div class="flex items-center gap-1">
                    @if($products->onFirstPage())
                        <span class="px-3 py-1 rounded-[6px] border border-[#e2e6ea] text-[#c0c8d2] text-[12px]">前へ</span>
                    @else
                        <a href="{{ $products->previousPageUrl() }}" class="px-3 py-1 rounded-[6px] border border-[#d4dae1] text-[#4a5566] text-[12px] no-underline hover:bg-[#f2f5f7]">前へ</a>
                    @endif
                    @if($products->hasMorePages())
                        <a href="{{ $products->nextPageUrl() }}" class="px-3 py-1 rounded-[6px] border border-[#d4dae1] text-[#4a5566] text-[12px] no-underline hover:bg-[#f2f5f7]">次へ</a>
                    @else
                        <span class="px-3 py-1 rounded-[6px] border border-[#e2e6ea] text-[#c0c8d2] text-[12px]">次へ</span>
                    @endif
                </div>
            </div>
        @endif
    </div>

</x-layout.admin-app>
