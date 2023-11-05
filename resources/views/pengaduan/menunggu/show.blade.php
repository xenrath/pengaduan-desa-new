@extends('layouts.app')

@section('title', 'Detail Pengaduan')

@section('content')
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Pengaduan Menunggu</h1>
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
                        </div>
                        <div class="col-lg-4">
                            <a href="">
                                <img src="{{ asset('storage/uploads/' . $pengaduan->gambar) }}" alt="{{ $pengaduan->nama }}"
                                    class="w-100 rounded">
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
