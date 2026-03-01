<link rel="icon" href="favicon.ico"><link href="{{asset('css/style.css')}}" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.datatables.net/2.2.2/css/dataTables.dataTables.min.css">
<style>
    .dark .dt-container,
    .dark .dt-container .dt-search label,
    .dark .dt-container .dt-length label,
    .dark .dt-container .dt-info,
    .dark .dt-container .dt-paging .dt-paging-button {
        color: #d1d5db !important;
    }
    .dark .dt-container .dt-paging .dt-paging-button.disabled {
        color: #4b5563 !important;
    }
    .dark .dt-container .dt-paging .dt-paging-button.current {
        color: #ffffff !important;
        background: #4f46e5 !important;
        border-color: #4f46e5 !important;
    }
    .dark .dt-container .dt-paging .dt-paging-button:hover:not(.disabled):not(.current) {
        background: #374151 !important;
        border-color: #374151 !important;
        color: #f3f4f6 !important;
    }
    .dark .dt-container .dt-input {
        background-color: #1f2937 !important;
        border-color: #374151 !important;
        color: #f3f4f6 !important;
    }
    .dark table.dataTable thead th,
    .dark table.dataTable thead td {
        color: #f3f4f6 !important;
        border-bottom-color: #374151 !important;
    }
    .dark table.dataTable tbody td {
        color: #d1d5db !important;
    }
    .dark table.dataTable tbody tr {
        background-color: transparent !important;
    }
    .dark table.dataTable > tbody > tr.odd > * {
        background-color: rgba(255,255,255,0.02) !important;
        box-shadow: none !important;
    }
    .dark table.dataTable > tbody > tr.even > * {
        background-color: transparent !important;
        box-shadow: none !important;
    }
</style>
@stack('styles')
