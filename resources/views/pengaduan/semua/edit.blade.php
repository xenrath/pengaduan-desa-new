@extends('layouts.app')

@section('title', 'Edit Pengaduan')

@section('content')
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Pengaduan</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item">
                            <a href="{{ url('pengaduan/semua') }}">Pengaduan</a>
                        </li>
                        <li class="breadcrumb-item active">Edit</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <section class="content">
        <div class="container-fluid">
            @if (session('error'))
                <div class="alert alert-danger alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                    <h5>
                        <i class="icon fas fa-ban"></i> Gagal!
                    </h5>
                    @foreach (session('error') as $error)
                        - {{ $error }} <br>
                    @endforeach
                </div>
            @endif
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Edit Pengaduan</h3>
                </div>
                <!-- /.card-header -->
                <form action="{{ url('pengaduan/semua/' . $pengaduan->id) }}" method="POST" enctype="multipart/form-data"
                    autocomplete="off">
                    @csrf
                    @method('PUT')
                    <div class="card-body">
                        <div class="form-group">
                            <label for="user_id">Pengadu</label>
                            <select class="form-control" id="user_id" name="user_id">
                                <option value="">- Pilih Pengadu -</option>
                                @foreach ($users as $user)
                                    <option value="{{ $user->id }}"
                                        {{ old('user_id', $pengaduan->user_id) == $user->id ? 'selected' : null }}>
                                        {{ $user->nama }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="kategori_id">Kategori</label>
                            <select class="form-control" id="kategori_id" name="kategori_id">
                                <option value="">- Pilih Kategori -</option>
                                @foreach ($kategoris as $kategori)
                                    <option value="{{ $kategori->id }}"
                                        {{ old('kategori_id', $pengaduan->kategori_id) == $kategori->id ? 'selected' : null }}>
                                        {{ $kategori->nama }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="deskripsi">Deskripsi</label>
                            <textarea type="text" class="form-control" id="deskripsi" name="deskripsi" placeholder="Masukan deskripsi">{{ old('deskripsi', $pengaduan->deskripsi) }}</textarea>
                        </div>
                        <div class="form-group">
                            <label for="lokasi">Lokasi</label>
                            <input type="text" class="form-control" id="lokasi" name="lokasi"
                                placeholder="Masukan lokasi" value="{{ old('lokasi', $pengaduan->lokasi) }}">
                        </div>
                        <div class="form-group">
                            <label for="gambar">Gambar <small>(Kosongkan saja jika tidak
                                    ingin menambahkan)</small></label>
                            <div class="custom-file">
                                <input type="file" class="custom-file-input" id="gambar" name="gambar"
                                    accept="image/*">
                                <label class="custom-file-label" for="gambar">Masukkan gambar</label>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-3">
                                <a href="#" data-toggle="modal" data-target="#modal-gambar">
                                    <img src="{{ asset('storage/uploads/' . $pengaduan->gambar) }}"
                                        alt="{{ $pengaduan->nama }}" class="w-100 rounded">
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer text-right">
                        <button type="reset" class="btn btn-secondary">Reset</button>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </section>
    <div class="modal fade" id="modal-gambar">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-body">
                    <img src="{{ asset('storage/uploads/' . $pengaduan->gambar) }}" alt="{{ $pengaduan->nama }}"
                        class="w-100 rounded">
                </div>
                <div class="modal-footer text-right">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>
@endsection
