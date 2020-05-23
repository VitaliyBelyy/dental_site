<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Mail\SendContactLetter as MailSendContactLetter;
use Illuminate\Http\Request;

class MailController extends Controller
{
    public function sendContactLetter(MailSendContactLetter $request)
    {
        
    }
}
