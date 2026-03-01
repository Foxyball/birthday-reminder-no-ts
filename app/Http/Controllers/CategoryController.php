<?php

namespace App\Http\Controllers;

use App\DataTables\CategoryDataTable;
use App\Http\Requests\StoreCategoryRequest;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    const SUCCESS_MESSAGE = 'messages.category_success_message';

    const UPDATE_MESSAGE = 'messages.category_update_message';

    const DELETE_MESSAGE = 'messages.category_delete_message';

    const STATUS_UPDATE_MESSAGE = 'messages.category_status_update_message';

    const RELATION_ERROR_MESSAGE = 'messages.category_relation_error_message';

    /**
     * Display a listing of the resource.
     */
    public function index(CategoryDataTable $dataTable)
    {
        return $dataTable->render('category.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('category.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCategoryRequest $request)
    {
        $data = $request->validated();
        $data['slug'] = Str::slug($data['name']);

        Category::create($data);

        return redirect()->route('category.index')->with('status', __(self::SUCCESS_MESSAGE));
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Category $category)
    {
        return view('category.edit', compact('category'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(StoreCategoryRequest $request, Category $category)
    {
        $data = $request->validated();
        $data['slug'] = Str::slug($data['name']);

        $category->update($data);

        return redirect()->route('category.index')->with('status', __(self::UPDATE_MESSAGE));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $category = Category::findOrFail($id);
        $category->delete();

        return response(['status' => 'success', 'message' => __(self::DELETE_MESSAGE)]);
    }

    public function changeStatus(Request $request)
    {
        $category = Category::findOrFail($request->id);

        $category->status = $request->status == 'true' ? 1 : 0;

        $category->save();

        return response(['status' => 'success', 'message' => __(self::STATUS_UPDATE_MESSAGE)]);
    }
}
