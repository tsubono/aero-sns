<div class="flex flex-col gap-5">

    <label class="flex flex-col gap-[6px]">
        <span class="text-[13px] font-semibold text-[#4a5566]">商品名 <span class="text-red-500">*</span></span>
        <input type="text" name="name" value="{{ old('name', $product->name ?? '') }}" placeholder="商品名を入力" class="aero-input">
        @error('name')
            <span class="text-[12px] text-red-500">{{ $message }}</span>
        @enderror
    </label>

    <label class="flex flex-col gap-[6px]">
        <span class="text-[13px] font-semibold text-[#4a5566]">カテゴリ <span class="text-red-500">*</span></span>
        <select name="category_id" class="aero-input">
            <option value="">選択してください</option>
            @foreach($categories as $category)
                <option value="{{ $category->id }}" {{ old('category_id', $product->category_id ?? '') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
            @endforeach
        </select>
        @error('category_id')
            <span class="text-[12px] text-red-500">{{ $message }}</span>
        @enderror
    </label>

    <div class="flex flex-col gap-[6px]">
        <span class="text-[13px] font-semibold text-[#4a5566]">サムネイル画像</span>
        @if(!empty($product->thumbnail_path))
            <div class="mb-2">
                <img src="{{ '/storage/' . $product->thumbnail_path }}" alt="現在のサムネイル" class="w-24 h-24 object-cover rounded-[8px] border border-[#e2e6ea]">
                <p class="text-[11px] text-[#7b8694] mt-1">現在の画像。新しい画像を選択すると上書きされます。</p>
            </div>
        @endif
        <input type="file" name="thumbnail" accept="image/*" class="block text-[13px] text-[#4a5566] file:mr-3 file:py-[8px] file:px-4 file:rounded-[7px] file:border file:border-[#d4dae1] file:text-[12px] file:font-semibold file:bg-white file:text-[#4a5566] hover:file:bg-[#f2f5f7] file:cursor-pointer file:transition-colors">
        <span class="text-[11px] text-[#7b8694]">JPG / PNG / GIF / WebP（5MB以内）</span>
        @error('thumbnail')
            <span class="text-[12px] text-red-500">{{ $message }}</span>
        @enderror
    </div>

    <div class="flex flex-col gap-[6px]">
        <span class="text-[13px] font-semibold text-[#4a5566]">商品内容</span>
        <textarea name="description" rows="4" placeholder="商品の説明を入力" class="aero-input resize-y">{{ old('description', $product->description ?? '') }}</textarea>
        @error('description')
            <span class="text-[12px] text-red-500">{{ $message }}</span>
        @enderror
    </div>

    {{-- アカウント情報リスト --}}
    <div
        class="flex flex-col gap-[6px]"
        x-data="{
            items: @js(
                collect(old('account_texts', []))
                    ->filter(fn($v) => isset($v['text']) && trim($v['text']) !== '')
                    ->values()
                    ->toArray()
                ?: (isset($product) && $product->relationLoaded('productAccounts')
                    ? $product->productAccounts->map(fn($a) => ['id' => $a->id, 'text' => $a->account_text, 'used' => $a->is_used])->values()->toArray()
                    : [])
            ),
            bulkText: '',
            bulkErrors: [],
            removeItem(index) {
                this.items.splice(index, 1);
            },
            parseText(text) {
                if (!text.trim()) return [];
                const delim = text.includes('|') ? '|' : ':';
                const parts = text.split(delim);
                const l6 = ['ID','PW','メール','メールPW','2FA','AuthToken'];
                const l7 = ['ID','PW','メール','メールPW','電話','2FA','AuthToken'];
                const labels = parts.length >= 7 ? l7 : l6;
                return parts.map((v, i) => ({ label: labels[i] || ('項目'+(i+1)), value: v }));
            },
            bulkAdd() {
                this.bulkErrors = [];
                const lines = this.bulkText.split('\n').map(l => l.trim()).filter(l => l !== '');
                if (lines.length === 0) return;
                const validLines = [];
                lines.forEach((line, i) => {
                    const delim = line.includes('|') ? '|' : ':';
                    const parts = line.split(delim);
                    if (parts.length !== 6 && parts.length !== 7) {
                        this.bulkErrors.push((i + 1) + '行目: 項目数が不正です(' + parts.length + '個) - ' + line);
                    } else {
                        validLines.push(line);
                    }
                });
                validLines.forEach(line => {
                    this.items.push({ id: null, text: line, used: false });
                });
                if (this.bulkErrors.length === 0) {
                    this.bulkText = '';
                }
            }
        }"
    >
        <div class="flex items-center justify-between">
            <span class="text-[13px] font-semibold text-[#4a5566]">アカウント情報</span>
        </div>

        <div class="flex flex-col gap-[6px] bg-[#f9fafb] border border-[#e2e6ea] rounded-[8px] p-3">
            <span class="text-[12px] font-semibold text-[#4a5566]">一括入力欄</span>
            <textarea
                x-model="bulkText"
                rows="4"
                placeholder="例: username1|password1|email1|email_password1|2fa1|auth_token1&#10;username2|password2|email2|email_password2|2fa2|auth_token2"
                class="aero-input resize-y font-mono text-[12px]"
            ></textarea>
            <div class="flex items-center justify-between gap-3">
                <span class="text-[11px] text-[#7b8694]">1行につき1アカウント。区切り文字は「|」または「:」、項目数は6個(ID/PW/メール/メールPW/2FA/AuthToken)または7個(+電話番号)。</span>
                <button type="button" @click="bulkAdd()" class="flex-none text-[12px] font-semibold text-white bg-[#1fa5c4] border-0 px-3 py-[5px] rounded-[7px] cursor-pointer hover:bg-[#178ba6] transition-colors font-sans">一括追加</button>
            </div>
            <div x-show="bulkErrors.length > 0" class="flex flex-col gap-[2px]">
                <template x-for="(err, ei) in bulkErrors" :key="ei">
                    <span class="text-[11px] text-red-500" x-text="err"></span>
                </template>
            </div>
        </div>

        <div class="flex flex-col gap-[8px]" x-show="items.length > 0">
            <template x-for="(item, index) in items" :key="index">
                <div class="flex flex-col gap-[4px]">
                    <div class="flex items-center gap-2">
                        <span class="text-[11px] text-[#7b8694] flex-none w-[28px]" x-text="(index + 1) + '.'"></span>
                        <input
                            type="hidden"
                            :name="'account_texts[' + index + '][id]'"
                            :value="item.id ?? ''"
                        >
                        <input
                            type="text"
                            :name="'account_texts[' + index + '][text]'"
                            x-model="item.text"
                            class="account-text-input aero-input flex-1 font-mono text-[12px]"
                            placeholder="例: username|password|email|email_password|2fa|auth_token"
                            :readonly="item.used"
                            :class="item.used ? 'bg-[#f2f5f7] text-[#7b8694] cursor-not-allowed' : ''"
                        >
                        <button
                            type="button"
                            @click="removeItem(index)"
                            x-show="!item.used"
                            class="flex-none w-[28px] h-[28px] flex items-center justify-center rounded-[7px] border-0 text-white bg-red-500 cursor-pointer hover:bg-red-600 transition-colors font-sans"
                        >
                            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 6L6 18M6 6l12 12"/></svg>
                        </button>
                        <span x-show="item.used" class="flex-none text-[10px] font-semibold text-[#7b8694] bg-[#f2f5f7] border border-[#e2e6ea] rounded-full px-2 py-[3px]">使用済</span>
                    </div>

                    {{-- インラインプレビュー（横スクロール） --}}
                    <div
                        x-show="item.text.trim() !== ''"
                        class="ml-[36px] flex gap-[10px] overflow-x-auto pb-[4px]"
                        style="scrollbar-width: thin;"
                    >
                        <template x-for="(field, fi) in parseText(item.text)" :key="fi">
                            <div class="flex flex-col gap-[2px] flex-none bg-[#f9fafb] border border-[#e2e6ea] rounded-[8px] px-[10px] py-[7px] min-w-[90px]">
                                <span class="text-[10px] font-semibold text-[#7b8694] whitespace-nowrap" x-text="field.label"></span>
                                <span class="text-[11px] font-semibold text-[#1a1f27] font-mono break-all" x-text="field.value"></span>
                            </div>
                        </template>
                    </div>
                </div>
            </template>
        </div>

        <p x-show="items.length === 0" class="text-[12px] text-[#7b8694]">上の「一括入力欄」からアカウント情報を追加できます。</p>
        <span class="text-[11px] text-[#7b8694]">使用済みのアカウントは編集・削除できません。在庫数 = 未使用アカウント数。</span>
        @error('account_texts')
            <span class="text-[12px] text-red-500">{{ $message }}</span>
        @enderror
    </div>

    <div class="grid grid-cols-2 gap-5">
        <div class="flex flex-col gap-[6px]">
            <span class="text-[13px] font-semibold text-[#4a5566]">ポイント <span class="text-red-500">*</span></span>
            <input type="number" name="point" value="{{ old('point', $product->point ?? '') }}" min="1" placeholder="例: 1200" class="aero-input">
            @error('point')
                <span class="text-[12px] text-red-500">{{ $message }}</span>
            @enderror
        </div>
        <div class="flex flex-col gap-[6px]">
            <span class="text-[13px] font-semibold text-[#4a5566]">おすすめ順</span>
            <input type="number" name="popularity_order" value="{{ old('popularity_order', $product->popularity_order ?? 0) }}" min="0" placeholder="例: 1" class="aero-input">
            <span class="text-[11px] text-[#7b8694]">小さい数値ほど上位に表示されます（0 は最後尾）</span>
            @error('popularity_order')
                <span class="text-[12px] text-red-500">{{ $message }}</span>
            @enderror
        </div>
    </div>


</div>
