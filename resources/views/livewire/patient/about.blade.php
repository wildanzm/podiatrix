<?php

use Livewire\Volt\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

new #[Layout('layouts.patient')] #[Title('Tentang')] class extends Component {
    //
}; ?>

<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    {{-- Page Header --}}
    <header class="text-center mb-10">
        <h1 class="text-3xl md:text-4xl font-bold text-slate-800">Tentang Aplikasi</h1>
        <p class="mt-3 text-slate-500 max-w-2xl mx-auto">Informasi mengenai sistem skrining kaki diabetik Podiatrix dan
            pengembangnya.</p>
    </header>

    <div class="space-y-8">
        {{-- Application Description Card --}}
        <div class="bg-white p-6 sm:p-8 rounded-2xl shadow-sm border border-slate-200 relative overflow-hidden group">
            <div
                class="absolute -right-10 -top-10 text-teal-50 opacity-50 transform group-hover:scale-110 transition-transform duration-500">
                <svg class="w-48 h-48" fill="currentColor" viewBox="0 0 24 24">
                    <path
                        d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-6h2v6zm0-8h-2V7h2v2z" />
                </svg>
            </div>

            <div class="relative z-10">
                <div class="flex items-center gap-3 mb-6">
                    <img src="{{ asset('images/logo-podiatrix.png') }}" class="h-10 w-auto" alt="Podiatrix Logo">
                    <h2 class="text-2xl font-bold text-primary">Podiatrix</h2>
                </div>

                <p class="text-lg font-medium text-slate-600 italic mb-6 pb-4 border-b border-slate-100">
                    "Langkahmu Berarti, Podiatrix yang peduli"
                </p>

                <div class="prose max-w-none text-slate-600 leading-relaxed space-y-4">
                    <p>
                        <strong>Podiatrix</strong> adalah aplikasi skrining kaki diabetik berbasis <strong>Inlow's
                            60-Second Diabetic Foot Screen</strong> yang dirancang khusus untuk membantu tenaga
                        kesehatan dan pasien dalam mendeteksi risiko komplikasi secara mandiri.
                    </p>
                    <p>
                        Aplikasi ini mendeteksi risiko secara cepat, akurat, dan terdokumentasi dengan baik. Sistem
                        mendukung klasifikasi risiko otomatis berdasarkan hasil skrining, serta memberikan rekomendasi
                        perawatan yang berstandar pada pedoman <strong>Wounds Canada</strong>.
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
