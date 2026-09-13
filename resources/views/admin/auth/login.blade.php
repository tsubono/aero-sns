<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>管理者ログイン | {{ config('app.name', 'AERO SNS') }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+JP:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
<div class="font-sans bg-[#f4f6f8] min-h-screen flex items-center justify-center px-4">
    <div class="w-full max-w-[380px]">
        <div class="text-center mb-8">
            <span class="text-[22px] font-extrabold tracking-wide text-[#1a1f27]">AERO <span class="text-[#1fa5c4]">Admin</span></span>
        </div>

        <div class="bg-white rounded-[14px] border border-[#e2e6ea] p-8">
            <h1 class="text-[18px] font-bold m-0 mb-6 text-center">管理者ログイン</h1>

            @if($errors->any())
                <div class="mb-5 px-4 py-3 rounded-[8px] bg-red-50 border border-red-200 text-red-600 text-[13px]">
                    {{ $errors->first() }}
                </div>
            @endif

            <form method="POST" action="{{ route('admin.login.store') }}" class="flex flex-col gap-5">
                @csrf

                <label class="flex flex-col gap-[7px]">
                    <span class="text-[13px] font-semibold text-[#4a5566]">メールアドレス</span>
                    <input type="email" name="email" value="{{ old('email') }}" placeholder="admin@example.com" autocomplete="username" class="aero-input">
                </label>

                <label class="flex flex-col gap-[7px]">
                    <span class="text-[13px] font-semibold text-[#4a5566]">パスワード</span>
                    <input type="password" name="password" autocomplete="current-password" class="aero-input">
                </label>

                <button type="submit" class="w-full py-[13px] rounded-[10px] bg-[#1fa5c4] text-white text-[14px] font-bold border-0 cursor-pointer font-sans transition-colors duration-150 hover:bg-[#178ba6] mt-1">ログイン</button>
            </form>
        </div>
    </div>
</div>
</body>
</html>
