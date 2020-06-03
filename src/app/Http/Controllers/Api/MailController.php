<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Mail\SendContactLetter as MailSendContactLetter;
use App\Mail\ContactMail;
use Illuminate\Support\Facades\Mail;

class MailController extends Controller
{
    public function sendContactLetter(MailSendContactLetter $request)
    {
        Mail::to(config('email.support_email'))->send(new ContactMail($request->all()));
        return response()->api(null, 200);
    }
}
