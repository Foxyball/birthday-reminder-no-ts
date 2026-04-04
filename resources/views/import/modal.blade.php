<div id="importContactsModal" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
    <div class="bg-white dark:bg-gray-900 rounded-lg shadow-lg max-w-md w-full mx-4">
        <!-- Header -->
        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-800 flex items-center justify-between">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                {{ __('messages.import_title') }}
            </h3>
            <button type="button" class="text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200" onclick="closeImportModal()">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>

        <!-- Content -->
        <form id="importContactsForm" class="p-6 space-y-4" enctype="multipart/form-data">
            @csrf

            <!-- Instructions -->
            <div class="text-sm text-gray-600 dark:text-gray-400 leading-relaxed">
                {{ __('messages.import_instructions', ['template' => __('messages.import_template_link')]) }}
                <a href="{{ route('admin.import.template') }}" class="text-blue-500 hover:text-blue-600 dark:text-blue-400 dark:hover:text-blue-300 font-medium underline" download>
                    {{ __('messages.import_template_link') }}
                </a>
            </div>

            <!-- File Upload -->
            <label for="fileInput" class="flex justify-center px-6 pt-12 pb-12 border-2 border-dashed border-gray-300 dark:border-gray-700 rounded-lg hover:border-blue-400 dark:hover:border-blue-600 transition cursor-pointer" id="dropZone">
                <div class="space-y-3 text-center">
                    <svg class="mx-auto h-20 w-20 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                        <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-12l-3.172-3.172a4 4 0 00-5.656 0L28 20M9 20l3.172-3.172a4 4 0 015.656 0L28 20" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                    </svg>
                    <div class="flex text-sm text-gray-600 dark:text-gray-400">
                        <span class="font-medium text-blue-600 dark:text-blue-400 hover:text-blue-500">
                            {{ __('messages.import_upload_label') }}
                        </span>
                        <input id="fileInput" name="file" type="file" accept=".csv,.txt" class="hidden" required>
                    </div>
                    <p class="text-xs text-gray-600 dark:text-gray-400" id="fileName"></p>
                </div>
            </label>

            <!-- Buttons -->
            <div class="flex gap-3 pt-4">
                <button type="button" class="flex-1 px-4 py-2 border border-gray-300 dark:border-gray-700 rounded-lg text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-800 transition font-medium" onclick="closeImportModal()">
                    {{ __('messages.import_cancel') }}
                </button>
                <button type="submit" class="flex-1 px-4 py-2 bg-blue-500 text-gray-700 rounded-lg hover:bg-blue-600 transition font-medium" id="importBtn">
                    {{ __('messages.import_button') }}
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    function openImportModal() {
        document.getElementById('importContactsModal').classList.remove('hidden');
        document.getElementById('fileInput').value = '';
        document.getElementById('fileName').textContent = '';
    }

    function closeImportModal() {
        document.getElementById('importContactsModal').classList.add('hidden');
    }

    document.addEventListener('DOMContentLoaded', function () {
        const fileInput = document.getElementById('fileInput');
        const dropZone = document.getElementById('dropZone');
        const importForm = document.getElementById('importContactsForm');
        const fileNameDisplay = document.getElementById('fileName');

        // File input change
        fileInput?.addEventListener('change', function (e) {
            if (this.files.length > 0) {
                fileNameDisplay.textContent = this.files[0].name;
            }
        });

        // Drag and drop
        dropZone?.addEventListener('dragover', (e) => {
            e.preventDefault();
            dropZone.classList.add('border-blue-400', 'bg-blue-50', 'dark:bg-blue-900/10');
        });

        dropZone?.addEventListener('dragleave', () => {
            dropZone.classList.remove('border-blue-400', 'bg-blue-50', 'dark:bg-blue-900/10');
        });

        dropZone?.addEventListener('drop', (e) => {
            e.preventDefault();
            dropZone.classList.remove('border-blue-400', 'bg-blue-50', 'dark:bg-blue-900/10');

            const files = e.dataTransfer.files;
            if (files.length > 0) {
                fileInput.files = files;
                fileNameDisplay.textContent = files[0].name;
            }
        });

        // Form submit
        importForm?.addEventListener('submit', async (e) => {
            e.preventDefault();

            const formData = new FormData(importForm);
            const importBtn = document.getElementById('importBtn');
            const originalText = importBtn.textContent;

            importBtn.disabled = true;
            importBtn.textContent = '{{ __("messages.importing") }}...';

            try {
                const response = await fetch('{{ route("admin.import.store") }}', {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                    },
                });

                const data = await response.json();

                if (data.status === 'success') {
                    showToast('success', data.message);
                    // Reset form before closing
                    importForm.reset();
                    document.getElementById('fileName').textContent = '';
                    // Close modal after a brief delay to show toast
                    setTimeout(() => {
                        closeImportModal();
                    }, 500);
                    // Reload contacts table if exists
                    if (window.contactsTable) {
                        window.contactsTable.ajax.reload(null, false);
                    }
                } else {
                    showToast('error', data.message);
                }
            } catch (error) {
                showToast('error', '{{ __("messages.import_error") }}');
            } finally {
                importBtn.disabled = false;
                importBtn.textContent = originalText;
            }
        });
    });

    // Close modal on Escape
    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape' && !document.getElementById('importContactsModal').classList.contains('hidden')) {
            closeImportModal();
        }
    });
</script>
