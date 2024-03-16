@extends('layouts.app')

@section('title', 'Pengaduan Selesai')

@section('content')
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Pengaduan Selesai</h1>
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
                                    <th>Status</th>
                                    <th class="text-center" style="width: 40px">Opsi</th>
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
                                        <td>
                                            @if ($pengaduan->status == 'selesai')
                                                <span class="badge badge-success">{{ $pengaduan->status }}</span>
                                            @elseif($pengaduan->status == 'tolak')
                                                <span class="badge badge-danger">{{ $pengaduan->status }}</span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            <a href="{{ url('pengaduan/riwayat/' . $pengaduan->id) }}"
                                                class="btn btn-info btn-sm">
                                                Lihat
                                            </a>
                                        </td>
                                    </tr>
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
