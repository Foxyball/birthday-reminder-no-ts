import Swal from "sweetalert2";

window.Swal = Swal;

const TOAST_CONFIGS = {
    success: {
        bg: "#f0fdf4",
        border: "1px solid #4ade80",
        iconColor: "#16a34a",
        titleColor: "#15803d",
        messageColor: "#14532d",
    },
    error: {
        bg: "#fef2f2",
        border: "1px solid #f87171",
        iconColor: "#dc2626",
        titleColor: "#b91c1c",
        messageColor: "#7f1d1d",
    },
    warning: {
        bg: "#fffbeb",
        border: "1px solid #fbbf24",
        iconColor: "#d97706",
        titleColor: "#b45309",
        messageColor: "#78350f",
    },
    info: {
        bg: "#eff6ff",
        border: "1px solid #60a5fa",
        iconColor: "#2563eb",
        titleColor: "#1d4ed8",
        messageColor: "#1e3a8a",
    },
};

window.closeToast = function (id) {
    const el = document.getElementById(id);
    if (!el) return;
    if (el._toastTimeout) clearTimeout(el._toastTimeout);
    el.style.opacity = "0";
    el.style.transform = "translateY(-0.5rem)";
    setTimeout(() => { if (el.parentNode) el.parentNode.removeChild(el); }, 350);
};

window.showToast = function (type, message, title) {
    const cfg = TOAST_CONFIGS[type] || TOAST_CONFIGS.success;
    const resolvedTitle = title || (type.charAt(0).toUpperCase() + type.slice(1));
    const id = "toast-" + Math.random().toString(36).slice(2, 10);

    const el = document.createElement("div");
    el.id = id;
    el.setAttribute("role", "alert");
    el.style.cssText = [
        "padding:1.25rem",
        "margin-bottom:1rem",
        "border-radius:0.5rem",
        `background:${cfg.bg}`,
        `border:${cfg.border}`,
        "max-width:24rem",
        "width:100%",
        "opacity:0",
        "transform:translateY(0.5rem)",
        "transition:opacity 300ms ease, transform 300ms ease",
    ].join(";");

    el.innerHTML = `
        <div style="display:flex;align-items:center;justify-content:space-between">
            <div style="display:flex;align-items:center;gap:0.5rem">
                <svg style="width:1.25rem;height:1.25rem;flex-shrink:0;color:${cfg.iconColor}" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 11h2v5m-2 0h4m-2.592-8.5h.01M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
                </svg>
                <span style="font-weight:600;font-size:0.875rem;color:${cfg.titleColor}">${resolvedTitle}</span>
            </div>
            <button type="button" onclick="closeToast('${id}')" style="background:none;border:none;cursor:pointer;padding:0.25rem;margin-left:0.75rem;color:${cfg.iconColor};line-height:1">
                <svg style="width:1rem;height:1rem" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L17.94 6M18 18L6.06 6"/>
                </svg>
            </button>
        </div>
        <p style="margin-top:0.5rem;font-size:0.875rem;color:${cfg.messageColor}">${message}</p>`;

    const containerId = "toastr-container";
    let container = document.getElementById(containerId);
    if (!container) {
        container = document.createElement("div");
        container.id = containerId;
        container.style.cssText =
            "position:fixed;right:1.25rem;top:3rem;z-index:99999;display:flex;flex-direction:column;align-items:flex-end;gap:0.5rem;";
        document.body.appendChild(container);
    }

    container.appendChild(el);

    requestAnimationFrame(() => {
        el.style.opacity = "1";
        el.style.transform = "translateY(0)";
    });

    el._toastTimeout = setTimeout(() => window.closeToast(id), 5000);
};

function setupAjaxCsrf() {
    if (
        window.jQuery &&
        document.querySelector('meta[name="csrf-token"]')
    ) {
        $.ajaxSetup({
            headers: {
                "X-CSRF-TOKEN": document
                    .querySelector('meta[name="csrf-token"]')
                    .getAttribute("content"),
            },
        });
    }
}

function handleDeleteDelegation() {
    document.body.addEventListener(
        "click",
        function (e) {
            var btn = e.target.closest && e.target.closest(".delete-item");
            if (!btn) return;
            e.preventDefault();

            var deleteUrl = btn.getAttribute("href");
            var t = (window.AppLang && window.AppLang.swal) || {};

            Swal.fire({
                title: t.delete_title || "Are you sure?",
                text: t.delete_text || "You will not be able to undo this action!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: t.confirm_btn || "Yes, delete it!",
                cancelButtonText: t.cancel_btn || "Cancel",
            }).then(function (result) {
                if (!result.isConfirmed) return;

                var csrf = document.querySelector('meta[name="csrf-token"]')
                    ? document
                          .querySelector('meta[name="csrf-token"]')
                          .getAttribute("content")
                    : null;

                var ajaxPromise;
                if (window.jQuery) {
                    ajaxPromise = $.ajax({
                        type: "DELETE",
                        url: deleteUrl,
                        headers: { "X-CSRF-TOKEN": csrf },
                    });
                } else {
                    ajaxPromise = fetch(deleteUrl, {
                        method: "DELETE",
                        headers: { "X-CSRF-TOKEN": csrf },
                    }).then(function (r) {
                        return r.json();
                    });
                }

                ajaxPromise
                    .then(function (data) {
                        if (data && data.status === "success") {
                            Swal.fire({
                                title: t.deleted_title || "Deleted!",
                                text: data.message,
                                icon: "success",
                                timer: 1500,
                                showConfirmButton: false,
                            });

                            try {
                                if (
                                    window.jQuery &&
                                    $.fn.dataTable !== undefined &&
                                    $.fn.dataTable.isDataTable(".dataTable")
                                ) {
                                    $(".dataTable")
                                        .DataTable()
                                        .ajax.reload(null, false);
                                } else {
                                    window.location.reload();
                                }
                            } catch (err) {
                                window.location.reload();
                            }
                        } else if (data && data.status === "error") {
                            Swal.fire(t.cant_delete || "Can't Delete", data.message, "error");
                        } else {
                            Swal.fire(t.error_title || "Error", t.unexpected || "Unexpected response from server", "error");
                        }
                    })
                    .catch(function (err) {
                        console.error(err);
                        Swal.fire(t.error_title || "Error", t.ajax_error || "An unexpected error occurred", "error");
                    });
            });
        },
        false,
    );
}

function handleDataTableAlpineReinit() {
    if (!window.jQuery) return;

    $(document).on("draw.dt", ".dataTable", function () {
        if (window.Alpine) {
            Alpine.initTree(this);
        }
    });
}

function handleStatusToggle() {
    if (!window.jQuery) return;

    $("body").on("change", ".change-status", function () {
        const $checkbox = $(this);
        const isChecked = $checkbox.is(":checked");
        const id = $checkbox.data("id");
        const url = $checkbox.closest("[data-status-url]").data("status-url");

        if (!url) return;

        $.ajax({
            url: url,
            method: "PUT",
            data: { status: isChecked, id: id },
            success: function (data) {
                showToast("success", data.message);
            },
            error: function (xhr) {
                showToast("error", xhr.status + ": " + xhr.statusText);

                // Revert checkbox to previous state
                $checkbox.prop("checked", !isChecked);

                // Revert Alpine switcherToggle to stay in sync with the checkbox
                const alpineEl = $checkbox.closest("[x-data]")[0];
                if (alpineEl && window.Alpine) {
                    Alpine.$data(alpineEl).switcherToggle = !isChecked;
                }
            },
        });
    });
}

document.addEventListener("DOMContentLoaded", function () {
    setupAjaxCsrf();
    handleDeleteDelegation();
    handleDataTableAlpineReinit();

    if (window.jQuery && typeof $.fn.dataTable !== 'undefined' && window.AppLang?.datatable) {
        $.extend(true, $.fn.dataTable.defaults, { language: window.AppLang.datatable });
    }
});

if (window.jQuery) {
    $(function () {
        handleStatusToggle();
    });
} else {
    document.addEventListener("DOMContentLoaded", handleStatusToggle);
}
