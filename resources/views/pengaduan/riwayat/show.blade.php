@extends('layouts.app')

@section('title', 'Detail Pengaduan')

@section('content')
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.5.1/dist/leaflet.css"
        integrity="sha512-xwE/Az9zrjBIphAcBb3F6JVqxf46+CDLwfLMHloNu6KEQCAWi6HcDUbeOfBIptF7tcCzusKFjFw2yuvEpDL9wQ=="
        crossorigin="" />
    <script src="https://unpkg.com/leaflet@1.5.1/dist/leaflet.js"
        integrity="sha512-GffPMF3RvMeYyc1LWMHtK8EbPv0iNZ8/oTtHPx9/cc2ILxQ+u905qIwdpULaqDkyBKgOaB57QTMg7ztg8Jm2Og=="
        crossorigin=""></script>
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Riwayat Pengaduan</h1>
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
            <div class="row">
                <div class="col-lg-6">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Detail Pengaduan</h3>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
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
                            <div class="row">
                                <div class="col-lg-4 mb-3">
                                    <strong>Tanggal Selesai</strong>
                                </div>
                                <div class="col-lg-8">
                                    {{ date('d M Y', strtotime($pengaduan->tanggal_selesai)) }}
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-4 mb-3">
                                    <strong>Status</strong>
                                </div>
                                <div class="col-lg-8">
                                    @if ($pengaduan->status == 'selesai')
                                        <span class="badge badge-success">{{ $pengaduan->status }}</span>
                                    @elseif($pengaduan->status == 'tolak')
                                        <span class="badge badge-danger">{{ $pengaduan->status }}</span>
                                    @endif
                                </div>
                            </div>
                            <a href="#" data-toggle="modal" data-target="#modal-gambar">
                                <img src="{{ asset('storage/uploads/' . $pengaduan->gambar) }}"
                                    alt="{{ $pengaduan->nama }}" class="w-100 rounded">
                            </a>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div id="map" style="width:100%; height:400px;"></div>
                    <script>
                        var map = L.map('map').setView([{{ $pengaduan->latitude }}, {{ $pengaduan->longitude }}], 18);
                        mapLink = '<a href="http://openstreetmap.org">OpenStreetMap</a>';
                        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
                        }).addTo(map);
                        L.marker([{{ $pengaduan->latitude }}, {{ $pengaduan->longitude }}])
                            .addTo(map)
                            .bindPopup("{{ $pengaduan->kategori->nama }}")
                            .openPopup();
                    </script>
                </div>
            </div>
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Detail Pengaduan Proses</h3>
                </div>
                <div class="card-body">
                    @forelse ($detail_pengaduans as $detail_pengaduan)
                        <small class="text-muted">{{ date('d M Y', strtotime($detail_pengaduan->created_at)) }}</small>
                        <p>{{ $detail_pengaduan->deskripsi }}</p>
                        <div class="row">
                            <div class="col-lg-4">
                                <a href="#" data-toggle="modal"
                                    data-target="#modal-detail-gambar-{{ $detail_pengaduan->id }}">
                                    <img src="{{ asset('storage/uploads/' . $detail_pengaduan->gambar) }}" alt=""
                                        class="w-100 rounded">
                                </a>
                            </div>
                        </div>
                        <hr>
                        <div class="modal fade" id="modal-detail-gambar-{{ $detail_pengaduan->id }}">
                            <div class="modal-dialog modal-lg">
                                <div class="modal-content">
                                    <div class="modal-body">
                                        <img src="{{ asset('storage/uploads/' . $detail_pengaduan->gambar) }}"
                                            alt="" class="w-100 rounded">
                                    </div>
                                    <div class="modal-footer text-right">
                                        <button type="button" class="btn btn-default" data-dismiss="modal">Tutup</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <p class="text-center p-4 border rounded text-muted">- Detail pengaduan belum ditambahkan -</p>
                    @endforelse
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
                <form action="{{ url('pengaduan/proses/add-detail/' . $pengaduan->id) }}" method="post"
                    enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="deskripsi">Deskripsi</label>
                            <textarea class="form-control" rows="3" name="deskripsi">{{ old('deskripsi') }}</textarea>
                        </div>
                        <div class="form-group">
                            <label for="gambar">Gambar</label>
                            <div class="custom-file">
                                <input type="file" class="custom-file-input" id="gambar" name="gambar"
                                    accept="image/*">
                                <label class="custom-file-label" for="gambar">Pilih Gambar</label>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer justify-content-between">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Tambah</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="modal fade" id="modal-selesaikan">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Selesaikan Pengaduan</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    Yakin selesaikan pengaduan dari <strong>{{ $pengaduan->user->nama }}</strong>?
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
                    <a href="{{ url('pengaduan/proses/selesai/' . $pengaduan->id) }}"
                        class="btn btn-success">Selesaikan</a>
                </div>
            </div>
        </div>
    </div>
@endsection
