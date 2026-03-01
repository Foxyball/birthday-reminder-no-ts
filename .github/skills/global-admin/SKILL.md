---
name: global-admin
description: All admin-side AJAX interactions (delete, status toggle, DataTable Alpine reinit, toasts) are centralised in resources/js/global-admin.js. Do NOT write inline AJAX in Blade views — hook into the existing handlers instead.
---

## File: `resources/js/global-admin.js`

This file is loaded on every admin page via `@vite('resources/js/global-admin.js')` in `layouts/master_dashboard.blade.php`. It wires up four reusable behaviours automatically on `DOMContentLoaded`.

---

## 1. Toast notifications — `showToast(type, message, title?)`

Call from any JS (inline or AJAX callbacks). Types: `success`, `error`, `warning`, `info`.

```js
showToast('success', 'Category saved.');
showToast('error', 'Something went wrong.');
```

---

## 2. Delete — `.delete-item` class

Add class `delete-item` to any delete anchor. The `href` must be the destroy URL. The handler fires a SweetAlert2 confirm, then a DELETE AJAX call and expects JSON:

```json
{ "status": "success"|"error", "message": "..." }
```

On success it reloads the DataTable (or falls back to `location.reload()`).

```php
// Controller
public function destroy(string $id)
{
    $record = Model::findOrFail($id);
    $record->delete();
    return response(['status' => 'success', 'message' => __(self::DELETE_MESSAGE)]);
}
```

```php
// DataTable action column
$delete = '<a href="' . route('resource.destroy', $row->id) . '"
    class="... delete-item">' . __('messages.delete') . '</a>';
```

---

## 3. Status toggle — `.change-status` class + `data-status-url`

Place `data-status-url` on the wrapping `<table>` element (passed via `$dataTable->table([...])`). The checkbox must have class `change-status` and `data-id`. Sends a PUT with `{ status: bool, id: int }` and expects:

```json
{ "status": "success", "message": "..." }
```

```php
// index.blade.php
{{ $dataTable->table([
    'class'           => 'w-full ...',
    'data-status-url' => route('admin.category.change-status'),
]) }}
```

```php
// Controller
public function changeStatus(Request $request)
{
    $record = Model::findOrFail($request->id);
    $record->status = $request->status == 'true' ? 1 : 0;
    $record->save();
    return response(['status' => 'success', 'message' => __(self::STATUS_UPDATE_MESSAGE)]);
}
```

```php
// DataTable status column (Alpine x-data toggle)
return '<div x-data="{ switcherToggle: ' . ($row->status ? 'true' : 'false') . ' }">
    <label class="flex cursor-pointer select-none items-center">
        <div class="relative">
            <input type="checkbox" ' . ($row->status ? 'checked' : '') . '
                data-id="' . $row->id . '" class="sr-only change-status"
                @change="switcherToggle = !switcherToggle">
            <div class="block h-6 w-11 rounded-full transition-colors"
                :class="switcherToggle ? \'bg-brand-500\' : \'bg-gray-200 dark:bg-white/10\'"></div>
            <div class="shadow-theme-sm absolute top-0.5 left-0.5 h-5 w-5 rounded-full bg-white duration-300 ease-linear"
                :class="switcherToggle ? \'translate-x-full\' : \'translate-x-0\'"></div>
        </div>
    </label>
</div>';
```

---

## 4. Alpine reinit after DataTable draw — automatic

`handleDataTableAlpineReinit()` listens for `draw.dt` on any `.dataTable` and calls `Alpine.initTree()` on the table element. This is required because Alpine doesn't observe DataTable-injected rows.

**You do not need to add this manually** — it runs globally. Do not add per-page `draw.dt` handlers for Alpine reinit.

---

## Rule: no inline AJAX in Blade views

All AJAX patterns above are already handled globally. The only JS that belongs in a view's `@push('scripts')` is DataTable initialisation:

```blade
@push('scripts')
    {{ $dataTable->scripts(attributes: ['type' => 'module']) }}
@endpush
```
