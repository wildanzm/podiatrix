<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">

<head>
    @include('partials.head')
</head>

<body class="bg-slate-50 antialiased" x-data="{ sidebarOpen: true }">
    <div class="flex h-screen bg-slate-50">
        <!-- Desktop Sidebar Navigation -->
        <aside class="hidden md:flex flex-col bg-white transition-all duration-300 ease-in-out shadow-lg"
            :class="sidebarOpen ? 'w-64' : 'w-20'">

            <!-- Sidebar Header with Logo and Toggle Button -->
            <div class="flex items-center h-16 p-4 border-b border-slate-200"
                :class="sidebarOpen ? 'justify-between' : 'justify-center'">
                <!-- Logo and Brand Name (visible when sidebar is open) -->
                <div x-show="sidebarOpen" class="flex items-center gap-2"
                    x-transition:enter="transition-opacity ease-in-out duration-300" x-transition:enter-start="opacity-0"
                    x-transition:enter-end="opacity-100">
                    <img src="{{ asset('images/logo-podiatrix.png') }}" class="h-8 w-auto" alt="Podiatrix Logo">
                    <span class="font-bold text-lg text-slate-800">Podiatrix</span>
                </div>
                <!-- Sidebar Toggle Button -->
                <button @click="sidebarOpen = !sidebarOpen"
                    class="p-2 rounded-lg hover:bg-slate-100 focus:outline-none focus:ring-2 focus:ring-primary">
                    <svg class="w-6 h-6 text-gray-600" xmlns="http://www.w3.org/2000/svg" fill="none"
                        viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M3.75 6.75h16.5M3.75 12h16.5M3.75 17.25h16.5" />
                    </svg>
                </button>
            </div>

            <!-- Main Navigation Menu -->
            <nav class="flex-1 px-3 py-4 space-y-2">

                <!-- Screening Menu Item -->
                <a href="{{ route('admin.screenings') }}" wire:navigate
                    class="flex items-center p-3 rounded-lg group font-semibold"
                    :class="{
                        'bg-gradient-to-r from-teal-400 to-primary text-white shadow-md': {{ request()->routeIs('admin.screenings') ? 'true' : 'false' }},
                        'text-slate-600 hover:bg-slate-100': !
                            {{ request()->routeIs('admin.screenings') ? 'true' : 'false' }},
                        'justify-center': !sidebarOpen
                    }">
                    <div class="w-6 h-6 flex items-center justify-center">
                        <svg class="w-full h-full"
                            :class="!{{ request()->routeIs('admin.screenings') ? 'true' : 'false' }} &&
                                'text-slate-600 group-hover:text-primary'"
                            viewBox="0 0 72 72" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                            <path
                                d="M67.625,56.738C67.592,56.688,67.66,56.787,67.625,56.738L47.823,28.167V5.42c0-1.105-0.539-1.92-1.643-1.92H27.179 c-1.104,0-1.356,0.816-1.356,1.92v22.097L4.904,56.764c-0.035,0.049-0.217,0.277-0.248,0.33c-1.688,2.926-2.078,5.621-0.785,7.86 c1.306,2.261,3.915,3.546,7.347,3.546h49.451c3.429,0,6.118-1.287,7.424-3.551C69.389,62.704,69.313,59.666,67.625,56.738z M29.137,29.302c0.237-0.338,0.687-0.74,0.687-1.152V7.5h14v21.301c0,0.412-0.194,0.824,0.044,1.161l9.19,13.262 c-3.056,1.232-6.822,2.05-14.531-2.557c-5.585-3.337-12.499-2.048-17.199-0.449L29.137,29.302z M64.55,62.949 c-0.554,0.96-1.969,1.551-3.88,1.551H11.219c-1.915,0-3.33-0.589-3.883-1.547c-0.532-0.922-0.324-2.391,0.571-3.975l11.287-15.777 c3.942-1.702,12.219-4.454,18.308-0.816c4.852,2.898,8.367,3.814,11.116,3.814c2.291,0,4.05-0.637,5.607-1.291l9.755,14.076 C64.877,60.568,65.085,62.023,64.55,62.949z">
                            </path>
                            <path
                                d="M22.026,50.969c-3.017,0-5.471,2.453-5.471,5.471c0,3.017,2.454,5.471,5.471,5.471c3.016,0,5.471-2.454,5.471-5.471 C27.497,53.422,25.043,50.969,22.026,50.969z M22.026,59.911c-1.914,0-3.471-1.558-3.471-3.472s1.557-3.471,3.471-3.471 s3.471,1.557,3.471,3.471S23.94,59.911,22.026,59.911z">
                            </path>
                            <path
                                d="M50.775,52.469c-2.603,0-4.721,2.117-4.721,4.721c0,2.603,2.118,4.721,4.721,4.721c2.604,0,4.722-2.118,4.722-4.721 C55.497,54.586,53.379,52.469,50.775,52.469z M50.775,59.911c-1.5,0-2.721-1.222-2.721-2.722s1.221-2.721,2.721-2.721 s2.722,1.221,2.722,2.721S52.275,59.911,50.775,59.911z">
                            </path>
                            <path
                                d="M35.077,45.469c-2.217,0-4.021,1.803-4.021,4.021c0,2.217,1.803,4.021,4.021,4.021c2.217,0,4.021-1.805,4.021-4.021 S37.294,45.469,35.077,45.469z M35.077,51.512c-1.114,0-2.021-0.908-2.021-2.021c0-1.114,0.907-2.021,2.021-2.021 c1.114,0,2.021,0.906,2.021,2.021S36.191,51.512,35.077,51.512z">
                            </path>
                            <path
                                d="M40.824,22.42c0.553,0,1-0.447,1-1v-11c0-0.553-0.447-1-1-1s-1,0.447-1,1v11C39.824,21.973,40.271,22.42,40.824,22.42z">
                            </path>
                            <path
                                d="M40.824,27.42c0.553,0,1-0.447,1-1v-1c0-0.553-0.447-1-1-1s-1,0.447-1,1v1C39.824,26.973,40.271,27.42,40.824,27.42z">
                            </path>
                        </svg>
                    </div>
                    <span x-show="sidebarOpen" class="ml-3" x-transition>Skrining</span>
                </a>

                <!-- Schedule Menu Item -->
                <a href="{{ route('admin.schedule') }}" wire:navigate
                    class="flex items-center p-3 rounded-lg group font-semibold"
                    :class="{
                        'bg-gradient-to-r from-teal-400 to-primary text-white shadow-md': {{ request()->routeIs('admin.schedule') ? 'true' : 'false' }},
                        'text-slate-600 hover:bg-slate-100': !
                            {{ request()->routeIs('admin.schedule') ? 'true' : 'false' }},
                        'justify-center': !sidebarOpen
                    }">
                    <div class="w-6 h-6 flex items-center justify-center">
                        <svg class="w-full h-full"
                            :class="!{{ request()->routeIs('admin.schedule') ? 'true' : 'false' }} &&
                                'text-slate-600 group-hover:text-primary stroke-currentColor'"
                            viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path
                                d="M10 21H6.2C5.0799 21 4.51984 21 4.09202 20.782C3.71569 20.5903 3.40973 20.2843 3.21799 19.908C3 19.4802 3 18.9201 3 17.8V8.2C3 7.0799 3 6.51984 3.21799 6.09202C3.40973 5.71569 3.71569 5.40973 4.09202 5.21799C4.51984 5 5.0799 5 6.2 5H17.8C18.9201 5 19.4802 5 19.908 5.21799C20.2843 5.40973 20.5903 5.71569 20.782 6.09202C21 6.51984 21 7.0799 21 8.2V10M7 3V5M17 3V5M3 9H21M13.5 13.0001L7 13M10 17.0001L7 17M14 21L16.025 20.595C16.2015 20.5597 16.2898 20.542 16.3721 20.5097C16.4452 20.4811 16.5147 20.4439 16.579 20.399C16.6516 20.3484 16.7152 20.2848 16.8426 20.1574L21 16C21.5523 15.4477 21.5523 14.5523 21 14C20.4477 13.4477 19.5523 13.4477 19 14L14.8426 18.1574C14.7152 18.2848 14.6516 18.3484 14.601 18.421C14.5561 18.4853 14.5189 18.5548 14.4903 18.6279C14.458 18.7102 14.4403 18.7985 14.405 18.975L14 21Z"
                                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            </path>
                        </svg>
                    </div>
                    <span x-show="sidebarOpen" class="ml-3" x-transition>Jadwal Pemeriksaan</span>
                </a>

            </nav>

            <!-- Profile & Logout Section -->
            <div class="px-3 py-4 mt-auto border-t border-slate-200">
                <!-- User Profile Info -->
                <a href="{{ route('admin.profile') }}" wire:navigate class="flex items-center p-2 mb-2 rounded-lg hover:bg-slate-100 group transition-colors" :class="!sidebarOpen && 'justify-center'">
                    <div class="w-8 h-8 rounded-lg bg-teal-100 text-teal-700 flex items-center justify-center font-bold text-sm flex-shrink-0">
                        {{ substr(auth()->user()->name, 0, 1) }}
                    </div>
                    <div x-show="sidebarOpen" class="ml-3 flex-1 overflow-hidden" x-transition>
                        <p class="text-sm font-semibold text-slate-800 truncate">{{ auth()->user()->name }}</p>
                        <p class="text-xs text-slate-500 truncate">{{ auth()->user()->email }}</p>
                    </div>
                </a>

                <!-- Logout Button -->
                <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                    class="flex items-center p-2 text-slate-600 rounded-lg hover:bg-red-100 hover:text-red-600 group font-semibold transition-colors"
                    :class="!sidebarOpen && 'justify-center'">
                    <div class="w-6 h-6 flex items-center justify-center flex-shrink-0">
                        <svg class="w-5 h-5"
                            xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15m3 0l3-3m0 0l-3-3m3 3H9" />
                        </svg>
                    </div>
                    <span x-show="sidebarOpen" class="ml-3 text-sm" x-transition>Logout</span>
                </a>
                <!-- Hidden Logout Form -->
                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">
                    @csrf
                </form>
            </div>
        </aside>

        <!-- Mobile Top Header for Small Screens -->
        <div class="md:hidden fixed top-0 left-0 right-0 z-40 bg-white shadow-sm border-b border-slate-200">
            <div class="flex items-center justify-start h-16 px-4">
                <!-- Mobile Logo and Brand -->
                <div class="flex items-center">
                    <img src="{{ asset('images/logo-podiatrix.png') }}" class="h-15 w-auto" alt="Podiatrix Logo">
                    <span class="font-bold text-lg text-slate-800">Podiatrix</span>
                </div>
            </div>
        </div>

        <!-- Mobile Bottom Navigation Bar -->
        <nav
            class="md:hidden fixed bottom-4 left-4 right-4 bg-white/95 backdrop-blur-md border border-slate-200 rounded-2xl shadow-[0_8px_30px_rgba(0,0,0,0.12)] z-50">
            <div class="flex justify-around items-center h-16 px-2">
                <!-- Mobile Screening Menu -->
                <a href="{{ route('admin.screenings') }}" wire:navigate
                    class="flex flex-col items-center justify-center p-2 rounded-lg min-w-0 flex-1"
                    :class="{{ request()->routeIs('admin.screenings') ? 'true' : 'false' }} ? 'text-primary' : 'text-slate-600'">
                    <div class="w-6 h-6 flex items-center justify-center mb-1">
                        <svg class="w-full h-full" viewBox="0 0 72 72" fill="currentColor"
                            xmlns="http://www.w3.org/2000/svg">
                            <path
                                d="M67.625,56.738C67.592,56.688,67.66,56.787,67.625,56.738L47.823,28.167V5.42c0-1.105-0.539-1.92-1.643-1.92H27.179 c-1.104,0-1.356,0.816-1.356,1.92v22.097L4.904,56.764c-0.035,0.049-0.217,0.277-0.248,0.33c-1.688,2.926-2.078,5.621-0.785,7.86 c1.306,2.261,3.915,3.546,7.347,3.546h49.451c3.429,0,6.118-1.287,7.424-3.551C69.389,62.704,69.313,59.666,67.625,56.738z M29.137,29.302c0.237-0.338,0.687-0.74,0.687-1.152V7.5h14v21.301c0,0.412-0.194,0.824,0.044,1.161l9.19,13.262 c-3.056,1.232-6.822,2.05-14.531-2.557c-5.585-3.337-12.499-2.048-17.199-0.449L29.137,29.302z M64.55,62.949 c-0.554,0.96-1.969,1.551-3.88,1.551H11.219c-1.915,0-3.33-0.589-3.883-1.547c-0.532-0.922-0.324-2.391,0.571-3.975l11.287-15.777 c3.942-1.702,12.219-4.454,18.308-0.816c4.852,2.898,8.367,3.814,11.116,3.814c2.291,0,4.05-0.637,5.607-1.291l9.755,14.076 C64.877,60.568,65.085,62.023,64.55,62.949z">
                            </path>
                            <path
                                d="M22.026,50.969c-3.017,0-5.471,2.453-5.471,5.471c0,3.017,2.454,5.471,5.471,5.471c3.016,0,5.471-2.454,5.471-5.471 C27.497,53.422,25.043,50.969,22.026,50.969z M22.026,59.911c-1.914,0-3.471-1.558-3.471-3.472s1.557-3.471,3.471-3.471 s3.471,1.557,3.471,3.471S23.94,59.911,22.026,59.911z">
                            </path>
                            <path
                                d="M50.775,52.469c-2.603,0-4.721,2.117-4.721,4.721c0,2.603,2.118,4.721,4.721,4.721c2.604,0,4.722-2.118,4.722-4.721 C55.497,54.586,53.379,52.469,50.775,52.469z M50.775,59.911c-1.5,0-2.721-1.222-2.721-2.722s1.221-2.721,2.721-2.721 s2.722,1.221,2.722,2.721S52.275,59.911,50.775,59.911z">
                            </path>
                            <path
                                d="M35.077,45.469c-2.217,0-4.021,1.803-4.021,4.021c0,2.217,1.803,4.021,4.021,4.021c2.217,0,4.021-1.805,4.021-4.021 S37.294,45.469,35.077,45.469z M35.077,51.512c-1.114,0-2.021-0.908-2.021-2.021c0-1.114,0.907-2.021,2.021-2.021 c1.114,0,2.021,0.906,2.021,2.021S36.191,51.512,35.077,51.512z">
                            </path>
                            <path
                                d="M40.824,22.42c0.553,0,1-0.447,1-1v-11c0-0.553-0.447-1-1-1s-1,0.447-1,1v11C39.824,21.973,40.271,22.42,40.824,22.42z">
                            </path>
                            <path
                                d="M40.824,27.42c0.553,0,1-0.447,1-1v-1c0-0.553-0.447-1-1-1s-1,0.447-1,1v1C39.824,26.973,40.271,27.42,40.824,27.42z">
                            </path>
                        </svg>
                    </div>
                    <span class="text-xs font-medium">Skrining</span>
                </a>

                <!-- Mobile Schedule Menu -->
                <a href="{{ route('admin.schedule') }}" wire:navigate
                    class="flex flex-col items-center justify-center p-2 rounded-lg min-w-0 flex-1"
                    :class="{{ request()->routeIs('admin.schedule') ? 'true' : 'false' }} ? 'text-primary' : 'text-slate-600'">
                    <div class="w-6 h-6 flex items-center justify-center mb-1">
                        <svg class="w-full h-full" viewBox="0 0 24 24" fill="none"
                            xmlns="http://www.w3.org/2000/svg">
                            <path
                                d="M10 21H6.2C5.0799 21 4.51984 21 4.09202 20.782C3.71569 20.5903 3.40973 20.2843 3.21799 19.908C3 19.4802 3 18.9201 3 17.8V8.2C3 7.0799 3 6.51984 3.21799 6.09202C3.40973 5.71569 3.71569 5.40973 4.09202 5.21799C4.51984 5 5.0799 5 6.2 5H17.8C18.9201 5 19.4802 5 19.908 5.21799C20.2843 5.40973 20.5903 5.71569 20.782 6.09202C21 6.51984 21 7.0799 21 8.2V10M7 3V5M17 3V5M3 9H21M13.5 13.0001L7 13M10 17.0001L7 17M14 21L16.025 20.595C16.2015 20.5597 16.2898 20.542 16.3721 20.5097C16.4452 20.4811 16.5147 20.4439 16.579 20.399C16.6516 20.3484 16.7152 20.2848 16.8426 20.1574L21 16C21.5523 15.4477 21.5523 14.5523 21 14C20.4477 13.4477 19.5523 13.4477 19 14L14.8426 18.1574C14.7152 18.2848 14.6516 18.3484 14.601 18.421C14.5561 18.4853 14.5189 18.5548 14.4903 18.6279C14.458 18.7102 14.4403 18.7985 14.405 18.975L14 21Z"
                                stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round"></path>
                        </svg>
                    </div>
                    <span class="text-xs font-medium">Jadwal</span>
                </a>

                <!-- Mobile Profile Menu -->
                <a href="{{ route('admin.profile') }}" wire:navigate
                    class="flex flex-col items-center justify-center p-2 rounded-lg min-w-0 flex-1"
                    :class="{{ request()->routeIs('admin.profile') ? 'true' : 'false' }} ? 'text-primary' : 'text-slate-600'">
                    <div class="w-6 h-6 flex items-center justify-center mb-1">
                        <svg class="w-full h-full" xmlns="http://www.w3.org/2000/svg" fill="none"
                            viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M17.982 18.725A7.488 7.488 0 0012 15.75a7.488 7.488 0 00-5.982 2.975m11.963 0a9 9 0 10-11.963 0m11.963 0A8.966 8.966 0 0112 21a8.966 8.966 0 01-5.982-2.275M15 9.75a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                    </div>
                    <span class="text-xs font-medium">Profil</span>
                </a>
            </div>
        </nav>

        <!-- Main Content Area -->
        <div class="flex flex-col flex-1 overflow-y-auto">
            <!-- Main content with responsive padding for mobile header and bottom navigation -->
            <main class="flex-1 p-6 md:p-8 pt-20 md:pt-6 pb-20 md:pb-6">
                {{ $slot }}
            </main>
        </div>
    </div>

    <!-- JavaScript Dependencies -->
    @fluxScripts
    <script src="https://cdn.jsdelivr.net/npm/flowbite@3.1.2/dist/flowbite.min.js"></script>
    @stack('scripts')
</body>

</html>
