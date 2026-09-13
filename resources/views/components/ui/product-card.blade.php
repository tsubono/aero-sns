@props(['product'])

<a href="{{ route('products.show', $product) }}" {{ $attributes->merge(['class' => 'no-underline text-inherit flex flex-col gap-3 transition-transform duration-[180ms] hover:-translate-y-1']) }}>
    <div class="relative w-full aspect-square rounded-[14px] overflow-hidden bg-[#f2f5f7] border border-[#e2e6ea]">
        @if($product->thumbnail_path)
            <img src="{{ '/storage/' . $product->thumbnail_path }}" alt="{{ $product->name }}" class="w-full h-full object-cover {{ $product->stock === 0 ? 'opacity-50' : '' }}">
        @else
            <div class="aero-img-placeholder {{ $product->stock === 0 ? 'opacity-50' : '' }}">
                <svg width="34" height="34" viewBox="0 0 24 24" fill="none" stroke="#9fbcc6" stroke-width="1.4"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><path d="M21 15l-5-5L5 21"/></svg>
            </div>
        @endif
        @if($product->stock === 0)
            <div class="absolute inset-0 flex items-center justify-center">
                <span class="text-[12px] font-bold text-white bg-[rgba(0,0,0,0.55)] px-3 py-1 rounded-full tracking-wide">SOLD OUT</span>
            </div>
        @endif
    </div>
    <div class="flex flex-col gap-[6px]">
        <span class="text-[11px] font-semibold text-[#178ba6]">{{ $product->category->name ?? '' }}</span>
        <span class="text-[15px] font-semibold leading-[1.5] {{ $product->stock === 0 ? 'text-[#9ba7b4]' : '' }}">{{ $product->name }}</span>
        <span class="text-[15px] font-bold {{ $product->stock === 0 ? 'text-[#b0bac5]' : '' }}">{{ number_format($product->point) }}<span class="text-[12px] text-[#7b8694] font-medium"> pt</span></span>
    </div>
</a>
