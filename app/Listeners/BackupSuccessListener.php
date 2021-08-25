<?php

namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Spatie\Backup\Events\BackupWasSuccessful;
use Illuminate\Support\Facades\Storage;

class BackupSuccessListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */

    public $path;
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle(BackupWasSuccessful $event)
    {
        $path = Storage::path($event->backupDestination->newestBackup()->path());
        session(['path_backup' => $path]);
    }
}
