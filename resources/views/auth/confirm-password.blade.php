<x-layout.app-simple title="パスワードの確認" :centerLogo="true">
<div class="flex-1 flex justify-center px-6 pt-16 pb-[100px]">
    <div class="w-full max-w-[400px]">
        <h1 class="text-[22px] font-bold m-0 mb-2">パスワードの確認</h1>
        <p class="text-[13px] text-[#7b8694] m-0 mb-8 leading-[1.7]">セキュリティ保護のため、続行する前に現在のパスワードを入力してください。</p>

        <form method="POST" action="{{ route('password.confirm') }}" class="flex flex-col gap-[18px]">
            @csrf

            <label class="flex flex-col gap-[7px]">
                <span class="text-[13px] font-semibold text-[#4a5566]">現在のパスワード</span>
                <input type="password" name="password" placeholder="現在のパスワード" autocomplete="current-password" autofocus class="aero-input">
                @error('password')
                    <span class="text-[12px] text-red-500">{{ $message }}</span>
                @enderror
            </label>

            <button type="submit" class="block text-center px-0 py-[15px] rounded-[10px] bg-[#1fa5c4] text-white text-[15px] font-bold mt-[6px] transition-colors duration-150 hover:bg-[#178ba6] border-0 cursor-pointer font-sans w-full">確認して続行</button>
        </form>
    </div>
</div>
</x-layout.app-simple>
