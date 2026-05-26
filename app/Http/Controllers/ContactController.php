<?php

namespace App\Http\Controllers;

use App\Http\Requests\ContactStore;
use App\Models\ContactInquiry;

class ContactController extends Controller
{
    public function index()
    {
        return view('contact');
    }

    public function store(ContactStore $request)
    {
        ContactInquiry::create($request->validated());

        return redirect()->route('contact')
            ->with('success', 'Thank you for your message! We will get back to you soon.');
    }
}
