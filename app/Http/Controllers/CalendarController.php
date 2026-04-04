<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CalendarController extends Controller
{
    /**
     * Display the calendar page with user's contacts birthdays.
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $month = (int) $request->query('month', now()->month);
        $year = (int) $request->query('year', now()->year);

        // Validate month and year
        $month = max(1, min(12, $month));
        $year = max(1900, min(2100, $year));

        // Get contacts with birthdays in the specified month
        $contacts = $user->contacts()
            ->get()
            ->filter(function ($contact) use ($month) {
                return (int) explode('-', $contact->birthday)[1] === $month;
            })
            ->map(function ($contact) {
                [$year, $month, $day] = explode('-', $contact->birthday);
                return [
                    'id' => $contact->id,
                    'name' => $contact->name,
                    'day' => (int) $day,
                    'month' => (int) $month,
                    'year' => (int) $year,
                ];
            })
            ->keyBy('day');

        return view('calendar.calendar', [
            'contacts' => $contacts,
            'month' => $month,
            'year' => $year,
            'monthName' => now()->setMonth($month)->format('F'),
            'today' => now(),
        ]);
    }
}
