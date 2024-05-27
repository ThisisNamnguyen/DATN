<?php

namespace App\Http\Controllers;

use App\Mail\WelcomeEmail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class EmailController extends Controller
{
    public function sendWelcomeEmail() {
        $toEmail = 'namtapcode@gmail.com';
        $message = 'Welcome';
        $subject = 'Welcome Email';
        $response = Mail::to($toEmail)->send(new WelcomeEmail($message, $subject));

        dd($response);
    }
}
