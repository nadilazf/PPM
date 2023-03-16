<?php

namespace App\Http\Controllers;

use App\Models\Masyarakat;
use App\Models\Tanggapan;
use App\Models\Pengaduan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MasyarakatController extends Controller
{
    public function index()
    {
        $masyarakats = Masyarakat::latest()->paginate(5);
        return view('masyarakat.index', compact('masyarakats'));
    }

    public function destroy($id)
    {
        if(Auth::guard('petugas')->user()->level == 'petugas') {
            return back()->with('error', 'Anda tidak memiliki akses');
        }

        $masyarakat = Masyarakat::findOrFail($id);
        $pengaduans = Pengaduan::where('nik', $masyarakat->nik)->get();
        foreach ($pengaduans as $pengaduan){
            $tanggapans = Tanggapan::where('id_pengaduan', $pengaduan->id)->get();
            foreach ($tanggapans as $tanggapan){
                $tanggapan->delete();
            }
            $pengaduan->delete();
        }
        $masyarakat->delete();

        return redirect()->route('masyarakat.index')->with('success', 'Berhasil menghapus masyarakat');
    }
}
