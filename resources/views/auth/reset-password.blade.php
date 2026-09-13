<x-layout.app-simple title="パスワードの再設定" :centerLogo="true">
<div class="flex-1 flex justify-center px-6 pt-16 pb-[100px]">
    <div class="w-full max-w-[400px]">
        <h1 class="text-[22px] font-bold m-0 mb-2">パスワードの再設定</h1>
        <p class="text-[13px] text-[#7b8694] m-0 mb-8 leading-[1.7]">新しいパスワードを入力してください。</p>

        <form method="POST" action="{{ route('password.store') }}" class="flex flex-col gap-[18px]">
            @csrf
            <input type="hidden" name="token" value="{{ $request->route('token') }}">

            <label class="flex flex-col gap-[7px]">
                <span class="text-[13px] font-semibold text-[#4a5566]">メールアドレス</span>
                <input type="email" name="email" value="{{ old('email', $request->email) }}" placeholder="example@aero.jp" autocomplete="username" autofocus class="aero-input">
                @error('email')
                    <span class="text-[12px] text-red-500">{{ $message }}</span>
                @enderror
            </label>

            <label class="flex flex-col gap-[7px]">
                <span class="text-[13px] font-semibold text-[#4a5566]">新しいパスワード</span>
                <input type="password" name="password" placeholder="8文字以上" autocomplete="new-password" class="aero-input">
                @error('password')
                    <span class="text-[12px] text-red-500">{{ $message }}</span>
                @enderror
            </label>

            <label class="flex flex-col gap-[7px]">
                <span class="text-[13px] font-semibold text-[#4a5566]">新しいパスワード（確認）</span>
                <input type="password" name="password_confirmation" placeholder="もう一度入力してください" autocomplete="new-password" class="aero-input">
                @error('password_confirmation')
                    <span class="text-[12px] text-red-500">{{ $message }}</span>
                @enderror
            </label>

            <button type="submit" class="block text-center px-0 py-[15px] rounded-[10px] bg-[#1fa5c4] text-white text-[15px] font-bold mt-[6px] transition-colors duration-150 hover:bg-[#178ba6] border-0 cursor-pointer font-sans w-full">パスワードを再設定</button>
        </form>
    </div>
</div>
</x-layout.app-simple>
