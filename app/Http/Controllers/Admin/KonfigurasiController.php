<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\KonfigurasiJamKerja;
use App\Models\HariLibur;

class KonfigurasiController extends Controller
{
    public function jamKerja()
    {
        $konfigurasi = KonfigurasiJamKerja::orderBy('status', 'desc')->orderBy('id', 'desc')->get();
        return view('admin.konfigurasi.jam_kerja', compact('konfigurasi'));
    }

    public function storeJamKerja(Request $request)
    {
        $request->validate([
            'nama'                => 'required|string|max:255',
            'jam_masuk'           => 'required',
            'jam_masuk_toleransi' => 'required',
            'jam_pulang'          => 'required',
            'is_wfa'              => 'nullable|boolean',
            'tanggal_mulai'       => 'nullable|date',
            'tanggal_selesai'     => 'nullable|date',
            'radius_meter'        => 'required|integer|min:1',
        ]);

        $data = $request->all();
        $data['is_wfa'] = $request->has('is_wfa') ? 1 : 0;
        $data['status'] = 1;

        KonfigurasiJamKerja::create($data);

        return redirect()->back()->with('success', 'Konfigurasi jam kerja berhasil ditambahkan.');
    }

    public function updateJamKerja(Request $request, $id)
    {
        $konfigurasi = KonfigurasiJamKerja::findOrFail($id);

        $request->validate([
            'nama'                => 'required|string|max:255',
            'jam_masuk'           => 'required',
            'jam_masuk_toleransi' => 'required',
            'jam_pulang'          => 'required',
            'is_wfa'              => 'nullable|boolean',
            'tanggal_mulai'       => 'nullable|date',
            'tanggal_selesai'     => 'nullable|date',
            'radius_meter'        => 'required|integer|min:1',
        ]);

        $data = $request->all();
        $data['is_wfa'] = $request->has('is_wfa') ? 1 : 0;

        $konfigurasi->update($data);

        return redirect()->back()->with('success', 'Konfigurasi jam kerja berhasil diperbarui.');
    }

    public function destroyJamKerja($id)
    {
        $konfigurasi = KonfigurasiJamKerja::findOrFail($id);
        $konfigurasi->delete();

        return redirect()->back()->with('success', 'Konfigurasi jam kerja berhasil dihapus.');
    }

    public function hariLibur()
    {
        $hariLibur = HariLibur::orderBy('tanggal', 'desc')->get();
        return view('admin.konfigurasi.hari_libur', compact('hariLibur'));
    }

    public function storeHariLibur(Request $request)
    {
        $request->validate([
            'tanggal'    => 'required|date|unique:hari_liburs,tanggal',
            'keterangan' => 'required|string|max:255',
        ]);

        HariLibur::create($request->all());

        return redirect()->back()->with('success', 'Hari libur berhasil ditambahkan.');
    }

    public function destroyHariLibur($id)
    {
        $hariLibur = HariLibur::findOrFail($id);
        $hariLibur->delete();

        return redirect()->back()->with('success', 'Hari libur berhasil dihapus.');
    }
}
