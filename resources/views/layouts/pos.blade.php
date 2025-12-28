@<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>POS - {{ config('app.name', 'FoodKing') }}</title>
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">
    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    @yield('styles')
</head>
<body class="font-sans antialiased">
    <div id="app">
        <header class="bg-white shadow">
            <div class="max-w-7xl mx-auto py-4 px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
                    <div class="flex">
                        <div class="flex-shrink-0 flex items-center">
                            <a href="{{ route('pos.dashboard') }}" class="text-xl font-bold text-indigo-600">
                                POS System
                            </a>
                        </div>
                        <nav class="hidden sm:ml-6 sm:flex sm:space-x-8">
                            <a href="{{ route('pos.dashboard') }}" class="border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700 inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium">
                                Dashboard
                            </a>
                            <a href="{{ route('pos.sessions.index') }}" class="border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700 inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium">
                                Sessions
                            </a>
                            @if(auth()->user()->hasRole('manager') || auth()->user()->hasRole('admin'))
                                <a href="{{ route('pos.approvals.index') }}" class="border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700 inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium">
                                    Approvals
                                </a>
                                <a href="{{ route('shift-types.index') }}" class="border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700 inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium">
                                    Shift Types
                                </a>
                            @endif
                            @if(auth()->user()->posSessions()->where('status', 'open')->exists())
                                <a href="{{ route('pos.cash.movement') }}" class="border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700 inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium">
                                    Cash Movement
                                </a>
                            @endif
                        </nav>
                    </div>
                    <div class="hidden sm:ml-6 sm:flex sm:items-center">
                        <span class="mr-4 text-gray-700">{{ auth()->user()->name }}</span>
                        <a href="{{ route('logout') }}" class="text-gray-500 hover:text-gray-700">
                            Logout
                        </a>
                    </div>
                </div>
            </div>
        </header>

        <main class="py-4">
            @yield('content')
        </main>
    </div>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>
    @yield('scripts')
</body>
</html>
