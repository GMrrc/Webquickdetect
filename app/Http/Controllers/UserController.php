<?php

namespace App\Http\Controllers;

use App\Http\Controllers\FormRequest\EmailVerificationRequest;
use App\Http\Requests\UserRequest;
use App\Models\User;
use App\Models\Library;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

use App\Models\Picture;
use Closure;

class UserController extends Controller
{
    public function index()
    {
        $state = 'register';
        return view('home')->with('state', $state);
    }

    public function create(Request $request)
    {
        try {
            $state1 = 'register';

            // Validation des données
            $validator = Validator::make($request->all(), [
                'terms' => 'accepted',
                'email' => 'required|email|unique:users,email|regex:/[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}/',
                'password1' => 'required|min:8|regex:/^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[!@#$%^&*().,\/]).*$/',
                'name' => "required|string|max:255|regex:/[A-Za-zÀ-ÖØ-öø-ÿ -]+/",
                'surname' => "required|string|max:255|regex:/[A-Za-zÀ-ÖØ-öø-ÿ '-]+/",
                'dateOfBirth' => 'required|date',
            ], [
                'terms.accepted' => 'Vous devez accepter les termes et conditions.',
                'email.required' => 'L’adresse e-mail est obligatoire.',
                'email.email' => 'Veuillez entrer une adresse e-mail valide.',
                'email.unique' => 'Cette adresse e-mail est déjà utilisée.',
                'email.regex' => 'Le format de l’adresse e-mail n’est pas valide.',
                'age.required' => 'La date de naissance est obligatoire.',
                'age.date' => 'Veuillez entrer une date valide.',
                'age.before_or_equal' => 'Vous devez avoir au moins 18 ans.',
                'age.before' => 'La date de naissance doit être inférieure à la date actuelle.',
                'password1.required' => 'Le mot de passe est obligatoire.',
                'password1.min' => 'Le mot de passe doit contenir au moins 8 caractères.',
                'password1.regex' => 'Le mot de passe doit inclure un chiffre, une minuscule, une majuscule et un caractère spécial.',
                'name.required' => 'Le nom est obligatoire.',
                'name.regex' => 'Le format du nom n’est pas valide.',
                'surname.required' => 'Le prénom est obligatoire.',
                'surname.regex' => 'Le format du prénom n’est pas valide.',
            ]);

            if ($validator->fails()) {
                return redirect()->back()
                    ->with('state', $state1)
                    ->withErrors($validator)
                    ->withInput();
            }

            // Vérifiez si les mots de passe correspondent
            if ($request->input('password1') !== $request->input('password2')) {
                return redirect()->back()
                    ->with('state', $state1)
                    ->withErrors(['password2' => 'Les mots de passe ne sont pas identiques.'])
                    ->withInput();
            }

            // Création de l'utilisateur
            $user = new User();
            $user->name = $request->input('name');
            $user->surname = $request->input('surname');
            $user->dateOfBirth = $request->input('dateOfBirth');
            $user->role = 'user';
            $user->email = $request->input('email');
            $user->password = Hash::make($request->input('password1'));
            $user->save();

            // Création de la librairie par défaut pour l'utilisateur
            $library = new Library();
            $library->name = 'Default Library';
            $library->idUser = $user->idUser;
            $library->save();

            // Envoi de l'email de vérification
            //event(new Registered($user));

            $state2 = 'verify-email';
            return view('home')->with('state', $state2);
        } catch (Exception $e) {
            $state = 'register';
            return redirect()->route('home')->with([
                'state' => $state,
                'error' => $e->getMessage()
            ]);
        }
    }


    // Afficher le formulaire de mise à jour
    public function parametre()
    {
        try {
            $user = Auth::user();
            return view('user_parametre', compact('user'));
        } catch (\Exception $e) {
            return view('error')->with('data', ['error' => $e->getMessage()]);
        }
    }

    // Traiter la mise à jour de l'utilisateur
    public function update(Request $request)
    {
        try {
            $user = Auth::user();

            // Validation des données
            $validatedData = $request->validate([
                'name' => 'required|string',
                'surname' => 'required|string',
                'dateOfBirth' => 'required|date',
                'email' => [
                    'required',
                    'email',
                    function ($attribute, $value, $fail) use ($user) {
                        // Vérifier si l'email a changé
                        if ($value !== $user->email) {
                            // Si l'email a changé, appliquer la règle unique
                            $exists = \DB::table('users')
                                ->where('email', $value)
                                ->exists();

                            if ($exists) {
                                $fail('The email has already been taken.');
                            }
                        }
                    },
                ],
            ]);


            // Mise à jour des propriétés du modèle User
            $user->name = $validatedData['name'];
            $user->surname = $validatedData['surname'];
            $user->dateOfBirth = $validatedData['dateOfBirth'];
            $user->role = 'user'; // Utilisation de la valeur validée pour le champ 'role'
            $user->email = $validatedData['email'];

            $user->save();

            // Redirection ou réponse
            return redirect()->route('parametre')->with('success', 'Profil mis à jour avec succès');
        } catch (\Exception $e) {
            return view('error')->with('data', ['error' => $e->getMessage()]);
        }
    }




    // Supprimer un utilisateur
    public function destroy(Request $request)
    {
        try {
            $user = Auth::user(); // Obtention de l'utilisateur actuellement connecté

            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            // Suppression de la librairie associée à l'utilisateur
            $librarie = $user->library()->first();
            if (!is_null($librarie)) {
                Picture::destroy($librarie->pictures()->pluck('idPicture'));
                Library::destroy($user->library()->pluck('idLibrary'));
                $librarie->delete();
            }

            // Suppression de l'utilisateur
            $user->delete();

            $state = 'login';
            return redirect()->route('home')->with([
                'state' => $state,
                'success' => 'Profile supprimé avec succès'
            ]);
        } catch (\Exception $e) {
            return view('error')->with('data', ['error' => $e->getMessage()]);
        }
    }



    public function edit_password()
    {
        try {
            $user = Auth::user();
            return view('user_new_password', compact('user'));
            // $this->middleware('auth');
        } catch (\Exception $e) {
            return view('error')->with('data', ['error' => $e->getMessage()]);
        }
    }

    public function updatePassword(Request $request)
    {
        try {

            $user = Auth::user();

            $validated = $request->validate([
                'old_password' => [
                    'required',
                    'string',
                    function (string $attribute, mixed $value, Closure $fail) use ($user) {
                        if (!Hash::check($value, $user->password)) {
                            $fail('Le mot de passe actuel est incorrect.');
                        }
                    },
                ],
                'new_password' => ['required','string','min:8','confirmed', 'regex:/^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[!@#$%^&*().,\/]).*$/', ],
                'new_password_confirmation' => ['required','string','min:8'],
            ]);

            // Vérifiez si les mots de passe correspondent
            if ($request->input('new_password') !== $request->input('new_password_confirmation')) {
                return redirect()->back()
                    ->withErrors(['new_password_confirmation' => 'Les mots de passe ne sont pas identiques.'])
                    ->withInput();
            }


            $user->update([
                'password' => Hash::make($request->input('new_password')),
            ]);

            return redirect()->route('parametre')->with('success', 'Mot de passe mis à jour avec succès');

        } catch (\Exception $e) {
            return view('error')->with('data', ['error' => $e->getMessage()]);
        }
    }

    public function verifyEmail()
    {
        return redirect()->route('login')->with('success', 'Email vérifié avec succès');
    }

}




