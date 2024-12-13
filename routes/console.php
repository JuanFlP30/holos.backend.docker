<?php

use App\Schedules\DeleteResetPasswords;
use Illuminate\Support\Facades\Schedule;

if(config('telescope.enabled') == 'true') {
    Schedule::command('telescope:prune')->daily();
}

Schedule::call(new DeleteResetPasswords)->hourly();
