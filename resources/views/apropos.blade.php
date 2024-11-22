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



<div class="sm:ml-32 w-full justify-between bg-white-200">
    <!-- Titre Principal et sous titre partie 1-->
    <section class="pt-8 text-gray-600 body-font" style="background-color:  white;"   >
        <div class="container px-5 py-5 mx-auto">
            <div class="flex flex-wrap w-full flex-col items-center text-center">
                <h1 class="sm:text-5xl text-2xl font-medium title-font mb-2 text-gray-900">
                    <font style="vertical-align: inherit;">
                        <font style="vertical-align: inherit;">{{ __('about.project_presentation') }}</font>
                    </font>
                </h1>
                <h1 class="sm:text-4xl text-2xl font-medium title-font mb-2 text-gray-900">
                    <font style="vertical-align: inherit;">
                        <font style="vertical-align: inherit;">{{ __('about.who_are_we') }}</font>
                    </font>
                </h1>
            </div>
        </div>
    </section>

    <!-- Contexte du projet Partie 1 -->
    <section class="bg-white-500 text-gray-600 body-font bg-gray-100">
        <div class="container mx-auto flex px-5 py-10 md:flex-row flex-col items-center">
            <div class="lg:flex-grow md:w-1/2 lg:pr-24 md:pr-16 flex flex-col md:items-start md:text-left mb-16 md:mb-0 items-center text-center">
                <h1 class="title-font sm:text-4xl text-3xl mb-4 font-medium text-gray-900">
                    <font style="vertical-align: inherit;">
                        <font style="vertical-align: inherit;">{{ __('about.academic_context') }}</font>
                    </font>
                </h1>
                <p class="mb-8 leading-relaxed">
                    <font style="vertical-align: inherit;">
                        <font style="vertical-align: inherit;">{{ __('about.academic_description') }}</font>
                    </font>
                </p>
            </div>
            <div class="lg:max-w-lg lg:w-full md:w-1/2 w-5/6">
                <img class="object-cover object-center rounded" alt="Université" src="{{ asset('assets/images/universite.jpg') }}">
            </div>
        </div>
    </section>

    <!-- L'Innovation en Vision par Ordinateur du projet Partie 1 -->

    <section class="text-gray-600 body-font">
        <div class="container mx-auto flex px-5 py-10 md:flex-row flex-col items-center">
            <div class="lg:max-w-lg lg:w-full md:w-1/2 w-5/6 mb-10 md:mb-0">
                <img class="object-cover object-center rounded" alt="Serveur" src="{{ asset('assets/images/serveur.jpg') }}">
            </div>
            <div class="lg:flex-grow md:w-1/2 lg:pl-24 md:pl-16 flex flex-col md:items-start md:text-left items-center text-center">
                <h1 class="title-font sm:text-4xl text-3xl mb-4 font-medium text-gray-900">
                    <font style="vertical-align: inherit;">
                        <font style="vertical-align: inherit;">{{ __('about.computer_vision_innovation') }}</font>
                    </font>
                </h1>
                <p class="mb-8 leading-relaxed">
                    <font style="vertical-align: inherit;">
                        <font style="vertical-align: inherit;">{{ __('about.vision_description') }}</font>
                    </font>
                </p>
            </div>
        </div>
    </section>

    <!-- Notre Mission du projet Partie 1 -->
    <section class="text-gray-600 body-font bg-gray-100">
        <div class="container mx-auto flex px-5 py-10 md:flex-row flex-col items-center">
            <div class="lg:flex-grow md:w-1/2 lg:pr-24 md:pr-16 flex flex-col md:items-start md:text-left mb-16 md:mb-0 items-center text-center">
                <h1 class="title-font sm:text-4xl text-3xl mb-4 font-medium text-gray-900">
                    <font style="vertical-align: inherit;">
                        <font style="vertical-align: inherit;">{{ __('about.our_mission') }}</font>
                    </font>
                </h1>
                <p class="mb-8 leading-relaxed">
                    <font style="vertical-align: inherit;">
                        <font style="vertical-align: inherit;">{{ __('about.mission_description') }}</font>
                    </font>
                </p>
            </div>
            <div class="lg:max-w-lg lg:w-full md:w-1/2 w-5/6">
                <img class="object-cover object-center rounded" alt="Objectif" src="{{ asset('assets/images/objectif.jpg') }}">
            </div>
        </div>
    </section>

    <!-- Sous titre - Partie 2-->
    <section class="text-gray-600 body-font" style="background-color:  white;"   >
        <div class="container px-5 py-24 mx-auto">
            <div class="flex flex-wrap w-full flex-col items-center text-center">
                <h1 class="sm:text-4xl text-2xl font-medium title-font mb-2 text-gray-900">
                    <font style="vertical-align: inherit;">
                        <font style="vertical-align: inherit;">{{ __('about.specs_title') }}</font>
                    </font>
                </h1>
            </div>
    </section>

    <!-- Cahier des charges - Partie 2 -->
    <section class="text-gray-600 body-font overflow-hidden">
        <div class="container px-5 py-5 mx-auto">
            <div class="-my-8 divide-y-2 divide-gray-100">
                <div class="py-8 flex flex-wrap md:flex-nowrap">
                    <div class="md:w-64 md:mb-0 mb-6 flex-shrink-0 flex flex-col">
                <span class="font-semibold title-font text-gray-700">
                  <font style="vertical-align: inherit;">
                    <font style="vertical-align: inherit;">{{ __('about.web_development') }}</font>
                  </font>
                </span>
                    </div>
                    <div class="md:flex-grow">
                        <h2 class="text-2xl font-medium text-gray-900 title-font mb-2">
                            <font style="vertical-align: inherit;">
                                <font style="vertical-align: inherit;">{{ __('about.web_fusion') }}</font>
                            </font>
                        </h2>
                        <p class="leading-relaxed">
                            <font style="vertical-align: inherit;">
                                <font style="vertical-align: inherit;">{{ __('about.web_dev_description') }}</font>
                            </font>
                        </p>
                    </div>
                </div>
                <div class="py-8 flex flex-wrap md:flex-nowrap">
                    <div class="md:w-64 md:mb-0 mb-6 flex-shrink-0 flex flex-col">
                <span class="font-semibold title-font text-gray-700">
                  <font style="vertical-align: inherit;">
                    <font style="vertical-align: inherit;">{{ __('about.ui_design') }}</font>
                  </font>
                </span>
                    </div>
                    <div class="md:flex-grow">
                        <h2 class="text-2xl font-medium text-gray-900 title-font mb-2">
                            <font style="vertical-align: inherit;">
                                <font style="vertical-align: inherit;">{{ __('about.ui_def') }}</font>
                            </font>
                        </h2>
                        <p class="leading-relaxed">
                            <font style="vertical-align: inherit;">
                                <font style="vertical-align: inherit;">{{ __('about.ui_design_description') }}</font>
                            </font>
                        </p>
                    </div>
                </div>
                <div class="py-8 flex flex-wrap md:flex-nowrap">
                    <div class="md:w-64 md:mb-0 mb-6 flex-shrink-0 flex flex-col">
                <span class="font-semibold title-font text-gray-700">
                  <font style="vertical-align: inherit;">
                    <font style="vertical-align: inherit;">{{ __('about.flexibility_and_customization') }}</font>
                  </font>
                </span>
                    </div>
                    <div class="md:flex-grow">
                        <h2 class="text-2xl font-medium text-gray-900 title-font mb-2">
                            <font style="vertical-align: inherit;">
                                <font style="vertical-align: inherit;">{{ __('about.flexibility_def') }}</font>
                            </font>
                        </h2>
                        <p class="leading-relaxed">
                            <font style="vertical-align: inherit;">
                                <font style="vertical-align: inherit;">{{ __('about.flexibility_description') }}</font>
                            </font>
                        </p>
                    </div>
                </div>
                <div class="py-8 flex flex-wrap md:flex-nowrap">
                    <div class="md:w-64 md:mb-0 mb-6 flex-shrink-0 flex flex-col">
                <span class="font-semibold title-font text-gray-700">
                  <font style="vertical-align: inherit;">
                    <font style="vertical-align: inherit;">{{ __('about.advanced_features') }}</font>
                  </font>
                </span>
                    </div>
                    <div class="md:flex-grow">
                        <h2 class="text-2xl font-medium text-gray-900 title-font mb-2">
                            <font style="vertical-align: inherit;">
                                <font style="vertical-align: inherit;">{{ __('about.advanced_def') }}</font>
                            </font>
                        </h2>
                        <p class="leading-relaxed">
                            <font style="vertical-align: inherit;">
                                <font style="vertical-align: inherit;">{{ __('about.advanced_features_description') }}</font>
                            </font>
                        </p>
                    </div>
                </div>
                <div class="py-8 flex flex-wrap md:flex-nowrap">
                    <div class="md:w-64 md:mb-0 mb-6 flex-shrink-0 flex flex-col">
                <span class="font-semibold title-font text-gray-700">
                  <font style="vertical-align: inherit;">
                    <font style="vertical-align: inherit;">{{ __('about.documentation_and_demonstration') }}</font>
                  </font>
                </span>
                    </div>
                    <div class="md:flex-grow">
                        <h2 class="text-2xl font-medium text-gray-900 title-font mb-2">
                            <font style="vertical-align: inherit;">
                                <font style="vertical-align: inherit;">{{ __('about.documentation_def') }}</font>
                            </font>
                        </h2>
                        <p class="leading-relaxed">
                            <font style="vertical-align: inherit;">
                                <font style="vertical-align: inherit;">{{ __('about.documentation_description') }}</font>
                            </font>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Sous titre - Partie 3-->
    <section class="text-gray-600 body-font bg-gray-100">
        <div class="container px-3 py-24 mx-auto">
            <div class="flex flex-wrap w-full flex-col items-center text-center">
                <h1 class="sm:text-4xl text-2xl font-medium title-font mb-2 text-gray-900">
                    <font style="vertical-align: inherit;">
                        <font style="vertical-align: inherit;">{{ __('about.our_team') }}</font>
                    </font>
                </h1>
            </div>
    </section>

    <!-- Presentation de l'equipe 3-->
    <section class="text-gray-600 body-font bg-gray-100">
        <div class="container px-5 py-3 mx-auto">
            <div class="flex flex-wrap -m-4">
                <div class="lg:w-1/3 lg:mb-0 mb-6 p-4">
                    <div class="h-full text-center">
                        <img alt="témoignage" class="w-20 h-20 mb-8 object-cover object-center rounded-full inline-block border-2 border-gray-200 bg-gray-100" src="{{ asset('assets/images/mathis-PERRUDIN.png') }}">
                        <p class="leading-relaxed">
                            <font style="vertical-align: inherit;">
                                <font style="vertical-align: inherit;">{{ __('about.our_mathis') }}</font>
                            </font>
                        </p>
                        <span class="inline-block h-1 w-10 rounded bg-blue-500 mt-6 mb-4"></span>
                        <h2 class="text-gray-900 font-medium title-font tracking-wider text-sm">
                            <font style="vertical-align: inherit;">
                                <font style="vertical-align: inherit;">MATHIS PERRUDIN</font>
                            </font>
                        </h2>
                    </div>
                </div>
                <div class="lg:w-1/3 lg:mb-0 mb-6 p-4">
                    <div class="h-full text-center">
                        <img alt="témoignage" class="w-20 h-20 mb-8 object-cover object-center rounded-full inline-block border-2 border-gray-200 bg-gray-100" src="{{ asset('assets/images/alexandre-BRAVO.png') }}">
                        <p class="leading-relaxed">
                            <font style="vertical-align: inherit;">
                                <font style="vertical-align: inherit;">{{ __('about.our_alexandre') }}</font>
                            </font>
                        </p>
                        <span class="inline-block h-1 w-10 rounded bg-blue-500 mt-6 mb-4"></span>
                        <h2 class="text-gray-900 font-medium title-font tracking-wider text-sm">
                            <font style="vertical-align: inherit;">
                                <font style="vertical-align: inherit;">ALEXANDRE BRAVO</font>
                            </font>
                        </h2>
                    </div>
                </div>
                <div class="lg:w-1/3 lg:mb-0 p-4">
                    <div class="h-full text-center">
                        <img alt="témoignage" class="w-20 h-20 mb-8 object-cover object-center rounded-full inline-block border-2 border-gray-200 bg-gray-100" src="{{ asset('assets/images/guillaume-MARREC.png') }}">
                        <p class="leading-relaxed">
                            <font style="vertical-align: inherit;">
                                <font style="vertical-align: inherit;">{{ __('about.our_guillaume') }}</font>
                            </font>
                        </p>
                        <span class="inline-block h-1 w-10 rounded bg-blue-500 mt-6 mb-4"></span>
                        <h2 class="text-gray-900 font-medium title-font tracking-wider text-sm">
                            <font style="vertical-align: inherit;">
                                <font style="vertical-align: inherit;">GUILLAUME MARREC</font>
                            </font>
                        </h2>
                        <p class="text-gray-500">
                            <font style="vertical-align: inherit;">
                                <font style="vertical-align: inherit;">Directeur technique</font>
                            </font>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Conclusion -->
    <section class="text-gray-600 body-font" style="background-color:  white;"   >
        <div class="container px-5 py-24 mx-auto">
            <div class="flex flex-wrap w-full flex-col items-center text-center">
                <h1 class="sm:text-4xl text-2xl font-medium title-font mb-2 text-gray-900">
                    <font style="vertical-align: inherit;">
                        <font style="vertical-align: inherit;">{{ __('about.conclusion_title') }}</font>
                    </font>
                </h1>
                <div class="container px-5 py-4 mx-auto"></div>
                <p class="lg:w-1/2 w-full leading-relaxed text-gray-500">
                    <font style="vertical-align: inherit;">
                        <font style="vertical-align: inherit;">{{ __('about.conclusion_text') }}</font>
                    </font>
                </p>
            </div>
        </div>
    </section>
    </body>
    </html>

@include('includes.footer')
