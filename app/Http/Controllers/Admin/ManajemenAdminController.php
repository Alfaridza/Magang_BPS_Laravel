<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\AdminActivityLog;
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

    public function create(Request $request)
    {
        if ($request->ajax()) {
            
              if ($request->hasSession()) {
                 $request->session()->forget('_old_input');
              }

              return view('admin.manajemen_admin._form');
        }

        return view('admin.manajemen_admin.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:admins,email',
            'password' => 'required|string|min:8|confirmed',
            'no_hp' => 'nullable|string|max:15',
        ], [
            'email.unique' => 'Email sudah terdaftar',
            'password.min' => 'Password harus minimal 8 karakter',
            'email.email' => 'Format email tidak valid',
            'password.confirmed' => 'Konfirmasi password tidak cocok',
        ]);

        $newAdmin = Admin::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'no_hp' => $request->no_hp ?? '-',
        ]);

        AdminActivityLog::record(Auth::guard('admin')->user(), 'Tambah Admin', 'Menambahkan akun admin ' . $newAdmin->email, Admin::class, $newAdmin->id);

        if ($request->ajax() || $request->wantsJson()) {
            $request->session()->flash('success', 'Admin berhasil ditambahkan.');
            return response()->json(['message' => 'Admin berhasil ditambahkan.'], 201);
        }

        return redirect()->route('admin.manajemen_admin.index')->with('success', 'Admin berhasil ditambahkan.');
    }

    public function edit(Request $request, $id)
    {
        $admin = Admin::findOrFail($id);
        if ($request->ajax()) {
            if ($request->hasSession()) {
                $request->session()->forget('_old_input');
            }
            return view('admin.manajemen_admin._form', compact('admin'));
        }

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
        ], [
            'email.unique' => 'Email sudah terdaftar',
            'email.email' => 'Format email tidak valid',
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

        AdminActivityLog::record(Auth::guard('admin')->user(), 'Update Admin', 'Memperbarui akun admin ' . $admin->email, Admin::class, $admin->id);

        if ($request->ajax() || $request->wantsJson()) {
            $request->session()->flash('success', 'Data Admin berhasil diperbarui.');
            return response()->json(['message' => 'Data Admin berhasil diperbarui.'], 200);
        }

        return redirect()->route('admin.manajemen_admin.index')->with('success', 'Data Admin berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $admin = Admin::findOrFail($id);

        if ($admin->id === Auth::guard('admin')->id()) {
            return redirect()->back()->with('error', 'Anda tidak dapat menghapus akun Anda sendiri.');
        }

        AdminActivityLog::record(Auth::guard('admin')->user(), 'Hapus Admin', 'Menghapus akun admin ' . $admin->email, Admin::class, $admin->id);
        $admin->delete();

        return redirect()->route('admin.manajemen_admin.index')->with('success', 'Admin berhasil dihapus.');
    }
}
