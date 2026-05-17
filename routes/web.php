<?php

use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;
use App\Http\Controllers\PdfController;

Volt::route('/', 'patient.home')->name('home');

Route::middleware(['auth'])->group(function () {
    // -----------------------------------------------------------------------
    // Admin Routes
    // -----------------------------------------------------------------------
    Route::middleware(['role:admin'])->prefix('admin')->name('admin.')->group(function () {
        // Dashboard & Profile
        Volt::route('profile', 'admin.profile')->name('profile');

        // Screenings
        Volt::route('screening', 'admin.screening')->name('screenings');
        Volt::route('/screening/result/{screening}', 'admin.result')->name('screening.result');
        Route::get('/screening/pdf/{screening}', [PdfController::class, 'downloadScreeningReport'])
            ->name('screening.pdf');

        // Schedules Management
        Volt::route('schedule', 'admin.schedule')->name('schedule');
        Volt::route('schedule/{schedule}/patients', 'admin.schedule.patients')->name('schedule.patients');
    });

    // -----------------------------------------------------------------------
    // Patient Routes
    // -----------------------------------------------------------------------
    Route::middleware(['role:patient'])->name('patient.')->group(function () {
        // Profile
        Volt::route('/profile', 'patient.profile')->name('profile');

        // Screenings
        Volt::route('/screening', 'patient.screening')->name('screenings');

        // History
        Volt::route('/history', 'patient.history')->name('history');
        Volt::route('/history/{screening}', 'patient.result')->name('result');

        // Schedule
        Volt::route('/schedule', 'patient.schedule')->name('schedule');


        // Appointments Management (Placeholders)
        Route::post('/appointments', function () {
            return 'Patient Appointment Store';
        })->name('appointments.store');
    });
});


require __DIR__ . '/auth.php';
