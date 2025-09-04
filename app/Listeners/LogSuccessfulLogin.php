<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Login;
use App\Models\AuditLog;
use Carbon\Carbon;

class LogSuccessfulLogin
{
    public function handle(Login $event)
    {
        $user = $event->user;

        AuditLog::create([
            'user' => $user->full_name,
            'date_time' => now(),
            'activity_type' => 'Login',
        ]);
    }
}
