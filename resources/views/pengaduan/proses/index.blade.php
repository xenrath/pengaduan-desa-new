@extends('layouts.app')

@section('title', 'Pengaduan Proses')

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
                        <li class="breadcrumb-item active">Pengaduan</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            @if (session('success'))
                <div class="alert alert-success alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                    <h5>
                        <i class="icon fas fa-check"></i> Success!
                    </h5>
                    {{ session('success') }}
                </div>
            @endif
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Data Pengaduan</h3>
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="example1" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th class="text-center" style="width: 20px">No</th>
                                    <th>Nama Pengadu</th>
                                    <th>Kategori</th>
                                    <th>Waktu Buat</th>
                                    <th class="text-center" style="width: 160px">Opsi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($pengaduans as $pengaduan)
                                    <tr>
                                        <td class="text-center">{{ $loop->iteration }}</td>
                                        <td>{{ $pengaduan->user->nama }}</td>
                                        <td>{{ $pengaduan->kategori->nama }}</td>
                                        <td>
                                            {{ date('H:i', strtotime($pengaduan->jam_aduan)) }},
                                            {{ date('d M Y', strtotime($pengaduan->tanggal_aduan)) }}
                                        </td>
                                        <td class="text-center">
                                            <a href="{{ url('pengaduan/proses/' . $pengaduan->id) }}"
                                                class="btn btn-info btn-sm">
                                                Lihat
                                            </a>
                                            <button type="button" class="btn btn-success btn-sm" data-toggle="modal"
                                                data-target="#modal-selesai-{{ $pengaduan->id }}">
                                                Selesaikan
                                            </button>
                                        </td>
                                    </tr>
                                    <div class="modal fade" id="modal-selesai-{{ $pengaduan->id }}">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h4 class="modal-title">Selesaikan Pengaduan</h4>
                                                    <button type="button" class="close" data-dismiss="modal"
                                                        aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body">
                                                    Yakin selesaikan pengaduan dari
                                                    <strong>{{ $pengaduan->user->nama }}</strong>?
                                                </div>
                                                <div class="modal-footer justify-content-between">
                                                    <button type="button" class="btn btn-default"
                                                        data-dismiss="modal">Batal</button>
                                                    <a href="{{ url('pengaduan/proses/selesai/' . $pengaduan->id) }}"
                                                        class="btn btn-success">Selesaikan</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                <!-- /.card-body -->
            </div>
        </div>
    </section>
    <!-- /.card -->
@endsection
