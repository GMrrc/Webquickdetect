<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use App\Models\PasswordResetToken;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class ForgotPassword extends Controller
{
    public function showForgotPasswordForm()
    {
        return view('home')->with('state', 'forgot_password');
    }

    public function sendPasswordResetLink(Request $request)
    {
        $request->validate(['email' => 'required|email']);
        $user = User::where('email', $request->input('email'))->first();

        $token_email = PasswordResetToken::where('email', $request->input('email'))->first();

        if ($token_email) {
            $token_email->delete();
        }

        if (!$user) {
            return back()->withErrors(['email' => "L'email fourni n'existe pas dans nos enregistrements."]);
        }

        $status = Password::sendResetLink($request->only('email'));

        return $status === Password::RESET_LINK_SENT
            ? back()->with(['status' => __($status), 'success' => 'Un mail vous a été envoyé'])
            : back()->withErrors(['email' => __($status)]);
            
            
        if ($status == Password::RESET_LINK_SENT) {
            return back()->with(['status' => __($status), 'success' => 'Un mail vous a été envoyé']);
        } elseif ($status == Password::PASSWORD_THROTTLED) {
            return back()->withErrors(['email' => 'Veuillez attendre quelques minutes avant de redemander.']);
        } else {
            return back()->withErrors(['email' => __($status)]);
        }
    }


    public function showResetPasswordForm(string $token)
    {
        return view('reset_password', ['token' => $token]);
    }

    public function resetPassword(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'token' => 'required',
                'email' => 'required|email',
                'new_password' => 'required|min:8|confirmed',
                'new_password_confirmation' => 'required|min:8'
            ], [
                'new_password.regex' => 'Le mot de passe doit contenir au moins une lettre majuscule, une lettre minuscule, un chiffre et un caractère spécial.',
                'email.required' => 'L’adresse e-mail est obligatoire.',
                'email.email' => 'Veuillez entrer une adresse e-mail valide.',
                'new_password.required' => 'Le mot de passe est obligatoire.',
                'new_password.min' => 'Le mot de passe doit contenir au moins 8 caractères.',
                'new_password.confirmed' => 'Les mots de passe ne correspondent pas.',
                'new_password_confirmation.required' => 'La confirmation du mot de passe est obligatoire.',
                'new_password_confirmation.min' => 'La confirmation du mot de passe doit contenir au moins 8 caractères.'
            ]);
            
            if ($validator->fails()) {
                return redirect()->back()
                    ->with('state', 'register')
                    ->withErrors($validator)
                    ->withInput();
            }

            $hashedToken = hash_hmac('sha256', $request->input('token'), config('app.token_key'));

            $tokenRecord = PasswordResetToken::where('email', $request->input('email'))
                                                ->first();

        

            if (!$tokenRecord) {
                return redirect()->back()
                    ->withErrors(['email' => "Le token fourni n'est pas valide ou a expiré."])
                    ->withInput();
            }

            $user = User::where('email', $request->input('email'))->first();

            if (!$user) {
                return redirect()->back()
                    ->withErrors(['email' => "Aucun utilisateur trouvé avec cet email."])
                    ->withInput();
            }



            $user->update([
                'password' => Hash::make($request->input('new_password')),
            ]);

            $tokenRecord->delete();

            return redirect()->route('login')->with('success', 'Votre mot de passe a été réinitialisé avec succès.');

        } catch (\Exception $e) {
            Log::error("Error resetting password: " . $e->getMessage());
            return view('error')->with('data', ['error' => $e->getMessage()]);
        }
    }

}


