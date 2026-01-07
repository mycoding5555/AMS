<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    public function index()
    {
        return view('admin.users', [
            'users' => User::with('roles')->paginate(10),
            'roles' => Role::all()
        ]);
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'role' => 'required|exists:roles,name',
            'status' => 'required|in:active,suspended'
        ]);

        // Update role
        $user->syncRoles([$request->role]);

        // Update status
        $user->update([
            'status' => $request->status
        ]);

        return redirect()->back()->with('success', 'User updated successfully');
    }
}
