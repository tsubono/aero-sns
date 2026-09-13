<x-layout.admin-app title="商品編集">

    <div class="mb-4">
        <a href="{{ route('admin.products.index') }}" class="text-[13px] text-[#7b8694] no-underline hover:text-[#4a5566] flex items-center gap-1">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M15 18l-6-6 6-6"/></svg>
            商品一覧に戻る
        </a>
    </div>

    <div class="bg-white rounded-[12px] border border-[#e2e6ea] p-6">
        <form method="POST" action="{{ route('admin.products.update', $product) }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            @include('admin.products._form-content')

            <div class="flex gap-3 mt-6 pt-5 border-t border-[#eef1f4]">
                <button type="submit" class="px-6 py-[11px] rounded-[9px] bg-[#1fa5c4] text-white text-[14px] font-bold border-0 cursor-pointer font-sans hover:bg-[#178ba6] transition-colors duration-150">更新する</button>
                <a href="{{ route('admin.products.index') }}" class="px-6 py-[11px] rounded-[9px] border border-[#d4dae1] text-[#4a5566] text-[14px] font-semibold no-underline hover:bg-[#f2f5f7] transition-colors duration-150">キャンセル</a>
            </div>
        </form>
    </div>

</x-layout.admin-app>
