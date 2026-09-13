<x-layout.app-simple title="メールアドレスの確認" :centerLogo="true">
<div class="flex-1 flex justify-center px-6 pt-16 pb-[100px]">
    <div class="w-full max-w-[400px]">
        <div class="w-14 h-14 rounded-full bg-[rgba(31,165,196,0.1)] flex items-center justify-center mb-6">
            <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="#1fa5c4" stroke-width="2"><rect x="2" y="4" width="20" height="16" rx="2"/><path d="M2 7l10 7 10-7"/></svg>
        </div>

        <h1 class="text-[22px] font-bold m-0 mb-2">メールアドレスの確認</h1>
        <p class="text-[13px] text-[#7b8694] m-0 mb-8 leading-[1.7]">ご登録いただきありがとうございます。登録されたメールアドレスに確認リンクをお送りしました。メールをご確認の上、リンクをクリックしてください。</p>

        @if(session('status') === 'verification-link-sent')
            <div class="mb-6 px-4 py-3 rounded-[10px] bg-[rgba(43,158,120,0.08)] border border-[rgba(43,158,120,0.25)] text-[14px] text-[#2b9e78] font-semibold">
                確認メールを再送しました。
            </div>
        @endif

        <div class="flex flex-col gap-3">
            <form method="POST" action="{{ route('verification.send') }}">
                @csrf
                <button type="submit" class="block text-center px-0 py-[15px] rounded-[10px] bg-[#1fa5c4] text-white text-[15px] font-bold transition-colors duration-150 hover:bg-[#178ba6] border-0 cursor-pointer font-sans w-full">確認メールを再送する</button>
            </form>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="block text-center px-0 py-[14px] rounded-[10px] border border-[#d4dae1] bg-white text-[#4a5566] text-[14px] font-semibold transition-colors duration-150 hover:bg-[#f2f5f7] hover:border-[#b9c3cd] cursor-pointer font-sans w-full">ログアウト</button>
            </form>
        </div>
    </div>
</div>
</x-layout.app-simple>
