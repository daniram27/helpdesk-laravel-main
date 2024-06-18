<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- PWA  -->
    <meta name="theme-color" content="#6777ef"/>
    <link rel="apple-touch-icon" href="{{ asset('pwa-512x512.png') }}">
    <link rel="manifest" href="{{ asset('/manifest.json') }}">
    <meta name="theme-color" content="#ffffff">
    <title>{{ config('app.name') }} </title>
    <script src="{{ asset('js/firebase-config.js') }}"></script>
    <meta name="description" content="{{ env('APP_DESCRIPTION', 'Laravel description') }}">
    @vite(['resources/css/app.css'])
</head>

<body>
    <section class="bg-white dark:bg-gray-900">
        <div class="grid py-8 px-4 mx-auto max-w-screen-xl lg:gap-8 xl:gap-0 lg:py-16 lg:grid-cols-12">
            <div class="place-self-center mr-auto lg:col-span-7">
                <h1 class="mb-4 max-w-2xl text-4xl font-extrabold leading-none md:text-5xl xl:text-6xl dark:text-white">
                    {{ config('app.name') }}</h1>
                <p class="mb-6 max-w-2xl font-light text-gray-500 lg:mb-8 md:text-lg lg:text-xl dark:text-gray-400">
                    {{ env('APP_DESCRIPTION', 'This is descriptions') }}</p>
                <a href="{{ route('filament.auth.login') }}"
                    class="inline-flex justify-center items-center py-3 px-5 mr-3 text-base font-medium text-center text-white rounded-lg bg-primary-700 hover:bg-primary-800 focus:ring-4 focus:ring-primary-300 dark:focus:ring-primary-900">
                    {{ __('Login') }}
                </a>
            </div>
            <div class="hidden lg:mt-0 lg:col-span-5 lg:flex">
                <img src="{{ url('pwa-512x512.png') }}">
            </div>
        </div>
    </section>

    <script src="{{ asset('/sw.js') }}"></script>
    <script>
       if ("serviceWorker" in navigator) {
          // Register a service worker hosted at the root of the
          // site using the default scope.
          navigator.serviceWorker.register("/sw.js").then(
          (registration) => {
             console.log("Service worker registration succeeded:", registration);
          },
          (error) => {
             console.error(`Service worker registration failed: ${error}`);
          },
        );
      } else {
         console.error("Service workers are not supported.");
      }
    </script>
</body>

</html>
