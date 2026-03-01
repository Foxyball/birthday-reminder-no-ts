@if(session('status'))
    <div
        x-data="{ show: true }"
        x-show="show"
        x-init="setTimeout(() => show = false, 5000)"
        x-transition:leave="transition ease-in duration-300"
        x-transition:leave-start="opacity-100 translate-y-0"
        x-transition:leave-end="opacity-0 -translate-y-2"
        class="mb-4 rounded-xl border border-emerald-400 bg-emerald-50 p-4 dark:border-emerald-500/30 dark:bg-emerald-900/20"
    >
        <div class="flex items-start gap-3">
            <div class="-mt-0.5 text-emerald-600">
                <svg class="fill-current" width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path fill-rule="evenodd" clip-rule="evenodd" d="M3.70186 12.0001C3.70186 7.41711 7.41711 3.70186 12.0001 3.70186C16.5831 3.70186 20.2984 7.41711 20.2984 12.0001C20.2984 16.5831 16.5831 20.2984 12.0001 20.2984C7.41711 20.2984 3.70186 16.5831 3.70186 12.0001ZM12.0001 1.90186C6.423 1.90186 1.90186 6.423 1.90186 12.0001C1.90186 17.5772 6.423 22.0984 12.0001 22.0984C17.5772 22.0984 22.0984 17.5772 22.0984 12.0001C22.0984 6.423 17.5772 1.90186 12.0001 1.90186ZM15.6197 10.7395C15.9712 10.388 15.9712 9.81819 15.6197 9.46672C15.2683 9.11525 14.6984 9.11525 14.347 9.46672L11.1894 12.6243L9.6533 11.0883C9.30183 10.7368 8.73198 10.7368 8.38051 11.0883C8.02904 11.4397 8.02904 12.0096 8.38051 12.3611L10.553 14.5335C10.7217 14.7023 10.9507 14.7971 11.1894 14.7971C11.428 14.7971 11.657 14.7023 11.8257 14.5335L15.6197 10.7395Z" fill=""></path>
                </svg>
            </div>
            <div class="flex-1">
                <p class="text-sm text-emerald-800 dark:text-emerald-200">{{ session('status') }}</p>
            </div>
            <button @click="show = false" class="shrink-0 text-emerald-500 hover:text-emerald-700">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                    <path fill-rule="evenodd" clip-rule="evenodd" d="M5.47 5.47a.75.75 0 0 1 1.06 0L12 10.94l5.47-5.47a.75.75 0 1 1 1.06 1.06L13.06 12l5.47 5.47a.75.75 0 1 1-1.06 1.06L12 13.06l-5.47 5.47a.75.75 0 0 1-1.06-1.06L10.94 12 5.47 6.53a.75.75 0 0 1 0-1.06Z"/>
                </svg>
            </button>
        </div>
    </div>
@endif

@if(session('status_error'))
    <div
        x-data="{ show: true }"
        x-show="show"
        x-init="setTimeout(() => show = false, 5000)"
        x-transition:leave="transition ease-in duration-300"
        x-transition:leave-start="opacity-100 translate-y-0"
        x-transition:leave-end="opacity-0 -translate-y-2"
        class="mb-4 rounded-xl border border-red-400 bg-red-50 p-4 dark:border-red-500/30 dark:bg-red-900/20"
    >
        <div class="flex items-start gap-3">
            <div class="-mt-0.5 text-red-600">
                <svg class="fill-current" width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path fill-rule="evenodd" clip-rule="evenodd" d="M3.70186 12.0001C3.70186 7.41711 7.41711 3.70186 12.0001 3.70186C16.5831 3.70186 20.2984 7.41711 20.2984 12.0001C20.2984 16.5831 16.5831 20.2984 12.0001 20.2984C7.41711 20.2984 3.70186 16.5831 3.70186 12.0001ZM12.0001 1.90186C6.423 1.90186 1.90186 6.423 1.90186 12.0001C1.90186 17.5772 6.423 22.0984 12.0001 22.0984C17.5772 22.0984 22.0984 17.5772 22.0984 12.0001C22.0984 6.423 17.5772 1.90186 12.0001 1.90186ZM9.46672 9.46672C9.81819 9.11525 10.388 9.11525 10.7395 9.46672L12.0001 10.7273L13.2607 9.46672C13.6122 9.11525 14.182 9.11525 14.5335 9.46672C14.885 9.81819 14.885 10.388 14.5335 10.7395L13.2729 12.0001L14.5335 13.2607C14.885 13.6122 14.885 14.182 14.5335 14.5335C14.182 14.885 13.6122 14.885 13.2607 14.5335L12.0001 13.2729L10.7395 14.5335C10.388 14.885 9.81819 14.885 9.46672 14.5335C9.11525 14.182 9.11525 13.6122 9.46672 13.2607L10.7273 12.0001L9.46672 10.7395C9.11525 10.388 9.11525 9.81819 9.46672 9.46672Z" fill=""></path>
                </svg>
            </div>
            <div class="flex-1">
                <p class="text-sm text-red-800 dark:text-red-200">{{ session('status_error') }}</p>
            </div>
            <button @click="show = false" class="shrink-0 text-red-500 hover:text-red-700">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                    <path fill-rule="evenodd" clip-rule="evenodd" d="M5.47 5.47a.75.75 0 0 1 1.06 0L12 10.94l5.47-5.47a.75.75 0 1 1 1.06 1.06L13.06 12l5.47 5.47a.75.75 0 1 1-1.06 1.06L12 13.06l-5.47 5.47a.75.75 0 0 1-1.06-1.06L10.94 12 5.47 6.53a.75.75 0 0 1 0-1.06Z"/>
                </svg>
            </button>
        </div>
    </div>
@endif

@if(session('status_error'))
    <div
        x-data="{ show: true }"
        x-show="show"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="mb-4 rounded-xl border border-error-500 bg-error-50 p-4 dark:border-error-500/30 dark:bg-error-500/15"
    >
        <div class="flex items-start gap-3">
            <div class="-mt-0.5 text-error-500">
                <svg class="fill-current" width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path fill-rule="evenodd" clip-rule="evenodd" d="M3.70186 12.0001C3.70186 7.41711 7.41711 3.70186 12.0001 3.70186C16.5831 3.70186 20.2984 7.41711 20.2984 12.0001C20.2984 16.5831 16.5831 20.2984 12.0001 20.2984C7.41711 20.2984 3.70186 16.5831 3.70186 12.0001ZM12.0001 1.90186C6.423 1.90186 1.90186 6.423 1.90186 12.0001C1.90186 17.5772 6.423 22.0984 12.0001 22.0984C17.5772 22.0984 22.0984 17.5772 22.0984 12.0001C22.0984 6.423 17.5772 1.90186 12.0001 1.90186ZM9.46672 9.46672C9.81819 9.11525 10.388 9.11525 10.7395 9.46672L12.0001 10.7273L13.2607 9.46672C13.6122 9.11525 14.182 9.11525 14.5335 9.46672C14.885 9.81819 14.885 10.388 14.5335 10.7395L13.2729 12.0001L14.5335 13.2607C14.885 13.6122 14.885 14.182 14.5335 14.5335C14.182 14.885 13.6122 14.885 13.2607 14.5335L12.0001 13.2729L10.7395 14.5335C10.388 14.885 9.81819 14.885 9.46672 14.5335C9.11525 14.182 9.11525 13.6122 9.46672 13.2607L10.7273 12.0001L9.46672 10.7395C9.11525 10.388 9.11525 9.81819 9.46672 9.46672Z" fill=""></path>
                </svg>
            </div>
            <div class="flex-1">
                <p class="text-sm text-gray-700 dark:text-gray-300">{{ session('status_error') }}</p>
            </div>
            <button @click="show = false" class="shrink-0 text-error-500 hover:text-error-700 dark:text-error-400 dark:hover:text-error-300">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                    <path fill-rule="evenodd" clip-rule="evenodd" d="M5.47 5.47a.75.75 0 0 1 1.06 0L12 10.94l5.47-5.47a.75.75 0 1 1 1.06 1.06L13.06 12l5.47 5.47a.75.75 0 1 1-1.06 1.06L12 13.06l-5.47 5.47a.75.75 0 0 1-1.06-1.06L10.94 12 5.47 6.53a.75.75 0 0 1 0-1.06Z"/>
                </svg>
            </button>
        </div>
    </div>
@endif