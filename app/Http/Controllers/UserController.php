<?php

namespace App\Http\Controllers;

use App\DataTables\DeactivatedUserDataTable;
use App\DataTables\UserDataTable;
use App\Http\Requests\StoreUserRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    const SUCCESS_MESSAGE = 'messages.user_success_message';

    const UPDATE_MESSAGE = 'messages.user_update_message';

    const DELETE_MESSAGE = 'messages.user_delete_message';

    const RESTORE_MESSAGE = 'messages.user_restore_message';

    const LOCK_STATUS_UPDATE_MESSAGE = 'messages.user_lock_status_update_message';

    public function index(UserDataTable $dataTable)
    {
        return $dataTable->render('user.index');
    }

    public function deactivated(DeactivatedUserDataTable $dataTable)
    {
        return $dataTable->render('user.deactivated');
    }

    public function create()
    {
        return view('user.create');
    }

    public function store(StoreUserRequest $request)
    {
        User::create($request->validated());

        return redirect()->route('users.index')->with('status', __(self::SUCCESS_MESSAGE));
    }

    public function edit(User $user)
    {
        return view('user.edit', compact('user'));
    }

    public function update(StoreUserRequest $request, User $user)
    {
        $user->update($request->validated());

        return redirect()->route('users.index')->with('status', __(self::UPDATE_MESSAGE));
    }

    public function destroy(string $id)
    {
        $user = User::findOrFail($id);

        if ((int) $user->id === (int) Auth::id()) {
            return response(['status' => 'error', 'message' => 'You cannot delete your own account.'], 422);
        }

        $user->delete();

        return response(['status' => 'success', 'message' => __(self::DELETE_MESSAGE)]);
    }

    public function changeStatus(Request $request)
    {
        $user = User::findOrFail($request->id);

        if ((int) $user->id === (int) Auth::id()) {
            return response(['status' => 'error', 'message' => 'You cannot change your own lock status.'], 422);
        }

        $user->update([
            'is_locked' => $request->status == 'true' ? 1 : 0,
        ]);

        return response(['status' => 'success', 'message' => __(self::LOCK_STATUS_UPDATE_MESSAGE)]);
    }

    public function restore(string $id)
    {
        $user = User::onlyTrashed()->findOrFail($id);
        $user->restore();

        return redirect()->route('users.deactivated')->with('status', __(self::RESTORE_MESSAGE));
    }
}
