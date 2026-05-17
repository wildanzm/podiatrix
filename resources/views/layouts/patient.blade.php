<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">

<head>
    @include('partials.head')
</head>

<body class="bg-slate-50 antialiased font-sans text-slate-900" x-data="{ mobileMenuOpen: false, profileDropdownOpen: false }">
    <div class="flex flex-col min-h-screen">

        <!-- Desktop/Tablet Header Navigation -->
        <header class="hidden md:block bg-white shadow-sm sticky top-0 z-50">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16 items-center">

                    <!-- Logo & Brand -->
                    <div class="flex items-center gap-3">
                        <a href="{{ route('home') }}" class="flex items-center" wire:navigate>
                            <img src="{{ asset('images/logo-podiatrix.png') }}" class="h-16 w-auto" alt="Podiatrix Logo">
                            <span class="font-bold text-xl text-primary">Podiatrix</span>
                        </a>
                    </div>

                    <!-- Desktop Navigation Links -->
                    <nav class="hidden md:flex space-x-8">
                        <a href="{{ route('home') }}" wire:navigate
                            class="inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium transition-colors duration-200 {{ request()->routeIs('home') ? 'border-primary text-primary' : 'border-transparent text-slate-500 hover:text-slate-700 hover:border-slate-300' }}">
                            Beranda
                        </a>
                        <a href="{{ route('patient.screenings') }}" wire:navigate
                            class="inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium transition-colors duration-200 {{ request()->routeIs('patient.screenings') ? 'border-primary text-primary' : 'border-transparent text-slate-500 hover:text-slate-700 hover:border-slate-300' }}">
                            Skrining
                        </a>
                        <a href="{{ route('patient.schedule') }}" wire:navigate
                            class="inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium transition-colors duration-200 {{ request()->routeIs('patient.schedule') ? 'border-primary text-primary' : 'border-transparent text-slate-500 hover:text-slate-700 hover:border-slate-300' }}">
                            Jadwal
                        </a>
                        <a href="{{ route('patient.history') }}" wire:navigate
                            class="inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium transition-colors duration-200 {{ request()->routeIs('patient.history') ? 'border-primary text-primary' : 'border-transparent text-slate-500 hover:text-slate-700 hover:border-slate-300' }}">
                            Riwayat
                        </a>

                    </nav>

                    <!-- User Actions -->
                    <div class="flex items-center gap-4">
                        @auth
                            <div class="relative">
                                <button @click="profileDropdownOpen = !profileDropdownOpen"
                                    @click.away="profileDropdownOpen = false"
                                    class="flex items-center gap-2 focus:outline-none">
                                    <div
                                        class="h-8 w-8 rounded-full bg-primary text-white flex items-center justify-center font-bold shadow-sm">
                                        {{ auth()->user()->initials() }}
                                    </div>
                                    <span class="text-sm font-medium text-slate-700 hidden lg:block">Halo,
                                        {{ explode(' ', auth()->user()->name)[0] }}</span>
                                    <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 9l-7 7-7-7"></path>
                                    </svg>
                                </button>

                                <!-- Dropdown Menu -->
                                <div x-show="profileDropdownOpen" x-transition:enter="transition ease-out duration-100"
                                    x-transition:enter-start="transform opacity-0 scale-95"
                                    x-transition:enter-end="transform opacity-100 scale-100"
                                    x-transition:leave="transition ease-in duration-75"
                                    x-transition:leave-start="transform opacity-100 scale-100"
                                    x-transition:leave-end="transform opacity-0 scale-95" style="display: none;"
                                    class="absolute right-0 mt-2 w-48 bg-white rounded-xl shadow-xl border border-slate-100 py-1.5 focus:outline-none z-50">
                                    <a href="{{ route('patient.profile') }}" wire:navigate
                                        class="block px-4 py-2.5 text-sm md:text-base text-slate-700 hover:bg-slate-50 hover:text-primary transition-colors">Profil
                                        Saya</a>
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <a href="{{ route('logout') }}"
                                            onclick="event.preventDefault(); this.closest('form').submit();"
                                            class="block px-4 py-2.5 text-sm md:text-base text-red-600 hover:bg-red-50 transition-colors">
                                            Keluar
                                        </a>
                                    </form>
                                </div>
                            </div>
                        @else
                            <a href="{{ route('login') }}" wire:navigate
                                class="text-sm font-medium text-slate-600 hover:text-primary transition-colors">Masuk</a>
                            <a href="{{ route('register') }}" wire:navigate
                                class="px-4 py-2 bg-primary text-white text-sm font-medium rounded-lg hover:bg-teal-700 transition-colors shadow-sm focus:ring-4 focus:ring-teal-300">Daftar</a>
                        @endauth
                    </div>
                </div>
            </div>
        </header>

        <!-- Mobile Top Header -->
        <header class="md:hidden fixed top-0 w-full bg-white shadow-sm z-40 border-b border-slate-200">
            <div class="flex items-center justify-between h-14 px-4">
                <a href="{{ route('home') }}" class="flex items-center gap-1" wire:navigate>
                    <img src="{{ asset('images/logo-podiatrix.png') }}" class="h-14 w-auto" alt="Podiatrix Logo">
                    <span class="font-bold text-lg text-primary">Podiatrix</span>
                </a>

                @guest
                    <div class="flex items-center gap-3">
                        <a href="{{ route('login') }}" wire:navigate
                            class="text-sm font-medium text-slate-600 hover:text-primary transition-colors">Masuk</a>
                        <a href="{{ route('register') }}" wire:navigate
                            class="px-3 py-1.5 bg-primary text-white text-xs font-semibold rounded-lg hover:bg-teal-700 transition-colors shadow-sm">Daftar</a>
                    </div>
                @endguest
                @auth
                    <div class="flex items-center gap-2.5">
                        <div
                            class="h-8 w-8 rounded-full bg-primary text-white flex items-center justify-center font-bold shadow-sm text-xs">
                            {{ auth()->user()->initials() }}
                        </div>
                        <span class="text-sm font-medium text-slate-700">Halo, <span
                                class="text-primary">{{ explode(' ', auth()->user()->name)[0] }}</span></span>
                    </div>
                @endauth
            </div>
        </header>

        <!-- Main Content -->
        <main class="flex-grow pt-14 md:pt-0 pb-16 md:pb-0">
            {{ $slot }}
        </main>

        <!-- Footer -->
        <footer class="bg-white border-t border-slate-200 mt-auto pb-20 md:pb-0">
            <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                <p class="text-center text-sm text-gray-500">
                    Copyright &copy; {{ date('Y') }} Podiatrix. All rights reserved.
                </p>
            </div>
        </footer>

        <!-- Mobile Bottom Float Navigation -->
        <nav
            class="md:hidden fixed bottom-4 left-4 right-4 bg-white/95 backdrop-blur-md border border-slate-200 rounded-2xl shadow-[0_8px_30px_rgba(0,0,0,0.12)] z-50">
            <div class="flex justify-around items-center h-16 px-2">

                <!-- Home -->
                <a href="{{ route('home') }}" wire:navigate
                    class="flex flex-col items-center justify-center w-full h-full space-y-1 {{ request()->routeIs('home') ? 'text-primary' : 'text-slate-400 hover:text-primary transition-colors' }}">
                    <div class="relative p-1">
                        <svg class="w-6 h-6 custom-svg-nav" viewBox="0 -0.5 25 25" fill="none"
                            xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" clip-rule="evenodd"
                                d="M18.867 15.8321L18.873 10.0391L14.75 5.92908C13.5057 4.69031 11.4942 4.69031 10.25 5.92908L6.13599 10.0291V15.8291C6.1393 17.5833 7.56377 19.0028 9.31799 19.0001H15.685C17.438 19.0029 18.862 17.5851 18.867 15.8321Z"
                                stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                            </path>
                            <path fill-rule="evenodd" clip-rule="evenodd"
                                d="M14 11.365C13.9846 12.1896 13.3064 12.8471 12.4817 12.8369C11.657 12.8267 10.9952 12.1526 11.0003 11.3279C11.0053 10.5031 11.6752 9.83718 12.5 9.83704C12.9015 9.84073 13.2852 10.0038 13.5665 10.2904C13.8478 10.5769 14.0037 10.9635 14 11.365V11.365Z"
                                stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                            </path>
                            <path
                                d="M19.63 5.76314C19.6306 5.34892 19.2953 5.01267 18.8811 5.01209C18.4668 5.0115 18.1306 5.34682 18.13 5.76103L19.63 5.76314ZM18.874 10.0391L18.124 10.038C18.1237 10.2377 18.2031 10.4293 18.3445 10.5702L18.874 10.0391ZM19.9705 12.1912C20.2638 12.4837 20.7387 12.4829 21.0311 12.1896C21.3236 11.8963 21.3229 11.4214 21.0295 11.1289L19.9705 12.1912ZM6.66552 10.5602C6.95886 10.2678 6.95959 9.79291 6.66714 9.49957C6.3747 9.20623 5.89982 9.2055 5.60648 9.49794L6.66552 10.5602ZM3.97048 11.1289C3.67714 11.4214 3.67641 11.8963 3.96886 12.1896C4.2613 12.4829 4.73618 12.4837 5.02952 12.1912L3.97048 11.1289ZM10.5 15.1971C10.0858 15.1971 9.75 15.5329 9.75 15.9471C9.75 16.3613 10.0858 16.6971 10.5 16.6971V15.1971ZM14.5 16.6971C14.9142 16.6971 15.25 16.3613 15.25 15.9471C15.25 15.5329 14.9142 15.1971 14.5 15.1971V16.6971ZM18.13 5.76103L18.124 10.038L19.624 10.0401L19.63 5.76314L18.13 5.76103ZM18.3445 10.5702L19.9705 12.1912L21.0295 11.1289L19.4035 9.50794L18.3445 10.5702ZM5.60648 9.49794L3.97048 11.1289L5.02952 12.1912L6.66552 10.5602L5.60648 9.49794ZM10.5 16.6971H14.5V15.1971H10.5V16.6971Z"
                                fill="currentColor"></path>
                        </svg>
                    </div>
                    <span class="text-[10px] font-bold">Beranda</span>
                </a>

                <!-- Skrining -->
                <a href="{{ route('patient.screenings') }}" wire:navigate
                    class="flex flex-col items-center justify-center w-full h-full space-y-1 {{ request()->routeIs('patient.screenings') ? 'text-primary' : 'text-slate-400 hover:text-primary transition-colors' }}">
                    <div class="relative p-1">
                        <svg class="w-6 h-6 custom-svg-nav" fill="currentColor" viewBox="-2.5 0 19 19"
                            xmlns="http://www.w3.org/2000/svg">
                            <path
                                d="M11.56 10.11v2.046a3.827 3.827 0 1 1-7.655 0v-.45A3.61 3.61 0 0 1 .851 8.141V5.25a1.682 1.682 0 0 1 .763-1.408 1.207 1.207 0 1 1 .48 1.04.571.571 0 0 0-.135.368v2.89a2.5 2.5 0 0 0 5 0V5.25a.57.57 0 0 0-.108-.334 1.208 1.208 0 1 1 .533-1.018 1.681 1.681 0 0 1 .683 1.352v2.89a3.61 3.61 0 0 1-3.054 3.565v.45a2.719 2.719 0 0 0 5.438 0V10.11a2.144 2.144 0 1 1 1.108 0zm.48-2.07a1.035 1.035 0 1 0-1.035 1.035 1.037 1.037 0 0 0 1.036-1.035z">
                            </path>
                        </svg>
                    </div>
                    <span class="text-[10px] font-bold">Skrining</span>
                </a>

                <!-- Schedule -->
                <a href="{{ route('patient.schedule') }}" wire:navigate
                    class="flex flex-col items-center justify-center w-full h-full space-y-1 {{ request()->routeIs('patient.schedule') ? 'text-primary' : 'text-slate-400 hover:text-primary transition-colors' }}">
                    <div class="relative p-1">
                        <svg class="w-6 h-6 custom-svg-nav" viewBox="0 0 24 24" fill="none"
                            xmlns="http://www.w3.org/2000/svg">
                            <path
                                d="M3 9H21M7 3V5M17 3V5M6 12H8M11 12H13M16 12H18M6 15H8M11 15H13M16 15H18M6 18H8M11 18H13M16 18H18M6.2 21H17.8C18.9201 21 19.4802 21 19.908 20.782C20.2843 20.5903 20.5903 20.2843 20.782 19.908C21 19.4802 21 18.9201 21 17.8V8.2C21 7.07989 21 6.51984 20.782 6.09202C20.5903 5.71569 20.2843 5.40973 19.908 5.21799C19.4802 5 18.9201 5 17.8 5H6.2C5.0799 5 4.51984 5 4.09202 5.21799C3.71569 5.40973 3.40973 5.71569 3.21799 6.09202C3 6.51984 3 7.07989 3 8.2V17.8C3 18.9201 3 19.4802 3.21799 19.908C3.40973 20.2843 3.71569 20.5903 4.09202 20.782C4.51984 21 5.07989 21 6.2 21Z"
                                stroke="currentColor" stroke-width="2" stroke-linecap="round"></path>
                        </svg>
                    </div>
                    <span class="text-[10px] font-bold">Jadwal</span>
                </a>

                <!-- History -->
                <a href="{{ route('patient.history') }}" wire:navigate
                    class="flex flex-col items-center justify-center w-full h-full space-y-1 {{ request()->routeIs('patient.history') ? 'text-primary' : 'text-slate-400 hover:text-primary transition-colors' }}">
                    <div class="relative p-1">
                        <svg class="w-6 h-6 custom-svg-nav" viewBox="0 0 24 24" fill="none"
                            xmlns="http://www.w3.org/2000/svg">
                            <path d="M12 8V12L14.5 14.5" stroke="currentColor" stroke-width="1.5"
                                stroke-linecap="round" stroke-linejoin="round"></path>
                            <path
                                d="M5.60423 5.60423L5.0739 5.0739V5.0739L5.60423 5.60423ZM4.33785 6.87061L3.58786 6.87438C3.58992 7.28564 3.92281 7.61853 4.33408 7.6206L4.33785 6.87061ZM6.87963 7.63339C7.29384 7.63547 7.63131 7.30138 7.63339 6.88717C7.63547 6.47296 7.30138 6.13549 6.88717 6.13341L6.87963 7.63339ZM5.07505 4.32129C5.07296 3.90708 4.7355 3.57298 4.32129 3.57506C3.90708 3.57715 3.57298 3.91462 3.57507 4.32882L5.07505 4.32129ZM3.75 12C3.75 11.5858 3.41421 11.25 3 11.25C2.58579 11.25 2.25 11.5858 2.25 12H3.75ZM16.8755 20.4452C17.2341 20.2378 17.3566 19.779 17.1492 19.4204C16.9418 19.0619 16.483 18.9393 16.1245 19.1468L16.8755 20.4452ZM19.1468 16.1245C18.9393 16.483 19.0619 16.9418 19.4204 17.1492C19.779 17.3566 20.2378 17.2341 20.4452 16.8755L19.1468 16.1245ZM5.14033 5.07126C4.84598 5.36269 4.84361 5.83756 5.13505 6.13191C5.42648 6.42626 5.90134 6.42862 6.19569 6.13719L5.14033 5.07126ZM18.8623 5.13786C15.0421 1.31766 8.86882 1.27898 5.0739 5.0739L6.13456 6.13456C9.33366 2.93545 14.5572 2.95404 17.8017 6.19852L18.8623 5.13786ZM5.0739 5.0739L3.80752 6.34028L4.86818 7.40094L6.13456 6.13456L5.0739 5.0739ZM4.33408 7.6206L6.87963 7.63339L6.88717 6.13341L4.34162 6.12062L4.33408 7.6206ZM5.08784 6.86684L5.07505 4.32129L3.57507 4.32882L3.58786 6.87438L5.08784 6.86684ZM12 3.75C16.5563 3.75 20.25 7.44365 20.25 12H21.75C21.75 6.61522 17.3848 2.25 12 2.25V3.75ZM12 20.25C7.44365 20.25 3.75 16.5563 3.75 12H2.25C2.25 17.3848 6.61522 21.75 12 21.75V20.25ZM16.1245 19.1468C14.9118 19.8483 13.5039 20.25 12 20.25V21.75C13.7747 21.75 15.4407 21.2752 16.8755 20.4452L16.1245 19.1468ZM20.25 12C20.25 13.5039 19.8483 14.9118 19.1468 16.1245L20.4452 16.8755C21.2752 15.4407 21.75 13.7747 21.75 12H20.25ZM6.19569 6.13719C7.68707 4.66059 9.73646 3.75 12 3.75V2.25C9.32542 2.25 6.90113 3.32791 5.14033 5.07126L6.19569 6.13719Z"
                                fill="currentColor"></path>
                        </svg>
                    </div>
                    <span class="text-[10px] font-bold">Riwayat</span>
                </a>



                <!-- Profile (Only logged in users) -->
                @auth
                    <a href="{{ route('patient.profile') }}" wire:navigate
                        class="flex flex-col items-center justify-center w-full h-full space-y-1 {{ request()->routeIs('patient.profile') ? 'text-primary' : 'text-slate-400 hover:text-primary transition-colors' }}">
                        <div class="relative p-1">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
                            </svg>
                        </div>
                        <span class="text-[10px] font-bold">Profil</span>
                    </a>
                @endauth

            </div>
        </nav>

    </div>

    @fluxScripts
    <script src="https://cdn.jsdelivr.net/npm/flowbite@3.1.2/dist/flowbite.min.js"></script>
    @stack('scripts')
</body>

</html>
