<x-layout.app title="商品一覧">
<div class="flex-1 w-full box-border max-w-[1180px] mx-auto px-8 pt-5 pb-[110px]">
    <div class="text-[13px] text-[#7b8694] flex gap-2 items-center mb-6">
        <a href="{{ route('top') }}" class="text-[#7b8694] no-underline">ホーム</a>
        <span>/</span>
        <span class="text-[#4a5566]">商品一覧</span>
    </div>

    <h1 class="text-[26px] font-bold m-0 mb-[26px] flex items-center gap-[10px]"><span class="w-1 h-[22px] bg-[#1fa5c4] rounded-[2px] inline-block"></span>商品一覧</h1>

    <form method="GET" action="{{ route('products.index') }}" id="filter-form" class="mb-[14px]">
        <input type="hidden" name="category_id" value="{{ request('category_id') }}">
        <input type="hidden" name="sort" value="{{ request('sort', 'recommend') }}">
        <div class="relative flex-1 min-w-[260px] max-w-[420px]">
            <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="#a3adb9" stroke-width="2" class="absolute left-[14px] top-1/2 -translate-y-1/2"><circle cx="11" cy="11" r="7"/><path d="M20 20l-4.3-4.3"/></svg>
            <input type="text" name="keyword" value="{{ request('keyword') }}" placeholder="キーワードで検索" class="aero-input-search">
        </div>
    </form>

    <div class="flex items-center justify-between gap-4 flex-wrap mb-[18px]">
        <div class="flex gap-2 flex-wrap">
            <a href="{{ route('products.index', array_filter(['keyword' => request('keyword'), 'sort' => request('sort')])) }}"
               class="{{ !request('category_id') ? 'bg-[#1a1f27] border-[#1a1f27] text-white font-bold' : 'bg-white border-[#d4dae1] text-[#4a5566] font-semibold hover:bg-[#f2f5f7] hover:border-[#b9c3cd]' }} px-5 py-[10px] rounded-[10px] text-[13px] font-sans border transition-colors duration-150 no-underline">すべて</a>
            @foreach($categories as $category)
                <a href="{{ route('products.index', array_filter(['keyword' => request('keyword'), 'category_id' => $category->id, 'sort' => request('sort')])) }}"
                   class="{{ request('category_id') == $category->id ? 'bg-[#1a1f27] border-[#1a1f27] text-white font-bold' : 'bg-white border-[#d4dae1] text-[#4a5566] font-semibold hover:bg-[#f2f5f7] hover:border-[#b9c3cd]' }} px-5 py-[10px] rounded-[10px] text-[13px] font-sans border transition-colors duration-150 no-underline">{{ $category->name }}</a>
            @endforeach
        </div>

        <div class="flex gap-2 flex-wrap justify-end">
            @foreach([['key' => 'recommend', 'label' => 'おすすめ順'], ['key' => 'new', 'label' => '新着順'], ['key' => 'price', 'label' => '価格順']] as $sortOption)
                <a href="{{ route('products.index', array_filter(['keyword' => request('keyword'), 'category_id' => request('category_id'), 'sort' => $sortOption['key']])) }}"
                   class="{{ request('sort', 'recommend') === $sortOption['key'] ? 'bg-[#1fa5c4] border-[#1fa5c4] text-white' : 'bg-white border-[#d4dae1] text-[#4a5566] hover:bg-[#f2f5f7] hover:border-[#b9c3cd]' }} px-[18px] py-[9px] rounded-full text-[13px] font-semibold font-sans border transition-colors duration-150 no-underline">{{ $sortOption['label'] }}</a>
            @endforeach
        </div>
    </div>

    <div class="h-px bg-[#e2e6ea] mb-8"></div>

    <div class="grid grid-cols-[repeat(auto-fill,minmax(min(230px,calc(50%-13px)),1fr))] gap-[26px]">
        @forelse($products as $product)
            <x-ui.product-card :product="$product" />
        @empty
            <div class="col-span-full py-[70px] text-center text-[#7b8694] text-[14px]">該当する商品が見つかりませんでした。</div>
        @endforelse
    </div>

    @if($products->hasPages())
        <div class="flex items-center justify-center gap-2 mt-14 flex-wrap">
            @if($products->previousPageUrl())
                <a href="{{ $products->previousPageUrl() }}" class="w-[38px] h-[38px] rounded-[8px] border border-[#d4dae1] bg-white text-[#4a5566] flex items-center justify-center transition-colors duration-150 hover:bg-[#f2f5f7]" aria-label="前のページ">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M15 6l-6 6 6 6"/></svg>
                </a>
            @endif
            @foreach($products->getUrlRange(1, $products->lastPage()) as $page => $url)
                @if($page == $products->currentPage())
                    <span class="min-w-[38px] h-[38px] px-3 rounded-[8px] border border-[#1fa5c4] bg-[#1fa5c4] text-white text-[14px] font-bold flex items-center justify-center">{{ $page }}</span>
                @else
                    <a href="{{ $url }}" class="min-w-[38px] h-[38px] px-3 rounded-[8px] border border-[#d4dae1] bg-white text-[#4a5566] text-[14px] font-semibold flex items-center justify-center transition-colors duration-150 hover:bg-[#f2f5f7] hover:border-[#b9c3cd]">{{ $page }}</a>
                @endif
            @endforeach
            @if($products->nextPageUrl())
                <a href="{{ $products->nextPageUrl() }}" class="w-[38px] h-[38px] rounded-[8px] border border-[#d4dae1] bg-white text-[#4a5566] flex items-center justify-center transition-colors duration-150 hover:bg-[#f2f5f7]" aria-label="次のページ">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 6l6 6-6 6"/></svg>
                </a>
            @endif
        </div>
    @endif
</div>
</x-layout.app>
