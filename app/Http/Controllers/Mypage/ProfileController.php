<?php

namespace App\Http\Controllers\Mypage;

use App\Http\Controllers\Controller;
use App\Http\Requests\Mypage\Profile\UpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * 会員情報設定
     *
     * @return View
     */
    public function index(): View
    {
        return view('mypage.profile.index');
    }

    /**
     * 基本情報更新処理
     *
     * @param UpdateRequest $request
     * @return RedirectResponse
     */
    public function update(UpdateRequest $request): RedirectResponse
    {
        $user = auth()->user();

        $user->update([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
        ]);

        return redirect()->route('mypage.profile.index')->with('status', 'profile-updated');
    }

    /**
     * パスワード更新処理
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function updatePassword(Request $request): RedirectResponse
    {
        $validated = $request->validateWithBag('updatePassword', [
            'password' => ['required', Password::defaults(), 'confirmed'],
        ]);

        $request->user()->update([
            'password' => Hash::make($validated['password']),
        ]);

        return redirect()->route('mypage.profile.index')->with('status', 'password-updated');
    }

    /**
     * 退会処理
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function destroy(Request $request): RedirectResponse
    {
        $user = auth()->user();
        $user->carts()->delete();
        $user->delete();

        auth('web')->logout();
        $request->session()->regenerateToken();

        return redirect()->route('top');
    }
}
