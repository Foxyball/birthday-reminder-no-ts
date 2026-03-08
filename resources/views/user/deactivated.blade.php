@extends('layouts.master_dashboard')

@section('content')
    <div class="col-span-12">
        <div class="rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]">
            <div class="flex items-center justify-between border-b border-gray-200 px-6 py-4 dark:border-gray-800">
                <h3 class="text-base font-semibold text-gray-800 dark:text-white/90">
                    {{ __('messages.deactivated_users') }}
                </h3>
                <a href="{{ route('users.index') }}"
                   class="inline-flex items-center gap-1.5 text-sm text-gray-500 transition-colors hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" clip-rule="evenodd" d="M11.03 4.47a.75.75 0 0 1 0 1.06L5.81 11.25H20a.75.75 0 0 1 0 1.5H5.81l5.22 5.72a.75.75 0 1 1-1.06 1.06l-6.5-7a.75.75 0 0 1 0-1.06l6.5-7a.75.75 0 0 1 1.06 0Z"/>
                    </svg>
                    {{ __('messages.users') }}
                </a>
            </div>
            <div class="overflow-x-auto p-6">
                {{ $dataTable->table(['class' => 'w-full text-left text-sm text-gray-700 dark:text-gray-300']) }}
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    {{ $dataTable->scripts(attributes: ['type' => 'module']) }}
@endpush