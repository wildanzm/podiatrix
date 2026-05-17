<?php

use Livewire\Volt\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\WithPagination;
use App\Models\Patient;
use App\Models\Screening;

new #[Layout('layouts.patient')] #[Title('Riwayat Skrining')] class extends Component {
    use WithPagination;

    public function with(): array
    {
        $user = auth()->user();
        $patient = Patient::where('name', $user->name)->where('date_of_birth', $user->date_of_birth)->first();

        $screenings = $patient ? Screening::where('patient_id', $patient->id)->orderBy('created_at', 'desc')->paginate(10) : collect([]);

        return [
            'screenings' => $screenings,
        ];
    }
}; ?>

<div class="px-4 pb-24 sm:px-6 lg:px-8 mt-6">
    <div class="sm:flex sm:items-center sm:justify-between mb-8">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Riwayat Berdasarkan Pemeriksaan</h1>
            <p class="mt-2 text-sm text-gray-700">Daftar semua hasil skrining kaki diabetik Anda sebelumnya yang dapat
                Anda tinjau kembali.</p>
        </div>
        <div class="mt-4 sm:mt-0">
            <a href="{{ route('patient.screenings') }}"
                class="inline-flex items-center justify-center rounded-lg bg-primary px-4 py-2.5 text-sm font-semibold text-white shadow-sm hover:focus:ring-teal-700 transition">
                <svg class="mr-2 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                </svg>
                Lakukan Skrining Baru
            </a>
        </div>
    </div>

    @if (!$screenings || $screenings->isEmpty())
        <div class="text-center bg-white p-12 rounded-2xl border border-gray-200 shadow-sm mt-4">
            <svg class="mx-auto h-16 w-16 text-gray-300 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
            </svg>
            <h3 class="mt-2 text-lg font-semibold text-gray-900">Belum ada riwayat layar</h3>
            <p class="mt-1 text-sm text-gray-500">Mulai skrining pertama Anda untuk memantau kesehatan kaki Anda.</p>
        </div>
    @else
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach ($screenings as $screening)
                <div
                    class="bg-white overflow-hidden rounded-2xl shadow-sm border border-gray-200 hover:shadow-md transition duration-200">
                    <div class="px-6 py-5 border-b border-gray-200 flex justify-between items-center bg-gray-50">
                        <span class="text-sm font-semibold text-gray-600 block">
                            {{ $screening->created_at->locale('id')->translatedFormat('l, d F Y') }} •
                            {{ $screening->created_at->format('H:i') }} WIB
                        </span>
                        <div class="p-2 rounded-full bg-teal-100/50">
                            <svg class="w-5 h-5 text-primary" xmlns="http://www.w3.org/2000/svg" fill="none"
                                viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                    </div>
                    <div class="p-6">
                        <div class="flex flex-col space-y-4">
                            <div>
                                <p class="text-xs text-gray-500 mb-2">Klasifikasi Risiko</p>
                                <span
                                    class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold tracking-wide"
                                    style="background-color:
                                        @if ($screening->risk_classification === 'Sangat Rendah') #058a84; color: #ffffff
                                        @elseif($screening->risk_classification === 'Rendah') #ffd867; color: #1f2937
                                        @elseif($screening->risk_classification === 'Sedang') #faba5c; color: #1f2937
                                        @elseif($screening->risk_classification === 'Tinggi') #ef810b; color: #ffffff
                                        @elseif($screening->risk_classification === 'Darurat') #d0222a; color: #ffffff
                                        @else #6b7280; color: #ffffff @endif">
                                    {{ $screening->risk_classification }}
                                </span>
                            </div>
                        </div>

                        <div class="mt-6">
                            <a href="{{ route('patient.result', $screening) }}"
                                class="w-full inline-flex justify-center items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-lg text-white bg-primary hover:bg-teal-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary transition-colors">
                                Lihat Detail Hasil
                                <svg class="ml-2 -mr-1 w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none"
                                    viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M8.25 4.5l7.5 7.5-7.5 7.5" />
                                </svg>
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        @if (method_exists($screenings, 'hasPages') && $screenings->hasPages())
            <div class="mt-8 border-t border-gray-200 pt-6">
                {{ $screenings->links() }}
            </div>
        @endif
    @endif
</div>
