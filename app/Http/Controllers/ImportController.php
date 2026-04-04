<?php

namespace App\Http\Controllers;

use App\Http\Requests\ImportContactsRequest;
use App\Services\ImportService;
use Illuminate\Support\Facades\Log;

/**
 * Controller for handling contact imports from CSV files.
 *
 * Handles the complete import workflow including displaying the import modal,
 * processing uploaded CSV files, and providing template download functionality.
 */
class ImportController extends Controller
{
    const SUCCESS_MESSAGE = 'messages.import_success';

    const ERROR_MESSAGE = 'messages.import_error';

    private ImportService $importService;

    public function __construct(ImportService $importService)
    {
        $this->importService = $importService;
    }

    /**
     * Display the import contacts modal view.
     *
     * @return \Illuminate\View\View The import modal view
     */
    public function show()
    {
        return view('import.modal');
    }

    /**
     * Process and import contacts from an uploaded CSV file.
     *
     * Validates the uploaded CSV file and delegates to ImportService for processing.
     * Returns JSON response with import results (count, errors) or validation errors.
     *
     * @param  ImportContactsRequest  $request  The import request with validated CSV file
     * @return \Illuminate\Http\JsonResponse With structure:
     *                                       {
     *                                       "status": "success|error",
     *                                       "message": "...",
     *                                       "count": int,
     *                                       "errors": array
     *                                       }
     */
    public function store(ImportContactsRequest $request)
    {
        try {
            $file = $request->file('file');
            $filePath = $file->path();

            $result = $this->importService->importContacts($filePath, auth()->id());

            if ($result['imported'] === 0) {
                return response()->json([
                    'status' => 'error',
                    'message' => __('messages.import_no_valid_rows'),
                ], 422);
            }

            $message = __('messages.import_success', ['count' => $result['imported']]);

            return response()->json([
                'status' => 'success',
                'message' => $message,
                'count' => $result['imported'],
                'errors' => $result['errors'],
            ]);
        } catch (\Exception $e) {
            // log the error for debugging
            Log::error('Import error: '.$e->getMessage());

            return response()->json([
                'status' => 'error',
                'message' => __('messages.import_error'),
            ], 422);
        }
    }

    /**
     * Download the CSV import template file.
     *
     * Serves the static CSV template file from the public directory.
     * The template contains column headers and an example row to guide users.
     *
     * @return \Illuminate\Http\Response File download response
     */
    public function template()
    {
        $filePath = public_path('import_contact_template.csv');

        return response()->download($filePath, 'contacts-template.csv');
    }
}
