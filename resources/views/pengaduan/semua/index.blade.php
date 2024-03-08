@extends('layouts.app')

@section('title', 'Semua Pengaduan')

@section('content')
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Semua Pengaduan</h1>
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
                                        <td>
                                            @if ($pengaduan->status == 'menunggu')
                                                <span class="badge badge-warning">Menunggu</span>
                                            @elseif($pengaduan->status == 'proses')
                                                <span class="badge badge-primary">Proses</span>
                                            @elseif($pengaduan->status == 'selesai')
                                                <span class="badge badge-success">Selesai</span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            <a href="{{ url('pengaduan/semua/' . $pengaduan->id . '/edit') }}"
                                                class="btn btn-warning btn-sm">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <button type="button" class="btn btn-danger btn-sm" data-toggle="modal"
                                                data-target="#modal-hapus-{{ $pengaduan->id }}">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                                    <div class="modal fade" id="modal-hapus-{{ $pengaduan->id }}">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h4 class="modal-title">Hapus Pengaduan</h4>
                                                    <button type="button" class="close" data-dismiss="modal"
                                                        aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body">
                                                    <p>Yakin hapus pengaduan?</p>
                                                </div>
                                                <div class="modal-footer justify-content-between">
                                                    <button type="button" class="btn btn-default"
                                                        data-dismiss="modal">Batal</button>
                                                    <form action="{{ url('pengaduan/semua/' . $pengaduan->id) }}" method="POST">
                                                        @csrf
                                                        @method('delete')
                                                        <button type="submit" class="btn btn-danger">Hapus</button>
                                                    </form>
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
@endsection
