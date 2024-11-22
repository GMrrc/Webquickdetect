<!DOCTYPE html>
<html>
  <head>
      <title>Mention Légales
      </title>
      <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/tailwindcss/2.0.3/tailwind.min.css">
      <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700&display=swap">
  </head>
  <body style="background-color:  white;">

    <section class="text-gray-600 body-font">
        <div class="container mx-auto flex px-5 py-24 items-center justify-center flex-col">
            <div class="text-center lg:w-2/3 w-full">
                <h1 class="title-font sm:text-4xl text-3xl mb-4 font-medium text-gray-900">
                <font style="vertical-align: inherit;">
                    <font style="vertical-align: inherit;">{{ __('mention_legal.legal_notice') }}</font>
                </font>
                </h1>
            </div>
        </div>
    </section>

    <section class="text-gray-600 body-font">
      <div class="container mx-auto flex px-5 py-10 md:flex-row flex-col items-center">
        <div class="lg:flex-grow md:w-1/2 lg:pr-24 md:pr-16 flex flex-col md:items-start md:text-left mb-16 md:mb-0 items-center text-center">
          <h1 class="title-font sm:text-2xl text-3xl mb-4 font-medium text-gray-900">
              <font style="vertical-align: inherit;">
                <font style="vertical-align: inherit;">{{ __('mention_legal.welcome') }}</font>
              </font>
          </h1>
          <h1 class="title-font sm:text-2xl text-3xl mb-4 font-medium text-gray-900">
              <font style="vertical-align: inherit;">
                <font style="vertical-align: inherit;">{{ __('mention_legal.publisher_section') }}</font>
              </font>
          </h1>
          <p class="mb-8 leading-relaxed">
            <font style="vertical-align: inherit;">
              <font style="vertical-align: inherit;">
                {{ __('mention_legal.publisher_name') }}
              </font>
            </font>
          </p>
          <h1 class="title-font sm:text-2xl text-3xl mb-4 font-medium text-gray-900">
              <font style="vertical-align: inherit;">
                <font style="vertical-align: inherit;">{{ __('mention_legal.publication_director_section') }}</font>
              </font>
          </h1>
          <p class="mb-8 leading-relaxed">
              <font style="vertical-align: inherit;">
                <font style="vertical-align: inherit;">
                  {{ __('mention_legal.publication_director') }}<br><br>
                  {{ __('mention_legal.contact_email') }}<br><br>
                  {{ __('mention_legal.contact_phone') }}<br><br>
                  </font>
              </font>
          </p>
          <h1 class="title-font sm:text-2xl text-3xl mb-4 font-medium text-gray-900">
              <font style="vertical-align: inherit;">
                <font style="vertical-align: inherit;">{{ __('mention_legal.host_section') }}</font>
              </font>
          </h1>
          <p class="mb-8 leading-relaxed">
              <font style="vertical-align: inherit;">
                  <font style="vertical-align: inherit;">
                    {{ __('mention_legal.host_info') }}<br><br>
                    {{ __('mention_legal.host_company') }}<br><br>
                    {{ __('mention_legal.host_location') }}<br><br>
                    {{ __('mention_legal.host_contact') }}<br><br>
                  </font>
              </font>
          </p>
          <h1 class="title-font sm:text-2xl text-3xl mb-4 font-medium text-gray-900">
              <font style="vertical-align: inherit;">
                <font style="vertical-align: inherit;">{{ __('mention_legal.terms_of_use') }}</font>
              </font>
          </h1>
          <p class="mb-8 leading-relaxed">
              <font style="vertical-align: inherit;">
                  <font style="vertical-align: inherit;">
                      <strong>{{ __('mention_legal.intellectual_property') }}</strong><br><br>

                      {{ __('mention_legal.intellectual_property_text') }}<br><br>

                      <strong>{{ __('mention_legal.hyperlinks') }}</strong><br><br>

                      {{ __('mention_legal.hyperlinks_text') }}<br><br>

                      {{ __('mention_legal.additional_info') }}<br><br>
                  </font>
              </font>
          </p>

        </div>
      </div>
    </section>
  </body>
</html>


@include('includes.footer')

