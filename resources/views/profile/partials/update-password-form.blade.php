<section class="rounded-2xl border border-gray-200 bg-gray-50/70 p-6 dark:border-gray-800 dark:bg-gray-900/40">
    <header>
        <h2 class="text-lg font-medium text-gray-900 dark:text-white/90">
            {{ __('messages.update_password') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
            {{ $user->password ? __('messages.update_password_description') : __('messages.update_password_social_description') }}
        </p>
    </header>

    <form method="post" action="{{ route('password.update') }}" class="mt-6 space-y-6">
        @csrf
        @method('put')

        @if ($user->password)
            <div>
                <label for="update_password_current_password" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                    {{ __('messages.current_password') }}
                </label>
                <input
                    id="update_password_current_password"
                    name="current_password"
                    type="password"
                    autocomplete="current-password"
                    class="shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:outline-hidden focus:ring-3 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 {{ $errors->updatePassword->has('current_password') ? 'border-error-400 dark:border-error-500' : 'border-gray-300 dark:border-gray-700' }}"
                >
                @if ($errors->updatePassword->has('current_password'))
                    <p class="mt-1.5 text-xs text-error-500">{{ $errors->updatePassword->first('current_password') }}</p>
                @endif
            </div>
        @endif

        <div>
            <label for="update_password_password" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                {{ __('messages.new_password') }}
            </label>
            <input
                id="update_password_password"
                name="password"
                type="password"
                autocomplete="new-password"
                class="shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:outline-hidden focus:ring-3 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 {{ $errors->updatePassword->has('password') ? 'border-error-400 dark:border-error-500' : 'border-gray-300 dark:border-gray-700' }}"
            >
            @if ($errors->updatePassword->has('password'))
                <p class="mt-1.5 text-xs text-error-500">{{ $errors->updatePassword->first('password') }}</p>
            @endif
        </div>

        <div>
            <label for="update_password_password_confirmation" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                {{ __('messages.confirm_password') }}
            </label>
            <input
                id="update_password_password_confirmation"
                name="password_confirmation"
                type="password"
                autocomplete="new-password"
                class="shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:outline-hidden focus:ring-3 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 {{ $errors->updatePassword->has('password_confirmation') ? 'border-error-400 dark:border-error-500' : 'border-gray-300 dark:border-gray-700' }}"
            >
            @if ($errors->updatePassword->has('password_confirmation'))
                <p class="mt-1.5 text-xs text-error-500">{{ $errors->updatePassword->first('password_confirmation') }}</p>
            @endif
        </div>

        <div class="flex items-center gap-4">
            <button
                type="submit"
                class="inline-flex items-center gap-2 rounded-lg bg-brand-500 px-5 py-2.5 text-sm font-medium text-white transition-colors hover:bg-brand-600 focus:outline-none focus:ring-3 focus:ring-brand-500/20"
            >
                {{ __('messages.update_password_action') }}
            </button>

            @if (session('status') === 'password-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-gray-600 dark:text-gray-400"
                >{{ __('messages.saved') }}</p>
            @endif
        </div>
    </form>
</section>
