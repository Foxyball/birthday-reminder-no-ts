<?php

namespace App\Services;

use App\Models\Category;
use App\Models\Contact;
use DateTime;
use Illuminate\Support\Facades\Log;
use OpenSpout\Reader\CSV\Reader as CSVReader;

/**
 * Service for importing contacts from CSV files.
 *
 * This service handles the complete workflow of importing contacts from CSV files,
 * including reading CSV data, validating entries, parsing dates, matching categories,
 * and creating contact records in the database.
 *
 * CSV Format Expected:
 * - Column 1: Name (required)
 * - Column 2: Email (optional)
 * - Column 3: Phone (optional)
 * - Column 4: Birthday (required, format: DD/MM/YYYY or YYYY-MM-DD)
 * - Column 5: Category (optional, matched by name)
 */
class ImportService
{
    /**
     * Read and parse a CSV file.
     *
     * Opens a CSV file using OpenSpout library and extracts all rows with their cell values.
     * The first row (header) is preserved and should be skipped during processing.
     *
     * @param  string  $filePath  Absolute path to the CSV file
     * @return array<int, array<string, mixed>> Array of rows with structure:
     *                                          [
     *                                          'index' => int (1-based row number),
     *                                          'cells' => array<string|null> (cell values)
     *                                          ]
     *
     * @throws \Exception On file reading or parsing errors
     */
    public function readCsvFile(string $filePath): array
    {
        $reader = new CSVReader;
        $reader->open($filePath);

        $rows = [];
        foreach ($reader->getSheetIterator() as $sheet) {
            foreach ($sheet->getRowIterator() as $rowIndex => $row) {
                $cells = $row->getCells();
                $rows[] = [
                    'index' => $rowIndex,
                    'cells' => array_map(fn ($cell) => $cell?->getValue(), $cells),
                ];
            }
        }

        $reader->close();

        return $rows;
    }

    /**
     * Import contacts from a CSV file for a specific user.
     *
     * Processes a CSV file and creates Contact records. Performs validation on:
     * - Required fields (name, birthday)
     * - Birthday format and validity
     * - Category name matching
     * - Email uniqueness per user
     *
     * Errors are logged and collected without stopping the import process.
     * Successfully created contacts are returned along with any errors encountered.
     *
     * @param  string  $filePath  Absolute path to the CSV file to import
     * @param  int  $userId  The ID of the user importing contacts
     * @return array<string, mixed> Result array with structure:
     *                              [
     *                              'imported' => int (number of successfully created contacts),
     *                              'errors' => array<string> (validation/processing errors)
     *                              ]
     *
     * Example return:
     * ```php
     * [
     *     'imported' => 3,
     *     'errors' => [
     *         'Error processing row 2',
     *         'Invalid birthday format in row 5'
     *     ]
     * ]
     * ```
     */
    public function importContacts(string $filePath, int $userId): array
    {
        try {
            $rows = $this->readCsvFile($filePath);
        } catch (\Exception $e) {
            Log::error('CSV reading error: '.$e->getMessage());

            return [
                'imported' => 0,
                'errors' => ['CSV file reading error: '.$e->getMessage()],
            ];
        }

        $importedCount = 0;
        $errors = [];

        // Skip header row (row 1 is header)
        foreach ($rows as $row) {
            if ($row['index'] === 1) {
                continue;
            }

            [
                $name,
                $email,
                $phone,
                $birthday,
                $categoryName,
            ] = array_pad($row['cells'], 5, null);

            $rowNumber = $row['index'];

            // Validate required fields
            if (empty($name) || empty($birthday)) {
                $errors[] = __('messages.import_row_error', ['row' => $rowNumber]);

                continue;
            }

            // Parse birthday
            $parsedBirthday = $this->parseBirthday($birthday);
            if (! $parsedBirthday) {
                $errors[] = __('messages.import_invalid_birthday', ['row' => $rowNumber]);

                continue;
            }

            // Match category by name
            $categoryId = null;
            if (! empty($categoryName)) {
                $category = Category::where('name', trim($categoryName))
                    ->first();
                $categoryId = $category?->id;
            }

            // Check for duplicate email (per user)
            if (! empty($email)) {
                $existingContact = Contact::where('user_id', $userId)
                    ->where('email', trim($email))
                    ->exists();

                if ($existingContact) {
                    $errors[] = __('messages.import_duplicate_email', ['row' => $rowNumber, 'email' => $email]);

                    continue;
                }
            }

            // Create contact
            try {
                Contact::create([
                    'user_id' => $userId,
                    'name' => trim($name),
                    'email' => ! empty($email) ? trim($email) : null,
                    'phone' => ! empty($phone) ? trim($phone) : null,
                    'birthday' => $parsedBirthday->format('Y-m-d'),
                    'category_id' => $categoryId,
                    'slug' => \Illuminate\Support\Str::slug(trim($name)),
                ]);

                $importedCount++;
            } catch (\Exception $e) {
                Log::error('Contact creation error on row '.$rowNumber.': '.$e->getMessage());
                $errors[] = __('messages.import_row_error', ['row' => $rowNumber]);
            }
        }

        return [
            'imported' => $importedCount,
            'errors' => $errors,
        ];
    }

    /**
     * Parse and validate a birthday string in multiple formats.
     *
     * Attempts to parse the birthday string using multiple common date formats.
     * Supports flexible formats to accommodate user input variations.
     *
     * Supported formats:
     * - d/m/Y (15/01/1990)
     * - Y-m-d (1990-01-15)
     * - d-m-Y (15-01-1990)
     * - d.m.Y (15.01.1990)
     * - m/d/Y (01/15/1990)
     * - Y/m/d (1990/01/15)
     *
     * @param  mixed  $birthday  Birthday string or value to parse (null/empty returns null)
     * @return DateTime|null Parsed DateTime object if valid format detected, null otherwise
     *
     * Example:
     * ```php
     * $birthday = $this->parseBirthday('15/01/1990');
     * // Returns: DateTime object for 1990-01-15
     *
     * $birthday = $this->parseBirthday('invalid-date');
     * // Returns: null
     * ```
     */
    public function parseBirthday($birthday): ?DateTime
    {
        if (empty($birthday)) {
            return null;
        }

        // Convert to string if it's not already
        $birthdayStr = (string) $birthday;

        $formats = [
            'd/m/Y',
            'Y-m-d',
            'd-m-Y',
            'd.m.Y',
            'm/d/Y',
            'Y/m/d',
        ];

        foreach ($formats as $format) {
            try {
                $date = DateTime::createFromFormat($format, $birthdayStr);
                if ($date !== false) {
                    return $date;
                }
            } catch (\Exception $e) {
                continue;
            }
        }

        return null;
    }
}
