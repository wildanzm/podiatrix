<?php

use Livewire\Volt\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

// Using main layout and setting page title
new #[Layout('components.layouts.app')] #[Title('Profil')] class extends Component {
    // No logic needed here as this is a static page
}; ?>

{{-- View Section (HTML) --}}
<div class="space-y-8">
    {{-- Page Header --}}
    <header>
        <h1 class="text-3xl font-bold text-slate-800">Profil Akun</h1>
        <p class="mt-1 text-slate-500">Kelola informasi akun Anda dan pengaturan sesi.</p>
    </header>

    {{-- Admin Profile Card --}}
    <div class="bg-white p-6 sm:p-8 rounded-2xl shadow-md border border-slate-200">
        <h2 class="text-xl font-semibold text-primary mb-6 pb-4 border-b border-slate-200">Informasi Pengguna</h2>
        <div class="flex flex-col md:flex-row items-center md:items-start gap-6 md:gap-8">
            {{-- Avatar --}}
            <div class="flex-shrink-0">
                <div class="h-32 w-32 rounded-full bg-gradient-to-br from-teal-400 to-primary text-white flex items-center justify-center font-bold text-5xl shadow-lg border-4 border-white ring-4 ring-slate-50">
                    {{ substr(auth()->user()->name, 0, 1) }}
                </div>
            </div>
            
            {{-- User Details --}}
            <div class="text-center md:text-left flex-1 mt-2">
                <p class="text-2xl font-bold text-slate-800">{{ auth()->user()->name }}</p>
                <p class="text-md text-slate-600 mt-1 flex items-center justify-center md:justify-start gap-2">
                    <svg class="w-4 h-4 text-slate-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75" />
                    </svg>
                    {{ auth()->user()->email }}
                </p>
                
                <div class="mt-4 inline-block bg-teal-50 text-teal-700 px-4 py-1.5 rounded-full text-sm font-semibold border border-teal-100 shadow-sm">
                    Administrator
                </div>
                
                {{-- Logout Button --}}
                <div class="mt-8 pt-6 border-t border-slate-100">
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="inline-flex items-center justify-center w-full md:w-auto px-6 py-3 rounded-xl text-white bg-red-600 hover:bg-red-700 font-semibold shadow-sm transition-all hover:shadow-md hover:-translate-y-0.5 focus:ring-4 focus:ring-red-200">
                            <svg class="w-5 h-5 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15m3 0l3-3m0 0l-3-3m3 3H9" />
                            </svg>
                            Logout dari Aplikasi
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
