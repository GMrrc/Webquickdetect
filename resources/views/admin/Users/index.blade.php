@extends('admin.admin')

@section('title', 'Tous les Utilisateurs')

@section('content')
    @php
        if (Auth::guest() || Auth::user()->role != 'admin') {
            return redirect('/login');
        }
    @endphp
    @if(session('success'))
        <div class="sm:ml-32 bg-green-200 border-l-4 border-green-500 text-green-700 p-4" role="alert">
            <p class="font-bold">Succès</p>
            <p>{{ session('success') }}</p>
        </div>
        <br>
    @endif
    @if($errors->any())
        <div class="sm:ml-32 bg-red-200 border-l-4 border-red-500 text-red-700 p-4" role="alert">
            <p class="font-bold">Erreur</p>
            <ul>
                @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        <br>
    @endif
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-6"> <!-- Limiter la largeur, centrer et ajouter du padding vertical -->

        <div class="mb-6"> <!-- Margin Bottom pour le titre -->
            <h1 class="text-2xl font-bold">@yield('title')</h1>
        </div>

        <div class="flex justify-between items-center mb-6"> <!-- Margin Bottom pour le bouton et l'espacement entre le titre -->
            <a href="{{ route('admin.UserAdmin.create') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold rounded py-1 px-4">Ajouter un utilisateur</a>
        </div>

        <div class="overflow-x-auto bg-white shadow-md rounded-lg">
            <table class="table-auto w-full">
                <thead class="bg-gray-200">
                    <tr>
                        <th class="px-4 py-2">Nom</th>
                        <th class="px-4 py-2">Prénom</th>
                        <th class="px-4 py-2">Date de naissance</th>
                        <th class="px-4 py-2">Rôle</th>
                        <th class="px-4 py-2">Email</th>
                        <th class="px-4 py-2">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($users as $user)
                    <tr>
                        <td class="px-4 py-2">{{ $user->name }}</td>
                        <td class="px-4 py-2">{{ $user->surname }}</td>
                        <td class="px-4 py-2">{{ $user->dateOfBirth }}</td>
                        <td class="px-4 py-2">{{ $user->role }}</td>
                        <td class="px-4 py-2">{{ $user->email }}</td>
                        <td class="px-4 py-2">
                            <div class="flex gap-2 justify-end">
                                <form action="{{ route('admin.UserAdmin.destroyLibrairy', $user) }}" method="post" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer toutes les bibliothèques associées à cet utilisateur ?');">
                                    @csrf
                                    @method('delete')
                                    <button class="bg-green-500 hover:bg-green-700 text-white font-bold rounded py-1 px-4 flex items-center relative">
                                        <span class="material-symbols-outlined">delete</span>
                                        <span class="absolute left-full ml-2 w-max px-2 py-1 bg-gray-700 text-white text-xs rounded hidden group-hover:block">Supprimer la librairie</span>
                                    </button>
                                </form>
                                <a href="{{ route('admin.UserAdmin.edit', $user) }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold rounded py-1 px-4">Éditer</a>
                                <form action="{{ route('admin.UserAdmin.destroy', $user) }}" method="post" class="delete-user-form">
                                    @csrf
                                    @method('delete')
                                    <button class="bg-red-500 hover:bg-red-700 text-white font-bold rounded py-1 px-4">Supprimer</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{ $users->links() }}
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const deleteForms = document.querySelectorAll('.delete-user-form');
            deleteForms.forEach(form => {
                form.addEventListener('submit', function (e) {
                    if (!confirm('Êtes-vous sûr de vouloir supprimer cet utilisateur ?')) {
                        e.preventDefault();
                    } else {
                        if (!confirm('Cette action est irréversible. Êtes-vous vraiment sûr ?')) {
                            e.preventDefault();
                        }
                    }
                });
            });
        });
    </script>

    <style>
        .relative .group-hover\:block {
            display: none;
        }
        .relative:hover .group-hover\:block {
            display: block;
        }
    </style>
@endsection
