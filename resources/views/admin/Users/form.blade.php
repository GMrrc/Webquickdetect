@extends('admin.admin')

@section('title', $User->exists ? "Éditer un utilisateur" : "Créer un utilisateur")

@section('content')
@php
if (Auth::guest() || Auth::user()->role != 'admin') {
return redirect('/login');
}
@endphp

<div class="flex items-center justify-center min-h-screen bg-gray-100">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-6 bg-white shadow-lg rounded-lg">
        <h1 class="text-2xl font-bold text-center mb-6">@yield('title')</h1>

        <form class="flex flex-col gap-4"
            action="{{ route($User->exists ? 'admin.UserAdmin.update' : 'admin.UserAdmin.store', $User) }}"
            method="post" enctype="multipart/form-data">
            @csrf
            @method($User->exists ? 'put' : 'post')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label for="nom" class="block text-sm font-medium text-gray-700">Nom</label>
                    <input type="text" id="nom" name="nom" value="{{ old('nom', $User->name) }}"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                </div>

                <div>
                    <label for="prenom" class="block text-sm font-medium text-gray-700">Prénom</label>
                    <input type="text" id="prenom" name="prenom" value="{{ old('prenom', $User->surname) }}"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                </div>
            </div>

            <div class="mt-4 grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label for="dateNaissance" class="block text-sm font-medium text-gray-700">Date de Naissance</label>
                    <input type="date" id="dateNaissance" name="dateNaissance"
                        value="{{ old('dateNaissance', $User->dateOfBirth) }}"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                </div>

                <div>
                    <label for="role" class="block text-sm font-medium text-gray-700">Rôle</label>
                    <select id="role" name="role"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                        <option value="admin" {{ old('role', $User->role) == 'admin' ? 'selected' : '' }}>Admin</option>
                        <option value="user" {{ old('role', $User->role) == 'user' ? 'selected' : '' }}>User</option>
                        <!-- Ajouter d'autres rôles ici si nécessaire -->
                    </select>
                </div>
            </div>

            <div class="mt-4 grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                    <input type="email" id="email" name="email" value="{{ old('email', $User->email) }}"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                </div>

                <div>
                    <label for="motDePasse" class="block text-sm font-medium text-gray-700">Mot de Passe</label>
                    <input type="password" id="motDePasse" name="motDePasse"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                </div>
            </div>

            <div class="mt-6 flex justify-center">
                <button type="submit"
                    class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                    @if($User->exists)
                    Modifier
                    @else
                    Créer
                    @endif
                </button>
            </div>

            @if(session('success'))
            <div class="mt-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative"
                role="alert">
                <span class="block sm:inline">{{ session('success') }}</span>
            </div>
            @endif

            @if($errors->any())
            <div class="mt-4 text-red-500 text-center">
                <ul>
                    @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif
        </form>
    </div>
</div>
@endsection