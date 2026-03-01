@props(['type' => 'success', 'title' => null, 'message' => '', 'showButton' => false, 'buttonText' => 'View more', 'buttonUrl' => null, 'id' => null])

@php
    $id = $id ?? 'toast-'.(string)\Illuminate\Support\Str::random(8);
    switch($type) {
        case 'error':
            $border = 'border-error-400';
            $icon = 'text-red-600';
            $titleColor = 'text-red-700';
            $bgClass = 'bg-error-500';
            $messageColor = 'text-red-900 dark:text-red-200';
            break;
        case 'warning':
            $border = 'border-yellow-400';
            $icon = 'text-amber-600';
            $titleColor = 'text-amber-700';
            $bgClass = 'bg-yellow-400';
            $messageColor = 'text-amber-900 dark:text-amber-200';
            break;
        case 'info':
            $border = 'border-blue-400';
            $icon = 'text-blue-600';
            $titleColor = 'text-blue-700';
            $bgClass = 'bg-blue-50';
            $messageColor = 'text-blue-900 dark:text-blue-200';
            break;
        default:
            $border = 'border-emerald-400';
            $icon = 'text-emerald-600';
            $titleColor = 'text-emerald-700';
            $bgClass = 'bg-emerald-50';
            $messageColor = 'text-emerald-900 dark:text-emerald-200';
            break;
    }
    $title = $title ?? ucfirst($type);
@endphp
<div id="{{ $id }}" role="alert" aria-live="polite" class="p-5 mb-4 text-base rounded-lg {{ $bgClass }} border {{ $border }} opacity-0 translate-y-2 transition duration-300 ease-in-out max-w-md w-full">
    <div class="flex items-center justify-between">
        <div class="flex items-center">
            @if($type === 'success' || $type === 'error' || $type === 'warning' || $type === 'info')
                <svg class="w-5 h-5 shrink-0 me-3 {{ $icon }}" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"><path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 11h2v5m-2 0h4m-2.592-8.5h.01M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/></svg>
            @endif
            <span class="sr-only">{{ ucfirst($type) }}</span>
            <h3 class="font-medium {{ $titleColor ?? '' }} text-base">{{ $title }}</h3>
        </div>
        <button type="button" aria-label="Close" onclick="closeToast('{{ $id }}')" class="ms-auto -mx-1.5 -my-1.5 rounded focus:ring-2 hover:bg-opacity-10 inline-flex items-center justify-center h-8 w-8 shrink-0">
            <span class="sr-only">Close</span>
            <svg class="w-4 h-4" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"><path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L17.94 6M18 18L6.06 6"/></svg>
        </button>
    </div>
    <div class="mt-3 mb-4">
        <p class="text-base {{ $messageColor ?? '' }}">{{ $message }}</p>
    </div>
    @if(filter_var($showButton, FILTER_VALIDATE_BOOLEAN) && $buttonUrl)
        <button type="button" class="inline-flex items-center text-emerald-900 bg-emerald-200 box-border border border-transparent hover:bg-emerald-300 focus:ring-4 focus:ring-emerald-300 shadow-xs font-medium leading-5 rounded-md text-sm px-4 py-2 focus:outline-none">
            <svg class="w-3.5 h-3.5 me-1.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"><path stroke="currentColor" stroke-width="2" d="M21 12c0 1.2-4.03 6-9 6s-9-4.8-9-6c0-1.2 4.03-6 9-6s9 4.8 9 6Z"/><path stroke="currentColor" stroke-width="2" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"/></svg>
            {{ $buttonText }}
        </button>
    @endif
</div>

<script>
    // Mount toast after DOM is ready to avoid interference from other scripts
    (function(){
        const mount = ()=>{
            const id = '{{ $id }}';
            console.log('toastr:init', id);
            const containerId = 'toastr-container';
            let container = document.getElementById(containerId);
            if(!container){
                container = document.createElement('div');
                container.id = containerId;
                container.style.position = 'fixed';
                container.style.right = '1.25rem';
                container.style.top = '3rem';
                container.style.zIndex = 99999;
                container.style.display = 'flex';
                container.style.flexDirection = 'column';
                container.style.alignItems = 'flex-end';
                container.style.gap = '0.5rem';
                document.body.appendChild(container);
            }

            const el = document.getElementById(id);
            if(!el){ console.warn('toastr: element not found', id); return; }
            if(el.dataset.moved) return;

            container.appendChild(el);
            el.dataset.moved = '1';
            el.dataset.createdAt = String(Date.now());
            console.log('toastr: appended', id);

            // animate in
            requestAnimationFrame(()=>{
                el.classList.remove('opacity-0','translate-y-2');
                el.classList.add('opacity-100','translate-y-0');
            });

            // auto-dismiss after 5s (store as property to avoid dataset string coercion)
            el._toastTimeout = setTimeout(()=> closeToast(id), 5000);
        };

        if(document.readyState === 'loading'){
            document.addEventListener('DOMContentLoaded', mount);
        } else {
            mount();
        }
    })();

    function closeToast(id){
        const el = document.getElementById(id);
        if(!el) return;
        // guard to ignore accidental immediate closes caused by other scripts
        const created = parseInt(el.dataset.createdAt || '0', 10);
        if(created && (Date.now() - created) < 250){
            console.log('toastr: close ignored (too new)', id);
            return;
        }
        console.log('toastr: closing', id);
        // clear auto-dismiss
        if(el._toastTimeout) clearTimeout(el._toastTimeout);
        el.classList.remove('opacity-100','translate-y-0');
        el.classList.add('opacity-0','translate-y-2');
        // helpful trace for debugging where close was invoked from
        console.trace();
        setTimeout(()=>{ if(el.parentNode) el.parentNode.removeChild(el); }, 350);
    }
</script>
