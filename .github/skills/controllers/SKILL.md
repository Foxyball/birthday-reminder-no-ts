---
name: controllers
description: How to create and structure resource controllers in this Laravel project, including DataTable injection, route-model binding, flash messages, status toggling, and AJAX delete responses.
---

## Structure

Every resource controller follows this exact pattern. Use `CategoryController` as the canonical reference.

```php
class CategoryController extends Controller
{
    // 1. Declare all translation key constants up top
    const SUCCESS_MESSAGE        = 'messages.category_success_message';
    const UPDATE_MESSAGE         = 'messages.category_update_message';
    const DELETE_MESSAGE         = 'messages.category_delete_message';
    const STATUS_UPDATE_MESSAGE  = 'messages.category_status_update_message';
    const RELATION_ERROR_MESSAGE = 'messages.category_relation_error_message';

    // 2. Inject the DataTable into index()
    public function index(CategoryDataTable $dataTable)
    {
        return $dataTable->render('category.index');
    }

    // 3. store() and update() share one FormRequest; always append slug
    public function store(StoreCategoryRequest $request)
    {
        $data = $request->validated();
        $data['slug'] = Str::slug($data['name']);
        Category::create($data);
        return redirect()->route('category.index')->with('status', __(self::SUCCESS_MESSAGE));
    }

    // 4. edit() and update() use route-model binding (type-hint the Model)
    public function edit(Category $category)
    {
        return view('category.edit', compact('category'));
    }

    public function update(StoreCategoryRequest $request, Category $category)
    {
        $data = $request->validated();
        $data['slug'] = Str::slug($data['name']);
        $category->update($data);
        return redirect()->route('category.index')->with('status', __(self::UPDATE_MESSAGE));
    }

    // 5. destroy() returns JSON — consumed by the global AJAX delete handler
    public function destroy(string $id)
    {
        $category = Category::findOrFail($id);
        $category->delete();
        return response(['status' => 'success', 'message' => __(self::DELETE_MESSAGE)]);
    }

    // 6. changeStatus() — always a separate PUT route registered BEFORE Route::resource
    public function changeStatus(Request $request)
    {
        $category = Category::findOrFail($request->id);
        $category->status = $request->status == 'true' ? 1 : 0;
        $category->save();
        return response(['status' => 'success', 'message' => __(self::STATUS_UPDATE_MESSAGE)]);
    }
}
```

## Routes

Register `change-status` **before** `Route::resource` or Laravel will shadow it:

```php
Route::middleware(['auth', 'admin'])->group(function () {
    Route::put('/bgc/category/change-status', [CategoryController::class, 'changeStatus'])
        ->name('admin.category.change-status');
    Route::resource('/bgc/category', CategoryController::class);
});
```

## FormRequest

One `Store{Name}Request` is reused for both store and update. Generate with:
```bash
php artisan make:request StoreCategoryRequest
```

## Checklist for a new resource

1. `php artisan make:controller {Name}Controller -r`
2. `php artisan make:request Store{Name}Request`
3. Create `app/DataTables/{Name}DataTable.php` (copy `CategoryDataTable` as template)
4. Add routes to `routes/web.php` (change-status PUT before resource)
5. Create `resources/views/{name}/index.blade.php`, `create.blade.php`, `edit.blade.php`
6. Add translation keys to both `resources/lang/en/messages.php` and `resources/lang/bg/messages.php`
7. Run `vendor/bin/pint app/Http/Controllers/{Name}Controller.php`
