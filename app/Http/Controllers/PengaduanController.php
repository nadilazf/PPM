<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Intervention\Image\Facades\image;
use App\Models\Pengaduan;
use App\Models\Tanggapan;

class PengaduanController extends Controller
{
    public function index()
    {
        $pengaduans = pengaduan::where('nik', Auth::guard('masyarakat')->user()->nik)->latest()->paginate(5);
        return view('pengaduan.index', compact('pengaduans'));
    }

    public function indexPetugas()
    {
        $pengaduans = Pengaduan::latest()->with('getDataMasyarakat')->paginate(5);
        return view('pengaduan.indexPetugas', compact('pengaduans'));
    }

    public function create ()
    {
        return view('pengaduan.create');
    }

    public function store(Request $request)
    {
        $validateData = $request->validate([
            'tgl_pengaduan' => 'required',
            'isi_laporan' => 'required',
            'foto' => 'required|mimes:png,jpg',
            'nik' =>'required'
        ]);

        if ($request->file('foto')) {
            $fileImage = hexdec(uniqid()) . '.' . $request->foto->extension();
            Image::make($request->file('foto'))->save('assets/images/'. $fileImage);
            $pengaduanImage = 'assets/images/' . $fileImage;

            $validateData['foto'] = $pengaduanImage;
            $validateData['status'] = '0';

            Pengaduan::create($validateData);
        } else {
            $validateData['foto'] = '-';
            $validateData['status'] = '0';
            
            Pengaduan::create($validateData);
        }
        return redirect()->route('pengaduan.index')->with('success', 'Pengaduanmu sudah terkirim, tunggu respon dari petugas');
    }

    public function edit($id)
    {
        $pengaduan = Pengaduan::find($id);
        if($pengaduan->status == 'selesai') {
            return back()->with('error', 'Status pengaduan sudah selesai');
        }
        return view('pengaduan.edit', compact('pengaduan'));
    }

    public function update(Request $request, $id)
    {
        if ($request->file('foto')) {
            $fileImage = hexdec(uniqid()) . '.' . $request->foto->extension();
            Image::make($request->file('foto'))->save('assets/images/'. $fileImage);
            $pengaduanImage = 'assets/images/' . $fileImage;

            $data = Pengaduan::findOrFail($id);
            $data->tgl_pengaduan = $request->tgl_pengaduan;
            $data->isi_laporan = $request->isi_laporan;
            $data->foto = $pengaduanImage;
            $data->update();
    } else {
        $data = Pengaduan::findOrFail($id);
        $data->tgl_pengaduan = $request->tgl_pengaduan;
        $data->isi_laporan = $request->isi_laporn;
        $data->foto = $request->foto_lama;
        $data->update();
    }
    return redirect()->route('pengaduan.index')->with('success', 'Berhasil mengubah pengaduan.');
}
    public function destroy($id)
    {
        $pengaduan = Pengaduan::findOrFail($id);
        $tanggapan = Tanggapan::where('id_pengaduan', $id);

        if($pengaduan && $tanggapan) {
            $pengaduan->delete();
            $tanggapan->delete();

            if(Auth::guard('masyarakat')->user()){
             return redirect()->route('pengaduan.index')->with('success', 'Berhasil menghapus pengaduan');
            }
        return redirect()->route('pengaduan.indexPetugas')->with('success', 'Berhasil menghapus pengaduan');
    }
    return redirect()->route('pengaduan.index')->with('error', 'Gagal menghapus pengaduan');
    }
}