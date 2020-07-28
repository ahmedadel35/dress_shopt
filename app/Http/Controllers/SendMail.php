<?php

namespace App\Http\Controllers;

use App\Http\Requests\ContactUsFormRequest;
use App\Mail\ContactUsMail;
use Illuminate\Http\Request;
use Mail;

class SendMail extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(ContactUsFormRequest $request)
    {
        $res = (object) $request->validated();

        Mail::to(config('mail.from.address'))
            ->locale(session()->get('locale', 'ar'))
            ->send(new ContactUsMail(
                $res
            ));

        return response()->json(['sent' => true]);
    }
}
