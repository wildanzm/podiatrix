<?php

use Livewire\Volt\Component;
use App\Models\Schedule;
use App\Models\Appointment;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

new #[Layout('components.layouts.app', ['title' => 'Daftar Pasien'])] #[Title('Daftar Pasien')] class extends Component {
    public Schedule $schedule;
    public $appointments = [];

    public function mount(Schedule $schedule)
    {
        $this->schedule = $schedule;
        $this->loadAppointments();
    }

    public function loadAppointments()
    {
        $this->appointments = Appointment::with('patient', 'screening')->where('schedule_id', $this->schedule->id)->orderBy('queue_number', 'asc')->get();
    }
}; ?>

<div class="space-y-6">
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <div class="flex items-center gap-2 mb-2">
                <a href="{{ route('admin.schedule') }}" wire:navigate
                    class="text-slate-400 hover:text-primary transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                </a>
                <h1 class="text-2xl font-bold text-slate-800">Daftar Antrean Pasien</h1>
            </div>
            <p class="text-slate-500 mt-1">Jadwal Praktik:
                {{ \Carbon\Carbon::parse($schedule->date)->locale('id')->translatedFormat('l, d F Y') }} |
                {{ \Carbon\Carbon::parse($schedule->start_time)->format('H:i') }} -
                {{ \Carbon\Carbon::parse($schedule->end_time)->format('H:i') }} WIB</p>
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
        <!-- Desktop Table View -->
        <div class="hidden lg:block overflow-x-auto">
            <table class="w-full text-sm text-left text-slate-600">
                <thead class="text-xs text-slate-500 uppercase bg-slate-50 border-b border-slate-200">
                    <tr>
                        <th scope="col" class="px-6 py-4 font-medium text-center">No. Antrean</th>
                        <th scope="col" class="px-6 py-4 font-medium">Nama Pasien</th>
                        <th scope="col" class="px-6 py-4 font-medium">Usia</th>
                        <th scope="col" class="px-6 py-4 font-medium">Waktu Daftar</th>
                        <th scope="col" class="px-6 py-4 font-medium">Status</th>
                        <th scope="col" class="px-6 py-4 font-medium text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($appointments as $appointment)
                        <tr class="border-b border-slate-100">
                            <td class="px-6 py-4 text-center">
                                <div class="inline-flex w-10 h-10 rounded-full bg-teal-50 text-primary items-center justify-center font-bold text-lg border border-teal-100 shadow-sm">
                                    {{ $appointment->queue_number }}
                                </div>
                            </td>
                            <td class="px-6 py-4 font-semibold text-slate-800">
                                {{ $appointment->patient->name }}
                            </td>
                            <td class="px-6 py-4">
                                @if ($appointment->patient->date_of_birth)
                                    <span class="inline-flex items-center px-2 py-0.5 rounded bg-slate-100 font-medium text-xs text-slate-600">
                                        {{ \Carbon\Carbon::parse($appointment->patient->date_of_birth)->age }} Tahun
                                    </span>
                                @else
                                    -
                                @endif
                            </td>
                            <td class="px-6 py-4 text-xs text-slate-500">
                                <div class="flex items-center text-slate-500 whitespace-nowrap">
                                    <svg class="w-3.5 h-3.5 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                    {{ $appointment->created_at->locale('id')->translatedFormat('d M Y, H:i') }}
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                @if ($appointment->status === 'waiting')
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-semibold bg-amber-100 text-amber-800 border border-amber-200">Menunggu</span>
                                @elseif ($appointment->status === 'completed')
                                    <span class="inline-flex px-2.5 py-1 rounded-md text-xs font-semibold bg-green-100 text-green-800 border border-green-200">Selesai</span>
                                @else
                                    <span class="inline-flex px-2.5 py-1 rounded-md text-xs font-semibold bg-red-100 text-red-800 border border-red-200">{{ ucfirst($appointment->status) }}</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-center">
                                @if ($appointment->status === 'waiting')
                                    <a href="{{ route('admin.screenings', ['appointment_id' => $appointment->id]) }}"
                                        wire:navigate
                                        class="inline-flex items-center px-4 py-2 bg-primary text-white text-xs font-medium rounded-lg hover:bg-teal-700 transition-colors shadow-sm focus:ring-4 focus:ring-teal-100">
                                        <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4">
                                            </path>
                                        </svg>
                                        Mulai Skrining
                                    </a>
                                @else
                                    @if ($appointment->screening)
                                        <a href="{{ route('admin.screening.result', $appointment->screening->id) }}"
                                            wire:navigate
                                            class="inline-flex items-center px-4 py-2 bg-blue-50 text-blue-700 border border-blue-200 text-xs font-semibold rounded-lg hover:bg-blue-100 hover:text-blue-800 transition-colors shadow-sm focus:ring-4 focus:ring-blue-50">
                                            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                                </path>
                                            </svg>
                                            Lihat Hasil
                                        </a>
                                    @else
                                        <span class="text-xs text-slate-400 bg-slate-50 px-3 py-1.5 rounded-lg border border-slate-100">Tidak ada aksi</span>
                                    @endif
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center text-slate-500">
                                <div class="flex flex-col items-center justify-center">
                                    <svg class="w-12 h-12 text-slate-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path></svg>
                                    <p>Belum ada pasien yang mendaftar pada jadwal ini.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Mobile & Tablet Card View -->
        <div class="lg:hidden flex flex-col md:grid md:grid-cols-2 md:gap-4 md:p-4 md:bg-slate-50/50 divide-y md:divide-y-0 divide-slate-100">
            @forelse ($appointments as $appointment)
                <div class="p-4 sm:p-5 bg-white hover:bg-slate-50 transition-colors md:rounded-2xl md:border md:border-slate-200 md:shadow-sm flex flex-col h-full">
                    <div class="flex justify-between items-start gap-3 mb-4">
                        <div class="flex items-start gap-3 min-w-0 flex-1">
                            <div class="flex-shrink-0 w-12 h-12 rounded-full bg-teal-50 text-primary flex items-center justify-center font-bold text-xl border border-teal-100 shadow-sm">
                                {{ $appointment->queue_number }}
                            </div>
                            <div class="mt-0.5 min-w-0 flex-1">
                                <h3 class="font-bold text-slate-800 text-base leading-tight break-words">{{ $appointment->patient->name }}</h3>
                                <div class="flex flex-wrap items-center text-xs text-slate-500 mt-1.5 gap-x-2 gap-y-1">
                                    <span class="inline-flex items-center px-1.5 py-0.5 rounded bg-slate-100 font-medium whitespace-nowrap">Usia: {{ $appointment->patient->date_of_birth ? \Carbon\Carbon::parse($appointment->patient->date_of_birth)->age . ' Thn' : '-' }}</span>
                                    <span class="inline-flex items-center text-slate-400 whitespace-nowrap">
                                        <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                        {{ $appointment->created_at->format('H:i') }}
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="flex-shrink-0 mt-1">
                            @if ($appointment->status === 'waiting')
                                <span class="inline-flex px-2 py-1 rounded-md border border-amber-200 bg-amber-50 text-[10px] font-bold text-amber-700 uppercase tracking-wider shadow-sm">Menunggu</span>
                            @elseif ($appointment->status === 'completed')
                                <span class="inline-flex px-2 py-1 rounded-md border border-green-200 bg-green-50 text-[10px] font-bold text-green-700 uppercase tracking-wider shadow-sm">Selesai</span>
                            @else
                                <span class="inline-flex px-2 py-1 rounded-md border border-red-200 bg-red-50 text-[10px] font-bold text-red-700 uppercase tracking-wider shadow-sm">{{ ucfirst($appointment->status) }}</span>
                            @endif
                        </div>
                    </div>
                    <div class="flex w-full mt-auto">
                        @if ($appointment->status === 'waiting')
                            <a href="{{ route('admin.screenings', ['appointment_id' => $appointment->id]) }}" wire:navigate class="w-full inline-flex items-center justify-center px-4 py-3 bg-primary text-white text-sm font-semibold rounded-xl hover:bg-teal-700 transition-colors shadow-sm focus:ring-4 focus:ring-teal-100">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path></svg>
                                Mulai Skrining Pasien
                            </a>
                        @else
                            @if ($appointment->screening)
                                <a href="{{ route('admin.screening.result', $appointment->screening->id) }}" wire:navigate class="w-full inline-flex items-center justify-center px-4 py-3 bg-blue-50 text-blue-700 border border-blue-200 text-sm font-bold rounded-xl hover:bg-blue-100 hover:text-blue-800 transition-colors shadow-sm focus:ring-4 focus:ring-blue-50">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                    Lihat Hasil Skrining
                                </a>
                            @else
                                <div class="w-full text-center py-2.5 text-sm font-medium text-slate-400 bg-slate-50 rounded-xl border border-slate-100">Tidak ada aksi tersedia</div>
                            @endif
                        @endif
                    </div>
                </div>
            @empty
                <div class="p-8 text-center text-slate-500 flex flex-col items-center justify-center md:col-span-2">
                    <svg class="w-12 h-12 text-slate-300 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path></svg>
                    <p>Belum ada pasien yang mendaftar pada jadwal ini.</p>
                </div>
            @endforelse
        </div>
    </div>
    </div>
</div>
