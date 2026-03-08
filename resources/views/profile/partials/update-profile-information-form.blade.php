<section class="rounded-2xl border border-gray-200 bg-gray-50/70 p-6 dark:border-gray-800 dark:bg-gray-900/40">
    <header>
        <h2 class="text-lg font-medium text-gray-900 dark:text-white/90">
            {{ __('messages.profile_information') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600 dark:text-white/90">
            {{ __('messages.profile_information_description') }}
        </p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="mt-6 space-y-6">
        @csrf
        @method('patch')

        <div>
            <label for="name" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-white/90">
                {{ __('messages.name') }} <span class="text-error-500">*</span>
            </label>
            <input
                id="name"
                name="name"
                type="text"
                value="{{ old('name', $user->name) }}"
                required
                autofocus
                autocomplete="name"
                class="shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-white/90 focus:outline-hidden focus:ring-3 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 {{ $errors->has('name') ? 'border-error-400 dark:border-error-500' : 'border-gray-300 dark:border-gray-700' }}"
            >
            @error('name')
                <p class="mt-1.5 text-xs text-error-500">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="email" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-white/90">
                {{ __('messages.email') }} <span class="text-error-500">*</span>
            </label>
            <input
                id="email"
                name="email"
                type="email"
                value="{{ old('email', $user->email) }}"
                required
                autocomplete="username"
                class="shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-white/90 focus:outline-hidden focus:ring-3 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 {{ $errors->has('email') ? 'border-error-400 dark:border-error-500' : 'border-gray-300 dark:border-gray-700' }}"
            >
            @error('email')
                <p class="mt-1.5 text-xs text-error-500">{{ $message }}</p>
            @enderror

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <div>
                    <p class="text-sm mt-2 text-gray-800 dark:text-gray-200">
                        {{ __('messages.profile_email_unverified') }}

                        <button form="send-verification" class="ml-1 underline text-sm text-gray-600 dark:text-white/90 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800">
                            {{ __('messages.profile_resend_verification') }}
                        </button>
                    </p>

                    @if (session('status') === 'verification-link-sent')
                        <p class="mt-2 font-medium text-sm text-green-600 dark:text-green-400">
                            {{ __('messages.profile_verification_link_sent') }}
                        </p>
                    @endif
                </div>
            @endif
        </div>

        <div class="flex items-center gap-4">
            <button
                type="submit"
                class="inline-flex items-center gap-2 rounded-lg bg-brand-500 px-5 py-2.5 text-sm font-medium text-white transition-colors hover:bg-brand-600 focus:outline-none focus:ring-3 focus:ring-brand-500/20"
            >
                {{ __('messages.profile_save_changes') }}
            </button>

            @if (session('status') === 'profile-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-gray-600 dark:text-white/90"
                >{{ __('messages.saved') }}</p>
            @endif
        </div>
    </form>
</section>
