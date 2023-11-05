@extends('layouts.app')

@section('title', 'Detail Pengaduan')

@section('content')
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Pengaduan Proses</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item">
                            <a href="{{ url('pengaduan/menunggu') }}">Pengaduan</a>
                        </li>
                        <li class="breadcrumb-item active">Detail</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <section class="content">
        <div class="container-fluid">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Detail Pengaduan</h3>
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-8">
                            <div class="row">
                                <div class="col-lg-4 mb-3">
                                    <strong>Nama Pengadu</strong>
                                </div>
                                <div class="col-lg-8">
                                    {{ $pengaduan->user->nama }}
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-4 mb-3">
                                    <strong>Kategori</strong>
                                </div>
                                <div class="col-lg-8">
                                    {{ $pengaduan->kategori->nama }}
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-4 mb-3">
                                    <strong>Deskripsi</strong>
                                </div>
                                <div class="col-lg-8">
                                    {{ $pengaduan->deskripsi }}
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-4 mb-3">
                                    <strong>Waktu Aduan</strong>
                                </div>
                                <div class="col-lg-8">
                                    {{ date('H:i', strtotime($pengaduan->jam_aduan)) }},
                                    {{ date('d M Y', strtotime($pengaduan->tanggal_aduan)) }}
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-4 mb-3">
                                    <strong>Tanggal Proses</strong>
                                </div>
                                <div class="col-lg-8">
                                    {{ date('d M Y', strtotime($pengaduan->tanggal_proses)) }}
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <a href="#" data-toggle="modal" data-target="#modal-gambar">
                                <img src="{{ asset('storage/uploads/' . $pengaduan->gambar) }}" alt="{{ $pengaduan->nama }}"
                                    class="w-100 rounded">
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Detail Pengaduan Proses</h3>
                    <div class="float-right">
                        <button type="button" class="btn btn-primary btn-sm" data-toggle="modal"
                            data-target="#modal-tambah">
                            <i class="fas fa-plus"></i> Tambah
                        </button>
                    </div>
                </div>
                <div class="card-body">

                </div>
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
    <div class="modal fade" id="modal-tambah">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Tambah Proses</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="deskripsi">Deskripsi</label>
                        <textarea class="form-control" rows="3"></textarea>
                    </div>
                    <div class="form-group">
                        <label for="gambar">Gambar</label>
                        <div class="custom-file">
                            <input type="file" class="custom-file-input" id="customFile">
                            <label class="custom-file-label" for="customFile">Pilih Gambar</label>
                        </div>
                    </div>
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
                    <a href="{{ url('pengaduan/proses/tambah/' . $pengaduan->id) }}" class="btn btn-primary">Tambah</a>
                </div>
            </div>
        </div>
    </div>
@endsection
