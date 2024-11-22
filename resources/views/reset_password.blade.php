<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>YoloV8 webUI - Reset Password</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="/assets/images/favicon.png" type="image/png">
    <!-- Importation Tailwind CSS -->
    <link rel="stylesheet" href="{{ asset('static/css/output.css') }}">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700&display=swap">
</head>

<body>
    
    @if(session('success'))
            <div class="bg-green-200 border-l-4 border-green-500 text-green-700 p-4" role="alert">
                <p class="font-bold">{{ __('contact_us.success_alert') }}</p>
                <p>{{ session('success') }}</p>
            </div>
            <br>
            @endif
            @if($errors->any())
            <div class="bg-red-200 border-l-4 border-red-500 text-red-700 p-4" role="alert">
                <p class="font-bold">{{ __('contact_us.error_alert') }}</p>
                <ul>
                    @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            <br>
            @endif

    <section class="text-gray-600 body-font relative">
        <div class="container px-5 py-24 mx-auto">
            <div class="flex flex-col text-center w-full mb-12">
                <h1 class="sm:text-3xl text-2xl font-medium title-font mb-4 text-gray-900">
                    {{ __('reset_password.change_your_password') }}
                </h1>
            </div>
            <form action="{{ route('password-update') }}" method="POST">
                <input type="hidden" name="token" value="{{ $token }}"> 
                @csrf
                <div class="lg:w-1/2 md:w-2/3 mx-auto">
                    <div class="flex flex-wrap -m-2">
                        <div class="p-2 w-full">
                            <div class="relative">
                                <label for="email" class="leading-7 text-sm text-gray-600">Email</label>
                                <input type="email" id="email" name="email" required class="w-full bg-gray-100 rounded border border-gray-300 focus:border-blue-500 focus:bg-white focus:ring-2 focus:ring-blue-200 text-base outline-none text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out">
                            </div>
                        </div>
                        <div class="p-2 w-full">
                            <div class="relative">
                                <label for="new_password" class="leading-7 text-sm text-gray-600">{{ __('reset_password.new_password') }}</label>
                                <input type="password" id="new_password" name="new_password" pattern="(?=.*[a-z])(?=.*[A-Z])(?=.*[!@#$%^&*().,\/]).+" required class="w-full bg-gray-100 rounded border border-gray-300 focus:border-blue-500 focus:bg-white focus:ring-2 focus:ring-blue-200 text-base outline-none text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out">
                                <span class="absolute inset-y-1 right-0 pr-3 bottom-0 flex items-center justify-center text-sm leading-5 cursor-pointer" onclick="togglePassword('new_password')">
                                    <img src="{{ asset('assets/svg/cacher.svg') }}" alt="Cacher le mot de passe" class="h-4 w-4">
                                </span>
                            </div>
                        </div>
                        <div class="p-2 w-full">
                            <div class="relative">
                                <label for="new_password_confirmation" class="leading-7 text-sm text-gray-600">{{ __('reset_password.confirm_new_password') }}</label>
                                <input type="password" id="new_password_confirmation" name="new_password_confirmation" pattern="(?=.*[a-z])(?=.*[A-Z])(?=.*[!@#$%^&*().,\/]).+" required class="w-full bg-gray-100 rounded border border-gray-300 focus:border-blue-500 focus:bg-white focus:ring-2 focus:ring-blue-200 text-base outline-none text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out">
                                <span class="absolute inset-y-0 right-0 pr-3 flex items-center justify-center text-sm leading-5 cursor-pointer" onclick="togglePassword('new_password_confirmation')">
                                    <img src="{{ asset('assets/svg/cacher.svg') }}" alt="Cacher le mot de passe" class="h-4 w-4">
                                </span>
                            </div>
                        </div>
                        <div class="p-4 py-8 w-full">
                            <button type="submit" class="flex mx-auto text-white bg-blue-500 border-0 py-2 px-8 focus:outline-none hover:bg-blue-600 rounded text-lg">
                                {{ __('reset_password.change_password_button') }}
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </section>


    <script>
        function togglePassword(id) {
            var x = document.getElementById(id);
            if (x.type === "password") {
                x.type = "text";
            } else {
                x.type = "password";
            }
        }

    </script>
</body>