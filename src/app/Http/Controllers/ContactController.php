<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Contact;

class ContactController extends Controller
{
    // 入力画面
    public function index() {
        return view('contact.index');
    }

    // 保存処理
    public function store(Request $request) {
        $request->validate([
            'name' => 'required|max:255',
            'email' => 'required|email|max:255',
            'message' => 'required',
        ]);

        Contact::create($request->all());

        return redirect()->route('contact.index')->with('message', 'お問い合わせを受け付けました。ありがとうございます！');
    }
}
