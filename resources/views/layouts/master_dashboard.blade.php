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

                @include('partials.alerts')

                <div class="grid grid-cols-12 gap-4 md:gap-6">

                    @yield('content')

                </div>
            </div>
        </main>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.datatables.net/2.2.2/js/dataTables.min.js"></script>
<script>
    window.AppLang = {
        datatable: {
            sProcessing:   @json(__('messages.dt_processing')),
            sLengthMenu:   @json(__('messages.dt_length_menu')),
            sZeroRecords:  @json(__('messages.dt_zero_records')),
            sInfo:         @json(__('messages.dt_info')),
            sInfoEmpty:    @json(__('messages.dt_info_empty')),
            sInfoFiltered: @json(__('messages.dt_info_filtered')),
            sSearch:       @json(__('messages.dt_search')),
            oPaginate: {
                sFirst:    @json(__('messages.dt_paginate_first')),
                sPrevious: @json(__('messages.dt_paginate_previous')),
                sNext:     @json(__('messages.dt_paginate_next')),
                sLast:     @json(__('messages.dt_paginate_last')),
            },
            oAria: {
                sSortAscending:  @json(__('messages.dt_aria_sort_asc')),
                sSortDescending: @json(__('messages.dt_aria_sort_desc')),
            },
        },
    };
</script>
<script defer src="{{asset('js/bundle.js')}}"></script>
@vite('resources/js/global-admin.js')
@stack('scripts')



</body>
</html>
