<x-layout.admin-app title="ユーザー管理">

    <div class="flex flex-wrap items-center gap-2 mb-5">
        <form method="GET" action="{{ route('admin.users.index') }}" class="flex flex-wrap gap-2">
            <input type="text" name="keyword" value="{{ request('keyword') }}" placeholder="名前・メールで検索" class="aero-input w-[220px]">
            <select name="status" class="aero-input w-[140px]">
                <option value="">ステータス：全て</option>
                @foreach(\App\Enums\UserStatus::cases() as $status)
                    <option value="{{ $status->value }}" {{ request('status') == $status->value ? 'selected' : '' }}>{{ $status->label() }}</option>
                @endforeach
            </select>
            <button type="submit" class="px-4 py-[10px] rounded-[8px] bg-[#4a5566] text-white text-[13px] font-semibold border-0 cursor-pointer font-sans hover:bg-[#374151] transition-colors duration-150">絞り込む</button>
            @if(request('keyword') || request('status'))
                <a href="{{ route('admin.users.index') }}" class="px-4 py-[10px] rounded-[8px] border border-[#d4dae1] bg-white text-[#4a5566] text-[13px] font-semibold no-underline hover:bg-[#f2f5f7] transition-colors duration-150">クリア</a>
            @endif
        </form>
    </div>

    <div class="bg-white rounded-[12px] border border-[#e2e6ea] overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-[13px]">
                <thead>
                    <tr class="bg-[#f7fbfc] border-b border-[#e2e6ea]">
                        <th class="text-left px-4 py-3 font-semibold text-[#7b8694] w-12">ID</th>
                        <th class="text-left px-4 py-3 font-semibold text-[#7b8694]">名前</th>
                        <th class="text-left px-4 py-3 font-semibold text-[#7b8694]">メールアドレス</th>
                        <th class="text-right px-4 py-3 font-semibold text-[#7b8694] w-28">保有ポイント</th>
                        <th class="text-left px-4 py-3 font-semibold text-[#7b8694] w-24">ステータス</th>
                        <th class="text-left px-4 py-3 font-semibold text-[#7b8694] w-36">登録日</th>
                        <th class="text-right px-4 py-3 font-semibold text-[#7b8694] w-20">操作</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $user)
                        <tr class="border-b border-[#eef1f4] hover:bg-[#f9fafb] transition-colors duration-100">
                            <td class="px-4 py-3 text-[#7b8694]">{{ $user->id }}</td>
                            <td class="px-4 py-3 font-semibold">{{ $user->name }}</td>
                            <td class="px-4 py-3 text-[#7b8694]">{{ $user->email }}</td>
                            <td class="px-4 py-3 text-right font-semibold">{{ number_format($user->point) }} pt</td>
                            <td class="px-4 py-3">
                                @if($user->status === \App\Enums\UserStatus::Active)
                                    <span class="px-2 py-[3px] rounded-full text-[11px] font-bold bg-green-50 text-green-600 border border-green-200">{{ $user->status->label() }}</span>
                                @else
                                    <span class="px-2 py-[3px] rounded-full text-[11px] font-bold bg-red-50 text-red-500 border border-red-200">{{ $user->status->label() }}</span>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-[#7b8694]">{{ $user->created_at->format('Y/m/d') }}</td>
                            <td class="px-4 py-3 text-right">
                                <a href="{{ route('admin.users.show', $user) }}" class="px-3 py-1 rounded-[6px] border border-[#d4dae1] text-[#4a5566] text-[12px] font-semibold no-underline hover:bg-[#f2f5f7] transition-colors duration-150 whitespace-nowrap">詳細</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-4 py-10 text-center text-[#7b8694]">ユーザーが見つかりません。</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($users->hasPages())
            <div class="flex items-center justify-between px-4 py-3 border-t border-[#eef1f4]">
                <span class="text-[12px] text-[#7b8694]">全 {{ $users->total() }} 件中 {{ $users->firstItem() }}〜{{ $users->lastItem() }} 件</span>
                <div class="flex items-center gap-1">
                    @if($users->onFirstPage())
                        <span class="px-3 py-1 rounded-[6px] border border-[#e2e6ea] text-[#c0c8d2] text-[12px]">前へ</span>
                    @else
                        <a href="{{ $users->previousPageUrl() }}" class="px-3 py-1 rounded-[6px] border border-[#d4dae1] text-[#4a5566] text-[12px] no-underline hover:bg-[#f2f5f7]">前へ</a>
                    @endif
                    @if($users->hasMorePages())
                        <a href="{{ $users->nextPageUrl() }}" class="px-3 py-1 rounded-[6px] border border-[#d4dae1] text-[#4a5566] text-[12px] no-underline hover:bg-[#f2f5f7]">次へ</a>
                    @else
                        <span class="px-3 py-1 rounded-[6px] border border-[#e2e6ea] text-[#c0c8d2] text-[12px]">次へ</span>
                    @endif
                </div>
            </div>
        @endif
    </div>

</x-layout.admin-app>
