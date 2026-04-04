<?php

namespace App\Http\Controllers;

use App\DataTables\ContactDataTable;
use App\Helpers\ImageHelper;
use App\Http\Requests\StoreContactRequest;
use App\Models\Category;
use App\Models\Contact;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    const SUCCESS_MESSAGE = 'messages.contact_success_message';

    const UPDATE_MESSAGE = 'messages.contact_update_message';

    const DELETE_MESSAGE = 'messages.contact_delete_message';

    const STATUS_UPDATE_MESSAGE = 'messages.contact_status_update_message';

    /**
     * Display a listing of the resource.
     */
    public function index(ContactDataTable $dataTable)
    {
        return $dataTable->render('contact.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Category::where('status', 1)->get();

        return view('contact.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreContactRequest $request)
    {
        $validated = $request->validated();

        // Combine birthday fields
        $validated['birthday'] = $this->combineBirthdayFields(
            $validated['birthday_day'] ?? null,
            $validated['birthday_month'] ?? null,
            $validated['birthday_year'] ?? null
        );

        // Remove individual birthday fields
        unset($validated['birthday_day'], $validated['birthday_month'], $validated['birthday_year']);

        // Handle image upload
        if ($request->hasFile('image')) {
            $validated['image'] = ImageHelper::store($request->file('image'));
        }

        // Set user_id from authenticated user
        $validated['user_id'] = auth()->id();

        Contact::create($validated);

        return redirect()->route('contact.index')->with('status', __(self::SUCCESS_MESSAGE));
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
    public function edit(Contact $contact)
    {
        $categories = Category::where('status', 1)->get();

        return view('contact.edit', compact('contact', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(StoreContactRequest $request, Contact $contact)
    {
        $validated = $request->validated();

        // Combine birthday fields
        $validated['birthday'] = $this->combineBirthdayFields(
            $validated['birthday_day'] ?? null,
            $validated['birthday_month'] ?? null,
            $validated['birthday_year'] ?? null
        );

        // Remove individual birthday fields
        unset($validated['birthday_day'], $validated['birthday_month'], $validated['birthday_year']);

        // Handle image upload
        if ($request->hasFile('image')) {
            $validated['image'] = ImageHelper::store($request->file('image'), $contact->image);
        } elseif ($request->input('remove_image') == '1' && $contact->image) {
            ImageHelper::delete($contact->image);
            $validated['image'] = null;
        }

        $contact->update($validated);

        return redirect()->route('contact.index')->with('status', __(self::UPDATE_MESSAGE));
    }

    private function combineBirthdayFields($day, $month, $year)
    {
        if (! $day && ! $month && ! $year) {
            return null;
        }

        if (! $day || ! $month) {
            return null;
        }

        $year = $year ?? null;

        try {
            return sprintf('%04d-%02d-%02d', $year, $month, $day);
        } catch (\Exception $e) {
            return null;
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $contact = Contact::findOrFail($id);

        // Delete image if exists
        if ($contact->image) {
            ImageHelper::delete($contact->image);
        }

        $contact->delete();

        return response(['status' => 'success', 'message' => __(self::DELETE_MESSAGE)]);
    }

    public function changeStatus(Request $request)
    {
        $contact = Contact::findOrFail($request->id);

        $contact->status = $request->status == 'true' ? 1 : 0;

        $contact->save();

        return response(['status' => 'success', 'message' => __(self::STATUS_UPDATE_MESSAGE)]);
    }
}
