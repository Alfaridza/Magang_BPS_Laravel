<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class ManajemenAdminController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        
        $admins = Admin::query();
        
        if ($search) {
            $admins = $admins->where(function($query) use ($search) {
                $query->where('name', 'LIKE', "%{$search}%")
                      ->orWhere('email', 'LIKE', "%{$search}%")
                      ->orWhere('no_hp', 'LIKE', "%{$search}%");
            });
        }
        
        $admins = $admins->orderBy('created_at', 'desc')
                         ->paginate(10)
                         ->withQueryString();
        
        return view('admin.manajemen_admin.index', compact('admins', 'search'));
    }

    public function create()
    {
        return view('admin.manajemen_admin.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:admins,email',
            'password' => 'required|string|min:8|confirmed',
            'no_hp' => 'nullable|string|max:15',
        ]);

        Admin::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'no_hp' => $request->no_hp ?? '-',
        ]);

        return redirect()->route('admin.manajemen_admin.index')->with('success', 'Admin berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $admin = Admin::findOrFail($id);
        return view('admin.manajemen_admin.edit', compact('admin'));
    }

    public function update(Request $request, $id)
    {
        $admin = Admin::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:admins,email,'.$id,
            'password' => 'nullable|string|min:8|confirmed',
            'no_hp' => 'nullable|string|max:15',
        ]);

        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'no_hp' => $request->no_hp,
        ];

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $admin->update($data);

        return redirect()->route('admin.manajemen_admin.index')->with('success', 'Data Admin berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $admin = Admin::findOrFail($id);

        if ($admin->id === Auth::guard('admin')->id()) {
            return redirect()->back()->with('error', 'Anda tidak dapat menghapus akun Anda sendiri.');
        }

        $admin->delete();

        return redirect()->route('admin.manajemen_admin.index')->with('success', 'Akun Admin berhasil dihapus.');
    }
}
