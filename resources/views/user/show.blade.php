@extends('layouts.app')

@section('title', 'Detail User')

@section('content')
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Detail User</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item">
                            <a href="{{ url('user') }}">User</a>
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
                    <h3 class="card-title">Detail User</h3>
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-4 mb-3">
                            <strong>Nama User</strong>
                        </div>
                        <div class="col-lg-8">
                            {{ $user->nama }}
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-4 mb-3">
                            <strong>Nomor Telepon</strong>
                        </div>
                        <div class="col-lg-8">
                            +62{{ $user->telp }}
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-4 mb-3">
                            <strong>Status</strong>
                        </div>
                        <div class="col-lg-8">
                            @if ($user->is_block)
                                <span class="badge badge-danger">Terblockir</span>
                            @else
                                @if ($user->is_verif)
                                    <span class="badge badge-primary">Terverifikasi</span>
                                @else
                                    <span class="badge badge-warning">Belum Verifikasi</span>
                                @endif
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Pengaduan User</h3>
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th class="text-center" style="width: 20px">No</th>
                                    <th>Kategori</th>
                                    <th>Lokasi</th>
                                    <th>Deskripsi</th>
                                    <th style="width: 200px">Gambar</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($pengaduans as $pengaduan)
                                    <tr>
                                        <td class="text-center">{{ $loop->iteration }}</td>
                                        <td>{{ $pengaduan->kategori->nama }}</td>
                                        <td>{{ $pengaduan->lokasi }}</td>
                                        <td>{{ $pengaduan->deskripsi }}</td>
                                        <td>
                                            <a href="" data-toggle="modal"
                                                data-target="#modal-gambar-{{ $pengaduan->id }}">
                                                <img src="{{ asset('storage/uploads/' . $pengaduan->gambar) }}"
                                                    alt="" class="w-100">
                                            </a>
                                        </td>
                                    </tr>
                                    <div class="modal fade" id="modal-gambar-{{ $pengaduan->id }}">
                                        <div class="modal-dialog modal-lg">
                                            <div class="modal-content">
                                                <div class="modal-body">
                                                    <img src="{{ asset('storage/uploads/' . $pengaduan->gambar) }}"
                                                        alt="" class="w-100 rounded">
                                                </div>
                                                <div class="modal-footer text-right">
                                                    <button type="button" class="btn btn-default"
                                                        data-dismiss="modal">Tutup</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
