<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\User\ToggleStatusRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class UserController extends Controller
{
    /**
     * ユーザー管理
     *
     * @param Request $request
     * @return View
     */
    public function index(Request $request): View
    {
        $users = User::when($request->filled('keyword'), function ($query) use ($request) {
            $keyword = $request->input('keyword');
            $query->where(function ($innerQuery) use ($keyword) {
                $innerQuery->where('name', 'like', '%' . $keyword . '%')
                    ->orWhere('email', 'like', '%' . $keyword . '%');
            });
        })
            ->when($request->filled('status'), fn($query) => $query->where('status', $request->input('status')))
            ->latest()
            ->paginate(20)
            ->withQueryString();

        return view('admin.users.index', compact('users'));
    }

    /**
     * ユーザー詳細
     *
     * @param User $user
     * @return View
     */
    public function show(User $user): View
    {
        // 購入履歴
        $orders = $user->orders()
            ->with('orderDetails.product')
            ->latest()
            ->paginate(10);

        // ポイントチャージ履歴
        $pointCharges = $user->pointCharges()
            ->latest()
            ->paginate(10, ['*'], 'charge_page');

        return view('admin.users.show', compact('user', 'orders', 'pointCharges'));
    }

    /**
     * ステータス更新処理
     *
     * @param ToggleStatusRequest $request
     * @param User $user
     * @return RedirectResponse
     */
    public function status(ToggleStatusRequest $request, User $user): RedirectResponse
    {
        $user->update(['status' => $request->input('status')]);

        return redirect()->route('admin.users.show', $user)->with('success', 'ステータスを変更しました。');
    }
}
