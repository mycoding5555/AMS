<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen bg-apple-bg">
            @include('layouts.navigation')

            <!-- Page Heading -->
            @isset($header)
                <header class="bg-white border-b border-apple-border/50">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        <div class="flex items-center gap-3">
                            @isset($headerIcon)
                                <div class="section-icon">
                                    <x-icon :icon="$headerIcon" size="lg" class="text-apple-blue" />
                                </div>
                            @endisset
                            <div>
                                {{ $header }}
                            </div>
                        </div>
                    </div>
                </header>
            @endisset

            <!-- Page Content -->
            <main class="py-section">
                {{ $slot }}
            </main>
        </div>
    </body>
</html>
