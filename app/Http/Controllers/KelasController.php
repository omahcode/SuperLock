<?php

namespace App\Http\Controllers;

use App\Models\Kelas;
use Illuminate\Http\Request;

class KelasController extends Controller
{
    public function store(Request $request)
    {
        $data = $request->validate([
            'nama' => ['required', 'string', 'max:50', 'unique:kelas,nama'],
        ]);

        Kelas::create($data);

        return back()->with('pesan', 'Kelas berhasil ditambahkan.');
    }

    public function update(Request $request, Kelas $kelas)
    {
        $data = $request->validate([
            'nama' => ['required', 'string', 'max:50', 'unique:kelas,nama,' . $kelas->id],
        ]);

        $kelas->update($data);

        return back()->with('pesan', 'Kelas berhasil diubah.');
    }

    public function destroy(Request $request, Kelas $kelas)
    {
        if ($kelas->siswa()->count() > 0) {
            return back()->with('pesan', 'Gagal hapus. Kelas masih memiliki siswa.');
        }

        $kelas->delete();

        if ($request->query('kelas') == $kelas->id) {
            return redirect()->route('edit')->with('pesan', 'Kelas berhasil dihapus.');
        }

        return back()->with('pesan', 'Kelas berhasil dihapus.');
    }
}
