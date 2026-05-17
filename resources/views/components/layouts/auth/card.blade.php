<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    @include('partials.head')
</head>

<body class="font-sans text-gray-900 antialiased">
    <div
        class="min-h-screen flex flex-col sm:justify-center items-center p-4 bg-gradient-to-br from-cyan-100 via-teal-200 to-emerald-300 bg-[length:200%_200%] animate-gradient-bg">

        <div class="w-full max-w-md bg-white rounded-2xl shadow-2xl overflow-hidden my-4">

            <div class="w-full p-8 sm:p-12 flex flex-col justify-center">
                <div class="flex flex-col items-center mb-6 text-center">
                    <a href="{{ route('home') }}" class="flex flex-col items-center group" wire:navigate>
                        <img src="{{ asset('images/logo-podiatrix.png') }}" alt="Podiatrix Logo" class="w-20 h-20 transition-transform duration-200 group-hover:scale-105">
                        <h1 class="text-4xl font-bold text-gray-800 mt-2 group-hover:text-primary transition-colors">Podiatrix</h1>
                    </a>
                    <p class="text-gray-500 text-sm mt-1">Langkahmu Berarti, Podiatrix yang peduli.</p>
                </div>

                {{ $slot }}
            </div>

        </div>
    </div>
    @fluxScripts
    <script src="https://cdn.jsdelivr.net/npm/flowbite@3.1.2/dist/flowbite.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/flowbite@3.1.2/dist/datepicker.min.js"></script>
</body>

</html>
