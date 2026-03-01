@extends('layouts.master_dashboard')

@section('content')
    <div class="col-span-12">
        <div class="rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]">
            <div class="flex items-center justify-between px-6 py-4 border-b border-gray-200 dark:border-gray-800">
                <h3 class="text-base font-semibold text-gray-800 dark:text-white/90">
                    {{ __('messages.categories') }}
                </h3>
                <a href="{{ route('category.create') }}"
                   class="inline-flex items-center gap-2 rounded-lg bg-brand-500 px-4 py-2 text-sm font-medium text-white hover:bg-brand-600 transition-colors">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" clip-rule="evenodd" d="M12 3.25a.75.75 0 0 1 .75.75v7.25H20a.75.75 0 0 1 0 1.5h-7.25V20a.75.75 0 0 1-1.5 0v-7.25H4a.75.75 0 0 1 0-1.5h7.25V4a.75.75 0 0 1 .75-.75Z"/>
                    </svg>
                    {{ __('messages.create_new') }}
                </a>
            </div>
            <div class="p-6 overflow-x-auto">
                {{ $dataTable->table(['class' => 'w-full text-sm text-left text-gray-700 dark:text-gray-300']) }}
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    {{ $dataTable->scripts(attributes: ['type' => 'module']) }}

    <script>
        document.addEventListener('alpine:init', function () {});

        $(document).ready(function() {
            $('#category-table').on('draw.dt', function () {
                Alpine.initTree(document.getElementById('category-table'));
            });

            $('body').on('change', '.change-status', function() {
                let isChecked = $(this).is(':checked');
                let id = $(this).data('id');

                $.ajax({
                    url: "{{ route('admin.category.change-status') }}",
                    method: 'PUT',
                    data: {
                        status: isChecked,
                        id: id
                    },
                    success: function(data) {
                        showToast('success', data.message);
                    },
                    error: function(xhr, status, error) {
                        let errorMessage = xhr.status + ': ' + xhr.statusText;
                        showToast('error', errorMessage);
                    }
                });
            });
        });
    </script>
@endpush