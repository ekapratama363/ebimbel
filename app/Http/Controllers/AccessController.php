<?php

namespace App\Http\Controllers;

use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class AccessController extends Controller
{
    public function roles(): View
    {
        return view('pages.akses.roles', [
            'roles' => Role::with('permissions')->withCount('users')->orderBy('name')->get(),
            'modules' => config('permissions.modules', []),
            'actionLabels' => config('permissions.actions', []),
        ]);
    }

    public function storeRole(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'permissions' => 'nullable|array',
            'permissions.*' => 'string',
        ]);

        $role = Role::create([
            'name' => $data['name'],
            'slug' => $this->uniqueSlug($data['name']),
            'description' => $data['description'] ?? null,
            'is_system' => false,
        ]);

        $role->syncPermissionKeys($data['permissions'] ?? []);

        return back()->with('status', 'Role berhasil ditambahkan.');
    }

    public function updateRole(Request $request, Role $role): RedirectResponse
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'permissions' => 'nullable|array',
            'permissions.*' => 'string',
        ]);

        $role->update([
            'name' => $data['name'],
            'description' => $data['description'] ?? null,
        ]);

        if (! $role->is_system || $role->slug !== 'super_admin') {
            $role->syncPermissionKeys($data['permissions'] ?? []);
        }

        return back()->with('status', 'Role berhasil diperbarui.');
    }

    public function destroyRole(Role $role): RedirectResponse
    {
        if ($role->is_system) {
            return back()->withErrors(['role' => 'Role sistem tidak dapat dihapus.']);
        }

        if ($role->users()->exists()) {
            return back()->withErrors(['role' => 'Role masih dipakai oleh pengguna.']);
        }

        $role->delete();

        return back()->with('status', 'Role dihapus.');
    }

    public function users(): View
    {
        return view('pages.akses.users', [
            'users' => User::with('role')->orderBy('name')->get(),
            'roles' => Role::orderBy('name')->get(),
        ]);
    }

    public function storeUser(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'role_id' => 'required|exists:roles,id',
        ]);

        User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'role_id' => $data['role_id'],
        ]);

        return back()->with('status', 'Pengguna berhasil ditambahkan.');
    }

    public function updateUser(Request $request, User $user): RedirectResponse
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($user->id)],
            'password' => 'nullable|string|min:8|confirmed',
            'role_id' => 'required|exists:roles,id',
        ]);

        $user->name = $data['name'];
        $user->email = $data['email'];
        $user->role_id = $data['role_id'];

        if (! empty($data['password'])) {
            $user->password = Hash::make($data['password']);
        }

        $user->save();
        $user->clearPermissionCache();

        return back()->with('status', 'Pengguna berhasil diperbarui.');
    }

    public function destroyUser(Request $request, User $user): RedirectResponse
    {
        if ($user->id === $request->user()->id) {
            return back()->withErrors(['user' => 'Tidak dapat menghapus akun sendiri.']);
        }

        $user->delete();

        return back()->with('status', 'Pengguna dihapus.');
    }

    public function rolePermissions(Role $role): \Illuminate\Http\JsonResponse
    {
        return response()->json([
            'permissions' => $role->permissions->map(fn (Permission $p) => $p->key)->values(),
        ]);
    }

    private function uniqueSlug(string $name): string
    {
        $base = Str::slug($name);
        $slug = $base;
        $counter = 1;

        while (Role::where('slug', $slug)->exists()) {
            $slug = $base.'-'.$counter;
            $counter++;
        }

        return $slug;
    }
}
