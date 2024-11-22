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
    <!-- <style>
        body, h1, h2, h3, h4, h5, h6, p, a {
            user-select: none;
        }
    </style> -->
</head>

<body class="bg-white-300 flex flex-row min-h-screen">
    <nav id="mobile_navbar" class="fixed bg-gray-700 sm:w-32 w-42 py-4 h-full flex-col justify-between hidden z-50 sm:flex">
        <div class="flex flex-col">
            <a href="{{ route('home') }}"
                class="text-white px-3 text-2xl font-bold mb-4">{{ __('header.home') }}</a>
            <a href="{{ route('webcam_processing') }}"
                class="transition ease-in-out w-full bg-gray-700 text-white p-3 hover:bg-blue-600 border-t-2 border-b-2 border-gray-600 hover:border-transparent">{{ __('header.webCam') }}</a>
            <a href="{{ route('video_processing') }}"
                class="transition ease-in-out w-full bg-gray-700 text-white p-3 hover:bg-blue-600 border-b-2 border-gray-600 hover:border-transparent">{{ __('header.movie') }}</a>
            <a href="{{ route('image_processing') }}"
                class="transition ease-in-out w-full bg-gray-700 text-white p-3 hover:bg-blue-600 border-b-2 border-gray-600 hover:border-transparent">{{ __('header.picture') }}</a>
            <a href="{{ route('library.index') }}"
                class="transition ease-in-out w-full bg-gray-700 text-white p-3 hover:bg-green-600 border-b-2 border-gray-600 hover:border-transparent">{{ __('header.librairy') }}</a>
            <a href="/help"
                class="transition ease-in-out w-full bg-gray-700 text-white p-3 hover:bg-blue-600 border-b-2 border-gray-600 hover:border-transparent">{{ __('header.help') }}</a>
        </div>
        <div class="flex flex-col">
            <a href="{{ route('apropos') }}"
                class="transition ease-in-out w-full bg-gray-700 text-white p-3 hover:bg-blue-600 border-t-2 border-gray-600 hover:border-transparent">{{ __('header.about') }}</a>
            <a href="{{ route('contact') }}"
                class="transition ease-in-out w-full bg-gray-700 text-white p-3 hover:bg-blue-600 border-t-2 border-gray-600 hover:border-transparent">{{ __('header.contact_us') }}</a>
            <a href="{{ route('parametre') }}"
                class="transition ease-in-out w-full bg-gray-700 text-white p-3 hover:bg-blue-600 border-t-2 border-gray-600 hover:border-transparent">{{ __('header.setting') }}</a>
            <a href="{{ route('logout') }}"
                class="transition ease-in-out w-full bg-gray-700 text-white p-3 hover:bg-red-500 border-t-2 border-b-2 border-gray-600 hover:border-transparent">{{ __('header.disconnect') }}</a>
            <p class="w-full text-xs bg-gray-700 text-white p-3">G.Mrrc M.Perrudin A.Bravo</p>
        </div>
    </nav>
    @yield('content')
    <button id="mobile_navbar_button"
        class="fixed top-0 right-0 bg-gray-700 text-white p-4 hover:bg-blue-600 rounded-xl sm:hidden z-50 text-xl">Icon
    </button>
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
</script>
