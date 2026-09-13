<x-layout.app title="トップ">
<div class="flex-1 font-sans bg-white text-[#1a1f27]">
    <div class="w-full box-border max-w-[1180px] mx-auto px-8 pt-8">
        <form method="GET" action="{{ route('products.index') }}" class="flex items-center gap-3 mb-[14px] flex-wrap">
            <div class="relative flex-1 min-w-[260px] max-w-[420px]">
                <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="#a3adb9" stroke-width="2" class="absolute left-[14px] top-1/2 -translate-y-1/2"><circle cx="11" cy="11" r="7"/><path d="M20 20l-4.3-4.3"/></svg>
                <input type="text" name="keyword" placeholder="キーワードで検索" class="aero-input-search">
            </div>
        </form>
        <div class="flex items-center justify-between gap-4 flex-wrap mb-[18px]">
            <div class="flex gap-2 flex-wrap" id="top-category-filters">
                <button type="button" onclick="filterCategory(null, this)"
                    class="top-cat-btn px-5 py-[10px] rounded-[10px] text-[13px] font-sans border transition-colors duration-150 bg-[#1a1f27] border-[#1a1f27] text-white font-bold">すべて</button>
                @foreach($categories as $category)
                    <button type="button" onclick="filterCategory({{ $category->id }}, this)"
                        class="top-cat-btn px-5 py-[10px] rounded-[10px] text-[13px] font-sans border transition-colors duration-150 bg-white border-[#d4dae1] text-[#4a5566] font-semibold hover:bg-[#f2f5f7] hover:border-[#b9c3cd]">{{ $category->name }}</button>
                @endforeach
            </div>
        </div>
        <div class="h-px bg-[#e2e6ea]"></div>
    </div>

    @foreach($categories as $category)
        <section data-category="{{ $category->id }}" class="w-full box-border max-w-[1180px] mx-auto px-8 pt-10 {{ $loop->last ? 'pb-[100px]' : '' }}">
            <div class="flex items-center justify-between gap-4 mb-[22px] flex-wrap">
                <h2 class="text-[19px] font-bold m-0 flex items-center gap-[10px]">
                    <span class="w-1 h-[17px] bg-[#1fa5c4] rounded-[2px] inline-block"></span>{{ $category->name }}
                </h2>
                @if($productsByCategory[$category->id]->isNotEmpty())
                    <a href="{{ route('products.index', ['category_id' => $category->id]) }}" class="text-[14px] font-semibold no-underline inline-flex items-center gap-[6px]">すべて見る
                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 12h14"/><path d="M13 6l6 6-6 6"/></svg>
                    </a>
                @endif
            </div>
            <div class="relative">
                @if($productsByCategory[$category->id]->isNotEmpty())
                    <button onclick="scrollRail('rail-{{ $category->id }}', -1)" aria-label="前へ" class="absolute left-[-18px] top-[115px] z-[5] w-10 h-10 rounded-full bg-white border border-[#d4dae1] text-[#4a5566] cursor-pointer flex items-center justify-center shadow-[0_4px_14px_rgba(26,31,39,0.14)] transition-colors duration-150 hover:bg-[#1fa5c4] hover:border-[#1fa5c4] hover:text-white">
                        <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M15 6l-6 6 6 6"/></svg>
                    </button>
                    <button onclick="scrollRail('rail-{{ $category->id }}', 1)" aria-label="次へ" class="absolute right-[-18px] top-[115px] z-[5] w-10 h-10 rounded-full bg-white border border-[#d4dae1] text-[#4a5566] cursor-pointer flex items-center justify-center shadow-[0_4px_14px_rgba(26,31,39,0.14)] transition-colors duration-150 hover:bg-[#1fa5c4] hover:border-[#1fa5c4] hover:text-white">
                        <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 6l6 6-6 6"/></svg>
                    </button>
                @endif
                <div id="rail-{{ $category->id }}" class="aero-rail flex gap-[22px] overflow-x-auto pb-[6px]">
                    @forelse($productsByCategory[$category->id] as $product)
                        <x-ui.product-card :product="$product" class="flex-none w-[230px]" />
                    @empty
                        <p class="text-[14px] text-[#7b8694] py-8">商品がありません。</p>
                    @endforelse
                </div>
            </div>
        </section>
    @endforeach
</div>
<script>
function filterCategory(categoryId, btn) {
    document.querySelectorAll('.top-cat-btn').forEach(function(b) {
        b.className = b.className
            .replace('bg-[#1a1f27] border-[#1a1f27] text-white font-bold', '')
            .replace('bg-white border-[#d4dae1] text-[#4a5566] font-semibold hover:bg-[#f2f5f7] hover:border-[#b9c3cd]', '')
            .trim();
        b.className += ' bg-white border-[#d4dae1] text-[#4a5566] font-semibold hover:bg-[#f2f5f7] hover:border-[#b9c3cd]';
    });
    btn.className = btn.className
        .replace('bg-white border-[#d4dae1] text-[#4a5566] font-semibold hover:bg-[#f2f5f7] hover:border-[#b9c3cd]', '')
        .trim();
    btn.className += ' bg-[#1a1f27] border-[#1a1f27] text-white font-bold';

    document.querySelectorAll('section[data-category]').forEach(function(sec) {
        if (categoryId === null || sec.dataset.category == categoryId) {
            sec.hidden = false;
        } else {
            sec.hidden = true;
        }
    });
}

function scrollRail(id, dir) {
    var el = document.getElementById(id);
    if (!el) return;
    var step = Math.max(252, Math.floor(el.clientWidth / 252) * 252);
    el.scrollBy({ left: dir * step, behavior: 'smooth' });
}
</script>
</x-layout.app>
