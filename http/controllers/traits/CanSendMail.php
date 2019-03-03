<?php

namespace RLuders\JWTAuth\Http\Controllers\Traits;

use Mail;

trait CanSendMail
{
    protected function sendMail(
        $email,
        $name,
        $template,
        $data = []
    ) {
        Mail::send(
            $template,
            $data,
            function ($message) use ($email, $name) {
                $message->to($email, $name);
            }
        );
    }
}