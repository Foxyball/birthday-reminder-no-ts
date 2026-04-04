<?php

namespace App\Observers;

use App\Models\Import;
use Illuminate\Support\Facades\Log;

class ImportObserver
{
    /**
     * Handle the Import "created" event.
     * Log when a new import is created.
     */
    public function created(Import $import): void
    {
        Log::info('Import created', [
            'import_id' => $import->id,
            'user_id' => $import->user_id,
            'imported_count' => $import->imported_count,
            'error_count' => $import->error_count,
            'file_name' => $import->file_name,
        ]);
    }

    /**
     * Handle the Import "updated" event.
     */
    public function updated(Import $import): void
    {
        //
    }

    /**
     * Handle the Import "deleted" event.
     */
    public function deleted(Import $import): void
    {
        //
    }

    /**
     * Handle the Import "restored" event.
     */
    public function restored(Import $import): void
    {
        //
    }

    /**
     * Handle the Import "force deleted" event.
     */
    public function forceDeleted(Import $import): void
    {
        //
    }
}
