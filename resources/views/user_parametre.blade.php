@if (Auth::check())
{{-- Vérifie le rôle de l'utilisateur --}}
@if (Auth::user()->role == 'admin')
{{-- Inclure le menu pour les administrateurs --}}
@include('includes.head_connected_admin')
@else
{{-- Inclure le menu pour les utilisateurs normaux --}}
@include('includes.head_connected_user')
@endif
@else
{{-- Inclure le menu pour les visiteurs non connectés --}}
@include('includes.head')
@endif


<html>

<body class="bg-gray-100">
    <?php 
        if (!Auth()->check()) {
            return redirect('/login');
        }
    ?>

    <div class="sm:ml-32 w-full justify-between">
        @if(session('success'))
        <div class="bg-green-200 border-l-4 border-green-500 text-green-700 p-4" role="alert">
            <p class="font-bold">Succès</p>
            <p>{{ session('success') }}</p>
        </div>
        <br>
        @endif
        @if($errors->any())
        <div class="bg-red-200 border-l-4 border-red-500 text-red-700 p-4" role="alert">
            <p class="font-bold">Erreur</p>
            <ul>
                @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        <br>
        @endif

        <div class="max-w-4xl mx-auto my-10 bg-white p-8 rounded-lg shadow-lg">
            <div class="flex justify-center mb-6">
                <img src="{{ asset('assets/images/avatar.png') }}" alt="Avatar" class="profile-avatar" style="width: 150px;  height: 150px; object-fit: cover; border-radius: 50%;">
            </div>
            <div class="text-center mb-10">
                <h1 class="text-3xl font-medium text-gray-900">{{ __('user_settings.personal_space') }}</h1>
            </div>

            <form action="{{ route('parametre', $user) }}" method="post" enctype="multipart/form-data">
                @csrf
                @method('post')
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                    <div class="relative">
                        <label for="name" class="leading-7 text-sm text-gray-600">{{ __('user_settings.first_name') }}</label>
                        <input type="text" id="name" name="name" value="{{ $user->name }}"
                            class="w-full bg-gray-100 bg-opacity-50 rounded border border-gray-300 focus:border-blue-500 focus:bg-white focus:ring-2 focus:ring-blue-200 text-base outline-none text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out">
                    </div>
                    <div class="relative">
                        <label for="surname" class="leading-7 text-sm text-gray-600">{{ __('user_settings.last_name') }}</label>
                        <input type="text" id="surname" name="surname" value="{{ $user->surname }}"
                            class="w-full bg-gray-100 bg-opacity-50 rounded border border-gray-300 focus:border-blue-500 focus:bg-white focus:ring-2 focus:ring-blue-200 text-base outline-none text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out">
                    </div>
                    <div class="relative">
                        <label for="dateOfBirth" class="leading-7 text-sm text-gray-600">{{ __('user_settings.date_of_birth') }}</label>
                        <input type="date" id="dateOfBirth" value="{{ $user->dateOfBirth }}" name="dateOfBirth"
                            class="w-full bg-gray-100 bg-opacity-50 rounded border border-gray-300 focus:border-blue-500 focus:bg-white focus:ring-2 focus:ring-blue-200 text-base outline-none text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out">
                    </div>
                    <div class="relative">
                        <label for="email" class="leading-7 text-sm text-gray-600">{{ __('user_settings.email') }}</label>
                        <input type="email" id="email" name="email" value="{{ $user->email }}"
                            class="w-full bg-gray-100 bg-opacity-50 rounded border border-gray-300 focus:border-blue-500 focus:bg-white focus:ring-2 focus:ring-blue-200 text-base outline-none text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out">
                    </div>
                </div>

                <div class="text-center mt-6">
                    <a href="{{ route('new-password') }}" class="text-blue-500 hover:text-blue-700 underline hover:no-underline">{{ __('user_settings.change_password') }}</a>
                </div>

                <div class="text-center mt-6">
                    <button type="submit" class="text-white bg-blue-500 border-0 py-2 px-8 focus:outline-none hover:bg-blue-600 rounded text-lg">{{ __('user_settings.update') }}</button>
                </div>
            </form>

            <div class="p-4 w-full">
                <form action="{{ route('parametre', $user) }}" method="post" enctype="multipart/form-data">
                    @csrf
                    @method("delete")
                    <button onclick="return confirmDelete()"
                        class="flex mx-auto text-white bg-red-500 border-0 py-2 px-8 focus:outline-none hover:bg-red-600 rounded text-lg">
                        Supprimer
                    </button>
                </form>
            </div>

            <div class="text-center mt-6">
                <div class="relative inline-block text-left">
                    <button type="button" class="inline-flex justify-center w-full rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" id="menu-button" aria-expanded="true" aria-haspopup="true">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 inline-block mr-2" viewBox="0 0 20 20" fill="currentColor">
                            <path d="M10 2a8 8 0 100 16 8 8 0 000-16zm.75 13.98a6.98 6.98 0 01-2.98-.75 7.001 7.001 0 01-2.98-2.98 6.98 6.98 0 01-.75-2.98h7.71a6.98 6.98 0 01-.75 2.98 7.001 7.001 0 01-2.98 2.98zm0-9.96a6.98 6.98 0 01-2.98.75 6.98 6.98 0 01-2.98-.75 6.978 6.978 0 012.98-.75c1.11 0 2.18.22 2.98.75zm0 1.25h-3.5A6.98 6.98 0 015.5 10a6.98 6.98 0 011.75-2.48 7.001 7.001 0 012.98-.75c.34 0 .68.03 1 .1v.12zm0 4.48v.13c-.32.07-.66.1-1 .1-1.11 0-2.18-.22-2.98-.75A6.978 6.978 0 015.5 10h7.71a6.978 6.978 0 01-2.98.75c-.34 0-.68-.03-1-.1z" />
                        </svg>
                        Langues
                        <svg class="-mr-1 ml-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                        </svg>
                    </button>

                    <div class="hidden origin-top-right absolute right-0 mt-2 w-56 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5" id="lang_options" role="menu" aria-orientation="vertical" aria-labelledby="menu-button" tabindex="-1">
                        <div class="py-1" role="none">
                            <a href="{{ route('change_language', ['locale' => 'en']) }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" role="menuitem" tabindex="-1" id="menu-item-0">{{ __('user_settings.english') }}</a>
                            <a href="{{ route('change_language', ['locale' => 'fr']) }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" role="menuitem" tabindex="-1" id="menu-item-1">{{ __('user_settings.french') }}</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function confirmDelete() {
            return confirm("Êtes-vous sûr de vouloir supprimer votre compte ? Cette action est irréversible.");
        }

        document.getElementById('menu-button').addEventListener('click', function () {
            document.getElementById('lang_options').classList.toggle('hidden');
        });
    </script>
</body>

</html>
