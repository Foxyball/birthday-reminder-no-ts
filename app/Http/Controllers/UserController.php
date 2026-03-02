<?php

namespace App\Http\Controllers;

use App\DataTables\UserDataTable;
use App\Http\Requests\StoreUserRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    const SUCCESS_MESSAGE = 'messages.user_success_message';

    const UPDATE_MESSAGE = 'messages.user_update_message';

    const DELETE_MESSAGE = 'messages.user_delete_message';

    const LOCK_STATUS_UPDATE_MESSAGE = 'messages.user_lock_status_update_message';


    public function index(UserDataTable $dataTable)
    {
        return $dataTable->render('user.index');
    }

    public function create()
    {
        return view('user.create');
    }

    public function store(StoreUserRequest $request)
    {
        User::create($request->validated());

        return redirect()->route('user.index')->with('status', __(self::SUCCESS_MESSAGE));
    }

    public function edit(string $id)
    {
        $user = User::findOrFail($id);

        return view('user.edit', compact('user'));
    }

    public function update(StoreUserRequest $request, string $id)
    {
        $user = User::findOrFail($id);
        $user->update($request->validated());

        return redirect()->route('user.index')->with('status', __(self::UPDATE_MESSAGE));
    }

    public function toggleLock(string $id)
    {
        $user = User::findOrFail($id);
        $user->update([
            'is_locked' => !$user->is_locked,
        ]);

        return response()->json([
            'success' => true,
            'is_locked' => $user->is_locked,
            'message' => $user->is_locked ? __('messages.user_locked_message') : __('messages.user_unlocked_message'),
        ]);
    }
}
