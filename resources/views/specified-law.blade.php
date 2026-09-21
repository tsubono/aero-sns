<x-layout.app title="特定商取引法に基づく表記">
    <div class="flex-1 w-full box-border max-w-[860px] mx-auto px-8 pt-8 pb-[100px]">
        <h1 class="text-[24px] font-bold m-0 mb-10">特定商取引法に基づく表記</h1>

        <div class="border border-[#e2e6ea] rounded-[14px] overflow-hidden text-[15px]">
            @foreach([
                ['label' => '販売事業者名',       'value' => '吉本 隼司'],
                ['label' => '代表者名',           'value' => '吉本 隼司'],
                ['label' => '所在地',             'value' => '請求があった場合、遅滞なく開示いたします'],
                ['label' => '電話番号',           'value' => '請求があった場合、遅滞なく開示いたします'],
                ['label' => 'メールアドレス',      'value' => 'aerosnsofficial@gmail.com'],
                ['label' => 'サービス名',          'value' => 'AERO SNS'],
                ['label' => '販売URL',            'value' => config('app.url')],
                ['label' => '販売価格',           'value' => '各商品ページに表示のポイント数（1pt = 1円相当）'],
                ['label' => '販売価格以外の費用', 'value' => 'なし（通信費・接続料等はユーザー負担）'],
                ['label' => 'お支払い方法',       'value' => 'クレジットカード決済（ポイント購入）'],
                ['label' => 'お支払い時期',       'value' => 'ポイント購入時に即時決済'],
                ['label' => '商品の引き渡し時期', 'value' => '購入完了後、即時にマイページの注文履歴にてご確認いただけます'],
                ['label' => '返品・キャンセル',   'value' => 'デジタルコンテンツの性質上、購入完了後のキャンセル・返品・返金はお受けできません。ただし、商品に重大な瑕疵がある場合はこの限りではありません。'],
                ['label' => '動作環境',           'value' => 'インターネット接続環境が必要です。推奨ブラウザ：Chrome / Firefox / Safari / Edge（各最新版）'],
            ] as $row)
                <div class="flex border-b border-[#eef1f4] last:border-b-0">
                    <div class="w-[200px] flex-none bg-[#f7fbfc] px-5 py-4 font-semibold text-[#4a5566] text-[14px] border-r border-[#eef1f4]">{{ $row['label'] }}</div>
                    <div class="flex-1 px-5 py-4 text-[#3d4757] leading-[1.8]">{{ $row['value'] }}</div>
                </div>
            @endforeach
            <div class="flex border-b border-[#eef1f4] last:border-b-0">
                <div class="w-[200px] flex-none bg-[#f7fbfc] px-5 py-4 font-semibold text-[#4a5566] text-[14px] border-r border-[#eef1f4]">お問い合わせ</div>
                <div class="flex-1 px-5 py-4 text-[#3d4757] leading-[1.8]">
                    <a href="{{ route('contact.index') }}" class="text-[#1fa5c4] hover:underline">お問い合わせフォーム</a>
                </div>
            </div>
        </div>
    </div>
</x-layout.app>
