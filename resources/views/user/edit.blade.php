@extends('layouts.master_dashboard')

@section('content')
	<div class="col-span-12">
		<div class="rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]">
			<div class="flex items-center justify-between border-b border-gray-200 px-6 py-4 dark:border-gray-800">
				<h3 class="text-base font-semibold text-gray-800 dark:text-white/90">
					{{ __('messages.edit_user') }}
				</h3>
				<a href="{{ route('users.index') }}"
				   class="inline-flex items-center gap-1.5 text-sm text-gray-500 transition-colors hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200">
					<svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
						<path fill-rule="evenodd" clip-rule="evenodd" d="M11.03 4.47a.75.75 0 0 1 0 1.06L5.81 11.25H20a.75.75 0 0 1 0 1.5H5.81l5.22 5.72a.75.75 0 1 1-1.06 1.06l-6.5-7a.75.75 0 0 1 0-1.06l6.5-7a.75.75 0 0 1 1.06 0Z"/>
					</svg>
					{{ __('messages.cancel') }}
				</a>
			</div>

			<form action="{{ route('users.update', $user) }}" method="POST" class="p-6">
				@csrf
				@method('PUT')
				<div class="space-y-6">
					<div>
						<label for="name" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
							{{ __('messages.name') }} <span class="text-error-500">*</span>
						</label>
						<input
							type="text"
							id="name"
							name="name"
							value="{{ old('name', $user->name) }}"
							placeholder="{{ __('messages.name') }}"
							class="shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:outline-hidden focus:ring-3 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 {{ $errors->has('name') ? 'border-error-400 dark:border-error-500' : 'border-gray-300 dark:border-gray-700' }}"
						>
						@error('name')
							<p class="mt-1.5 text-xs text-error-500">{{ $message }}</p>
						@enderror
					</div>

					<div>
						<label for="email" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
							{{ __('messages.email') }} <span class="text-error-500">*</span>
						</label>
						<input
							type="email"
							id="email"
							name="email"
							value="{{ old('email', $user->email) }}"
							placeholder="{{ __('messages.email') }}"
							class="shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:outline-hidden focus:ring-3 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 {{ $errors->has('email') ? 'border-error-400 dark:border-error-500' : 'border-gray-300 dark:border-gray-700' }}"
						>
						@error('email')
							<p class="mt-1.5 text-xs text-error-500">{{ $message }}</p>
						@enderror
					</div>

					<div>
						<label for="role" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
							{{ __('messages.role') }} <span class="text-error-500">*</span>
						</label>
						<select
							id="role"
							name="role"
							class="shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border bg-transparent px-4 py-2.5 text-sm text-gray-800 focus:outline-hidden focus:ring-3 dark:bg-gray-900 dark:text-white/90 {{ $errors->has('role') ? 'border-error-400 dark:border-error-500' : 'border-gray-300 dark:border-gray-700' }}"
						>
							<option value="0" @selected(old('role', (string) $user->role) == '0')>{{ __('messages.user') }}</option>
							<option value="1" @selected(old('role', (string) $user->role) == '1')>{{ __('messages.admin') }}</option>
						</select>
						@error('role')
							<p class="mt-1.5 text-xs text-error-500">{{ $message }}</p>
						@enderror
					</div>

					<div class="flex items-center gap-3 pt-2">
						<button
							type="submit"
							class="inline-flex items-center gap-2 rounded-lg bg-brand-500 px-5 py-2.5 text-sm font-medium text-white transition-colors hover:bg-brand-600 focus:outline-none focus:ring-3 focus:ring-brand-500/20"
						>
							{{ __('messages.save') }}
						</button>
						<a
							href="{{ route('users.index') }}"
							class="inline-flex items-center rounded-lg border border-gray-300 bg-white px-5 py-2.5 text-sm font-medium text-gray-700 transition-colors hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 dark:hover:bg-gray-800"
						>
							{{ __('messages.cancel') }}
						</a>
					</div>
				</div>
			</form>
		</div>
	</div>
@endsection
