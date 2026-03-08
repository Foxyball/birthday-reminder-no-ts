@extends('layouts.master_dashboard')

@section('content')
	<div class="col-span-12">
		<div class="rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]">
			<div class="flex items-center justify-between border-b border-gray-200 px-6 py-4 dark:border-gray-800">
				<h3 class="text-base font-semibold text-gray-800 dark:text-white/90">
					{{ __('messages.users') }}
				</h3>
				<a href="{{ route('users.create') }}"
				   class="inline-flex items-center gap-2 rounded-lg bg-brand-500 px-4 py-2 text-sm font-medium text-white transition-colors hover:bg-brand-600">
					<svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
						<path fill-rule="evenodd" clip-rule="evenodd" d="M12 3.25a.75.75 0 0 1 .75.75v7.25H20a.75.75 0 0 1 0 1.5h-7.25V20a.75.75 0 0 1-1.5 0v-7.25H4a.75.75 0 0 1 0-1.5h7.25V4a.75.75 0 0 1 .75-.75Z"/>
					</svg>
					{{ __('messages.create_new') }}
				</a>
			</div>
			<div class="overflow-x-auto p-6">
				{{ $dataTable->table(['class' => 'w-full text-left text-sm text-gray-700 dark:text-gray-300', 'data-status-url' => route('admin.users.change-status')]) }}
			</div>
		</div>
	</div>
@endsection

@push('scripts')
	{{ $dataTable->scripts(attributes: ['type' => 'module']) }}
@endpush
