<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use Exception;

class UserConnectForm extends Controller
{
    /**
     * Affiche le formulaire de connexion.
     */
    public function index()
    {
        $state = 'login';
        return view('home')->with('state', $state);
    }

    /**
     * Gère la tentative de connexion de l'utilisateur.
     */
    public function connect(Request $request)
    {
        try {
            $state = 'login';
            // Validation des données de la requête
            $validator = Validator::make($request->all(), [
                'email' => 'required|email',
                'password' => 'required',
            ], [
                'email.required' => 'L’adresse e-mail est obligatoire.',
                'email.email' => 'Veuillez entrer une adresse e-mail valide.',
                'password.required' => "Le mot de passe est requis.",
            ]);

            if ($validator->fails()) {
                return redirect()->back()
                                ->with('state', $state)
                                ->withErrors($validator)
                                ->withInput();
                                
            }

            // Vérifier si l'utilisateur existe
            $user = User::where('email', $request->input('email'))->first();

            if (!$user) {
                return back()
                ->with('state', $state)
                ->withErrors([
                    'email' => 'Les informations fournies ne correspondent pas à nos enregistrements.',
                ]);
            }

            // Authentifier l'utilisateur
            if (Auth::attempt($request->only('email', 'password'))) {
                $request->session()->regenerate();
                $request->session()->put('isConnected', true);

                return redirect()->intended('home');
            }

            return back()
                ->with('state', $state)
                ->withErrors([
                    'email' => 'Les informations fournies ne correspondent pas à nos enregistrements.',
                ]);
        } catch (Exception $e) {
            $state = 'login';
            return redirect()->route('home')->with([
                'state' => $state,
                'error' => $e->getMessage()
            ]);
        }
    }
}
