<?php

use Livewire\Volt\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use App\Models\Screening;

new #[Layout('layouts.patient')] #[Title('Hasil Skrining')] class extends Component {
    public Screening $screening;
    public $recommendations = [];
    public $notes = '';

    /**
     * Initialize component with screening data
     */
    public function mount(Screening $screening)
    {
        // Pastikan agar patient tidak bisa melihat hasil skrining pasien lain
        $user = auth()->user();
        $patient = \App\Models\Patient::where('name', $user->name)->where('date_of_birth', $user->date_of_birth)->first();

        if (!$patient || $screening->patient_id !== $patient->id) {
            abort(403, 'Akses ditolak.');
        }

        $this->screening = $screening;
        $this->recommendations = $screening->recommendation ?? [];
        $this->notes = $screening->notes ?? '';
    }
};
?>

<div class="px-4 sm:px-6 lg:px-8 mt-6 pb-24">
    <div class="sm:flex sm:items-center sm:justify-between mb-8">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Detail Hasil Skrining</h1>
            <p class="mt-2 text-sm text-gray-700">Rincian observasi dan rekomendasi dari skrining sebelumnya.</p>
        </div>
        <div class="mt-4 sm:mt-0 flex space-x-3">
            <a href="{{ route('patient.history') }}"
                class="inline-flex items-center justify-center rounded-lg bg-white px-4 py-2.5 text-sm font-semibold text-gray-700 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 transition">
                <svg class="mr-2 h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke-width="2"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
                </svg>
                Kembali
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        {{-- Main Results Column --}}
        <div class="lg:col-span-2 space-y-6">
            {{-- Risk Classification Section --}}
            <div class="bg-white p-6 sm:p-8 rounded-2xl shadow-sm border border-slate-200">
                <h2 class="text-lg font-semibold text-primary mb-4 pb-4 border-b border-slate-200">Klasifikasi Risiko
                </h2>
                <div class="w-full p-6 text-center font-bold shadow-sm"
                    style="border-radius: 1rem; background-color:
                        @if ($screening->risk_classification === 'Sangat Rendah') #058a84; color: #ffffff
                        @elseif($screening->risk_classification === 'Rendah') #ffd867; color: #1f2937
                        @elseif($screening->risk_classification === 'Sedang') #faba5c; color: #1f2937
                        @elseif($screening->risk_classification === 'Tinggi') #ef810b; color: #ffffff
                        @elseif($screening->risk_classification === 'Darurat') #d0222a; color: #ffffff
                        @else #6b7280; color: #ffffff @endif">
                    <p class="text-3xl font-bold">{{ $screening->risk_classification }}</p>
                </div>
            </div>

            {{-- Recommendations Section --}}
            @if (count($recommendations) > 0)
                <div class="bg-white p-6 sm:p-8 rounded-2xl shadow-sm border border-slate-200">
                    <h2 class="text-lg font-semibold text-primary mb-4 pb-4 border-b border-slate-200">Rekomendasi
                        Tindakan
                    </h2>
                    <div class="space-y-4">
                        @foreach ($recommendations as $recommendation)
                            <div class="flex items-start">
                                <div class="flex-shrink-0 mt-0.5">
                                    <svg class="h-5 w-5 text-teal-500" fill="none" viewBox="0 0 24 24"
                                        stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </div>
                                <p class="ml-3 text-sm text-gray-700 leading-relaxed">{{ $recommendation }}</p>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            {{-- Clinical Notes Section --}}
            @if (!empty(trim($notes)))
                <div class="bg-white p-6 sm:p-8 rounded-2xl shadow-sm border border-slate-200">
                    <h2 class="text-lg font-semibold text-primary mb-4 pb-4 border-b border-slate-200">Catatan
                        Klinis
                        Edukasi</h2>
                    <div class="prose prose-sm max-w-none">
                        <div class="bg-slate-50 p-5 rounded-xl border-l-4 border-primary shadow-sm">
                            <p class="text-slate-700 leading-relaxed whitespace-pre-wrap">{{ $notes }}</p>
                        </div>
                    </div>
                </div>
            @endif

            {{-- Puskesmas Info & CTA Section --}}
            <div class="bg-teal-50 p-6 sm:p-8 rounded-2xl shadow-sm border border-teal-100">
                <h2 class="text-lg font-semibold text-primary mb-4 pb-4 border-b border-teal-200">
                    Pengecekan Lebih Lanjut
                </h2>
                <div class="space-y-4 text-slate-700">
                    <div class="flex items-start bg-teal-50/50 p-4 rounded-xl">
                        <svg class="h-6 w-6 text-teal-600 mr-3 flex-shrink-0 mt-0.5" fill="none"
                            stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <p class="font-medium text-slate-700">Untuk mendapatkan pemeriksaan fisik menyeluruh dan penanganan medis lebih lanjut, silakan kunjungi fasilitas kesehatan atau dokter terdekat.</p>
                    </div>
                </div>
            </div>

            {{-- Healthy Lifestyle Image Section --}}
            <div class="bg-white p-6 sm:p-8 rounded-2xl shadow-sm border border-slate-200">
                <h2 class="text-lg font-semibold text-primary mb-4 pb-4 border-b border-slate-200">
                    Panduan Hidup Sehat untuk Penderita Diabetes Melitus
                </h2>
                <div>
                    <img src="{{ asset('images/panduan-hidup-sehat-untuk-diabetes.jpeg') }}"
                        alt="Panduan Hidup Sehat untuk Diabetes" class="w-full h-auto rounded-xl shadow-sm">
                </div>
            </div>
        </div>

        {{-- Side Column --}}
        <div class="space-y-6">
            <!-- Screening Information Card -->
            <div class="bg-white p-6 sm:p-8 rounded-2xl shadow-sm border border-slate-200">
                <h2 class="text-lg font-semibold text-primary mb-4 pb-4 border-b border-slate-200">Informasi
                    Skrining
                </h2>
                <dl class="text-sm space-y-4">
                    <div class="flex flex-col">
                        <dt class="font-medium text-slate-500 mb-1">Tanggal Skrining</dt>
                        <dd class="text-slate-900 font-semibold">
                            {{ $screening->created_at->locale('id')->translatedFormat('d F Y') }}</dd>
                    </div>
                    <div class="flex flex-col">
                        <dt class="font-medium text-slate-500 mb-1">Waktu</dt>
                        <dd class="text-slate-900 font-semibold">{{ $screening->created_at->format('H:i') }} WIB
                        </dd>
                    </div>
                </dl>
            </div>
        </div>
    </div>

    <!-- Methodology Information -->
    <div
        class="mt-8 bg-white shadow-sm rounded-2xl border border-slate-200 p-4 sm:p-6 flex flex-row items-start gap-3 sm:gap-4">
        <div class="flex-shrink-0 bg-slate-50 p-2 sm:p-3 rounded-xl shadow-sm border border-slate-100 hidden sm:block">
            <svg class="w-6 h-6 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
        </div>
        <div class="flex-1">
            <div class="flex items-center gap-2 mb-1 sm:mb-2">
                <svg class="w-5 h-5 text-primary sm:hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                    xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <h3 class="text-sm font-bold text-slate-800">Metodologi Skrining</h3>
            </div>
            <p class="text-sm text-slate-600 leading-relaxed text-justify sm:text-left">
                Instrumen penilaian dan rekomendasi tindakan pada skrining ini menggunakan standar <span
                    class="font-semibold text-primary">Inlow's 60-Second Diabetic Foot Screen</span>. Metode ini
                dikembangkan untuk mengidentifikasi risiko komplikasi kaki diabetik secara komprehensif.
            </p>
        </div>
    </div>

</div>
