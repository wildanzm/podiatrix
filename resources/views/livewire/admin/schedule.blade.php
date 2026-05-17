<?php

use Livewire\Volt\Component;
use App\Models\Schedule;
use App\Models\Appointment;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\On;

new #[Layout('components.layouts.app', ['title' => 'Jadwal Pemeriksaan'])] #[Title('Jadwal Pemeriksaan')] class extends Component {
    public $schedules = [];

    // Modal controls
    public $showScheduleModal = false;

    // Form fields
    public $schedule_id;
    public $date;
    public $start_time;
    public $end_time;
    public $quota;
    public $is_active = true;

    public function mount()
    {
        $this->loadSchedules();
    }

    public function loadSchedules()
    {
        $this->schedules = Schedule::withCount('appointments')->where('admin_id', Auth::id())->orderBy('date', 'desc')->orderBy('start_time', 'desc')->get();
    }

    public function createSchedule()
    {
        $this->resetValidation();
        $this->schedule_id = null;
        $this->date = date('d/m/Y');
        $this->start_time = '08:00';
        $this->end_time = '16:00';
        $this->quota = 10;
        $this->is_active = true;
        $this->showScheduleModal = true;
    }

    public function editSchedule($id)
    {
        $this->resetValidation();
        $schedule = Schedule::where('admin_id', Auth::id())->findOrFail($id);
        $this->schedule_id = $schedule->id;
        $this->date = \Carbon\Carbon::parse($schedule->date)->format('d/m/Y');
        $this->start_time = date('H:i', strtotime($schedule->start_time));
        $this->end_time = date('H:i', strtotime($schedule->end_time));
        $this->quota = $schedule->quota;
        $this->is_active = (bool) $schedule->is_active;
        $this->showScheduleModal = true;
    }

    public function saveSchedule()
    {
        $key = 'save-schedule:' . Auth::id();

        if (\Illuminate\Support\Facades\RateLimiter::tooManyAttempts($key, 10)) {
            $this->dispatch('alert', [
                'type' => 'error',
                'title' => 'Terlalu Sering',
                'text' => 'Batas percobaan menyimpan dicapai. Coba lagi nanti.',
            ]);
            return;
        }

        \Illuminate\Support\Facades\RateLimiter::hit($key, 60);

        $this->validate(
            [
                'date' => 'required|date_format:d/m/Y',
                'start_time' => 'required',
                'end_time' => 'required|after:start_time',
                'quota' => 'required|integer|min:1|max:1000000000',
            ],
            [
                'date.required' => 'Tanggal wajib diisi.',
                'date.date_format' => 'Format tanggal harus dd/mm/yyyy.',
                'start_time.required' => 'Waktu mulai wajib diisi.',
                'end_time.required' => 'Waktu selesai wajib diisi.',
                'end_time.after' => 'Waktu selesai harus setelah waktu mulai.',
                'quota.required' => 'Kuota wajib diisi.',
                'quota.min' => 'Kuota minimal 1.',
                'quota.max' => 'Kuota maksimal 1000000000.',
            ],
        );

        $isEdit = !empty($this->schedule_id);

        Schedule::updateOrCreate(
            ['id' => $this->schedule_id],
            [
                'admin_id' => Auth::id(),
                'date' => \Carbon\Carbon::createFromFormat('d/m/Y', $this->date)->format('Y-m-d'),
                'start_time' => $this->start_time,
                'end_time' => $this->end_time,
                'quota' => $this->quota,
                'is_active' => $this->is_active,
            ],
        );
        $this->showScheduleModal = false;
        $this->loadSchedules();

        $this->dispatch('alert', [
            'type' => 'success',
            'title' => 'Tersimpan!',
            'text' => $isEdit ? 'Jadwal berhasil diperbarui.' : 'Jadwal baru berhasil dibuat.',
        ]);
    }

    #[On('deleteConfirmed')]
    public function deleteSchedule($id)
    {
        Schedule::where('admin_id', Auth::id())->find($id)?->delete();
        $this->loadSchedules();

        $this->dispatch('alert', [
            'type' => 'success',
            'title' => 'Terhapus!',
            'text' => 'Jadwal berhasil dihapus.',
        ]);
    }
}; ?>

<div class="space-y-6">
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h1 class="text-2xl font-bold text-slate-800">Jadwal Pemeriksaan</h1>
            <p class="text-slate-500 mt-1">Kelola jadwal praktik dan lihat daftar antrean pasien.</p>
        </div>
        <button wire:click="createSchedule" type="button"
            class="inline-flex items-center px-4 py-2 bg-primary text-white text-sm font-medium rounded-lg hover:bg-teal-700 focus:ring-4 focus:ring-teal-100 transition-colors shadow-sm">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
            </svg>
            Buat Jadwal Baru
        </button>
    </div>

    {{-- Cards Layout --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse ($schedules as $schedule)
            <div
                class="bg-white rounded-2xl p-6 border border-slate-200 shadow-sm hover:shadow-md transition-shadow relative overflow-hidden flex flex-col h-full">
                <div class="absolute top-0 right-0 p-4">
                    @if ($schedule->is_active)
                        <span
                            class="bg-green-100 text-green-800 text-xs font-semibold px-2.5 py-0.5 rounded-full ring-1 ring-green-600/20">Aktif</span>
                    @else
                        <span
                            class="bg-red-100 text-red-800 text-xs font-semibold px-2.5 py-0.5 rounded-full ring-1 ring-red-600/20">Tutup</span>
                    @endif
                </div>

                <div class="flex-grow">
                    <h3 class="text-xl font-bold text-slate-800 pr-16 leading-tight">
                        {{ \Carbon\Carbon::parse($schedule->date)->locale('id')->translatedFormat('l, d F Y') }}
                    </h3>

                    <div class="mt-4 space-y-3">
                        <div class="flex items-center text-slate-600">
                            <svg class="w-5 h-5 mr-3 text-slate-400" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <span
                                class="font-medium text-sm">{{ \Carbon\Carbon::parse($schedule->start_time)->format('H:i') }}
                                - {{ \Carbon\Carbon::parse($schedule->end_time)->format('H:i') }} WIB</span>
                        </div>
                        <div class="flex items-center text-slate-600">
                            <svg class="w-5 h-5 mr-3 text-slate-400" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z">
                                </path>
                            </svg>
                            <span class="text-sm">Telah daftar: <strong
                                    class="text-slate-800">{{ $schedule->appointments_count }}</strong> dari <strong
                                    class="text-slate-800">{{ $schedule->quota }}</strong> Pasien</span>
                        </div>
                    </div>
                </div>

                {{-- Action Buttons --}}
                <div class="mt-6 pt-5 border-t border-slate-100 grid grid-cols-2 gap-2 shrink-0">
                    <a href="{{ route('admin.schedule.patients', $schedule->id) }}" wire:navigate
                        class="col-span-2 inline-flex justify-center items-center px-4 py-2 bg-blue-50 text-blue-600 text-sm font-medium rounded-lg hover:bg-blue-100 hover:text-blue-700 transition-colors focus:ring-4 focus:ring-blue-100 shadow-sm border border-blue-200/50">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                            </path>
                        </svg>
                        Lihat Pasien
                    </a>
                    <button wire:click="editSchedule({{ $schedule->id }})"
                        class="inline-flex justify-center items-center px-4 py-2 bg-amber-50 text-amber-600 text-sm font-medium rounded-lg hover:bg-amber-100 hover:text-amber-700 transition-colors border border-amber-200/50 shadow-sm">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                            </path>
                        </svg>
                        Edit
                    </button>
                    <button onclick="confirmDeleteSchedule({{ $schedule->id }})"
                        class="inline-flex justify-center items-center px-4 py-2 bg-red-50 text-red-600 text-sm font-medium rounded-lg hover:bg-red-100 hover:text-red-700 transition-colors border border-red-200/50 shadow-sm">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                            </path>
                        </svg>
                        Hapus
                    </button>
                </div>
            </div>
        @empty
            <div class="col-span-full bg-white rounded-2xl p-8 border border-slate-200 text-center">
                <div class="flex justify-center mb-4">
                    <div class="p-4 bg-teal-50 rounded-full">
                        <svg class="w-10 h-10 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                            </path>
                        </svg>
                    </div>
                </div>
                <h3 class="text-lg font-bold text-slate-800">Tidak Ada Jadwal Tersedia</h3>
                <p class="text-slate-500 mt-2 max-w-sm mx-auto">Anda belum membuat jadwal apapun. Klik "Buat Jadwal
                    Baru" untuk memulai.</p>
            </div>
        @endforelse
    </div>

    {{-- Schedule Form Modal --}}
    @if ($showScheduleModal)
        <div tabindex="-1"
            class="fixed inset-0 z-50 flex items-center justify-center overflow-y-auto overflow-x-hidden bg-slate-900/50 backdrop-blur-sm">
            <div class="relative w-full max-w-md p-4">
                <div class="relative bg-white rounded-2xl shadow-xl">
                    <div class="flex items-center justify-between p-5 border-b border-slate-100">
                        <h3 class="text-xl font-semibold text-slate-800">
                            {{ $schedule_id ? 'Edit Jadwal' : 'Buat Jadwal Baru' }}
                        </h3>
                        <button type="button" wire:click="$set('showScheduleModal', false)"
                            class="text-slate-400 bg-transparent hover:bg-slate-100 hover:text-slate-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center">
                            <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                                viewBox="0 0 14 14">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                    stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                            </svg>
                        </button>
                    </div>
                    <form wire:submit.prevent="saveSchedule" class="p-5">
                        <div class="grid gap-4 mb-4">
                            <div>
                                <label class="block mb-2 text-sm font-medium text-slate-900">Tanggal</label>
                                <div class="relative" x-data="{
                                    initDP() {
                                        setTimeout(() => {
                                            if (typeof Datepicker !== 'undefined') {
                                                const dp = new Datepicker(this.$refs.dateInput, {
                                                    format: 'dd/mm/yyyy',
                                                    autohide: true,
                                                });
                                                this.$refs.dateInput.addEventListener('changeDate', (e) => {
                                                    $wire.set('date', e.target.value);
                                                });
                                            }
                                        }, 100);
                                    }
                                }" x-init="initDP()" wire:ignore>
                                    <div class="absolute inset-y-0 start-0 flex items-center ps-3 pointer-events-none">
                                        <svg class="w-4 h-4 text-slate-500" aria-hidden="true"
                                            xmlns="http://www.w3.org/2000/svg" fill="currentColor"
                                            viewBox="0 0 20 20">
                                            <path
                                                d="M20 4a2 2 0 0 0-2-2h-2V1a1 1 0 0 0-2 0v1h-3V1a1 1 0 0 0-2 0v1H6V1a1 1 0 0 0-2 0v1H2a2 2 0 0 0-2 2v2h20V4ZM0 18a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V8H0v10Zm5-8h10a1 1 0 0 1 0 2H5a1 1 0 0 1 0-2Z" />
                                        </svg>
                                    </div>
                                    <input x-ref="dateInput" type="text" wire:model.defer="date"
                                        class="bg-slate-50 border border-slate-300 text-slate-900 text-sm rounded-lg focus:ring-primary focus:border-primary block w-full ps-10 p-2.5"
                                        placeholder="dd/mm/yyyy" autocomplete="off">
                                </div>
                                @error('date')
                                    <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block mb-2 text-sm font-medium text-slate-900">Mulai</label>
                                    <input type="time" wire:model="start_time"
                                        class="bg-slate-50 border border-slate-300 text-slate-900 text-sm rounded-lg focus:ring-primary focus:border-primary block w-full p-2.5">
                                    @error('start_time')
                                        <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div>
                                    <label class="block mb-2 text-sm font-medium text-slate-900">Selesai</label>
                                    <input type="time" wire:model="end_time"
                                        class="bg-slate-50 border border-slate-300 text-slate-900 text-sm rounded-lg focus:ring-primary focus:border-primary block w-full p-2.5">
                                    @error('end_time')
                                        <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div>
                                <label class="block mb-2 text-sm font-medium text-slate-900">Kuota Pasien</label>
                                <input type="number" wire:model="quota" min="1" max="1000000000"
                                    class="no-spinners bg-slate-50 border border-slate-300 text-slate-900 text-sm rounded-lg focus:ring-primary focus:border-primary block w-full p-2.5"
                                    placeholder="Contoh: 20 (Maks 1000000000)">
                                @error('quota')
                                    <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="flex items-center mt-2">
                                <label class="inline-flex items-center cursor-pointer">
                                    <input type="checkbox" wire:model="is_active" class="sr-only peer">
                                    <div
                                        class="relative w-11 h-6 bg-slate-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-teal-100 rounded-full peer peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-primary">
                                    </div>
                                    <span class="ms-3 text-sm font-medium text-slate-900">Jadwal Aktif</span>
                                </label>
                            </div>
                        </div>
                        <div class="flex justify-end gap-3 border-t border-slate-100 pt-5 mt-2">
                            <button type="button" wire:click="$set('showScheduleModal', false)"
                                class="text-slate-500 bg-white hover:bg-slate-100 focus:ring-4 focus:outline-none focus:ring-blue-300 rounded-lg border border-slate-200 text-sm font-medium px-5 py-2.5 hover:text-slate-900 focus:z-10">Batal</button>
                            <button type="submit"
                                class="text-white bg-primary hover:bg-teal-700 focus:ring-4 focus:outline-none focus:ring-teal-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center">Simpan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif
</div>

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/flowbite@3.1.2/dist/datepicker.min.js"></script>
    <style>
        /* Hide increment/decrement arrows on number input */
        .no-spinners::-webkit-inner-spin-button,
        .no-spinners::-webkit-outer-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }

        .no-spinners {
            -moz-appearance: textfield;
        }
    </style>
    <script>
        function confirmDeleteSchedule(id) {
            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    title: 'Hapus Jadwal?',
                    text: "Semua antrean pasien pada jadwal ini juga akan terhapus. Lanjutkan?",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#ef4444',
                    cancelButtonColor: '#94a3b8',
                    confirmButtonText: 'Ya, hapus!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        Livewire.dispatch('deleteConfirmed', {
                            id: id
                        });
                    }
                });
            } else {
                if (confirm('Yakin ingin menghapus jadwal ini?')) {
                    Livewire.dispatch('deleteConfirmed', {
                        id: id
                    });
                }
            }
        }
    </script>
@endpush

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
                    timer: alertData.type === 'success' || alertData.type === 'info' ? 3000 : undefined
                });
            } else {
                alert(alertData.text);
            }
        });
    </script>
@endscript
