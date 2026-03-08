@extends('layouts.master_dashboard')

@section('content')
    <div class="col-span-12 space-y-6">
        <div class="rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]">
            <div class="flex items-center justify-between border-b border-gray-200 px-6 py-4 dark:border-gray-800">
                <div>
                    <h3 class="text-base font-semibold text-gray-800 dark:text-white/90">
                        {{ __('messages.account_settings') }}
                    </h3>
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                        {{ __('messages.account_settings_description') }}
                    </p>
                </div>
            </div>

            <div class="space-y-6 p-6">
                @include('profile.partials.update-profile-information-form')
                @include('profile.partials.update-password-form')
                @include('profile.partials.delete-user-form')
            </div>
        </div>
    </div>
@endsection
