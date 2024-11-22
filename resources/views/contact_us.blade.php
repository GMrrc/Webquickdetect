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


<body style="background-color:  white;">
    <div class="sm:ml-32 w-full justify-between">

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



        <!-- Titre Principal et sous titre partie 1-->
        <section class="text-gray-600 body-font" style="background-color:  white;">
            <div class="container pt-10 mx-auto">
                <div class="flex flex-wrap w-full flex-col items-center text-center">
                    <h1 class="sm:text-5xl text-2xl font-medium title-font mb-2 text-gray-900">
                        <font style="vertical-align: inherit;">
                            <font style="vertical-align: inherit;">{{ __('contact_us.contact_us') }}</font>
                        </font>
                    </h1>
                </div>
        </section>

        <section class="text-gray-600 body-font">
            <div class="container mx-auto flex px-5 py-24 md:flex-row flex-col items-center">
                <div
                    class="lg:flex-grow md:w-1/2 lg:pr-24 md:pr-16 flex flex-col md:items-start md:text-left mb-16 md:mb-0 items-center text-center">
                    <h1 class="title-font sm:text-4xl text-3xl mb-4 font-medium text-gray-900">
                        <font style="vertical-align: inherit;">
                            <font style="vertical-align: inherit;">{{ __('contact_us.discover_our_technology') }}</font>
                        </font>
                    </h1>
                    <p class="mb-8 leading-relaxed">
                        <font style="vertical-align: inherit;">
                            <font style="vertical-align: inherit;">{{ __('contact_us.project_description') }}
                            </font>
                        </font>
                    </p>
                    <div class="flex justify-center">
                        <button
                            class="inline-flex text-white bg-blue-500 border-0 py-2 px-6 focus:outline-none hover:bg-blue-600 rounded text-lg">
                            <font style="vertical-align: inherit;">
                                <font style="vertical-align: inherit;"><a href="{{ route('apropos')}}" class="button-style">{{ __('contact_us.explore_more') }}</a></font>
                            </font>
                        </button>
                    </div>
                </div>
                <div class="lg:max-w-lg lg:w-full md:w-1/2 w-5/6">
                    <img class="object-cover object-center rounded" alt="téléphone"
                        src="{{ asset('assets/images/telephone-nous-contacter.jpg') }}">
                </div>
            </div>
        </section>

        <section class="text-gray-600 body-font relative">
            <div class="container px-5 py-10 mx-auto flex sm:flex-nowrap flex-wrap">
                <div
                    class="lg:w-2/3 md:w-1/2 bg-gray-300 rounded-lg overflow-hidden sm:mr-10 p-10 flex items-end justify-start relative">
                    <iframe width="100%" height="100%" class="absolute inset-0" frameborder="0" title="carte"
                        marginheight="0" marginwidth="0" scrolling="no"
                        src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d2688.0375791805236!2d-2.7794359871544856!3d47.644836571073185!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x48101c1b28e5dbcf%3A0xc24e2f05cd27aba4!2s8%20Rue%20Michel%20de%20Montaigne%2C%2056000%20Vannes!5e0!3m2!1sfr!2sfr!4v1699364749192!5m2!1sfr!2sfr"></iframe>
                    <div class="bg-white relative flex flex-wrap py-6 rounded shadow-md">
                        <div class="lg:w-1/2 px-6">
                            <h2 class="title-font font-semibold text-gray-900 tracking-widest text-xs">
                                <font style="vertical-align: inherit;">
                                    <font style="vertical-align: inherit;">ADRESSE</font>
                                </font>
                            </h2>
                            <p class="mt-1">
                                <font style="vertical-align: inherit;">
                                    <font style="vertical-align: inherit;">8 Rue Michel de Montaigne, 56000 Vannes
                                    </font>
                                </font>
                            </p>
                        </div>
                        <div class="lg:w-1/2 px-6 mt-4 lg:mt-0">
                            <h2 class="title-font font-semibold text-gray-900 tracking-widest text-xs">
                                <font style="vertical-align: inherit;">
                                    <font style="vertical-align: inherit;">E-MAIL</font>
                                </font>
                            </h2>
                            <a class="text-indigo-500 leading-relaxed">
                                <font style="vertical-align: inherit;">
                                    <font style="vertical-align: inherit;">exemple@email.com</font>
                                </font>
                            </a>
                            <h2 class="title-font font-semibold text-gray-900 tracking-widest text-xs mt-4">
                                <font style="vertical-align: inherit;">
                                    <font style="vertical-align: inherit;">TÉLÉPHONE</font>
                                </font>
                            </h2>
                            <p class="leading-relaxed">
                                <font style="vertical-align: inherit;">
                                    <font style="vertical-align: inherit;">02 97 62 64 64</font>
                                </font>
                            </p>
                        </div>
                    </div>
                </div>

                <div class="lg:w-1/3 md:w-1/2 bg-white flex flex-col md:ml-auto w-full md:py-8 mt-8 md:mt-0">
                    <h2 class="text-gray-900 text-lg mb-1 font-medium title-font">
                        <font style="vertical-align: inherit;">
                            <font style="vertical-align: inherit;">{{ __('contact_us.contact_form') }}</font>
                        </font>
                    </h2>
                    <p class="leading-relaxed mb-5 text-gray-600">
                        <font style="vertical-align: inherit;">
                            <font style="vertical-align: inherit;">{{ __('contact_us.contact_form_intro') }}</font>
                        </font>
                    </p>
                    <form action="/contact" method="post" enctype="multipart/form-data">
                        @csrf
                        <div class="relative mb-4">
                            <label for="name" class="leading-7 text-sm text-gray-600">
                                <font style="vertical-align: inherit;">
                                    <font style="vertical-align: inherit;">{{ __('contact_us.name_field') }}</font>
                                </font>
                            </label>
                            <input type="text" id="name" name="name" required
                                class="w-full bg-white rounded border border-gray-300 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 text-base outline-none text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out">
                        </div>
                        <div class="relative mb-4">
                            <label for="lastname" class="leading-7 text-sm text-gray-600">
                                <font style="vertical-align: inherit;">
                                    <font style="vertical-align: inherit;">{{ __('contact_us.firstname_field') }}</font>
                                </font>
                            </label>
                            <input type="text" id="lastname" name="lastname" required
                                class="w-full bg-white rounded border border-gray-300 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 text-base outline-none text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out">
                        </div>
                        <div class="relative mb-4">
                            <label for="email" class="leading-7 text-sm text-gray-600">
                                <font style="vertical-align: inherit;">
                                    <font style="vertical-align: inherit;">{{ __('contact_us.email_field') }}</font>
                                </font>
                            </label>
                            <input type="email" id="email" name="email" required pattern="[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}"
                                class="w-full bg-white rounded border border-gray-300 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 text-base outline-none text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out">
                        </div>
                        <div class="relative mb-4">
                            <label for="message" class="leading-7 text-sm text-gray-600">
                                <font style="vertical-align: inherit;">
                                    <font style="vertical-align: inherit;">{{ __('contact_us.message_field') }}</font>
                                </font>
                            </label>
                            <textarea id="message" name="message" required
                                class="w-full bg-white rounded border border-gray-300 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 h-32 text-base outline-none text-gray-700 py-1 px-3 resize-none leading-6 transition-colors duration-200 ease-in-out"></textarea>
                        </div>

                        <div class="flex items-center text-gray-600">
                            <input id="terms" type="checkbox" name="terms"
                                class="w-4 h-4 text-blue-600 bg-gray-100 rounded border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                            <label for="terms" class="ml-2 text-sm font-medium text-gray-900 text-gray-600">{{ __('contact_us.consent_statement') }}
                                <a href="/condition-d'utilisation" class="text-blue-500 hover:underline">{{ __('contact_us.terms_of_use') }}</a></label>
                        </div>
                        <br>
                        <button type="submit"
                            class="text-white bg-blue-500 border-0 py-2 px-6 focus:outline-none hover:bg-blue-600 rounded text-lg">
                            <font style="vertical-align: inherit;">
                                <font style="vertical-align: inherit;">{{ __('contact_us.submit_button') }}</font>
                            </font>
                        </button>
                    </form>
                    <p class="text-xs text-gray-500 mt-3">
                        <font style="vertical-align: inherit;">
                            <font style="vertical-align: inherit;">{{ __('contact_us.mandatory_fields') }}
                            </font>
                        </font>
                    </p>
                </div>
            </div>
        </section>
</body>

</html>

@include('includes.footer')
