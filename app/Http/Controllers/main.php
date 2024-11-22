<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Exception;

class main extends Controller
{
    public function index(Request $request)
    {
        try {
            // Passer l'état de connexion à la vue
            $state = 'home';
            return view('home')->with('state', $state);

        } catch (Exception $e) {
            // En cas d'erreur, rediriger vers une page d'erreur ou retourner un message d'erreur
            return redirect()->route('error')->with('error', $e->getMessage());
        }
    }
}
