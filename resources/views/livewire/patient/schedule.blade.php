<?php

use Livewire\Volt\Component;
use App\Models\Schedule;
use App\Models\Appointment;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

new #[Layout('layouts.patient')] #[Title('Jadwal Skrining')] class extends Component {
    public $activeTab = 'available'; // 'available' or 'my_appointments'

    public function with(): array
    {
        $now = \Carbon\Carbon::today();

        // Active schedules: active, date >= today, and quota > booked count.
        // We load count of appointments to check quota.
        $availableSchedules = Schedule::where('is_active', true)->where('date', '>=', $now)->withCount('appointments')->orderBy('date', 'asc')->orderBy('start_time', 'asc')->get();

        $myAppointments = Appointment::with('schedule')->where('patient_id', Auth::id())->orderBy('created_at', 'desc')->get();

        return [
            'availableSchedules' => $availableSchedules,
            'myAppointments' => $myAppointments,
        ];
    }

    public function switchTab($tab)
    {
        $this->activeTab = $tab;
    }

    public function confirmBooking($scheduleId)
    {
        $user = Auth::user();
        $key = 'confirm-booking:' . $user->id;

        if (\Illuminate\Support\Facades\RateLimiter::tooManyAttempts($key, 5)) {
            $this->dispatch('alert', [
                'type' => 'error',
                'title' => 'Terlalu Sering',
                'text' => 'Tunggu sebentar sebelum mencoba lagi.',
            ]);
            return;
        }

        \Illuminate\Support\Facades\RateLimiter::hit($key, 60);

        if (empty($user->date_of_birth) || empty($user->gender) || empty($user->occupation)) {
            $this->dispatch('alert-profile', [
                'type' => 'warning',
                'title' => 'Profil Belum Lengkap',
                'text' => 'Silakan lengkapi data profil Anda terlebih dahulu sebelum mendaftar antrean skrining.',
                'redirect' => route('patient.profile'),
            ]);
            return;
        }

        $this->dispatch('confirm-booking', [
            'scheduleId' => $scheduleId,
            'title' => 'Konfirmasi Pendaftaran',
            'text' => 'Apakah Anda yakin ingin mendaftar pada jadwal ini?',
        ]);
    }

    public function bookSchedule($scheduleId)
    {
        $user = Auth::user();
        $key = 'book-schedule:' . $user->id;

        if (\Illuminate\Support\Facades\RateLimiter::tooManyAttempts($key, 3)) {
            $this->dispatch('alert', [
                'type' => 'error',
                'title' => 'Terlalu Banyak Percobaan',
                'text' => 'Batas sistem terlampaui. Coba lagi dalam ' . \Illuminate\Support\Facades\RateLimiter::availableIn($key) . ' detik.',
            ]);
            return;
        }

        \Illuminate\Support\Facades\RateLimiter::hit($key, 60);

        try {
            $appointmentService = app(\App\Services\AppointmentService::class);
            $appointment = $appointmentService->bookAppointment($user->id, $scheduleId);

            $this->dispatch('alert', [
                'type' => 'success',
                'title' => 'Berhasil!',
                'text' => 'Anda berhasil mendaftar. Nomor antrean Anda adalah ' . $appointment->queue_number . '.',
            ]);

            $this->switchTab('my_appointments');
        } catch (\Exception $e) {
            $this->dispatch('alert', [
                'type' => 'error',
                'title' => 'Gagal',
                'text' => $e->getMessage(),
            ]);
        }
    }
}; ?>
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

    <div class="mb-8">
        <h1 class="text-3xl font-bold text-slate-800 tracking-tight">Jadwal Praktik & Antrean</h1>
        <p class="mt-2 text-slate-500 max-w-2xl">Mendaftar untuk pemeriksaan / skrining dan pantau nomor antrean
            Anda.</p>
    </div>

    {{-- Tabs --}}
    <div class="mb-6 border-b border-gray-200">
        <ul class="flex flex-wrap -mb-px text-sm font-medium text-center" role="tablist">
            <li class="me-2" role="presentation">
                <button wire:click="switchTab('available')"
                    class="inline-block p-4 border-b-2 rounded-t-lg transition-colors {{ $activeTab === 'available' ? 'border-primary text-primary' : 'border-transparent text-gray-500 hover:text-gray-600 hover:border-gray-300' }}"
                    type="button" role="tab" aria-selected="{{ $activeTab === 'available' ? 'true' : 'false' }}">
                    Jadwal Tersedia
                </button>
            </li>
            <li class="me-2" role="presentation">
                <button wire:click="switchTab('my_appointments')"
                    class="inline-block p-4 border-b-2 rounded-t-lg transition-colors {{ $activeTab === 'my_appointments' ? 'border-primary text-primary' : 'border-transparent text-gray-500 hover:text-gray-600 hover:border-gray-300' }}"
                    type="button" role="tab"
                    aria-selected="{{ $activeTab === 'my_appointments' ? 'true' : 'false' }}">
                    Jadwal & Antrean Saya
                </button>
            </li>
        </ul>
    </div>

    {{-- Tab Content: Available Schedules --}}
    <div x-show="$wire.activeTab === 'available'" x-transition>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse ($availableSchedules as $schedule)
                <div
                    class="bg-white rounded-2xl p-6 border border-slate-200 shadow-sm hover:shadow-md transition-shadow relative overflow-hidden">
                    <div class="absolute top-0 right-0 p-4">
                        @if ($schedule->appointments_count >= $schedule->quota)
                            <span
                                class="bg-red-100 text-red-800 text-xs font-semibold px-2.5 py-0.5 rounded-full">Penuh</span>
                        @else
                            <span
                                class="bg-teal-100 text-teal-800 text-xs font-semibold px-2.5 py-0.5 rounded-full">Tersedia</span>
                        @endif
                    </div>
                    <h3 class="text-xl font-bold text-slate-800">
                        {{ \Carbon\Carbon::parse($schedule->date)->locale('id')->translatedFormat('l, d F Y') }}
                    </h3>

                    <div class="mt-4 space-y-3">
                        <div class="flex items-center text-slate-600">
                            <svg class="w-5 h-5 mr-3 text-slate-400" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <span>{{ \Carbon\Carbon::parse($schedule->start_time)->format('H:i') }} -
                                {{ \Carbon\Carbon::parse($schedule->end_time)->format('H:i') }} WIB</span>
                        </div>
                        <div class="flex items-center text-slate-600">
                            <svg class="w-5 h-5 mr-3 text-slate-400" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z">
                                </path>
                            </svg>
                            <span>Kuota: {{ $schedule->appointments_count }} / {{ $schedule->quota }} Terisi</span>
                        </div>
                    </div>

                    <div class="mt-6">
                        @php
                            $isBooked = $myAppointments->where('schedule_id', $schedule->id)->isNotEmpty();
                        @endphp

                        @if ($isBooked)
                            <button disabled
                                class="w-full justify-center inline-flex items-center px-4 py-2 bg-gray-100 text-gray-500 text-sm font-medium rounded-lg cursor-not-allowed">
                                Sudah Terdaftar
                            </button>
                        @elseif ($schedule->appointments_count >= $schedule->quota)
                            <button disabled
                                class="w-full justify-center inline-flex items-center px-4 py-2 bg-gray-100 text-gray-500 text-sm font-medium rounded-lg cursor-not-allowed">
                                Kuota Penuh
                            </button>
                        @else
                            <button wire:click="confirmBooking({{ $schedule->id }})" wire:loading.attr="disabled"
                                class="w-full justify-center inline-flex items-center px-4 py-2 bg-primary text-white text-sm font-medium rounded-lg hover:bg-teal-700 transition-colors focus:ring-4 focus:ring-teal-300">
                                <span wire:loading.remove wire:target="confirmBooking({{ $schedule->id }})">Daftar
                                    Skrining</span>
                                <span wire:loading
                                    wire:target="confirmBooking({{ $schedule->id }})">Memproses...</span>
                            </button>
                        @endif
                    </div>
                </div>
            @empty
                <div class="col-span-full bg-white rounded-2xl p-8 border border-slate-200 text-center">
                    <div class="flex justify-center mb-4">
                        <div class="p-3 bg-gray-50 rounded-full">
                            <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                                </path>
                            </svg>
                        </div>
                    </div>
                    <h3 class="text-lg font-bold text-slate-800">Tidak Ada Jadwal Tersedia</h3>
                    <p class="text-slate-500 mt-2">Saat ini belum ada jadwal praktik yang dibuka oleh petugas.</p>
                </div>
            @endforelse
        </div>
    </div>

    {{-- Tab Content: My Appointments --}}
    <div x-show="$wire.activeTab === 'my_appointments'" x-transition style="display: none;">
        <div class="space-y-4">
            @forelse ($myAppointments as $appointment)
                <div
                    class="bg-white rounded-2xl p-6 border border-slate-200 shadow-sm flex flex-col md:flex-row items-center md:items-stretch gap-6">
                    <div
                        class="flex-shrink-0 flex flex-col items-center justify-center p-4 bg-teal-50 rounded-xl border border-teal-100 min-w-[120px]">
                        <span class="text-sm font-medium text-teal-800">No. Antrean</span>
                        <span class="text-4xl font-black text-primary my-1">{{ $appointment->queue_number }}</span>
                    </div>
                    <div class="flex-1 text-center md:text-left">
                        <div class="flex flex-col md:flex-row md:justify-between md:items-start mb-2">
                            <h3 class="text-xl font-bold text-slate-800">
                                {{ \Carbon\Carbon::parse($appointment->schedule->date)->locale('id')->translatedFormat('l, d F Y') }}
                            </h3>
                            <div class="mt-2 md:mt-0">
                                @if ($appointment->status === 'waiting')
                                    @php
                                        $scheduleDateTime = \Carbon\Carbon::parse(
                                            $appointment->schedule->date . ' ' . $appointment->schedule->start_time,
                                        );
                                        $now = \Carbon\Carbon::now();
                                        $isTimeArrived = $now->greaterThanOrEqualTo($scheduleDateTime);
                                    @endphp
                                    @if ($isTimeArrived)
                                        <span
                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-amber-100 text-amber-800">
                                            Menunggu Antrian
                                        </span>
                                    @else
                                        <span
                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                            Menunggu Waktu
                                        </span>
                                    @endif
                                @elseif ($appointment->status === 'completed')
                                    <span
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">Skrining
                                        Selesai</span>
                                @else
                                    <span
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">{{ ucfirst($appointment->status) }}</span>
                                @endif
                            </div>
                        </div>

                        <div class="space-y-2 mt-4 text-sm text-slate-600">
                            <div class="flex items-center justify-center md:justify-start">
                                <svg class="w-4 h-4 mr-2 text-slate-400" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <span>Jam:
                                    {{ \Carbon\Carbon::parse($appointment->schedule->start_time)->format('H:i') }}
                                    -
                                    {{ \Carbon\Carbon::parse($appointment->schedule->end_time)->format('H:i') }}
                                    WIB</span>
                            </div>
                            <div class="flex items-center justify-center md:justify-start">
                                <svg class="w-4 h-4 mr-2 text-slate-400" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                                    </path>
                                </svg>
                                <span>Didaftarkan pada:
                                    {{ $appointment->created_at->locale('id')->translatedFormat('d F Y H:i') }}
                                    WIB</span>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="bg-white rounded-2xl p-8 border border-slate-200 text-center">
                    <div class="flex justify-center mb-4">
                        <div class="p-4 bg-teal-50 rounded-full">
                            <svg class="w-10 h-10 text-primary" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4">
                                </path>
                            </svg>
                        </div>
                    </div>
                    <h3 class="text-xl font-bold text-slate-800">Belum Ada Antrean</h3>
                    <p class="text-slate-500 mt-2 max-w-sm mx-auto">Anda belum pernah mendaftar untuk jadwal
                        skrining. Silakan daftar melalui tab "Jadwal Tersedia".</p>
                </div>
            @endforelse
        </div>
    </div>

    @script
        <script>
            $wire.on('alert', (data) => {
                const alertData = Array.isArray(data) ? data[0] : data;
                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        icon: alertData.type,
                        title: alertData.title,
                        text: alertData.text,
                        confirmButtonColor: '#058a84',
                    });
                } else {
                    alert(alertData.text);
                }
            });

            $wire.on('alert-profile', (data) => {
                const alertData = Array.isArray(data) ? data[0] : data;
                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        icon: alertData.type,
                        title: alertData.title,
                        text: alertData.text,
                        showCancelButton: true,
                        confirmButtonText: 'Lengkapi Profil',
                        cancelButtonText: 'Nanti',
                        confirmButtonColor: '#058a84',
                        cancelButtonColor: '#6b7280',
                    }).then((result) => {
                        if (result.isConfirmed && alertData.redirect) {
                            window.location.href = alertData.redirect;
                        }
                    });
                } else {
                    if (confirm(alertData.text + " Pergi ke halaman profil?")) {
                        window.location.href = alertData.redirect;
                    }
                }
            });

            $wire.on('confirm-booking', (data) => {
                const confirmData = Array.isArray(data) ? data[0] : data;
                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        icon: 'question',
                        title: confirmData.title,
                        text: confirmData.text,
                        showCancelButton: true,
                        confirmButtonText: 'Ya, Daftar',
                        cancelButtonText: 'Batal',
                        confirmButtonColor: '#058a84',
                        cancelButtonColor: '#d33',
                    }).then((result) => {
                        if (result.isConfirmed) {
                            $wire.bookSchedule(confirmData.scheduleId);
                        }
                    });
                } else {
                    if (confirm(confirmData.text)) {
                        $wire.bookSchedule(confirmData.scheduleId);
                    }
                }
            });
        </script>
    @endscript
</div>
