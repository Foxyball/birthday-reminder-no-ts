<section class="rounded-2xl border border-red-200 bg-red-50/70 p-6 dark:border-red-500/20 dark:bg-red-900/10">
    <header>
        <h2 class="text-lg font-medium text-red-700 dark:text-white/90">
            {{ __('messages.delete_account') }}
        </h2>

        <p class="mt-1 text-sm text-red-700/80 dark:text-white/90">
            {{ __('messages.delete_account_description') }}
        </p>
    </header>

    <form method="post" action="{{ route('profile.destroy') }}" class="mt-6 space-y-6">
        @csrf
        @method('delete')

        <div>
            <label for="password" class="mb-1.5 block text-sm font-medium text-red-700 dark:text-white/90">
                {{ __('messages.current_password') }}
            </label>
            <input
                id="password"
                name="password"
                type="password"
                autocomplete="current-password"
                class="shadow-theme-xs h-11 w-full rounded-lg border border-red-300 bg-white px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:border-red-400 focus:outline-hidden focus:ring-3 focus:ring-red-500/10 dark:border-red-500/30 dark:bg-gray-900 dark:text-white/90"
                placeholder="{{ __('messages.current_password_placeholder') }}"
            >
            @if ($errors->userDeletion->has('password'))
                <p class="mt-1.5 text-xs text-error-500">{{ $errors->userDeletion->first('password') }}</p>
            @endif
        </div>

        <button
            type="submit"
            class="inline-flex items-center gap-2 rounded-lg px-5 py-2.5 text-sm font-medium transition-colors focus:outline-none focus:ring-3 focus:ring-red-500/20"
            style="background-color: #dc2626; color: #ffffff; border: 1px solid #dc2626;"
        >
            {{ __('messages.delete_account') }}
        </button>
    </form>
</section>
