<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">

        <title>{{ $title ?? config('app.name') }}</title>

        {{-- @vite(['resources/css/app.css', 'resources/js/app.js']) --}}

        <script src="https://cdn.tailwindcss.com"></script>
        @livewireStyles
    </head>
    <body class="bg-gray-900 text-gray-100 min-h-screen font-sans">
        <!-- Header / Navbar Principal -->
        <header class="border-b border-gray-800 bg-gray-950/50 backdrop-blur sticky top-0 z-50">
            <div class="max-w-6xl mx-auto px-4 py-4 flex justify-between items-center">
                <a href="/" class="text-2xl font-bold tracking-tight text-white hover:text-indigo-400 transition">
                    My<span class="text-indigo-500">.Azevedo</span>
                </a>
                <nav class="flex space-x-6 text-sm font-medium text-gray-400">
                    <a href="/" class="hover:text-white transition">Blog</a>
                    <a href="#" class="hover:text-white transition">Sobre mim</a>
                    <a href="#" class="hover:text-white transition">Projetos</a>
                </nav>
            </div>
        </header>
        <!-- Conteúdo Principal Dinâmico -->
        <main class="max-w-6xl mx-auto px-4 py-8">
            {{ $slot }}
        </main>
        <!-- Footer -->
        <footer class="border-t border-gray-800 mt-16 py-8 text-center text-sm text-gray-500">
            <p>&copy; {{ date('Y') }} My Azevedo. Desenvolvido com Laravel, Livewire, PostgreSQL e Redis.</p>
        </footer>
        @livewireScripts
    </body>
</html>
