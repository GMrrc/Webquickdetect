<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserContactRequest;
use App\Mail\UserContactMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Exception;

class ContactController extends Controller
{
    public function index()
    {
        return view('contact_us');
    }

    public function contact(UserContactRequest $request)
    {
        try {
            Mail::send(new UserContactMail($request->validated()));
            return back()->with('success', 'Votre demande à bien été envoyé');
        } catch (Exception $e) {
            return back()->with('error', 'Erreur lors de l\'envoi du message : ' . $e->getMessage());
        }
    }
}

