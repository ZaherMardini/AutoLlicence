@props(['enableBackground' => true])
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>
        <!-- Font awesome -->
        <link href="{{ asset('assets/css/all.min.css') }}" rel="stylesheet" />
        <link href="{{ asset('assets/css/brands.css') }}" rel="stylesheet" />
        <link href="{{ asset('assets/css/solid.css') }}" rel="stylesheet" />
        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased">
      @if ($enableBackground)
        
      <div class="bg-fixed sticky min-h-screen bg-gray-100 dark:bg-gray-900 z-3"
      style="background-image: url(/images/defaults/main.png); background-repeat: no-repeat; backgorud"
      >
      @else
      <div class="sticky min-h-screen bg-gray-100 dark:bg-gray-900 z-2">
      @endif
      @include('layouts.navigation')
      
            <!-- Page Heading -->
            @isset($header)
                <header class="bg-transparent shadow">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endisset

            <!-- Page Content -->
            <main class="flex justify-center align-center">
                <div id="overlay" class="absolute h-full inset-0 bg-linear-to-b from-transparent to-black/70 -z-50"></div>
                {{ $slot }}
            </main>
        </div>
        <!-- Flowbite library -->
        <script src="https://cdn.jsdelivr.net/npm/flowbite@4.0.1/dist/flowbite.min.js"></script>
        <!-- HTML To pdf libraries -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.12.1/html2pdf.bundle.min.js" integrity="sha512-D25Z8/1q2z65ZpJ3NzY6XiPZfwjhbv34OTQHDIZd+KPK+uWCovGt+fMkSzW8ArzCMFUgZt6Cdu7qoXNuy6a2GA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    </body>
</html>
