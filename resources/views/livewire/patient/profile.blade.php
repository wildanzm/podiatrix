<?php

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Volt\Component;

new #[Layout('layouts.patient')] #[Title('Profil Saya')] class extends Component {
    public ?string $name = '';
    public ?string $email = '';
    public ?string $date_of_birth = '';
    public ?string $gender = '';
    public ?string $last_education = '';
    public ?string $occupation = '';

    public ?string $current_password = '';
    public ?string $password = '';
    public ?string $password_confirmation = '';

    public function mount()
    {
        $user = Auth::user();
        if (!$user) {
            return redirect()->route('login');
        }
        $this->name = $user->name ?? '';
        $this->email = $user->email ?? '';
        $this->date_of_birth = $user->date_of_birth ? \Carbon\Carbon::parse($user->date_of_birth)->format('d-m-Y') : '';
        $this->gender = $user->gender ?? '';
        $this->last_education = $user->last_education ?? '';
        $this->occupation = $user->occupation ?? '';
    }

    public function updateProfile()
    {
        $user = Auth::user();

        try {
            $validator = \Illuminate\Support\Facades\Validator::make(
                [
                    'name' => $this->name,
                    'email' => $this->email,
                    'date_of_birth' => $this->date_of_birth,
                    'gender' => $this->gender,
                    'last_education' => $this->last_education,
                    'occupation' => $this->occupation,
                ],
                [
                    'name' => ['required', 'string', 'max:255'],
                    'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:users,email,' . $user->id],
                    'date_of_birth' => ['required', 'date_format:d-m-Y'],
                    'gender' => ['required', 'in:Laki-laki,Perempuan'],
                    'last_education' => ['required', 'in:Tidak Sekolah,SD,SMP,SMA,Diploma,S1,S2,S3'],
                    'occupation' => ['required', 'string', 'max:255'],
                ],
                [
                    'name.required' => 'Nama lengkap wajib diisi.',
                    'email.required' => 'Email wajib diisi.',
                    'email.email' => 'Format email tidak valid.',
                    'email.unique' => 'Email ini sudah terdaftar. Silakan gunakan email lain.',
                    'date_of_birth.required' => 'Tanggal lahir wajib diisi.',
                    'date_of_birth.date_format' => 'Format tanggal lahir tidak valid (dd-mm-yyyy).',
                    'gender.required' => 'Jenis kelamin wajib dipilih.',
                    'last_education.required' => 'Pendidikan terakhir wajib dipilih.',
                    'occupation.required' => 'Pekerjaan wajib diisi.',
                ],
            );

            if ($validator->fails()) {
                foreach ($validator->errors()->messages() as $field => $messages) {
                    $this->addError($field, $messages[0]);
                }
                $this->dispatch('profile-error', message: 'Mohon periksa kembali isian Anda.');
                return;
            }

            $validated = $validator->validated();
            $validated['date_of_birth'] = \Carbon\Carbon::createFromFormat('d-m-Y', $validated['date_of_birth'])->format('Y-m-d');

            $user->update($validated);

            $this->dispatch('profile-updated');
        } catch (\Exception $e) {
            $this->dispatch('profile-error', message: 'Gagal memperbarui profil: ' . $e->getMessage());
        }
    }

    public function updatePassword()
    {
        try {
            $validator = \Illuminate\Support\Facades\Validator::make(
                [
                    'current_password' => $this->current_password,
                    'password' => $this->password,
                    'password_confirmation' => $this->password_confirmation,
                ],
                [
                    'current_password' => ['required', 'current_password'],
                    'password' => ['required', 'string', 'min:8', 'confirmed', Rules\Password::defaults()],
                ],
                [
                    'current_password.required' => 'Kata sandi saat ini wajib diisi.',
                    'current_password.current_password' => 'Kata sandi saat ini tidak sesuai.',
                    'password.required' => 'Kata sandi baru wajib diisi.',
                    'password.confirmed' => 'Konfirmasi kata sandi tidak cocok.',
                    'password.min' => 'Kata sandi minimal berisi 8 karakter.',
                ],
            );

            if ($validator->fails()) {
                foreach ($validator->errors()->messages() as $field => $messages) {
                    $this->addError($field, $messages[0]);
                }
                $this->dispatch('password-error', message: 'Mohon periksa kembali isian Anda.');
                return;
            }

            Auth::user()->update([
                'password' => Hash::make($this->password),
            ]);

            $this->reset('current_password', 'password', 'password_confirmation');

            $this->dispatch('password-updated');
        } catch (\Exception $e) {
            $this->dispatch('password-error', message: 'Terjadi kesalahan sistem: ' . $e->getMessage());
        }
    }

    public function logout()
    {
        Auth::guard('web')->logout();
        session()->invalidate();
        session()->regenerateToken();
        return redirect('/');
    }

    public function deleteProfile()
    {
        $user = Auth::user();
        Auth::guard('web')->logout();
        if ($user) {
            $user->delete();
        }
        session()->invalidate();
        session()->regenerateToken();

        return redirect('/');
    }
}; ?>

<div class="bg-slate-50 min-h-screen py-10 px-4 sm:px-6 lg:px-8">
    <div class="max-w-4xl mx-auto space-y-8">

        <!-- Header Page -->
        <div>
            <h1 class="text-3xl font-bold text-slate-900">Profil Saya</h1>
            <p class="text-slate-500 mt-2">Kelola informasi data diri, kata sandi, dan keamanan akun Anda.</p>
        </div>

        <!-- Update Profile Information -->
        <div class="bg-white p-6 sm:p-8 rounded-2xl shadow-sm border border-slate-100">
            <h2 class="text-xl font-bold text-slate-900 mb-6 flex items-center gap-2">
                <svg class="w-6 h-6 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                </svg>
                Informasi Data Diri
            </h2>
            <form wire:submit="updateProfile" class="space-y-6">

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Name -->
                    <div>
                        <label for="name" class="block mb-2 text-sm font-medium text-gray-900">Nama Lengkap <span
                                class="text-red-500">*</span></label>
                        <input wire:model="name" type="text" id="name"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary focus:border-primary block w-full p-2.5"
                            required>
                        <x-input-error :messages="$errors->get('name')" class="mt-2" />
                    </div>

                    <!-- Email -->
                    <div>
                        <label for="email" class="block mb-2 text-sm font-medium text-gray-900">Alamat Email <span
                                class="text-red-500">*</span></label>
                        <input wire:model="email" type="email" id="email"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary focus:border-primary block w-full p-2.5"
                            required>
                        <x-input-error :messages="$errors->get('email')" class="mt-2" />
                    </div>

                    <!-- Date of Birth -->
                    <div>
                        <label for="date_of_birth" class="block mb-2 text-sm font-medium text-gray-900">Tanggal Lahir
                            <span class="text-red-500">*</span></label>
                        <div class="relative w-full">
                            <div class="absolute inset-y-0 start-0 flex items-center ps-3.5 pointer-events-none">
                                <svg class="w-4 h-4 text-gray-500" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                    fill="currentColor" viewBox="0 0 20 20">
                                    <path
                                        d="M20 4a2 2 0 0 0-2-2h-2V1a1 1 0 0 0-2 0v1h-3V1a1 1 0 0 0-2 0v1H6V1a1 1 0 0 0-2 0v1H2a2 2 0 0 0-2 2v2h20V4ZM0 18a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V8H0v10Zm5-8h10a1 1 0 0 1 0 2H5a1 1 0 0 1 0-2Z" />
                                </svg>
                            </div>
                            <input type="text" id="date_of_birth" wire:model="date_of_birth" x-data
                                x-init="if (typeof Datepicker !== 'undefined') {
                                    const dp = new Datepicker($el, {
                                        format: 'dd-mm-yyyy',
                                        maxDate: new Date(),
                                        autohide: true
                                    });
                                    $el.addEventListener('changeDate', function() {
                                        setTimeout(() => {
                                            $wire.set('date_of_birth', $el.value);
                                        }, 50);
                                    });
                                }"
                                class="bg-gray-50 border {{ $errors->has('date_of_birth') ? 'border-red-500 focus:border-red-500 focus:ring-red-500' : 'border-gray-300 focus:border-primary focus:ring-primary' }} text-gray-900 text-sm rounded-lg block w-full ps-10 p-2.5"
                                placeholder="dd-mm-yyyy" required>
                        </div>
                        <x-input-error :messages="$errors->get('date_of_birth')" class="mt-2" />
                    </div>

                    <!-- Gender -->
                    <div>
                        <label for="gender" class="block mb-2 text-sm font-medium text-gray-900">Jenis Kelamin <span
                                class="text-red-500">*</span></label>
                        <select wire:model="gender" id="gender"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary focus:border-primary block w-full p-2.5"
                            required>
                            <option value="">Pilih Jenis Kelamin</option>
                            <option value="Laki-laki">Laki-laki</option>
                            <option value="Perempuan">Perempuan</option>
                        </select>
                        <x-input-error :messages="$errors->get('gender')" class="mt-2" />
                    </div>

                    <!-- Last Education -->
                    <div>
                        <label for="last_education" class="block mb-2 text-sm font-medium text-gray-900">Pendidikan
                            Terakhir <span class="text-red-500">*</span></label>
                        <select wire:model="last_education" id="last_education"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary focus:border-primary block w-full p-2.5"
                            required>
                            <option value="">Pilih Pendidikan</option>
                            <option value="Tidak Sekolah">Tidak Sekolah</option>
                            <option value="SD">SD</option>
                            <option value="SMP">SMP</option>
                            <option value="SMA">SMA</option>
                            <option value="Diploma">Diploma</option>
                            <option value="S1">S1</option>
                            <option value="S2">S2</option>
                            <option value="S3">S3</option>
                        </select>
                        <x-input-error :messages="$errors->get('last_education')" class="mt-2" />
                    </div>

                    <!-- Occupation -->
                    <div>
                        <label for="occupation" class="block mb-2 text-sm font-medium text-gray-900">Pekerjaan <span
                                class="text-red-500">*</span></label>
                        <input wire:model="occupation" type="text" id="occupation"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary focus:border-primary block w-full p-2.5"
                            required>
                        <x-input-error :messages="$errors->get('occupation')" class="mt-2" />
                    </div>
                </div>

                <div class="flex justify-end pt-4">
                    <button type="submit" wire:loading.attr="disabled"
                        class="text-white bg-primary hover:bg-teal-700 focus:ring-4 focus:outline-none focus:ring-teal-300 font-medium rounded-lg text-sm px-6 py-2.5 text-center transition-colors duration-200 disabled:opacity-50">
                        <span wire:loading.remove wire:target="updateProfile">Simpan Perubahan</span>
                        <span wire:loading wire:target="updateProfile">Menyimpan...</span>
                    </button>
                </div>
            </form>
        </div>

        <!-- Update Password -->
        <div class="bg-white p-6 sm:p-8 rounded-2xl shadow-sm border border-slate-100">
            <h2 class="text-xl font-bold text-slate-900 mb-6 flex items-center gap-2">
                <svg class="w-6 h-6 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                    stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                </svg>
                Ubah Kata Sandi
            </h2>
            <form wire:submit="updatePassword" class="space-y-6 max-w-xl">
                <div x-data="{ show: false }">
                    <label for="current_password" class="block mb-2 text-sm font-medium text-gray-900">Kata Sandi Saat
                        Ini</label>
                    <div class="relative">
                        <input wire:model="current_password" x-bind:type="show ? 'text' : 'password'"
                            id="current_password"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary focus:border-primary block w-full p-2.5"
                            required autocomplete="current-password">
                        <div class="absolute inset-y-0 right-0 pr-3 flex items-center text-sm leading-5">
                            <button type="button" @click="show = !show"
                                class="p-1 text-gray-500 hover:text-gray-700">
                                <svg x-show="!show" class="w-5 h-5" xmlns="http://www.w3.org/2000/svg"
                                    fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.432 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                                <svg x-show="show" class="w-5 h-5" xmlns="http://www.w3.org/2000/svg"
                                    fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                                    style="display: none;">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M3.98 8.223A10.477 10.477 0 001.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.45 10.45 0 0112 4.5c4.756 0 8.773 3.162 10.065 7.498a10.523 10.523 0 01-4.293 5.774M6.228 6.228L3 3m3.228 3.228l3.65 3.65m7.894 7.894L21 21m-3.228-3.228l-3.65-3.65m0 0a3 3 0 10-4.243-4.243m4.243 4.243l-4.243-4.243" />
                                </svg>
                            </button>
                        </div>
                    </div>
                    <x-input-error :messages="$errors->get('current_password')" class="mt-2" />
                </div>

                <div x-data="{ show: false }">
                    <label for="password" class="block mb-2 text-sm font-medium text-gray-900">Kata Sandi Baru</label>
                    <div class="relative">
                        <input wire:model="password" x-bind:type="show ? 'text' : 'password'" id="password"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary focus:border-primary block w-full p-2.5"
                            required autocomplete="new-password">
                        <div class="absolute inset-y-0 right-0 pr-3 flex items-center text-sm leading-5">
                            <button type="button" @click="show = !show"
                                class="p-1 text-gray-500 hover:text-gray-700">
                                <svg x-show="!show" class="w-5 h-5" xmlns="http://www.w3.org/2000/svg"
                                    fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.432 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                                <svg x-show="show" class="w-5 h-5" xmlns="http://www.w3.org/2000/svg"
                                    fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                                    style="display: none;">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M3.98 8.223A10.477 10.477 0 001.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.45 10.45 0 0112 4.5c4.756 0 8.773 3.162 10.065 7.498a10.523 10.523 0 01-4.293 5.774M6.228 6.228L3 3m3.228 3.228l3.65 3.65m7.894 7.894L21 21m-3.228-3.228l-3.65-3.65m0 0a3 3 0 10-4.243-4.243m4.243 4.243l-4.243-4.243" />
                                </svg>
                            </button>
                        </div>
                    </div>
                    <x-input-error :messages="$errors->get('password')" class="mt-2" />
                </div>

                <div x-data="{ show: false }">
                    <label for="password_confirmation" class="block mb-2 text-sm font-medium text-gray-900">Konfirmasi
                        Kata Sandi Baru</label>
                    <div class="relative">
                        <input wire:model="password_confirmation" x-bind:type="show ? 'text' : 'password'"
                            id="password_confirmation"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary focus:border-primary block w-full p-2.5"
                            required autocomplete="new-password">
                        <div class="absolute inset-y-0 right-0 pr-3 flex items-center text-sm leading-5">
                            <button type="button" @click="show = !show"
                                class="p-1 text-gray-500 hover:text-gray-700">
                                <svg x-show="!show" class="w-5 h-5" xmlns="http://www.w3.org/2000/svg"
                                    fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.432 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                                <svg x-show="show" class="w-5 h-5" xmlns="http://www.w3.org/2000/svg"
                                    fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                                    style="display: none;">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M3.98 8.223A10.477 10.477 0 001.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.45 10.45 0 0112 4.5c4.756 0 8.773 3.162 10.065 7.498a10.523 10.523 0 01-4.293 5.774M6.228 6.228L3 3m3.228 3.228l3.65 3.65m7.894 7.894L21 21m-3.228-3.228l-3.65-3.65m0 0a3 3 0 10-4.243-4.243m4.243 4.243l-4.243-4.243" />
                                </svg>
                            </button>
                        </div>
                    </div>
                    <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                </div>

                <div class="flex justify-start pt-4">
                    <button type="submit" wire:loading.attr="disabled"
                        class="text-white bg-slate-900 hover:bg-slate-800 focus:ring-4 focus:outline-none focus:ring-slate-300 font-medium rounded-lg text-sm px-6 py-2.5 text-center transition-colors duration-200 disabled:opacity-50">
                        <span wire:loading.remove wire:target="updatePassword">Ubah Kata Sandi</span>
                        <span wire:loading wire:target="updatePassword">Menyimpan...</span>
                    </button>
                </div>
            </form>
        </div>

        <!-- Delete Account -->
        <div class="bg-white p-6 sm:p-8 rounded-2xl shadow-sm border border-red-100 relative overflow-hidden">
            <div class="absolute inset-0 bg-red-50/50"></div>
            <div class="relative z-10 flex flex-col md:flex-row justify-between items-start md:items-center gap-6">
                <div>
                    <h2 class="text-xl font-bold text-red-600 mb-2 flex items-center gap-2">
                        <svg class="w-6 h-6 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                            stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                        Hapus Akun Permanen
                    </h2>
                    <p class="text-sm text-red-800/80 max-w-xl">
                        Sekali Anda menghapus akun, semua data pribadi, riwayat skrining, dan jadwal Anda akan dihapus
                        secara permanen. Tindakan ini tidak dapat dibatalkan.
                    </p>
                </div>
                <!-- Button Triggers SweetAlert -->
                <button type="button" @click="confirmDelete()"
                    class="w-full md:w-auto text-red-700 hover:text-white border border-red-600 hover:bg-red-700 focus:ring-4 focus:outline-none focus:ring-red-300 font-medium rounded-lg text-sm px-6 py-2.5 text-center transition-colors whitespace-nowrap">
                    Hapus Akun Saya
                </button>
            </div>
        </div>

        <!-- Logout Account -->
        <div
            class="bg-white p-6 sm:p-8 rounded-2xl shadow-sm border border-slate-100 flex flex-col md:flex-row justify-between items-start md:items-center gap-6">
            <div>
                <h2 class="text-xl font-bold text-slate-900 mb-2 flex items-center gap-2">
                    <svg class="w-6 h-6 text-slate-700" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                        stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                    </svg>
                    Keluar dari Akun
                </h2>
                <p class="text-sm text-slate-500 max-w-xl">
                    Sesi Anda akan diakhiri dan Anda harus masuk kembali untuk mengakses fitur aplikasi ini.
                </p>
            </div>
            <!-- Button Triggers Logout -->
            <button type="button" @click="$wire.logout()"
                class="w-full md:w-auto text-slate-700 hover:text-white border border-slate-300 hover:bg-slate-700 hover:border-slate-700 focus:ring-4 focus:outline-none focus:ring-slate-200 font-medium rounded-lg text-sm px-6 py-2.5 text-center transition-colors whitespace-nowrap">
                Keluar Aplikasi
            </button>
        </div>

    </div>
</div>

@script
    <script>
        window.confirmDelete = function() {
            Swal.fire({
                title: 'Apakah Anda Yakin?',
                text: "Akun dan semua data Anda akan dihapus secara permanen. Tindakan ini tidak dapat diurungkan!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#64748b',
                confirmButtonText: 'Ya, Hapus Akun',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    $wire.deleteProfile();
                }
            });
        }

        $wire.on('profile-updated', () => {
            Swal.fire({
                title: 'Berhasil!',
                text: 'Informasi profil berhasil diperbarui.',
                icon: 'success',
                timer: 2000,
                showConfirmButton: false,
                confirmButtonColor: '#058a84'
            });
        });

        $wire.on('profile-error', (data) => {
            Swal.fire({
                title: 'Terjadi Kesalahan!',
                text: data[0].message || 'Gagal memperbarui profil.',
                icon: 'error',
                confirmButtonText: 'Tutup',
                confirmButtonColor: '#058a84'
            });
        });

        $wire.on('password-updated', () => {
            Swal.fire({
                title: 'Berhasil!',
                text: 'Kata sandi berhasil diperbarui.',
                icon: 'success',
                timer: 2000,
                showConfirmButton: false,
                confirmButtonColor: '#058a84'
            });
        });

        $wire.on('password-error', (data) => {
            Swal.fire({
                title: 'Gagal Ubah Kata Sandi!',
                text: data[0].message || 'Gagal memperbarui kata sandi.',
                icon: 'error',
                confirmButtonText: 'Tutup',
                confirmButtonColor: '#058a84'
            });
        });
    </script>
@endscript
