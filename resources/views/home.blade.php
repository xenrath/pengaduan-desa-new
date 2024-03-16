@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Dashboard</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item active">Dashboard</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <!-- Small boxes (Stat box) -->
            <div class="row">
                <div class="col-md-3">
                    <!-- small box -->
                    <div class="small-box bg-warning">
                        <div class="inner">
                            <h3>
                                {{ $menunggu }}&nbsp;data
                            </h3>
                            <p>Pengaduan Menunggu</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-clock"></i>
                        </div>
                        <a href="{{ url('pengaduan/menunggu') }}" class="small-box-footer">Lihat <i
                                class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>
                <div class="col-md-3">
                    <!-- small box -->
                    <div class="small-box bg-primary">
                        <div class="inner">
                            <h3>
                                {{ $proses }}&nbsp;data
                            </h3>
                            <p>Pengaduan Proses</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-tasks"></i>
                        </div>
                        <a href="{{ url('pengaduan/proses') }}" class="small-box-footer">Lihat <i
                                class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>
                <div class="col-md-3">
                    <!-- small box -->
                    <div class="small-box bg-success">
                        <div class="inner">
                            <h3>
                                {{ $selesai }}&nbsp;data
                            </h3>
                            <p>Pengaduan Selesai</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-check"></i>
                        </div>
                        <a href="{{ url('pengaduan/selesai') }}" class="small-box-footer">Lihat
                            <i class="fas fa-arrow-circle-right"></i>
                        </a>
                    </div>
                </div>
                <div class="col-md-3">
                    <!-- small box -->
                    <div class="small-box bg-danger">
                        <div class="inner">
                            <h3>
                                {{ $tolak }}&nbsp;data
                            </h3>
                            <p>Pengaduan Tolak</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-times"></i>
                        </div>
                        <a href="{{ url('pengaduan/tolak') }}" class="small-box-footer">Lihat
                            <i class="fas fa-arrow-circle-right"></i>
                        </a>
                    </div>
                </div>
            </div>
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Grafik Pengaduan</h3>
                    <form action="{{ url('/') }}" method="get" id="form-filter">
                        <div class="float-right">
                            <select class="form-control" id="status" name="status"
                                onchange="document.getElementById('form-filter').submit()">
                                <option value="">Semua Kategori</option>
                                <option value="menunggu" {{ request()->get('status') == 'menunggu' ? 'selected' : '' }}>
                                    Menunggu</option>
                                <option value="proses" {{ request()->get('status') == 'proses' ? 'selected' : '' }}>
                                    Diproses
                                </option>
                                <option value="selesai" {{ request()->get('status') == 'selesai' ? 'selected' : '' }}>
                                    Selesai</option>
                                <option value="tolak" {{ request()->get('status') == 'tolak' ? 'selected' : '' }}>Ditolak
                                </option>
                            </select>
                        </div>
                    </form>
                </div>
                <div class="card-body">
                    <canvas id="myChart"></canvas>
                </div>
            </div>
            <!-- /.row -->
        </div>
        <!-- /.container-fluid -->
    </section>
    <!-- /.content -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        const ctx = document.getElementById('myChart');

        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: {{ Js::from($label) }},
                datasets: [{
                    label: 'Jumlah Pengaduan',
                    data: {{ Js::from($data) }},
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    y: {
                        suggestedMin: 0,
                        ticks: {
                            precision: 0
                        },
                        beginAtZero: true
                    },
                }
            }
        });
    </script>
@endsection
