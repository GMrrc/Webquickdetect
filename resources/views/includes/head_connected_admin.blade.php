<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>YoloV8 webUI</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="/assets/images/favicon.png" type="image/png">
    <!-- Importation Tailwind CSS -->
    <link rel="stylesheet" href="{{ asset('static/css/output.css') }}">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700&display=swap">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0" />
</head>

<body class="bg-white-300 flex flex-row min-h-screen">
    <nav class="bg-gray-700 w-32 py-4 h-full flex flex-col justify-between fixed">
        <div class="flex flex-col">
            <a href="{{ route('home') }}" class="text-white px-3 text-2xl font-bold mb-4">{{ __('header.home') }}</a>
            <a href="{{ route('admin.UserAdmin.index') }}"
                class="transition ease-in-out w-full bg-gray-700 text-white p-3 hover:bg-blue-600 border-t-2 border-b-2 border-gray-600 hover:border-transparent">{{ __('header.administrator') }}</a>
            <div class="transition ease-in-out w-full bg-gray-700 text-white p-3 hover:bg-blue-600 border-b-2 border-gray-600 hover:border-transparent cursor-pointer lang-trigger">{{ __('header.change_language') }}
                <div id="lang_options" class="hidden mt-2">
                <a href="{{ route('change_language', ['locale' => 'en']) }}" class="block text-white py-2 hover:bg-blue-600 transition-colors border-b-2 border-transparent hover:border-white">{{ __('header.english') }}</a>
                <a href="{{ route('change_language', ['locale' => 'fr']) }}" class="block text-white py-2 hover:bg-blue-600 transition-colors border-b-2 border-transparent hover:border-white">{{ __('header.french') }}</a>
                </div>
            </div>
        </div>
        <div class="flex flex-col">
            <a href="{{ route('parametre') }}"
                class="transition ease-in-out w-full bg-gray-700 text-white p-3 hover:bg-blue-600 border-t-2 border-gray-600 hover:border-transparent">{{ __('header.setting') }}</a>
            <a href="{{ route('logout') }}"
                class="transition ease-in-out w-full bg-gray-700 text-white p-3 hover:bg-red-500 border-t-2 border-b-2 border-gray-600 hover:border-transparent">{{ __('header.disconnect') }}</a>
            <p class="w-full text-xs bg-gray-700 text-white p-3">G.Mrrc M.Perrudin A.Bravo</p>
        </div>
    </nav>
    <div class="mx-auto flex-col justify-center items-center">
        @yield('content')
    </div>
    <button id="mobile_navbar_button"
        class="fixed top-0 right-0 bg-gray-700 text-white p-4 hover:bg-blue-600 rounded-xl sm:hidden z-50 text-xl">Icon
    </button>
</body>

</html>


<script>
    document.getElementById('mobile_navbar_button').addEventListener('click', function() {
        document.getElementById('mobile_navbar').classList.toggle('hidden');
    });

    document.querySelector('.lang-trigger').addEventListener('click', function() {
        document.getElementById('lang_options').classList.toggle('hidden');
    });

    function isChildOfNavbar(element) {
        var navbar = document.getElementById('mobile_navbar');
        while (element.parentNode) {
            if (element == navbar) {
                return true;
            }
            element = element.parentNode;
        }
        return false;
    }

    function hideNavbarOnClickOutside(event) {
        var target = event.target;
        var navbar = document.getElementById('mobile_navbar');
        var navbarButton = document.getElementById('mobile_navbar_button');

        if (!isChildOfNavbar(target) && target !== navbar && target !== navbarButton) {
            navbar.classList.add('hidden');
        }
    }

    document.addEventListener('click', hideNavbarOnClickOutside);
</script>
