<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    @include('partials.meta')
    @include('partials.stylesheets')
</head>
<body
    x-data="{ page: 'ecommerce', 'loaded': true, 'darkMode': false, 'stickyMenu': false, 'sidebarToggle': false, 'scrollTop': false }"
    x-init="
         darkMode = JSON.parse(localStorage.getItem('darkMode'));
         $watch('darkMode', value => localStorage.setItem('darkMode', JSON.stringify(value)))"
    :class="{'dark bg-gray-900': darkMode === true}"
>
<!-- ===== Preloader Start ===== -->
<div
    x-show="loaded"
    x-init="window.addEventListener('DOMContentLoaded', () => {setTimeout(() => loaded = false, 500)})"
    class="fixed left-0 top-0 z-999999 flex h-screen w-screen items-center justify-center bg-white dark:bg-black"
>
    <div
        class="h-16 w-16 animate-spin rounded-full border-4 border-solid border-brand-500 border-t-transparent"
    ></div>
</div>

<!-- ===== Preloader End ===== -->

<!-- ===== Page Wrapper Start ===== -->
<div class="flex h-screen overflow-hidden">

    @include('sections.sidebar')

    <!-- ===== Content Area Start ===== -->
    <div
        class="relative flex flex-col flex-1 overflow-x-hidden overflow-y-auto"
    >
        <!-- Small Device Overlay Start -->
        <div
            @click="sidebarToggle = false"
            :class="sidebarToggle ? 'block lg:hidden' : 'hidden'"
            class="fixed w-full h-screen z-9 bg-gray-900/50"
        ></div>
        <!-- Small Device Overlay End -->

        @include('sections.header')

        <main>
            <div class="p-4 mx-auto max-w-(--breakpoint-2xl) md:p-6">
                <div class="grid grid-cols-12 gap-4 md:gap-6">

                    @yield('content')

                </div>
            </div>
        </main>
    </div>
</div>

<script defer src="{{asset('js/bundle.js')}}"></script>

@if(session('toast'))
    <x-toastr :type="session('toast.type')" :message="session('toast.message')" :title="session('toast.title')" :showButton="session('toast.showButton')" :buttonText="session('toast.buttonText')" :buttonUrl="session('toast.buttonUrl')" />
@endif

@if(session('toasts'))
    @foreach(session('toasts') as $t)
        <x-toastr :type="$t['type'] ?? 'info'" :message="$t['message'] ?? ''" :title="$t['title'] ?? null" :showButton="$t['showButton'] ?? false" :buttonText="$t['buttonText'] ?? 'View more'" :buttonUrl="$t['buttonUrl'] ?? null" />
    @endforeach
@endif

</body>
</html>
