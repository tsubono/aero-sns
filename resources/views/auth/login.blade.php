<x-layout.app-simple title="ログイン" :centerLogo="true">
<div class="flex-1 flex justify-center px-6 pt-16 pb-[100px]">
    <div class="w-full max-w-[400px]">
        <h1 class="text-[22px] font-bold m-0 mb-2">ログイン</h1>
        <p class="text-[13px] text-[#7b8694] m-0 mb-8 leading-[1.7]">登録済みのメールアドレスとパスワードを入力してください。</p>

        <x-auth-session-status class="mb-4" :status="session('status')" />

        <form method="POST" action="{{ route('login') }}" class="flex flex-col gap-[18px]">
            @csrf

            <label class="flex flex-col gap-[7px]">
                <span class="text-[13px] font-semibold text-[#4a5566]">メールアドレス</span>
                <input type="email" name="email" value="{{ old('email') }}" placeholder="example@aero.jp" autocomplete="username" class="aero-input">
                @error('email')
                    <span class="text-[12px] text-red-500">{{ $message }}</span>
                @enderror
            </label>

            <label class="flex flex-col gap-[7px]">
                <span class="text-[13px] font-semibold text-[#4a5566]">パスワード</span>
                <input type="password" name="password" placeholder="8文字以上" autocomplete="current-password" class="aero-input">
                @error('password')
                    <span class="text-[12px] text-red-500">{{ $message }}</span>
                @enderror
            </label>

            @if (Route::has('password.request'))
                <div class="flex justify-end">
                    <a href="{{ route('password.request') }}" class="text-[13px] no-underline">パスワードを忘れた方</a>
                </div>
            @endif

            <button type="submit" class="block text-center px-0 py-[15px] rounded-[10px] bg-[#1fa5c4] text-white text-[15px] font-bold no-underline mt-[6px] transition-colors duration-150 hover:bg-[#178ba6] border-0 cursor-pointer font-sans w-full">ログイン</button>
        </form>

        <div class="flex items-center gap-[14px] my-[34px]">
            <div class="flex-1 h-px bg-[#e2e6ea]"></div>
            <span class="text-[12px] text-[#a3adb9]">または</span>
            <div class="flex-1 h-px bg-[#e2e6ea]"></div>
        </div>

        <a href="{{ route('register') }}" class="block text-center py-[14px] rounded-[10px] bg-white border border-[#d4dae1] text-[#4a5566] text-[14px] font-semibold no-underline transition-colors duration-150 hover:bg-[#f2f5f7] hover:border-[#b9c3cd]">新規登録はこちら</a>
    </div>
</div>
</x-layout.app-simple>
