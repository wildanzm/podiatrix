<?php

use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Volt\Component;

new #[Layout('components.layouts.auth')] #[Title('Daftar Akun')] class extends Component {
    public string $name = '';
    public string $email = '';
    public string $password = '';
    public string $password_confirmation = '';

    /**
     * Handle an incoming registration request.
     */
    public function register(): void
    {
        \Illuminate\Support\Facades\Log::info('Registering user with data: ', [
            'name' => $this->name,
            'email' => $this->email,
            'password_length' => strlen($this->password),
            'password_confirmation_length' => strlen($this->password_confirmation),
        ]);
        try {
            $validator = \Illuminate\Support\Facades\Validator::make(
                [
                    'name' => $this->name,
                    'email' => $this->email,
                    'password' => $this->password,
                    'password_confirmation' => $this->password_confirmation,
                ],
                [
                    'name' => ['required', 'string', 'max:255'],
                    'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . User::class],
                    'password' => ['required', 'string', 'confirmed', Rules\Password::defaults()],
                ],
                [
                    'name.required' => 'Nama lengkap wajib diisi.',
                    'email.required' => 'Email wajib diisi.',
                    'email.email' => 'Format email tidak valid.',
                    'email.unique' => 'Email ini sudah terdaftar. Silakan gunakan email lain.',
                    'password.required' => 'Kata sandi wajib diisi.',
                    'password.confirmed' => 'Konfirmasi kata sandi tidak cocok.',
                    'password.min' => 'Kata sandi minimal berisi 8 karakter.',
                ],
            );

            if ($validator->fails()) {
                foreach ($validator->errors()->messages() as $field => $messages) {
                    $this->addError($field, $messages[0]);
                }
                $this->dispatch('register-failed', errors: $validator->errors()->all());
                return;
            }

            $validated = $validator->validated();

            $validated['password'] = Hash::make($validated['password']);

            $user = User::create($validated);

            // Assign patient role
            $user->assignRole('patient');

            event(new Registered($user));

            Auth::login($user);

            $intendedUrl = session()->pull('url.intended', route('home', absolute: false));
            $this->dispatch('register-success', intended: $intendedUrl);
        } catch (\Exception $e) {
            $this->dispatch('register-error', message: 'Terjadi kesalahan sistem. Silakan coba lagi.');
        }
    }
}; ?>

<div class="flex flex-col gap-6">

    <!-- Session Status -->
    <x-auth-session-status class="mb-4 text-center" :status="session('status')" />

    <form wire:submit="register" class="flex flex-col gap-4">
        <!-- Name -->
        <div>
            <label for="name" class="block mb-2 text-sm font-medium text-gray-900">Nama Lengkap <span
                    class="text-red-500">*</span></label>
            <input wire:model="name" type="text" id="name"
                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary focus:border-primary block w-full p-2.5"
                placeholder="Nama lengkap Anda" required autofocus autocomplete="name">
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <!-- Email Address -->
        <div>
            <label for="email" class="block mb-2 text-sm font-medium text-gray-900">Alamat Email <span
                    class="text-red-500">*</span></label>
            <input wire:model="email" type="email" id="email"
                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary focus:border-primary block w-full p-2.5"
                placeholder="email@example.com" required autocomplete="email">
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div x-data="{ show: false }">
            <label for="password" class="block mb-2 text-sm font-medium text-gray-900">Password <span
                    class="text-red-500">*</span></label>
            <div class="relative">
                <input wire:model="password" x-bind:type="show ? 'text' : 'password'" id="password"
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary focus:border-primary block w-full p-2.5"
                    placeholder="Masukan password" required autocomplete="new-password">
                <div class="absolute inset-y-0 right-0 pr-3 flex items-center text-sm leading-5">
                    <button type="button" @click="show = !show" class="p-1 text-gray-500 hover:text-gray-700">
                        <svg x-show="!show" class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none"
                            viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.432 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                        <svg x-show="show" class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none"
                            viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" style="display: none;">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M3.98 8.223A10.477 10.477 0 001.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.45 10.45 0 0112 4.5c4.756 0 8.773 3.162 10.065 7.498a10.523 10.523 0 01-4.293 5.774M6.228 6.228L3 3m3.228 3.228l3.65 3.65m7.894 7.894L21 21m-3.228-3.228l-3.65-3.65m0 0a3 3 0 10-4.243-4.243m4.243 4.243l-4.243-4.243" />
                        </svg>
                    </button>
                </div>
            </div>
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirm Password -->
        <div x-data="{ show: false }">
            <label for="password_confirmation" class="block mb-2 text-sm font-medium text-gray-900">Konfirmasi
                Password <span class="text-red-500">*</span></label>
            <div class="relative">
                <input wire:model="password_confirmation" x-bind:type="show ? 'text' : 'password'"
                    id="password_confirmation"
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary focus:border-primary block w-full p-2.5"
                    placeholder="Konfirmasi password" required autocomplete="new-password">
                <div class="absolute inset-y-0 right-0 pr-3 flex items-center text-sm leading-5">
                    <button type="button" @click="show = !show" class="p-1 text-gray-500 hover:text-gray-700">
                        <svg x-show="!show" class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none"
                            viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.432 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                        <svg x-show="show" class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none"
                            viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" style="display: none;">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M3.98 8.223A10.477 10.477 0 001.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.45 10.45 0 0112 4.5c4.756 0 8.773 3.162 10.065 7.498a10.523 10.523 0 01-4.293 5.774M6.228 6.228L3 3m3.228 3.228l3.65 3.65m7.894 7.894L21 21m-3.228-3.228l-3.65-3.65m0 0a3 3 0 10-4.243-4.243m4.243 4.243l-4.243-4.243" />
                        </svg>
                    </button>
                </div>
            </div>
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="mt-2">
            <button type="submit" wire:loading.attr="disabled"
                class="w-full text-white bg-primary hover:bg-teal-700 focus:ring-4 focus:outline-none focus:ring-teal-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center transition-colors duration-200 disabled:opacity-50">
                <span wire:loading.remove>Daftar Akun</span>
                <span wire:loading>Memproses...</span>
            </button>
        </div>
    </form>

    <div class="mt-6 text-center text-sm text-gray-600">
        <span>Sudah punya akun?</span>
        <a href="{{ route('login') }}" wire:navigate
            class="font-medium text-primary hover:text-teal-700 hover:underline">Masuk</a>
    </div>
</div>

@script
    <script>
        // SweetAlert untuk register
        $wire.on('register-success', (data) => {
            Swal.fire({
                title: 'Pendaftaran Berhasil!',
                text: 'Akun Anda telah dibuat.',
                icon: 'success',
                timer: 2000,
                showConfirmButton: false,
                confirmButtonColor: '#058a84'
            }).then(() => {
                const url = (data && data[0] && data[0].intended) ? data[0].intended : '/';
                Livewire.navigate(url);
            });
        });

        $wire.on('register-failed', (data) => {
            Swal.fire({
                title: 'Pendaftaran Gagal!',
                html: data[0].message,
                icon: 'error',
                confirmButtonText: 'Tutup',
                confirmButtonColor: '#058a84'
            });
        });

        $wire.on('register-error', (data) => {
            Swal.fire({
                title: 'Terjadi Kesalahan!',
                text: data[0].message || 'Tidak dapat memproses pendaftaran saat ini.',
                icon: 'error',
                confirmButtonText: 'Tutup',
                confirmButtonColor: '#058a84'
            });
        });
    </script>
@endscript
