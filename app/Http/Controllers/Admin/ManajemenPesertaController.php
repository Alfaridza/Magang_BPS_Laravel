<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ManajemenPesertaController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        
        $pesertas = User::query();
        
        if ($search) {
            $pesertas = $pesertas->where(function($query) use ($search) {
                $query->where('name', 'LIKE', "%{$search}%")
                      ->orWhere('email', 'LIKE', "%{$search}%")
                      ->orWhere('no_hp', 'LIKE', "%{$search}%");
            });
        }
        
        $pesertas = $pesertas->orderBy('created_at', 'desc')
                             ->paginate(10)
                             ->withQueryString();
        
        return view('admin.manajemen_peserta.index', compact('pesertas', 'search'));
    }
    
    public function show($id)
    {
        $peserta = User::findOrFail($id);
        return view('admin.manajemen_peserta.show', compact('peserta'));
    }
    
    public function edit($id)
    {
        $peserta = User::findOrFail($id);
        return view('admin.manajemen_peserta.edit', compact('peserta'));
    }
    
    public function update(Request $request, $id)
    {
       
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,'.$id,
            'jenis_kelamin' => 'nullable|string|max:255',
            'tanggal_lahir' => 'nullable|date',
            'tempat_lahir' => 'string|max:255',
            'no_hp' => 'nullable|string|max:15',
            'alamat' => 'nullable|string',
        ]);
        // var_dump($request->all());
        // die();
        $peserta = User::findOrFail($id);
        $peserta->update([
            'name' => $request->name,
            'email' => $request->email,
            'jenis_kelamin' => $request->jenis_kelamin,
            'tanggal_lahir' => $request->tanggal_lahir,
            'tempat_lahir' => $request->tempat_lahir,
            'no_hp' => $request->no_hp,
            'alamat' => $request->alamat,
        ]);

        return redirect()->route('admin.manajemen_peserta.index')->with('success', 'Berhasil memperbarui data peserta.');
    }
    
    public function destroy($id)
    {
        $user = User::findOrFail($id);
        
        // 
        
        // Delete related pengajuan magang records first
        $user->pengajuanMagangs()->delete();
        
        // Finally delete the user
        $user->delete();
        
        return redirect()->route('admin.manajemen_peserta.index')->with('success', 'Berhasil menghapus peserta.');
    }
}