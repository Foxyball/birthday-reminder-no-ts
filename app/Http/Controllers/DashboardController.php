<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        $totalContacts = $user->contacts()->count();
        
        $currentMonth = Carbon::now()->month;
        $upcomingBirthdays = $user->contacts()
            ->whereRaw('MONTH(birthday) = ?', [$currentMonth])
            ->count();
        
        // Recent 5 contacts added
        $recentContacts = $user->contacts()
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();
        
        return view('index', [
            'totalContacts' => $totalContacts,
            'upcomingBirthdays' => $upcomingBirthdays,
            'recentContacts' => $recentContacts,
        ]);
    }
}
