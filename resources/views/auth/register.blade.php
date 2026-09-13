<x-layout.app-simple title="新規登録" :centerLogo="true">
<div class="flex-1 flex justify-center px-6 pt-16 pb-[100px]">
    <div class="w-full max-w-[400px]">
        <h1 class="text-[22px] font-bold m-0 mb-8">新規登録</h1>

        <form method="POST" action="{{ route('register') }}" class="flex flex-col gap-[18px]">
            @csrf

            <label class="flex flex-col gap-[7px]">
                <span class="text-[13px] font-semibold text-[#4a5566]">名前</span>
                <input type="text" name="name" value="{{ old('name') }}" placeholder="aero_user" autocomplete="name" class="aero-input">
                @error('name')
                    <span class="text-[12px] text-red-500">{{ $message }}</span>
                @enderror
            </label>

            <label class="flex flex-col gap-[7px]">
                <span class="text-[13px] font-semibold text-[#4a5566]">メールアドレス</span>
                <input type="email" name="email" value="{{ old('email') }}" placeholder="example@aero.jp" autocomplete="username" class="aero-input">
                @error('email')
                    <span class="text-[12px] text-red-500">{{ $message }}</span>
                @enderror
            </label>

            <label class="flex flex-col gap-[7px]">
                <span class="text-[13px] font-semibold text-[#4a5566]">パスワード</span>
                <input type="password" name="password" placeholder="8文字以上（英数字混在）" autocomplete="new-password" class="aero-input">
                @error('password')
                    <span class="text-[12px] text-red-500">{{ $message }}</span>
                @enderror
            </label>

            <label class="flex flex-col gap-[7px]">
                <span class="text-[13px] font-semibold text-[#4a5566]">パスワード（確認）</span>
                <input type="password" name="password_confirmation" placeholder="もう一度入力してください" autocomplete="new-password" class="aero-input">
                @error('password_confirmation')
                    <span class="text-[12px] text-red-500">{{ $message }}</span>
                @enderror
            </label>

            <label class="flex items-start gap-[9px] text-[13px] text-[#4a5566] leading-[1.7] cursor-pointer mt-1">
                <input type="checkbox" class="w-[15px] h-[15px] mt-[3px] accent-[#1fa5c4] cursor-pointer">
                <span><a href="{{ route('terms') }}" class="no-underline">利用規約</a>および<a href="{{ route('privacy-policy') }}" class="no-underline">プライバシーポリシー</a>に同意します</span>
            </label>

            <button type="submit" class="block text-center px-0 py-[15px] rounded-[10px] bg-[#1fa5c4] text-white text-[15px] font-bold mt-[6px] transition-colors duration-150 hover:bg-[#178ba6] border-0 cursor-pointer font-sans w-full">登録する</button>
        </form>

        <p class="text-[13px] text-[#7b8694] text-center m-0 mt-[30px]">すでにアカウントをお持ちの方は <a href="{{ route('login') }}" class="no-underline font-semibold">ログイン</a></p>
    </div>
</div>
</x-layout.app-simple>
