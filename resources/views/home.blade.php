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

<div class="relative sm:ml-32 w-full justify-between">
    <div class="flex items-center justify-center h-screen" style="background-image: url('{{ asset('assets/svg/blurry-gradient.svg') }}'); background-size: cover; background-position: center;">
    @if ($state === 'home' && session('state') !== 'login' && session('state') !== 'register')
        <div id="home_content" class="transition ease-in-out max-w-3xl mx-auto p-8 bg-white shadow-lg rounded-lg">
    @else
        <div id="home_content" class="transition ease-in-out max-w-3xl mx-auto p-8 bg-white shadow-lg rounded-lg blur-[2px]">
    @endif


            @if (Auth::check())
                <p class="text-4xl font-bold mb-6">{{ __('home.hello') }}{{ Auth::user()->name }}</p>
                <h1>{{ __('home.welcome_to') }}</h1>
            @else
                <h1 class="text-4xl font-bold mb-6">{{ __('home.welcome_to') }}</h1>
                <p>{{ __('home.log_in') }}</p>
            @endif

            <div class="mt-8">
                <h2 class="text-2xl font-bold mb-4">{{ __('home.use_our_ai') }}</h2>
                <p>{{ __('home.use_our_ai_desc') }}</p>
            </div>

            <div class="mt-8">
                <h2 class="text-2xl font-bold mb-4">{{ __('home.site_features') }}</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                @if (Auth::check())
                    <a href="{{ route('image_processing') }}" class="bg-gray-200 p-4 rounded-lg hover:bg-gray-300">
                        <h3 class="text-xl font-bold mb-2">{{ __('home.image_feature') }}</h3>
                        <p class="text-gray-600">{{ __('home.image_description') }}</p>
                    </a>

                    <a href="{{ route('video_processing') }}" class="bg-gray-200 p-4 rounded-lg hover:bg-gray-300">
                        <h3 class="text-xl font-bold mb-2">{{ __('home.video_feature') }}</h3>
                        <p class="text-gray-600">{{ __('home.video_description') }}</p>
                    </a>

                    <a href="{{ route('webcam_processing') }}" class="bg-gray-200 p-4 rounded-lg hover:bg-gray-300">
                        <h3 class="text-xl font-bold mb-2">{{ __('home.webcam_feature') }}</h3>
                        <p class="text-gray-600">{{ __('home.webcam_description') }}</p>
                    </a>
                @else
                    <a class="login_button transition ease-in-out bg-gray-200 p-4 rounded-lg hover:bg-gray-300" style="cursor: pointer;">
                        <h3 class="text-xl font-bold mb-2">{{ __('home.image_feature') }}</h3>
                        <p class="text-gray-600">{{ __('home.image_description') }}</p>
                    </a>

                    <a class="login_button transition ease-in-out bg-gray-200 p-4 rounded-lg hover:bg-gray-300" style="cursor: pointer;">
                        <h3 class="text-xl font-bold mb-2">{{ __('home.video_feature') }}</h3>
                        <p class="text-gray-600">{{ __('home.video_description') }}</p>
                    </a>

                    <a href="{{ route('webcam_processing') }}" class="bg-gray-200 p-4 rounded-lg hover:bg-gray-300">
                        <h3 class="text-xl font-bold mb-2">{{ __('home.webcam_feature') }}</h3>
                        <p class="text-gray-600">{{ __('home.webcam_description') }}</p>
                    </a>
                @endif
                </div>
            </div>
        </div>
    </div>
</div>

@if (!Auth::check())
    @if (session('state') === 'login' || $state === 'login')
        <div id="login_form" class="absolute top-0 left-0 sm:ml-32 h-full bg-white shadow-lg p-8 sm:w-96 w-full">
    @else
        <div id="login_form" class="absolute top-0 left-0 sm:ml-32 h-full bg-white shadow-lg p-8 sm:w-96 w-full hidden">
    @endif
            <h3 class="text-2xl font-semibold mb-4">{{ __('home.sign_in') }}</h3>
            <form action="{{ route('login') }}" method="post" enctype="multipart/form-data">
                @csrf
                <div class="mb-4">
                    <label for="email" class="block text-sm font-medium text-gray-600">{{ __('home.email_address') }}</label>
                    <input name="email" id="email" type="email" required
                        class="mt-1 p-2 w-full border rounded-md focus:outline-none focus:ring focus:border-blue-300 <?php echo isset($data['error']) ? 'border-red-500 text-red-600' : ''; ?>">
                </div>
                <div class="mb-4">
                    <label for="password" class="block text-sm font-medium text-gray-600">{{ __('home.password') }}</label>
                    <div class="relative">
                        <input name="password" id="password" type="password" required
                        class="mt-1 p-2 w-full border rounded-md focus:outline-none focus:ring focus:border-blue-300">
                        <span class="absolute inset-y-0 right-0 pr-3 flex items-center text-sm leading-5 cursor-pointer" onclick="togglePassword('password')">
                            <img src="{{ asset('assets/svg/cacher.svg') }}" alt="Cacher le mot de passe" class="h-4 w-4 mt-1">
                        </span>
                    </div>
                </div>
                <div class="text-center mb-3">
                    <a id="forgot_password" tabindex="0" class="text-blue-500 hover:underline focus:outline-none focus:ring-2 focus:ring-blue-500" style="cursor: pointer">{{ __('home.forgot_password') }}</a>
                </div>
                <div class="mb-4">
                    <button type="submit"
                        class="w-full bg-blue-500 text-white p-2 rounded hover:bg-blue-600 focus:outline-none focus:ring focus:border-blue-300">{{ __('home.sign_in') }}
                    </button>
                </div>
            </form>
            <div class="text-center">
                <a id="register_button" tabindex="0" class="text-blue-500 hover:underline focus:outline-none focus:ring-2 focus:ring-blue-500" style="cursor: pointer;">{{ __('home.sign_up') }}</a>
            </div>
            @if(session('success'))
                <br>
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
                <br>
            @endif
            @if(session('error') && $state !== 'register' && session('state') !== 'register' && session('state') !== 'forgot_password' && $state !== 'forgot_password')
                <br>
                <div class="text-red-500 text-center mb-4">
                    {{ session('error') }}
                </div>
            @endif
            @if($errors->any() && $state !== 'register' && session('state') !== 'register' && session('state') !== 'forgot_password' && $state !== 'forgot_password')
                <br>
                <div class="text-red-500 text-center mb-4">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
        </div>

    @if (session('state') === 'register' || $state === 'register')
        <div id="register_form" class="absolute top-0 left-0 sm:ml-32 h-full bg-white shadow-lg p-8 sm:w-96 w-full">
    @else
        <div id="register_form" class="absolute top-0 left-0 sm:ml-32 h-full bg-white shadow-lg p-8 sm:w-96 w-full hidden">
    @endif
            <h3 class="text-2xl font-semibold mb-4">{{ __('home.sign_up') }}</h3>
            <form action="/register" method="post" enctype="multipart/form-data" onsubmit="saveFormData()">
                @csrf
                <div class="mb-4">
                    <label for="name" class="block text-sm font-medium text-gray-600">{{ __('home.first_name') }}</label>
                    <input name="name" id="name" type="text" required pattern="[A-Za-zÀ-ÖØ-öø-ÿ-]+"
                        class="mt-1 p-2 w-full border rounded-md focus:outline-none focus:ring focus:border-blue-300">
                </div>
                <div class="mb-4">
                    <label for="surname" class="block text-sm font-medium text-gray-600">{{ __('home.last_name') }}</label>
                    <input name="surname" id="surname" type="text" required pattern="[A-Za-zÀ-ÖØ-öø-ÿ-]+"
                        class="mt-1 p-2 w-full border rounded-md focus:outline-none focus:ring focus:border-blue-300">
                </div>
                <div class="mb-4">
                    <label for="dateOfBirth" class="block text-sm font-medium text-gray-600">{{ __('home.date_of_birth') }}</label>
                    <input name="dateOfBirth" id="dateOfBirth" type="date" required min="01-01-1900" max="31-12-2100"
                        class="mt-1 p-2 w-full border rounded-md focus:outline-none focus:ring focus:border-blue-300">
                </div>
                <div class="mb-4">
                    <label for="email" class="block text-sm font-medium text-gray-600">{{ __('home.email_address') }}</label>
                    <input name="email" id="email" type="email" required pattern="[A-Za-zÀ-ÖØ-öø-ÿ0-9.-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}"
                        class="mt-1 p-2 w-full border rounded-md focus:outline-none focus:ring focus:border-blue-300">
                </div>
                <div class="mb-4">
                    <label for="password1" class="block text-sm font-medium text-gray-600">{{ __('home.password') }}</label>
                    <div class="relative">
                        <!-- pattern="(?=.*[a-z])(?=.*[A-Z])(?=.*[!@#$%^&*().,\/]).+" -->
                        <input name="password1" id="password1" type="password" required pattern="(?=.*[a-z])(?=.*[A-Z])(?=.*[!@#$%^&*().,\/]).+"
                            class="mt-1 p-2 w-full border rounded-md focus:outline-none focus:ring focus:border-blue-300">
                        <span class="absolute inset-y-0 right-0 pr-3 flex items-center text-sm leading-5 cursor-pointer" onclick="togglePassword('password1')">
                            <img src="{{ asset('assets/svg/cacher.svg') }}" alt="Cacher le mot de passe" class="h-4 w-4 mt-1">
                        </span>
                    </div>
                </div>
                <div class="mb-4">
                    <label for="password2" class="block text-sm font-medium text-gray-600">{{ __('home.confirm_password') }}</label>
                    <div class="relative">
                        <input name="password2" id="password2" type="password" required pattern="(?=.*[a-z])(?=.*[A-Z])(?=.*[!@#$%^&*().,\/]).+"
                            class="mt-1 p-2 w-full border rounded-md focus:outline-none focus:ring focus:border-blue-300">
                        <span class="absolute inset-y-0 right-0 pr-3 flex items-center text-sm leading-5 cursor-pointer" onclick="togglePassword('password2')">
                            <img src="{{ asset('assets/svg/cacher.svg') }}" alt="Cacher le mot de passe" class="h-4 w-4 mt-1">
                        </span>
                    </div>
                </div>
                <div class="mb-4">
                    <input type="checkbox" id="terms" name="terms">
                    <label for="terms">{{ __('home.you_us') }} <a href="/condition-d'utilisation" onclick="window.open(this.href, '_blank', 'width=1100,height=900'); return false;" class="text-blue-500 hover:underline">{{ __('home.condition') }}</a></label>
                </div>
                <div class="mb-4">
                    <button type="submit"
                        class="w-full bg-blue-500 text-white p-2 rounded hover:bg-blue-600 focus:outline-none focus:ring focus:border-blue-300">
                        {{ __('home.sign_up_2') }}
                    </button>
                </div>
                @if(session('error') && session('state') !== 'login')
                <div class="text-red-500 text-center mb-4">
                    {{ session('error')}}
                </div>
                @endif
                @if($errors->any() && session('state') !== 'login')
                    <div class="text-red-500 text-center mb-4">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
            </form>
        </div>

        <div id="forgot_password_form" class="absolute top-0 left-0 sm:ml-32 h-full bg-white shadow-lg p-8 sm:w-96 w-full {{ (session('state') === 'forgot_password' || $state === 'forgot_password') ? '' : 'hidden' }}">
            <h3 class="text-2xl font-semibold mb-4">{{ __('home.forgot_password') }}</h3>
            <form action="{{ route('forgot-password') }}" method="post">
                @csrf
                <div class="mb-4">
                    <label for="email" class="block text-sm font-medium text-gray-600">{{ __('home.email_address') }}</label>
                    <input name="email" id="email_forgot" type="email" required
                           class="mt-1 p-2 w-full border rounded-md focus:outline-none focus:ring focus:border-blue-300">
                </div>
                <div class="mb-4">
                    <button type="submit"
                            class="w-full bg-blue-500 text-white p-2 rounded hover:bg-blue-600 focus:outline-none focus:ring focus:border-blue-300">
                        {{ __('home.envoi_address') }}
                    </button>
                </div>
            </form>
            <div class="text-center">
                <a id="login_from_forgot" class="text-blue-500 hover:underline cursor-pointer">{{ __('home.return_connexion') }}</a>
            </div>
            @if(session('error') && $state !== 'register' && session('state') !== 'register' && session('state') !== 'login' && $state !== 'login')
            <br>
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-2" role="alert">
                <span class="block sm:inline">{{ session('error') }}</span>
            </div>
            @endif
        </div>

        <div id="verify_email_form" class="absolute top-0 left-0 sm:ml-32 h-full bg-white shadow-lg p-8 sm:w-96 w-full {{ session('state') === 'verify-email' || $state === 'verify-email' ? '' : 'hidden' }}">
            <h3 style="font-size: 24px; font-weight: 600; margin-bottom: 16px;">{{ __('home.verify_email') }}</h3>
            <div class="bg-blue-100 border border-blue-400 text-blue-600 px-3 py-3 rounded relative mb-4">
                <p style="font-size: 16px; line-height: 1.5;">{{ __('home.send_email_verify') }}</p>
            </div>
            <div class="text-center">
                <a id="login_from_verify" style="color: #007bff; cursor: pointer; text-decoration: underline;" onclick="location.href='/login';">{{ __('home.return_connexion') }}</a>
            </div>
        </div>

        <script>

            function togglePassword(id) {
                var x = document.getElementById(id);
                if (x.type === "password") {
                    x.type = "text";
                } else {
                    x.type = "password";
                }
            }

            document.addEventListener("DOMContentLoaded", function() {
                loadFormData();

                const form = document.getElementById('register_form');
                const inputs = form.querySelectorAll('input[type=text], input[type=email], input[type=date], input[type=password], input[type=checkbox]');

                inputs.forEach(input => {
                    const savedValue = localStorage.getItem(input.name);
                    if (savedValue) {
                        if (input.type === 'checkbox') {
                            input.checked = savedValue === 'true' ? true : false;
                        } else {
                            input.value = savedValue;
                        }
                    }

                    input.addEventListener('change', () => {
                        if (input.type === 'checkbox') {
                            localStorage.setItem(input.name, input.checked);
                        } else {
                            localStorage.setItem(input.name, input.value);
                        }
                    });
                });

                form.addEventListener('submit', () => {
                    localStorage.clear();
                });

                document.getElementById('forgot_password').addEventListener('click', function() {
                    showForm('forgot_password_form');
                });

                document.getElementById('login_from_forgot').addEventListener('click', function() {
                    showForm('login_form');
                });

                document.getElementById('register_button').addEventListener('click', function() {
                    showForm('register_form');
                });

                document.querySelectorAll('.login_button').forEach(function(button) {
                    button.addEventListener('click', function() {
                        showForm('login_form');
                    });
                });

                document.getElementById('login_button').addEventListener('click', function() {
                    showForm('login_form');
                });

                document.getElementById('home_button').addEventListener('click', function() {
                    showForm('home_content');
                });

                function showForm(formId) {
                    document.getElementById('login_form').classList.add('hidden');
                    document.getElementById('register_form').classList.add('hidden');
                    document.getElementById('forgot_password_form').classList.add('hidden');
                    document.getElementById('home_content').classList.add('blur-[2px]');

                    document.getElementById(formId).classList.remove('hidden');

                    if (formId === 'home_content') {
                        document.getElementById('home_content').classList.remove('blur-[2px]');
                    }
                }

                function saveFormData() {
                    const formData = {
                        name: document.getElementById('name').value,
                        surname: document.getElementById('surname').value,
                        dateOfBirth: document.getElementById('dateOfBirth').value,
                        email: document.getElementById('email').value,
                        password1: document.getElementById('password1').value,
                        password2: document.getElementById('password2').value,
                        terms: document.getElementById('terms').checked,
                    };
                    localStorage.setItem('formData', JSON.stringify(formData));
                }

                function loadFormData() {
                    const formData = JSON.parse(localStorage.getItem('formData'));
                    if (formData) {
                        document.getElementById('name').value = formData.name;
                        document.getElementById('surname').value = formData.surname;
                        document.getElementById('dateOfBirth').value = formData.dateOfBirth;
                        document.getElementById('email').value = formData.email;
                        document.getElementById('password1').value = formData.password1;
                        document.getElementById('password2').value = formData.password2;
                        document.getElementById('terms').checked = formData.terms;
                    }
                }
            });
        </script>

@endif



