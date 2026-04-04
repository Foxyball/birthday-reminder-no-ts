<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8"/>
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0"/>
    <meta http-equiv="X-UA-Compatible" content="ie=edge"/>
    <title>Forgot Password - Birthday Reminder</title>
    <link rel="icon" href="{{ asset('favicon.ico') }}">
    <link href="{{ asset('css/style.css') }}" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Fira+Sans:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Fira Sans', Arial, sans-serif; }
    </style>
</head>
<body
    x-data="{ page: 'comingSoon', 'loaded': true, 'darkMode': false, 'stickyMenu': false, 'sidebarToggle': false, 'scrollTop': false }"
    x-init="darkMode = JSON.parse(localStorage.getItem('darkMode')); $watch('darkMode', value => localStorage.setItem('darkMode', JSON.stringify(value)))"
    :class="{'dark bg-gray-900': darkMode === true}"
>
<!-- ===== Preloader Start ===== -->
<div x-show="loaded" x-init="window.addEventListener('DOMContentLoaded', () => {setTimeout(() => loaded = false, 500)})"
     class="fixed left-0 top-0 z-999999 flex h-screen w-screen items-center justify-center bg-white dark:bg-black">
    <div class="h-16 w-16 animate-spin rounded-full border-4 border-solid border-brand-500 border-t-transparent"></div>
</div>
<!-- ===== Preloader End ===== -->
<div class="relative p-6 bg-white z-1 dark:bg-gray-900 sm:p-0">
    <div class="relative flex flex-col justify-center w-full h-screen dark:bg-gray-900 sm:p-0 lg:flex-row">
        <!-- Form -->
        <div class="flex flex-col flex-1 w-full lg:w-1/2">
            <div class="flex flex-col justify-center flex-1 w-full max-w-md mx-auto">
                <div>
                    <div class="mb-5 sm:mb-8">
                        <h1 class="mb-2 font-semibold text-gray-800 text-title-sm dark:text-white/90 sm:text-title-md">
                            {{ __('Forgot Password') }}
                        </h1>
                        <p class="text-sm text-gray-500 dark:text-gray-400">
                            {{ __('Forgot your password? No problem. Just let us know your email address and we will email you a password reset link that will allow you to choose a new one.') }}
                        </p>
                    </div>

                    <!-- Session Status -->
                    @if (session('status'))
                        <div class="mb-6 rounded-lg bg-success-50 border border-success-200 p-4 dark:bg-success-900/20 dark:border-success-800">
                            <p class="text-sm text-success-800 dark:text-success-200">{{ session('status') }}</p>
                        </div>
                    @endif

                    <!-- Form -->
                    <form method="POST" action="{{ route('password.email') }}">
                        @csrf
                        <div class="space-y-5">
                            <!-- Email Address -->
                            <div>
                                <label for="email" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                                    {{ __('Email') }}<span class="text-error-500">*</span>
                                </label>
                                <input
                                    type="email"
                                    id="email"
                                    name="email"
                                    placeholder="you@example.com"
                                    value="{{ old('email') }}"
                                    required
                                    autofocus
                                    autocomplete="email"
                                    class="h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 shadow-theme-xs placeholder:text-gray-400 focus:border-brand-300 focus:outline-hidden focus:ring-3 focus:ring-brand-500/10 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 dark:focus:border-brand-800"
                                />
                                @error('email')
                                <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Submit Button -->
                            <button
                                type="submit"
                                class="w-full h-11 rounded-lg bg-brand-500 font-medium text-white shadow-theme-xs hover:bg-brand-600 focus:outline-hidden focus:ring-3 focus:ring-brand-500/30 dark:bg-brand-600 dark:hover:bg-brand-700 dark:focus:ring-brand-400/30 transition-colors"
                            >
                                {{ __('Email Password Reset Link') }}
                            </button>

                            <!-- Back to Login -->
                            <div class="text-center">
                                <p class="text-sm text-gray-600 dark:text-gray-400">
                                    {{ __('Remember your password?') }}
                                    <a href="{{ route('login') }}" class="font-medium text-brand-500 hover:text-brand-600 dark:text-brand-400 dark:hover:text-brand-300 transition-colors">
                                        {{ __('Sign In') }}
                                    </a>
                                </p>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
</body>
</html>
