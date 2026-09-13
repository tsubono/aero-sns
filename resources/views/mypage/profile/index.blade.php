<x-layout.app title="会員情報設定">
<div class="flex-1 w-full box-border max-w-[640px] mx-auto px-8 pt-5 pb-[110px]">
    <div class="text-[13px] text-[#7b8694] flex gap-2 items-center mb-6">
        <a href="{{ route('top') }}" class="text-[#7b8694] no-underline">ホーム</a>
        <span>/</span>
        <a href="{{ route('mypage') }}" class="text-[#7b8694] no-underline">マイページ</a>
        <span>/</span>
        <span class="text-[#4a5566]">会員情報設定</span>
    </div>

    <h1 class="text-[21px] font-bold m-0 mb-[30px] flex items-center gap-[10px]"><span class="w-1 h-[18px] bg-[#1fa5c4] rounded-[2px] inline-block"></span>会員情報設定</h1>

    @if(session('status') === 'profile-updated')
        <div class="mb-6 px-4 py-3 rounded-[10px] bg-[rgba(43,158,120,0.08)] border border-[rgba(43,158,120,0.25)] text-[14px] text-[#2b9e78] font-semibold">
            会員情報を更新しました。
        </div>
    @endif

    <section class="mb-9">
        <h2 class="text-[15px] font-bold m-0 mb-5 pb-[10px] border-b border-[#e2e6ea]">基本情報</h2>
        <form method="POST" action="{{ route('mypage.profile.update') }}" class="flex flex-col gap-5">
            @csrf
            @method('PUT')

            <label class="flex flex-col gap-[7px]">
                <span class="text-[13px] font-semibold text-[#4a5566]">名前</span>
                <input type="text" name="name" value="{{ old('name', auth()->user()->name ?? '') }}" placeholder="山田 太郎" autocomplete="name" class="aero-input">
                @error('name')
                    <span class="text-[12px] text-red-500">{{ $message }}</span>
                @enderror
            </label>

            <label class="flex flex-col gap-[7px]">
                <span class="text-[13px] font-semibold text-[#4a5566]">メールアドレス</span>
                <input type="email" name="email" value="{{ old('email', auth()->user()->email ?? '') }}" placeholder="example@aero.jp" autocomplete="username" class="aero-input">
                @error('email')
                    <span class="text-[12px] text-red-500">{{ $message }}</span>
                @enderror
            </label>

            <button type="submit" class="self-start px-7 py-[13px] rounded-[10px] bg-[#1fa5c4] text-white text-[14px] font-bold border-0 cursor-pointer font-sans transition-colors duration-150 hover:bg-[#178ba6]">変更を保存</button>
        </form>
    </section>

    <section class="mb-9">
        <h2 class="text-[15px] font-bold m-0 mb-5 pb-[10px] border-b border-[#e2e6ea]">パスワード変更</h2>

        @if(session('status') === 'password-updated')
            <div class="mb-5 px-4 py-3 rounded-[10px] bg-[rgba(43,158,120,0.08)] border border-[rgba(43,158,120,0.25)] text-[14px] text-[#2b9e78] font-semibold">
                パスワードを更新しました。
            </div>
        @endif

        <form method="POST" action="{{ route('mypage.profile.password') }}" class="flex flex-col gap-5">
            @csrf
            @method('PUT')

            <label class="flex flex-col gap-[7px]">
                <span class="text-[13px] font-semibold text-[#4a5566]">新しいパスワード</span>
                <input type="password" name="password" placeholder="8文字以上（英数字混在）" autocomplete="new-password" class="aero-input">
                @error('password', 'updatePassword')
                    <span class="text-[12px] text-red-500">{{ $message }}</span>
                @enderror
            </label>

            <label class="flex flex-col gap-[7px]">
                <span class="text-[13px] font-semibold text-[#4a5566]">新しいパスワード（確認）</span>
                <input type="password" name="password_confirmation" placeholder="もう一度入力してください" autocomplete="new-password" class="aero-input">
                @error('password_confirmation', 'updatePassword')
                    <span class="text-[12px] text-red-500">{{ $message }}</span>
                @enderror
            </label>

            <button type="submit" class="self-start px-7 py-[13px] rounded-[10px] bg-[#1fa5c4] text-white text-[14px] font-bold border-0 cursor-pointer font-sans transition-colors duration-150 hover:bg-[#178ba6]">パスワードを変更</button>
        </form>
    </section>

    <div class="flex items-center justify-between">
        <a href="{{ route('mypage') }}" class="inline-flex items-center gap-[8px] text-[14px] font-semibold no-underline text-[#4a5566] hover:text-[#1a1f27]">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 12H5"/><path d="M11 6l-6 6 6 6"/></svg>
            マイページに戻る
        </a>
        <button
            type="button"
            onclick="document.getElementById('withdraw-modal').classList.remove('hidden')"
            class="text-[13px] text-[#9ba7b4] no-underline hover:text-red-400 transition-colors bg-transparent border-0 cursor-pointer font-sans p-0"
        >退会する</button>
    </div>
</div>

{{-- 退会確認モーダル --}}
<div id="withdraw-modal" class="hidden fixed inset-0 z-50 flex items-center justify-center px-4" style="background: rgba(0,0,0,0.45);">
    <div class="bg-white rounded-[16px] w-full max-w-[400px] p-8 shadow-xl flex flex-col gap-5">
        <div class="flex flex-col gap-2">
            <h3 class="text-[18px] font-bold m-0 text-[#1a1f27]">退会の確認</h3>
            <p class="text-[13px] text-[#7b8694] m-0 leading-relaxed">退会すると、アカウントおよびすべてのデータが削除されます。この操作は取り消せません。本当に退会しますか？</p>
        </div>
        <form method="POST" action="{{ route('mypage.profile.destroy') }}">
            @csrf
            @method('DELETE')
            <div class="flex flex-col gap-[10px]">
                <button type="submit" class="w-full py-[13px] rounded-[10px] bg-red-500 text-white text-[14px] font-bold border-0 cursor-pointer font-sans transition-colors hover:bg-red-600">退会する</button>
                <button
                    type="button"
                    onclick="document.getElementById('withdraw-modal').classList.add('hidden')"
                    class="w-full py-[13px] rounded-[10px] border border-[#d4dae1] text-[#4a5566] text-[14px] font-semibold bg-white cursor-pointer font-sans transition-colors hover:bg-[#f2f5f7]"
                >キャンセル</button>
            </div>
        </form>
    </div>
</div>
</x-layout.app>
