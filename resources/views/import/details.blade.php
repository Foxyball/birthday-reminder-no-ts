@extends('layouts.master_dashboard')

@section('content')
    <div class="col-span-12">
        <div class="rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]">
            <!-- Header -->
            <div class="flex items-center justify-between px-6 py-4 border-b border-gray-200 dark:border-gray-800">
                <h3 class="text-base font-semibold text-gray-800 dark:text-white/90">
                    {{ __('messages.import_contacts') }} - {{ __('messages.import_details_description') ?? 'Details' }}
                </h3>
                <a href="{{ route('dashboard') }}" class="inline-flex items-center gap-1.5 text-sm text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200 transition-colors px-3 py-1.5 rounded hover:bg-gray-100 dark:hover:bg-gray-900">
                    ← {{ __('messages.back') ?? 'Back' }}
                </a>
            </div>

            <!-- Content -->
            <div class="p-6 space-y-6">
              
                @if ($import->error_count > 0 && $import->errors)
                    <div class="rounded-lg border border-red-200 bg-red-50 p-6 dark:border-red-900/30 dark:bg-red-900/10">
                        <h2 class="mb-4 text-lg font-bold text-red-900 dark:text-red-200">
                            {{ __('messages.import_errors') ?? 'Errors' }} ({{ count($import->errors) }})
                        </h2>

                        <div class="space-y-2 max-h-96 overflow-y-auto">
                            @foreach ($import->errors as $index => $error)
                                <div class="flex gap-3 rounded-lg border border-red-200 bg-white p-3 dark:border-red-800/30 dark:bg-gray-900/50">
                                    <div class="flex-shrink-0 font-bold text-red-600 dark:text-red-400">{{ $index + 1 }}.</div>
                                    <div class="flex-1 text-sm text-gray-700 dark:text-gray-300">
                                        {{ $error }}
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @else
                    @if ($import->error_count === 0)
                        <div class="rounded-lg border border-green-200 bg-green-50 p-6 dark:border-green-900/30 dark:bg-green-900/10">
                            <p class="text-center text-green-900 dark:text-green-200">
                                ✓ {{ __('messages.import_no_errors') ?? 'No errors - all contacts imported successfully!' }}
                            </p>
                        </div>
                    @endif
                @endif
            </div>
        </div>
    </div>
@endsection
