<x-layout.app-simple title="パスワードをお忘れの方" :centerLogo="true">
<div class="flex-1 flex justify-center px-6 pt-16 pb-[100px]">
    <div class="w-full max-w-[400px]">
        <h1 class="text-[22px] font-bold m-0 mb-2">パスワードをお忘れの方</h1>
        <p class="text-[13px] text-[#7b8694] m-0 mb-8 leading-[1.7]">登録済みのメールアドレスを入力してください。パスワード再設定用のリンクをお送りします。</p>

        @if(session('status'))
            <div class="mb-6 px-4 py-3 rounded-[10px] bg-[rgba(43,158,120,0.08)] border border-[rgba(43,158,120,0.25)] text-[14px] text-[#2b9e78] font-semibold">
                {{ session('status') }}
            </div>
        @endif

        <form method="POST" action="{{ route('password.email') }}" class="flex flex-col gap-[18px]">
            @csrf

            <label class="flex flex-col gap-[7px]">
                <span class="text-[13px] font-semibold text-[#4a5566]">メールアドレス</span>
                <input type="email" name="email" value="{{ old('email') }}" placeholder="example@aero.jp" autocomplete="email" autofocus class="aero-input">
                @error('email')
                    <span class="text-[12px] text-red-500">{{ $message }}</span>
                @enderror
            </label>

            <button type="submit" class="block text-center px-0 py-[15px] rounded-[10px] bg-[#1fa5c4] text-white text-[15px] font-bold mt-[6px] transition-colors duration-150 hover:bg-[#178ba6] border-0 cursor-pointer font-sans w-full">リセットリンクを送信</button>
        </form>

        <div class="mt-8 text-center">
            <a href="{{ route('login') }}" class="text-[13px] no-underline text-[#7b8694] hover:text-[#4a5566]">← ログインに戻る</a>
        </div>
    </div>
</div>
</x-layout.app-simple>
