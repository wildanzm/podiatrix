<?php

use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Volt\Component;

new #[Layout('layouts.patient')] #[Title('Beranda')] class extends Component {
    public function mount()
    {
        if (Auth::check() && Auth::user()->hasRole('admin')) {
            return redirect()->route('admin.screenings');
        }
    }
}; ?>

<div class="bg-white">
    <!-- Hero Section -->
    <div class="relative bg-white overflow-hidden">
        <div class="max-w-7xl mx-auto">
            <div
                class="relative z-10 pb-8 bg-white sm:pb-16 md:pb-20 lg:max-w-2xl lg:w-full lg:pb-28 xl:pb-32 pt-10 sm:pt-16 lg:pt-20">
                <main class="mt-10 mx-auto max-w-7xl px-4 sm:mt-12 sm:px-6 md:mt-16 lg:mt-20 lg:px-8 xl:mt-28">
                    <div class="sm:text-center lg:text-left">
                        <span
                            class="inline-block py-1 px-3 rounded-full bg-teal-50 text-primary text-sm font-semibold mb-4 border border-teal-100">
                            Terpercaya & Profesional
                        </span>
                        <h1 class="text-4xl tracking-tight font-extrabold text-slate-900 sm:text-5xl md:text-6xl">
                            <span class="block xl:inline">Perawatan Kaki Anda</span>
                            <span class="block text-primary xl:inline">Langkah Utama Kami</span>
                        </h1>
                        <p
                            class="mt-3 text-base text-slate-500 sm:mt-5 sm:text-lg sm:max-w-xl sm:mx-auto md:mt-5 md:text-xl lg:mx-0 leading-relaxed">
                            Aplikasi pintar untuk deteksi dini risiko komplikasi kaki diabetik secara mandiri, sekaligus
                            memudahkan Anda membuat jadwal pemeriksaan dan konsultasi langsung dengan dokter ahli kami.
                        </p>
                        <div class="mt-8 sm:mt-10 sm:flex sm:justify-center lg:justify-start gap-4">
                            @guest
                                <div class="mt-3 sm:mt-0">
                                    <a href="{{ route('register') }}" wire:navigate
                                        class="w-full flex items-center justify-center px-4 py-3 text-base font-bold text-primary hover:text-teal-700 md:py-4 md:text-lg transition-colors group">
                                        Skrining Mandiri Gratis
                                        <svg class="ml-2 w-5 h-5 transform group-hover:translate-x-1 transition-transform"
                                            fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M13 7l5 5m0 0l-5 5m5-5H6" />
                                        </svg>
                                    </a>
                                </div>
                                <div class="mt-3 sm:mt-0 sm:ml-3">
                                    <a href="{{ route('register') }}" wire:navigate
                                        class="w-full flex items-center justify-center px-6 py-3 border-2 border-slate-200 text-base font-medium rounded-full text-slate-700 bg-white hover:bg-slate-50 hover:border-slate-300 md:py-4 md:text-lg transition-colors shadow-sm">
                                        <svg class="w-5 h-5 mr-2 text-slate-500" fill="none" viewBox="0 0 24 24"
                                            stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                        Buat Jadwal Dokter
                                    </a>
                                </div>
                            @endguest

                            @auth
                                <div class="mt-3 sm:mt-0">
                                    <a href="{{ route('patient.screenings') }}" wire:navigate
                                        class="w-full flex items-center justify-center px-4 py-3 text-base font-bold text-primary hover:text-teal-700 md:py-4 md:text-lg transition-colors group">
                                        Skrining Mandiri Gratis
                                        <svg class="ml-2 w-5 h-5 transform group-hover:translate-x-1 transition-transform"
                                            fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M13 7l5 5m0 0l-5 5m5-5H6" />
                                        </svg>
                                    </a>
                                </div>
                                <div class="mt-3 sm:mt-0 sm:ml-3">
                                    <a href="{{ route('patient.schedule') }}" wire:navigate
                                        class="w-full flex items-center justify-center px-6 py-3 border-2 border-slate-200 text-base font-medium rounded-full text-slate-700 bg-white hover:bg-slate-50 hover:border-slate-300 md:py-4 md:text-lg transition-colors shadow-sm">
                                        <svg class="w-5 h-5 mr-2 text-slate-500" fill="none" viewBox="0 0 24 24"
                                            stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                        Buat Jadwal Dokter
                                    </a>
                                </div>
                            @endauth
                        </div>
                    </div>
                </main>
            </div>
        </div>
        <!-- Decorative Image Area / Abstract UI Representation -->
        <div
            class="lg:absolute lg:inset-y-0 lg:right-0 lg:w-1/2 flex items-center justify-center bg-slate-50 lg:bg-transparent overflow-hidden px-4 md:px-0 py-10 lg:py-0">
            <div
                class="relative w-full max-w-lg lg:max-w-none lg:w-[800px] h-80 sm:h-[400px] md:h-96 lg:h-full lg:translate-x-10 rounded-2xl lg:rounded-none overflow-hidden lg:overflow-visible">
                <!-- Abstract Graphic replacing an actual image for a modern look without external assets -->
                <div
                    class="absolute inset-0 bg-gradient-to-tr from-teal-500 to-primary opacity-10 lg:rounded-l-3xl transform skew-x-[-10deg] scale-110">
                </div>
                <div
                    class="absolute inset-4 sm:inset-10 bg-white rounded-2xl shadow-xl flex items-center justify-center p-4 sm:p-8 border border-white/40 overflow-hidden backdrop-blur-sm">
                    <!-- decorative background blobs -->
                    <div
                        class="absolute top-0 right-0 -mr-16 -mt-16 w-48 h-48 sm:w-64 sm:h-64 rounded-full bg-teal-50 opacity-60">
                    </div>
                    <div
                        class="absolute bottom-0 left-0 -ml-16 -mb-16 w-32 h-32 sm:w-48 sm:h-48 rounded-full bg-primary opacity-5">
                    </div>

                    <div class="relative z-10 w-full flex flex-col items-center">
                        <div
                            class="w-16 h-16 sm:w-24 sm:h-24 mb-4 sm:mb-6 relative hover:scale-105 transition-transform duration-300 cursor-default">
                            <div
                                class="absolute inset-0 bg-teal-100 rounded-full animate-[ping_3s_cubic-bezier(0,0,0.2,1)_infinite] opacity-50">
                            </div>
                            <div
                                class="relative w-full h-full bg-primary rounded-full flex items-center justify-center text-white shadow-lg border-4 border-white">
                                <svg class="w-8 h-8 sm:w-12 sm:h-12" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24" stroke-width="1.5">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12z" />
                                </svg>
                            </div>
                        </div>
                        <h3
                            class="text-xl sm:text-3xl font-bold text-slate-800 mb-1 sm:mb-2 text-center shadow-slate-100">
                            Deteksi Dini,</h3>
                        <h3 class="text-xl sm:text-3xl font-bold text-primary mb-2 text-center">Pencegahan Pasti</h3>
                        <p
                            class="text-slate-500 text-center max-w-sm mt-1 sm:mt-2 text-sm sm:text-base leading-relaxed">
                            Dapatkan skor kesehatan kaki Anda secara instan dengan teknologi cerdas kami.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Features Section / Kenapa Podiatrix -->
    <div class="py-20 bg-slate-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-3xl mx-auto mb-16">
                <h2 class="text-primary font-semibold tracking-wide uppercase text-sm">Keunggulan Aplikasi</h2>
                <p class="mt-2 text-3xl leading-8 font-extrabold tracking-tight text-slate-900 sm:text-4xl">
                    Kenapa Memilih Podiatrix?
                </p>
                <p class="mt-4 max-w-2xl text-xl text-slate-500 mx-auto">
                    Kami hadir membawa kemudahan teknologi untuk membantu pencegahan dan penanganan masalah kaki Anda
                    secara proaktif.
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <!-- Feature 1 -->
                <div
                    class="bg-white rounded-2xl p-8 shadow-sm hover:shadow-xl transition-all duration-300 border border-slate-100 hover:border-teal-100 group">
                    <div
                        class="w-14 h-14 bg-teal-50 text-primary rounded-2xl flex items-center justify-center mb-6 group-hover:bg-primary group-hover:text-white transition-colors">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                            stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m5.231 13.481L15 17.25m-4.5-15H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9zm3.75 11.625a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-slate-900 mb-3">Skrining Mandiri Diabetes</h3>
                    <p class="text-slate-500 leading-relaxed text-sm">
                        Lakukan deteksi dini risiko komplikasi kaki diabetik secara mandiri menggunakan kuesioner cerdas
                        kapan pun dari kenyamanan rumah Anda.
                    </p>
                </div>

                <!-- Feature 2 -->
                <div
                    class="bg-white rounded-2xl p-8 shadow-sm hover:shadow-xl transition-all duration-300 border border-slate-100 hover:border-teal-100 group translate-y-0 md:-translate-y-4">
                    <div
                        class="absolute inset-0 bg-gradient-to-br from-teal-500/5 to-primary/5 rounded-2xl opacity-0 group-hover:opacity-100 transition-opacity">
                    </div>
                    <div class="relative">
                        <div
                            class="w-14 h-14 bg-teal-50 text-primary rounded-2xl flex items-center justify-center mb-6 group-hover:bg-primary group-hover:text-white transition-colors shadow-sm">
                            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5m-9-6h.008v.008H12v-.008zM12 15h.008v.008H12V15zm0 2.25h.008v.008H12v-.008zM9.75 15h.008v.008H9.75V15zm0 2.25h.008v.008H9.75v-.008zM7.5 15h.008v.008H7.5V15zm0 2.25h.008v.008H7.5v-.008zm6.75-4.5h.008v.008h-.008v-.008zm0 2.25h.008v.008h-.008V15zm0 2.25h.008v.008h-.008v-.008zm2.25-4.5h.008v.008H16.5v-.008zm0 2.25h.008v.008H16.5V15z" />
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-slate-900 mb-3">Jadwal Pemeriksaan Dokter</h3>
                        <p class="text-slate-500 leading-relaxed text-sm">
                            Makin mudah atur jadwal pertemuan secara langsung dengan dokter dan tenaga medis ahli untuk
                            penanganan dan pemeriksaan fisik lebih lanjut.
                        </p>
                    </div>
                </div>

                <!-- Feature 3 -->
                <div
                    class="bg-white rounded-2xl p-8 shadow-sm hover:shadow-xl transition-all duration-300 border border-slate-100 hover:border-teal-100 group">
                    <div
                        class="w-14 h-14 bg-teal-50 text-primary rounded-2xl flex items-center justify-center mb-6 group-hover:bg-primary group-hover:text-white transition-colors">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                            stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 002.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 00-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 00.75-.75 2.25 2.25 0 00-.1-.664m-5.8 0A2.251 2.251 0 0113.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25zM6.75 12h.008v.008H6.75V12zm0 3h.008v.008H6.75V15zm0 3h.008v.008H6.75v-.008z" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-slate-900 mb-3">Rekam Medis Terintegrasi</h3>
                    <p class="text-slate-500 leading-relaxed text-sm">
                        Seluruh hasil penilaian dan histori pemeriksaan dari dokter Anda tersimpan aman dan terhubung
                        untuk membantu memantau progres kesehatan kaki.
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Methodology Banner -->
    <div class="py-12 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-gradient-to-r from-teal-50 to-primary/5 rounded-3xl p-8 sm:p-12 border border-teal-100 flex flex-col md:flex-row items-center gap-8 shadow-sm">
                <div class="w-full md:w-1/3 flex justify-center relative">
                    <div class="relative w-32 h-32 bg-white rounded-full flex items-center justify-center shadow-md border-4 border-teal-50 z-10">
                        <svg class="w-16 h-16 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m5.231 13.481L15 17.25m-4.5-15H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9zm3.75 11.625a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z"></path>
                        </svg>
                        <div class="absolute -bottom-2 -right-4 bg-amber-400 text-white text-[10px] font-bold px-2 py-1 rounded-lg shadow-sm uppercase tracking-wider border-2 border-white">Terstandar</div>
                    </div>
                </div>
                <div class="w-full md:w-2/3">
                    <h2 class="text-primary font-semibold tracking-wide uppercase text-sm mb-2 text-start md:text-left">Metodologi Penilaian Klinis</h2>
                    <h3 class="text-2xl sm:text-3xl font-extrabold text-slate-900 mb-4 text-start md:text-left">
                        Inlow's 60-Second Diabetic Foot Screen
                    </h3>
                    <p class="text-slate-600 leading-relaxed text-base sm:text-lg mb-4 text-justify md:text-left">
                        Seluruh penilaian skrining pada aplikasi <span class="font-bold">Podiatrix</span> didasarkan pada metode standar <span class="font-semibold text-primary">Inlow's 60-Second Diabetic Foot Screen</span>. Metode ini dirancang secara khusus untuk mengidentifikasi risiko komplikasi kaki diabetik secara cepat, komprehensif, dan akurat.
                    </p>
                    <p class="text-slate-500 text-sm sm:text-base leading-relaxed text-justify md:text-left">
                        Metode ini merekomendasikan langkah perawatan berkelanjutan dan tingkat urgensi bagi Anda untuk segera berkonsultasi dengan ahli medis profesional demi mencegah komplikasi lebih lanjut.
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- How it Works Section -->
    <div id="cara-kerja" class="py-20 relative overflow-hidden bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="text-center mb-16">
                <h2 class="text-3xl font-extrabold text-slate-900 sm:text-4xl">Cara Penggunaan Mudah</h2>
                <div class="mt-2 text-lg text-slate-500">Hanya butuh 3 langkah singkat untuk memulai perjalanan sehat
                    Anda.</div>
            </div>

            <div class="relative">
                <!-- Connecting Line for Desktop -->
                <div class="hidden md:block absolute top-12 left-0 w-full h-0.5 bg-slate-100" aria-hidden="true">
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-10">
                    <!-- Step 1 -->
                    <div class="relative text-center">
                        <div class="hidden md:block absolute top-12 left-1/2 w-1/2 h-0.5 bg-teal-200"
                            aria-hidden="true"></div>
                        <div
                            class="relative w-24 h-24 mx-auto bg-white border-4 border-teal-50 text-primary rounded-full flex items-center justify-center text-3xl font-bold shadow-sm mb-6 z-10">
                            1
                        </div>
                        <h3 class="text-xl font-bold text-slate-900 mb-2">Daftar / Masuk Akun</h3>
                        <p class="text-slate-500 text-sm px-4">Buat akun menggunakan email Anda secara gratis dalam
                            hitungan detik untuk menjaga privasi data Anda.</p>
                    </div>

                    <!-- Step 2 -->
                    <div class="relative text-center">
                        <div class="hidden md:block absolute top-12 left-0 w-full h-0.5 bg-teal-200"
                            aria-hidden="true"></div>
                        <div
                            class="relative w-24 h-24 mx-auto bg-primary border-4 border-teal-100 text-white rounded-full flex items-center justify-center text-3xl font-bold shadow-md mb-6 z-10 transform md:scale-110">
                            2
                        </div>
                        <h3 class="text-xl font-bold text-slate-900 mb-2">Isi Kuesioner Skrining</h3>
                        <p class="text-slate-500 text-sm px-4">Jawab serangkaian pertanyaan singkat terkait kondisi
                            atau keluhan pada area kaki dan aktivitas harian dengan jujur.</p>
                    </div>

                    <!-- Step 3 -->
                    <div class="relative text-center">
                        <div class="hidden md:block absolute top-12 right-1/2 w-1/2 h-0.5 bg-teal-200"
                            aria-hidden="true"></div>
                        <div
                            class="relative w-24 h-24 mx-auto bg-white border-4 border-teal-50 text-primary rounded-full flex items-center justify-center text-3xl font-bold shadow-sm mb-6 z-10">
                            3
                        </div>
                        <h3 class="text-xl font-bold text-slate-900 mb-2">Dapatkan Rekomendasi</h3>
                        <p class="text-slate-500 text-sm px-4">Sistem akan otomatis menghitung profil risiko dan
                            merangkum anjuran medis atau perawatan khusus buat Anda.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Educational Banner / Trust -->
    <div class="bg-primary/5 py-16 lg:py-24 border-y border-teal-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white rounded-3xl shadow-xl overflow-hidden md:flex">
                <div class="md:w-1/2 p-8 md:p-12 lg:p-16 flex flex-col justify-center">
                    <div class="inline-flex items-center gap-2 text-primary font-semibold text-sm mb-4">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                            stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        Fakta Kesehatan
                    </div>
                    <h2 class="text-3xl font-bold text-slate-900 mb-4 leading-snug">
                        Mengapa Skrining Kaki Sangat Penting?
                    </h2>
                    <p class="text-slate-600 mb-6 leading-relaxed">
                        Gangguan pada sirkulasi darah dan saraf (neuropati) sering kali dimulai dari ujung ekstremitas
                        seperti telapak kaki. Bagi penderita diabetes atau lansia, luka kecil yang tidak terdeteksi
                        dapat memicu komplikasi serius hingga amputasi.
                    </p>
                    <p class="text-slate-600 leading-relaxed">
                        Pemeriksaan berkala membantu mendeteksi penurunan kepekaan sensorik sedini mungkin. <strong
                            class="text-slate-800">Mencegah selalu lebih baik daripada mengobati.</strong>
                    </p>
                </div>
                <div
                    class="md:w-1/2 bg-teal-900 relative min-h-[300px] flex items-center justify-center p-10 overflow-hidden">
                    <div class="absolute inset-0 bg-gradient-to-br from-teal-800 to-teal-950 opacity-90"></div>
                    <!-- Decorative abstract circles representing health/pulse -->
                    <div
                        class="absolute w-64 h-64 border-2 border-white/10 rounded-full animate-[ping_4s_cubic-bezier(0,0,0.2,1)_infinite]">
                    </div>
                    <div
                        class="absolute w-48 h-48 border-2 border-white/20 rounded-full animate-[ping_3s_cubic-bezier(0,0,0.2,1)_infinite]">
                    </div>
                    <div
                        class="absolute w-32 h-32 bg-white/10 backdrop-blur-sm rounded-full flex items-center justify-center z-10 border border-white/30 shadow-2xl">
                        <svg class="w-12 h-12 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                            stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12z" />
                        </svg>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bottom CTA -->
    <div class="bg-white">
        <div class="max-w-4xl mx-auto py-16 px-4 sm:py-24 sm:px-6 lg:px-8 text-center">
            <h2 class="text-3xl font-extrabold text-slate-900 sm:text-4xl">
                <span class="block mb-2">Pantau kaki diabetes Anda dengan tepat.</span>
                <span class="block text-primary">Deteksi dini atau buat jadwal dokter hari ini.</span>
            </h2>
            <p class="mt-4 text-lg leading-6 text-slate-500 mb-8 sm:mb-12">
                Aman, profesional, dan tepercaya untuk senantiasa mendukung kesehatan langkah Anda.
            </p>

            @guest
                <div class="flex flex-col sm:flex-row justify-center items-center gap-4">
                    <a href="{{ route('register') }}" wire:navigate
                        class="w-full sm:w-auto inline-flex items-center justify-center px-8 py-4 border border-transparent text-lg font-bold rounded-full text-white bg-slate-900 hover:bg-slate-800 shadow-xl hover:shadow-2xl transition-all scale-100 hover:scale-105">
                        Mulai Skrining Mandiri
                    </a>
                    <a href="{{ route('register') }}" wire:navigate
                        class="w-full sm:w-auto inline-flex items-center justify-center px-8 py-4 border-2 border-slate-900 text-lg font-bold rounded-full text-slate-900 bg-white hover:bg-slate-50 shadow-md hover:shadow-lg transition-all scale-100 hover:scale-105">
                        <svg class="w-5 h-5 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                            stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        Buat Jadwal Dokter
                    </a>
                </div>
                <div class="mt-8 text-sm text-slate-500">
                    Sudah punya akun? <a href="{{ route('login') }}" wire:navigate
                        class="font-medium text-primary hover:text-teal-700 underline">Masuk di sini</a>
                </div>
            @endguest

            @auth
                <div class="flex flex-col sm:flex-row justify-center items-center gap-4">
                    <a href="{{ route('patient.screenings') }}" wire:navigate
                        class="w-full sm:w-auto inline-flex items-center justify-center px-8 py-4 border border-transparent text-lg font-bold rounded-full text-white bg-slate-900 hover:bg-slate-800 shadow-xl hover:shadow-2xl transition-all scale-100 hover:scale-105">
                        Mulai Skrining Mandiri
                    </a>
                    <a href="{{ route('patient.schedule') }}" wire:navigate
                        class="w-full sm:w-auto inline-flex items-center justify-center px-8 py-4 border-2 border-slate-900 text-lg font-bold rounded-full text-slate-900 bg-white hover:bg-slate-50 shadow-md hover:shadow-lg transition-all scale-100 hover:scale-105">
                        <svg class="w-5 h-5 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                            stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        Buat Jadwal Dokter
                    </a>
                </div>
            @endauth
        </div>
    </div>
</div>
