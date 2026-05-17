<?php

namespace App\Services;

use App\Models\Appointment;
use App\Models\Schedule;
use Illuminate\Support\Facades\DB;
use Exception;

class AppointmentService
{
    /**
     * Book a new appointment safely preventing race conditions.
     *
     * @param int $patientId
     * @param int $scheduleId
     * @param string|null $complaint
     * @return \App\Models\Appointment
     * @throws \Exception
     */
    public function bookAppointment(int $patientId, int $scheduleId, ?string $complaint = null): Appointment
    {
        return DB::transaction(function () use ($patientId, $scheduleId, $complaint) {
            // 1. Lock the schedule row to prevent concurrent modifications
            $schedule = Schedule::where('id', $scheduleId)->lockForUpdate()->first();

            if (!$schedule) {
                throw new Exception("Schedule not found.");
            }

            if (!$schedule->is_active) {
                throw new Exception("This schedule is no longer active.");
            }

            // 2. Count existing appointments for this schedule to check quota
            $currentAppointmentsCount = Appointment::where('schedule_id', $scheduleId)->count();

            if ($currentAppointmentsCount >= $schedule->quota) {
                throw new Exception("The schedule quota is full.");
            }

            // 3. Ensure the patient hasn't already booked this schedule
            $existingAppointment = Appointment::where('patient_id', $patientId)
                ->where('schedule_id', $scheduleId)
                ->first();

            if ($existingAppointment) {
                throw new Exception("Patient has already booked an appointment for this schedule.");
            }

            // 4. Calculate the next queue number safely (we have the lock on the schedule)
            $maxQueueNumber = Appointment::where('schedule_id', $scheduleId)->max('queue_number');
            $nextQueueNumber = $maxQueueNumber ? $maxQueueNumber + 1 : 1;

            // 5. Create the appointment
            return Appointment::create([
                'patient_id' => $patientId,
                'schedule_id' => $scheduleId,
                'queue_number' => $nextQueueNumber,
                'status' => 'waiting',
                'complaint' => $complaint,
            ]);
        });
    }
}
