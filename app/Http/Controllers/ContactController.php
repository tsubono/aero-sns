<?php

namespace App\Http\Controllers;

use App\Http\Requests\Contact\StoreRequest;
use App\Mail\ContactNotification;
use App\Mail\ContactReceived;
use App\Models\Contact;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;

class ContactController extends Controller
{
    /**
     * 問い合わせフォーム
     *
     * @return View
     */
    public function index(): View
    {
        return view('contact.index');
    }

    /**
     * 問い合わせ送信処理
     *
     * @param StoreRequest $request
     * @return RedirectResponse
     */
    public function store(StoreRequest $request): RedirectResponse
    {
        // DB登録
        $contact = Contact::create(
            array_merge($request->validated(), ['user_id' => auth()->id()])
        );

        // メール通知
        Mail::to($contact->email)->send(new ContactReceived($contact));
        Mail::to(config('mail.admin'))->send(new ContactNotification($contact));

        return redirect()->route('contact.index')->with('success', 'お問い合わせを受け付けました。');
    }
}
