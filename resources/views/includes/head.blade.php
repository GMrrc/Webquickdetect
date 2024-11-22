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
    <style>
        body, h1, h2, h3, h4, h5, h6, p, a {
            user-select: none;
        }
    </style>
</head>

<body class="bg-white-300 flex flex-row min-h-screen">
    <nav id="mobile_navbar" class="fixed bg-gray-700 sm:w-32 w-42 py-4 h-full flex-col justify-between hidden z-50 sm:flex" aria-label="Main navigation">
        <div class="flex flex-col">
            <a id="home_button" tabindex="0" class="transition ease-in-out text-white px-3 text-2xl font-bold mb-4 focus:outline-none focus:ring-2 focus:ring-blue-500" style="cursor: pointer;">{{ __('header.home') }}</a>
            <a id="login_button" tabindex="0" class="transition ease-in-out w-full bg-gray-700 text-white p-3 hover:bg-blue-600 border-t-2 border-b-2 border-gray-600 hover:border-transparent focus:outline-none focus:ring-2 focus:ring-blue-500" style="cursor: pointer;">{{ __('header.login') }}</a>
            <a href="{{ route('webcam_processing') }}" class="transition ease-in-out w-full bg-gray-700 text-white p-3 hover:bg-blue-600 border-b-2 border-gray-600 hover:border-transparent">{{ __('header.Demo') }}</a>

            <div class="transition ease-in-out w-full bg-gray-700 text-white p-3 hover:bg-blue-600 border-b-2 border-gray-600 hover:border-transparent cursor-pointer lang-trigger focus:outline-none focus:ring-2 focus:ring-blue-500" tabindex="0">{{ __('header.change_language') }}
                <div id="lang_options" class="hidden mt-2">
                    <a href="{{ route('change_language', ['locale' => 'en']) }}" class="block text-white py-2 hover:bg-blue-600 transition-colors border-b-2 border-transparent hover:border-white focus:outline-none focus:ring-2 focus:ring-blue-500">{{ __('header.english') }}</a>
                    <a href="{{ route('change_language', ['locale' => 'fr']) }}" class="block text-white py-2 hover:bg-blue-600 transition-colors border-b-2 border-transparent hover:border-white focus:outline-none focus:ring-2 focus:ring-blue-500">{{ __('header.french') }}</a>
                </div>
            </div>
        </div>
        <div class="flex flex-col">
            <a href="{{ route('apropos') }}" class="transition ease-in-out w-full bg-gray-700 text-white p-3 hover:bg-blue-600 border-t-2 border-b-2 border-gray-600 hover:border-transparent focus:outline-none focus:ring-2 focus:ring-blue-500">{{ __('header.about') }}</a>
            <a href="{{ route('contact') }}" class="transition ease-in-out w-full bg-gray-700 text-white p-3 hover:bg-blue-600 border-b-2 border-gray-600 hover:border-transparent focus:outline-none focus:ring-2 focus:ring-blue-500">{{ __('header.contact_us') }}</a>
            <p class="w-full text-xs bg-gray-700 text-white p-3">G.Mrrc M.Perrudin A.Bravo</p>
        </div>
    </nav>
    @yield('content')
    <button id="mobile_navbar_button" class="fixed top-0 right-0 bg-gray-700 text-white p-4 hover:bg-blue-600 rounded-xl sm:hidden z-50 text-xl focus:outline-none focus:ring-2 focus:ring-blue-500" aria-label="Open navigation menu">Icon</button>
</body>

</html>

<script>
    document.getElementById('mobile_navbar_button').addEventListener('click', function() {
        document.getElementById('mobile_navbar').classList.toggle('hidden');
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

    document.getElementById('home_button').addEventListener('click', function() {
        if (window.location.href !== "{{ route('login') }}" && window.location.href !== "{{ route('home') }}" && window.location.href !== "{{ route('register') }}") {
            window.location.href = "{{ route('home') }}";
        }
    });

    document.getElementById('login_button').addEventListener('click', function() {
        if (window.location.href !== "{{ route('login') }}" && window.location.href !== "{{ route('home') }}" && window.location.href !== "{{ route('register') }}") {
            window.location.href = "{{ route('login') }}";
        }
    });

    document.querySelector('.lang-trigger').addEventListener('click', function() {
        document.getElementById('lang_options').classList.toggle('hidden');
    });

    document.querySelector('.lang-trigger').addEventListener('keypress', function(event) {
        handleKeyPress(event, function() {
            document.getElementById('lang_options').classList.toggle('hidden');
        });
    });
</script>
