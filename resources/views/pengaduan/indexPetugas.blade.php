@extends('layouts.master')
@section('header', 'DAFTAR PENGADUAN')
@section('content')
<div class="container">
    {{-- <div class="button my-4">
        {{-- <a type="button" class="btn btn-success" href="{{ route('pengaduan.create') }}">Create</a>
    </div> --}}
    @if ($errors->any())
    <div class="alert alert-danger">
        <strong>Whoops!</strong> There were some problems with your input.<br><br>
        <ul>
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif
    @if (session('success'))
    <div class="alert alert-success">
        {{ Session::get('success') }}
    </div>
    @endif
    @if (session('error'))
        <div class="alert alert-danger">
            {{ Session::get('error') }}
        </div>
        @endif
    <table class="table">
        <thead>
            <tr>
                <th scope="col">No</th>
                <th scope="col">Tanggal Pengaduan</th>
                <th scope="col">Nama</th>
                <th scope="col">Isi Laporan</th>
                <th>Status</th>
                <th scope="col">Foto</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($pengaduans as $pengaduan)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $pengaduan->tgl_pengaduan }}</td>
                <td>{{ $pengaduan->getDataMasyarakat->nama }}</td>
                <td>{{ $pengaduan->isi_laporan }}</td>
                <td>{{ $pengaduan->status }}</td>
                <td><img src="{{ asset($pengaduan->foto) }}" alt="" width="100px"></td>
                <td>
                    <form action="{{ route('pengaduan.destroyPetugas', $pengaduan->id) }}" method="POST">

                        <a class="btn btn-primary" href="/tanggapan/create/{{ $pengaduan->id }}" method="POST">Tanggapi</a>

                        @csrf
                            @method('DELETE')

                            <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure to delete?')">Delete</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    {{ $pengaduans->links() }}

    {{-- <div class="d-flex justify-content-end">
        <nav aria-label="...">
            <ul class="pagination">
                <li class="page-item disabled">
                    <a class="page-link">Previous</a>
                </li>
                <li class="page-item"><a class="page-link" href="#">1</a></li>
                <li class="page-item active" aria-current="page">
                    <a class="page-link" href="#">2</a>
                </li>
                <li class="page-item"><a class="page-link" href="#">3</a></li>
                <li class="page-item">
                    <a class="page-link" href="#">Next</a>
                </li>
            </ul>
        </nav>
    </div> --}}
</div>
@endsection
