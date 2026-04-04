@extends('layouts.master_dashboard')

@section('content')

    
    <div class="col-span-12">
        <div class="rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]">
            <!-- Header -->
            <div class="flex items-center justify-between px-6 py-4 border-b border-gray-200 dark:border-gray-800">
                <h3 class="text-base font-semibold text-gray-800 dark:text-white/90">
                    {{ __('messages.birthday_calendar', ['month' => $monthName, 'year' => $year]) }}
                </h3>
                <div class="flex gap-2">
                    <a href="{{ route('calendar.index', ['month' => ($month === 1 ? 12 : $month - 1), 'year' => ($month === 1 ? $year - 1 : $year)]) }}" class="inline-flex items-center gap-1.5 text-sm text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200 transition-colors px-3 py-1.5 rounded hover:bg-gray-100 dark:hover:bg-gray-900">
                        ← {{ __('messages.prev_month') }}
                    </a>
                    <a href="{{ route('calendar.index') }}" class="inline-flex items-center gap-1.5 text-sm text-gray-700 bg-blue-500 hover:bg-blue-600 transition-colors px-3 py-1.5 rounded">
                        {{ __('messages.today') }}
                    </a>
                    <a href="{{ route('calendar.index', ['month' => ($month === 12 ? 1 : $month + 1), 'year' => ($month === 12 ? $year + 1 : $year)]) }}" class="inline-flex items-center gap-1.5 text-sm text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200 transition-colors px-3 py-1.5 rounded hover:bg-gray-100 dark:hover:bg-gray-900">
                        {{ __('messages.next_month') }} →
                    </a>
                </div>
            </div>

            <div class="p-6">
            
            <div class="grid gap-1 mb-1" style="grid-template-columns: repeat(7, 1fr);">
                @foreach(['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'] as $day)
                    <div class="text-center font-semibold text-gray-700 dark:text-gray-300 py-2 text-sm">
                        {{ __('messages.day_' . strtolower($day)) }}
                    </div>
                @endforeach
            </div>

            <!-- Calendar Grid -->
            <div class="grid gap-1 bg-gray-100 dark:bg-gray-900 p-1 rounded" style="grid-template-columns: repeat(7, 1fr);">
                @php
                    $firstDay = \Carbon\Carbon::create($year, $month, 1);
                    $daysInMonth = $firstDay->daysInMonth;
                    $startingDayOfWeek = $firstDay->dayOfWeek;
                @endphp

                @for ($i = 0; $i < $startingDayOfWeek; $i++)
                    <div class="bg-white dark:bg-gray-800 min-h-24 p-2 rounded"></div>
                @endfor

                <!-- Calendar days -->
                @for ($day = 1; $day <= $daysInMonth; $day++)
                    @php
                        $isToday = $today->day === $day && $today->month === $month && $today->year === $year;
                        $hasContact = isset($contacts[$day]);
                        $contact = $contacts[$day] ?? null;
                    @endphp
                    
                    <div class="bg-white dark:bg-gray-800 min-h-24 p-2 rounded {{ $isToday ? 'ring-2 ring-blue-500' : '' }} hover:shadow-md transition">
                        <div class="flex items-start justify-between h-full">
                            <span class="font-semibold text-gray-900 dark:text-white {{ $isToday ? 'text-blue-600 dark:text-blue-400' : '' }} text-sm">
                                {{ $day }}
                            </span>
                            
                            @if ($hasContact && $contact)
                                <div class="flex-1 ml-1">
                                    <div class="bg-yellow-100 dark:bg-yellow-900 rounded px-2 py-1 text-xs">
                                        <p class="font-semibold text-yellow-900 dark:text-yellow-100 truncate">
                                            {{ $contact['name'] }}
                                        </p>
                                        <p class="text-yellow-700 dark:text-yellow-300 text-xs">
                                            {{ \Carbon\Carbon::create($contact['year'], $contact['month'], $contact['day'])->diff(now())->y }} {{ __('messages.years_old') }}
                                        </p>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                @endfor
            </div>

      
            </div>
        </div>
    </div>
@endsection
