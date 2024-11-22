<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Exception;

class logout extends Controller
{
    /**
     * Gère la déconnexion de l'utilisateur.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function index(Request $request)
    {
        try {
            // Déconnexion de l'utilisateur
            Auth::logout();

            // Vider la session pour éviter toute utilisation de session précédente
            $request->session()->invalidate();

            // Régénérer le token de la session pour éviter les attaques CSRF
            $request->session()->regenerateToken();
        
            // Rediriger vers la page de connexion
            return redirect('home');
        } catch (Exception $e) {
            // En cas d'erreur, rediriger vers une page d'erreur ou retourner un message d'erreur
            return redirect()->route('error')->with('error', $e->getMessage());
        }
    }
}
