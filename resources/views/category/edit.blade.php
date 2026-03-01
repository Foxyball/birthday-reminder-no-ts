@extends('layouts.master_dashboard')

@section('content')
    <div class="col-span-12">
        <div class="rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]">
            <!-- Header -->
            <div class="flex items-center justify-between px-6 py-4 border-b border-gray-200 dark:border-gray-800">
                <h3 class="text-base font-semibold text-gray-800 dark:text-white/90">
                    {{ __('messages.edit_category') }}
                </h3>
                <a href="{{ route('category.index') }}"
                   class="inline-flex items-center gap-1.5 text-sm text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200 transition-colors">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" clip-rule="evenodd" d="M11.03 4.47a.75.75 0 0 1 0 1.06L5.81 11.25H20a.75.75 0 0 1 0 1.5H5.81l5.22 5.72a.75.75 0 1 1-1.06 1.06l-6.5-7a.75.75 0 0 1 0-1.06l6.5-7a.75.75 0 0 1 1.06 0Z"/>
                    </svg>
                    {{ __('messages.cancel') }}
                </a>
            </div>

            <!-- Form -->
            <form action="{{ route('category.update', $category) }}" method="POST" class="p-6">
                @csrf
                @method('PUT')
                <div class="space-y-6">

                    <!-- Name -->
                    <div>
                        <label for="name" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                            {{ __('messages.name') }} <span class="text-error-500">*</span>
                        </label>
                        <input
                            type="text"
                            id="name"
                            name="name"
                            value="{{ old('name', $category->name) }}"
                            placeholder="{{ __('messages.name') }}"
                            class="shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30
                                {{ $errors->has('name') ? 'border-error-400 dark:border-error-500' : 'border-gray-300 dark:border-gray-700' }}"
                        >
                        @error('name')
                            <p class="mt-1.5 text-xs text-error-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Description -->
                    <div>
                        <label for="description" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                            {{ __('messages.description') }}
                        </label>
                        <textarea
                            id="description"
                            name="description"
                            rows="4"
                            placeholder="{{ __('messages.description') }}"
                            class="shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30"
                        >{{ old('description', $category->description) }}</textarea>
                        @error('description')
                            <p class="mt-1.5 text-xs text-error-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Actions -->
                    <div class="flex items-center gap-3 pt-2">
                        <button
                            type="submit"
                            class="inline-flex items-center gap-2 rounded-lg bg-brand-500 px-5 py-2.5 text-sm font-medium text-white hover:bg-brand-600 transition-colors focus:outline-none focus:ring-3 focus:ring-brand-500/20"
                        >
                            {{ __('messages.save') }}
                        </button>
                        <a
                            href="{{ route('category.index') }}"
                            class="inline-flex items-center rounded-lg border border-gray-300 bg-white px-5 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50 transition-colors dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 dark:hover:bg-gray-800"
                        >
                            {{ __('messages.cancel') }}
                        </a>
                    </div>

                </div>
            </form>
        </div>
    </div>
@endsection
