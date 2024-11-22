<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\UserAdminRequest; 
use App\Http\Controllers\Controller;
use App\Models\Library;
// use App\Http\Requests\Library;
use App\Models\Picture;
use App\Models\User;
use App\Models\Video;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;


class UserAdminController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('admin.Users.index', 
        [
            'users' => User::orderBy('created_at', 'desc')->paginate(20)
        ]);
    }

    public function create(){
        
        $user = new User();
        return view('admin.Users.form',[
            'User' => $user,
            //'options' => Option::pluck('name','id'),

        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(UserAdminRequest $request)
    {
        try {

            // Récupérez toutes les données validées
            $validatedData = $request->validated();

            $user = new User();
            $user->name = $validatedData['nom'];
            $user->surname = $validatedData['prenom'];
            $user->dateOfBirth = '2020-01-01'; 
            $user->role = 'admin';
            $user->email = $validatedData['email'];
            $user->password = Hash::make($validatedData['motDePasse']);
            $user->save();

            $library = new Library();
            $library->name = 'Default Library';
            $library->idUser = $user->idUser;
            $library->save();

            // Redirigez avec un message de succès
            return to_route('admin.UserAdmin.index')->with('success', 'Le User a bien été créé');
        } catch (\Exception $e) {
            return redirect()->route('error')->with('error', $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id){}

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        try {
            $user = User::findOrFail($id);
            return view('admin.Users.form', ['User' => $user]);
        } catch (\Exception $e) {
            return redirect()->route('error')->with('error', $e->getMessage());
        }
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        try {

            $user = User::find($id);
            $email = $request->email;

            if(User::where('email', $email)->where('idUser', '!=', $user->idUser)->exists()) {
                return view('error')->with('data', ['error' => 'Email already exists']);
            }

            $validatedData = $request->validate([
                'nom' => "required|string|max:255|regex:/[A-Za-zÀ-ÖØ-öø-ÿ '-]+/",
                'prenom' => "required|string|max:255|regex:/[A-Za-zÀ-ÖØ-öø-ÿ '-]+/",
                'dateNaissance' => 'required|date',
                'role' => 'required|string|max:25',
                'email' => [
                    'required',
                    'email',
                ],
            ], [
                'nom.regex' => 'Le format du nom n’est pas valide.',
                'nom.required' => 'Le nom est obligatoire',
                'prenom.required' => 'Le prénom est obligatoire',
                'prenom.regex' => 'Le format du prénom n’est pas valide.',
                'dateNaissance.required' => 'La date de naissance est obligatoire',
                'dateNaissance.date' => 'Veuillez entrer une date valide.',
                'role.required' => 'Le rôle est obligatoire',
                'email.required' => 'L\'email est obligatoire',
                'email.email' => 'L\'email doit être valide',
            
            ]);

            $user->name = $validatedData['nom'];
            $user->surname = $validatedData['prenom'];
            $user->dateOfBirth = $validatedData['dateNaissance'];
            $user->role = $validatedData['role'];
            $user->email = $validatedData['email'];

            if (isset($request->motDePasse)) {
                $user->password = Hash::make($request->motDePasse);
            }
            $user->update();

            return redirect()->route('admin.UserAdmin.index')->with('success', 'Le user a bien été modifié');
        } catch (\Exception $e) {
            return redirect()->route('error')->with('error', $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $user = User::find($id);
            $librarie = $user->library()->first();
            if (!is_null($librarie)) {
                if ($librarie->pictures()->exists()) {
                    Picture::destroy($librarie->pictures()->pluck('idPicture'));
                }
    
                if ($librarie->videos()->exists()) {
                    Video::destroy($librarie->videos()->pluck('idVideo'));
                }
                Library::destroy($user->library()->pluck('idLibrary'));
                $librarie->delete();
            }
            $user->delete();
        } catch (\Exception $e) {
            return redirect()->route('error')->with('error', $e->getMessage());
        }
        return to_route('admin.UserAdmin.index')->with('success', 'Le User a bien été supprimé');
    }


    public function destroyLibrairy($id)
    {
        try {
            $user = User::find($id);
            $librarie = $user->library()->first();
            if (!is_null($librarie)) {
                if ($librarie->pictures()->exists()) {
                    Picture::destroy($librarie->pictures()->pluck('idPicture'));
                }
    
                if ($librarie->videos()->exists()) {
                    Video::destroy($librarie->videos()->pluck('idVideo'));
                }
            }
        } catch (\Exception $e) {
            return redirect()->route('error')->with('error', $e->getMessage());
        }
        return to_route('admin.UserAdmin.index')->with('success', 'Les librairies sont supprimées');
    }
}