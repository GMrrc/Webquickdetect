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

<div class="sm:ml-32 w-full justify-between">
        <?php 
            if (!Auth()->check()) {
                return redirect('/login');
            }
        ?>

    <section class="text-gray-600 body-font relative">
        <div class="container px-5 py-24 mx-auto">
            <div class="flex flex-col text-left mb-4">
                <a href="javascript:history.back()" class="text-gray-600 hover:text-gray-900 inline-flex items-center bg-gray-200 hover:bg-gray-300 rounded-full px-4 py-2 w-32">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                    </svg>
                    Retour
                </a>
            </div>
            <div class="flex flex-col text-center w-full mb-12">
                <h1 class="sm:text-3xl text-2xl font-medium title-font mb-4 text-gray-900">
                    <font style="vertical-align: inherit;">
                        <font style="vertical-align: inherit;">{{ __('user_new_password.change_your_password') }}</font>
                    </font>
                </h1>
            </div>
            <form action="{{ route('new-password') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="lg:w-1/2 md:w-2/3 mx-auto">
                    <div class="flex flex-wrap -m-2">
                        <div class="p-2 w-1/2">
                            <div class="relative">
                                <label for="old_password" class="leading-7 text-sm text-gray-600">
                                    <font style="vertical-align: inherit;">
                                        <font style="vertical-align: inherit;">{{ __('user_new_password.old_password_label') }}</font>
                                    </font>
                                </label>
                                <input type="password" id="old_password" name="old_password"
                                    class="w-full bg-gray-100 bg-opacity-50 rounded border border-gray-300 focus:border-blue-500 focus:bg-white focus:ring-2 focus:ring-blue-200 text-base outline-none text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out">
                            </div>
                        </div>
                        <div class="p-2 w-1/2">
                            <div class="relative">
                                <label for="new_password" class="leading-7 text-sm text-gray-600">
                                    <font style="vertical-align: inherit;">
                                        <font style="vertical-align: inherit;">{{ __('user_new_password.new_password_label') }}</font>
                                    </font>
                                </label>
                                <input type="password" id="new_password" name="new_password"
                                    class="w-full bg-gray-100 bg-opacity-50 rounded border border-gray-300 focus:border-blue-500 focus:bg-white focus:ring-2 focus:ring-blue-200 text-base outline-none text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out">
                            </div>
                        </div>
                        <div class="p-2 w-full">
                            <div class="relative">
                                <label for="new_password_confirmation" class="leading-7 text-sm text-gray-600">
                                    <font style="vertical-align: inherit;">
                                        <font style="vertical-align: inherit;">{{ __('user_new_password.confirm_new_password') }}</font>
                                    </font>
                                </label>
                                <input type="password" id="new_password_confirmation" name="new_password_confirmation"
                                    class="w-full bg-gray-100 bg-opacity-50 rounded border border-gray-300 focus:border-blue-500 focus:bg-white focus:ring-2 focus:ring-blue-200 text-base outline-none text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out">
                            </div>
                        </div>
                        <div class="p-4 py-8 w-full">
                            <button type="submit"
                                class="flex mx-auto text-white bg-blue-500 border-0 py-2 px-8 focus:outline-none hover:bg-blue-600 rounded text-lg">
                                <font style="vertical-align: inherit;">
                                    <font style="vertical-align: inherit;">{{ __('user_new_password.change_password_button') }}</font>
                                </font>
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </section>
</div>
