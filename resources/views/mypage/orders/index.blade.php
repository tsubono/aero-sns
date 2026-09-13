<x-layout.app title="購入履歴">
<div class="flex-1 w-full box-border max-w-[900px] mx-auto px-8 pt-5 pb-[110px]">
    <div class="text-[13px] text-[#7b8694] flex gap-2 items-center mb-6 flex-wrap">
        <a href="{{ route('top') }}" class="text-[#7b8694] no-underline">ホーム</a>
        <span>/</span>
        <a href="{{ route('mypage') }}" class="text-[#7b8694] no-underline">マイページ</a>
        <span>/</span>
        <span class="text-[#4a5566]">購入履歴</span>
    </div>

    <h1 class="text-[21px] font-bold m-0 mb-[26px] flex items-center gap-[10px]"><span class="w-1 h-[18px] bg-[#1fa5c4] rounded-[2px] inline-block"></span>購入履歴</h1>

    <div class="flex flex-wrap gap-2 mb-[26px]">
        <a href="{{ route('mypage.orders.index') }}"
           class="border rounded-full text-[13px] font-semibold no-underline transition-colors duration-150 px-[18px] py-[9px] {{ !request('category_id') ? 'bg-[#1fa5c4] text-white border-[#1fa5c4]' : 'bg-white text-[#4a5566] border-[#d4dae1] hover:bg-[#f2f5f7] hover:border-[#b9c3cd]' }}">すべて</a>
        @foreach($categories as $category)
            <a href="{{ route('mypage.orders.index', ['category_id' => $category->id]) }}"
               class="border rounded-full text-[13px] font-semibold no-underline transition-colors duration-150 px-[18px] py-[9px] {{ request('category_id') == $category->id ? 'bg-[#1fa5c4] text-white border-[#1fa5c4]' : 'bg-white text-[#4a5566] border-[#d4dae1] hover:bg-[#f2f5f7] hover:border-[#b9c3cd]' }}">{{ $category->name }}</a>
        @endforeach
    </div>

    <div class="flex flex-col gap-[14px]">
        @forelse($orders as $order)
            <x-ui.order-card :order="$order" />
        @empty
            <div class="text-center text-[14px] text-[#7b8694] py-[70px] px-5">該当する購入履歴はありません。</div>
        @endforelse
    </div>

    @if($orders->hasPages())
        <div class="flex items-center justify-center gap-[6px] mt-9">
            @foreach($orders->getUrlRange(1, $orders->lastPage()) as $page => $url)
                @if($page == $orders->currentPage())
                    <span class="w-9 h-9 rounded-[8px] bg-[#1fa5c4] text-white flex items-center justify-center text-[13px] font-bold">{{ $page }}</span>
                @else
                    <a href="{{ $url }}" class="w-9 h-9 rounded-[8px] border border-[#e2e6ea] bg-white text-[#4a5566] flex items-center justify-center text-[13px] font-semibold no-underline hover:border-[#1fa5c4]">{{ $page }}</a>
                @endif
            @endforeach
        </div>
    @endif
</div>
</x-layout.app>
