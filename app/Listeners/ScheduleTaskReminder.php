<?php

namespace App\Listeners;

use App\Events\CrmActivityCreated;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Log;

class ScheduleTaskReminder
{
    /**
     * Handle the event.
     */
    public function handle(CrmActivityCreated $event): void
    {
        $activity = $event->activity;

        // Only schedule for tasks
        if ($activity->type === 'task') {
            // Logic for dashboard flags, push notifications, or emails
            // For now, we log it to simulate scheduling.
            Log::info("Lembrete agendado para a tarefa ID {$activity->id} - Vendedor ID {$activity->user_id}");
            
            // In a real scenario, you might add a record to a notifications table
            // or dispatch a delayed job for a specific notification channel.
        }
    }
}
