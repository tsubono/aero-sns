<x-layout.app title="お問い合わせ">
<div class="flex-1 w-full box-border max-w-[680px] mx-auto px-8 pt-5 pb-[110px]">
    <div class="text-[13px] text-[#7b8694] flex gap-2 items-center mb-6">
        <a href="{{ route('top') }}" class="text-[#7b8694] no-underline">ホーム</a>
        <span>/</span>
        <span class="text-[#4a5566]">お問い合わせ</span>
    </div>

    <h1 class="text-[21px] font-bold m-0 mb-4 flex items-center gap-[10px]"><span class="w-1 h-[18px] bg-[#1fa5c4] rounded-[2px] inline-block"></span>お問い合わせ</h1>
    <p class="text-[14px] text-[#4a5566] m-0 mb-8 leading-[1.9]">ご不明な点やお問い合わせがございましたら、下記フォームよりお気軽にご連絡ください。<br>通常3〜5営業日以内にご返信いたします。</p>

    @if(session('success'))
        <div class="mb-7 px-5 py-4 rounded-[12px] bg-[rgba(43,158,120,0.08)] border border-[rgba(43,158,120,0.25)] text-[14px] text-[#2b9e78] font-semibold flex items-center gap-3">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><path d="M20 6L9 17l-5-5"/></svg>
            お問い合わせを受け付けました。ご返信をお待ちください。
        </div>
    @endif

    <form method="POST" action="{{ route('contact.store') }}" class="flex flex-col gap-5">
        @csrf

        <label class="flex flex-col gap-[7px]">
            <span class="text-[13px] font-semibold text-[#4a5566]">お名前 <span class="text-red-500">*</span></span>
            <input type="text" name="name" value="{{ old('name', auth()->user()->name ?? '') }}" placeholder="山田 太郎" autocomplete="name" class="aero-input">
            @error('name')
                <span class="text-[12px] text-red-500">{{ $message }}</span>
            @enderror
        </label>

        <label class="flex flex-col gap-[7px]">
            <span class="text-[13px] font-semibold text-[#4a5566]">メールアドレス <span class="text-red-500">*</span></span>
            <input type="email" name="email" value="{{ old('email', auth()->user()->email ?? '') }}" placeholder="example@aero.jp" autocomplete="email" class="aero-input">
            @error('email')
                <span class="text-[12px] text-red-500">{{ $message }}</span>
            @enderror
        </label>

        <label class="flex flex-col gap-[7px]">
            <span class="text-[13px] font-semibold text-[#4a5566]">件名 <span class="text-red-500">*</span></span>
            <input type="text" name="subject" value="{{ old('subject') }}" placeholder="お問い合わせの件名" class="aero-input">
            @error('subject')
                <span class="text-[12px] text-red-500">{{ $message }}</span>
            @enderror
        </label>

        <label class="flex flex-col gap-[7px]">
            <span class="text-[13px] font-semibold text-[#4a5566]">お問い合わせ内容 <span class="text-red-500">*</span></span>
            <textarea name="body" rows="7" placeholder="ご質問・ご要望を入力してください。" class="aero-input resize-y">{{ old('body') }}</textarea>
            @error('body')
                <span class="text-[12px] text-red-500">{{ $message }}</span>
            @enderror
        </label>

        <button type="submit" class="mt-2 w-full py-[15px] rounded-[10px] bg-[#1fa5c4] text-white text-[15px] font-bold border-0 cursor-pointer font-sans transition-colors duration-150 hover:bg-[#178ba6]">送信する</button>
    </form>
</div>
</x-layout.app>
