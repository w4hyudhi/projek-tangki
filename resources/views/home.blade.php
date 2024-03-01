@extends('layouts.app')

@section('content')
        <!-- Content Header (Page header) -->
        <div class="content-header">
                <div class="container-fluid">
                        <div class="row mb-2">
                                <div class="col-sm-6">
                                        <h1 class="m-0">{{ __('Dashboard') }}</h1>
                                </div><!-- /.col -->
                        </div><!-- /.row -->
                </div><!-- /.container-fluid -->
        </div>
        <!-- /.content-header -->

        <!-- Main content -->
        <div class="content">
                <div class="container-fluid">
                        <div class="row">
                                <div class="col-12 col-sm-6 col-md-3">
                                    <div class="info-box">
                                        <span class="info-box-icon bg-info elevation-1"><i class="fas fa-map-marked-alt"></i></span>

                                        <div class="info-box-content">
                                            <span class="info-box-text">Total Distrik</span>
                                            <span class="info-box-number">
                                                10
                                            </span>
                                        </div>
                                        <!-- /.info-box-content -->
                                    </div>
                                    <!-- /.info-box -->
                                </div>
                                <!-- /.col -->
                                <div class="col-12 col-sm-6 col-md-3">
                                    <div class="info-box mb-3">
                                        <span class="info-box-icon bg-danger elevation-1"><i class="fas fa-home"></i></span>

                                        <div class="info-box-content">
                                            <span class="info-box-text">Total Kampung</span>
                                            <span class="info-box-number"> Kampung</span>
                                        </div>
                                        <!-- /.info-box-content -->
                                    </div>
                                    <!-- /.info-box -->
                                </div>
                                <!-- /.col -->
                                <div class="col-12 col-sm-6 col-md-3">
                                    <div class="info-box mb-3">
                                        <span class="info-box-icon bg-success elevation-1"><i class="fas fa-map-pin"></i></span>

                                        <div class="info-box-content">
                                            <span class="info-box-text">Total TPS</span>
                                            <span class="info-box-number">TPS</span>
                                        </div>
                                        <!-- /.info-box-content -->
                                    </div>
                                    <!-- /.info-box -->
                                </div>
                                <!-- /.col -->
                                <div class="col-12 col-sm-6 col-md-3">
                                    <div class="info-box mb-3">
                                        <span class="info-box-icon bg-warning elevation-1"><i class="fas fa-users"></i></span>

                                        <div class="info-box-content">
                                            <span class="info-box-text">Total Pemilih</span>
                                            <span class="info-box-number"> Orang</span>
                                        </div>
                                        <!-- /.info-box-content -->
                                    </div>
                                    <!-- /.info-box -->
                                </div>
                                <!-- /.col -->
                            </div>
                        <div class="row">
                                <div class="col-lg-12">
                                    <div class="card">
                                        <div class="card-header">
                                          <h3 class="card-title">US-Visitors Report</h3>

                                          <div class="card-tools">
                                            <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                              <i class="fas fa-minus"></i>
                                            </button>
                                            <button type="button" class="btn btn-tool" data-card-widget="remove">
                                              <i class="fas fa-times"></i>
                                            </button>
                                          </div>
                                        </div>
                                        <!-- /.card-header -->
                                        <div class="card-body p-0">
                                            <table class="table">
                                                <thead>
                                                    <tr>
                                                        <th>id</th>
                                                        <th>name</th>
                                                        <th>email</th>
                                                        <th>phone</th>
                                                        <th>store_name</th>
                                                        <th>type</th>
                                                        <th>status</th>
                                                        <th>price</th>
                                                        <th>created_at</th>
                                                        <th>Aksi</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                @foreach($tangki as $data)
                                                    <tr>
                                                        <td>{{$data->id}}</td>
                                                        <td>{{$data->user->name}}</td>
                                                        <td>{{$data->user->email}}</td>
                                                        <td>{{$data->user->phone}}</td>
                                                        <td>{{$data->name}}</td>
                                                        <td>{{$data->type}}</td>
                                                        <td>  <form class="form" action="{{ route('tangki.update',$data->id) }}" method="POST">
                                                            @csrf
                                                            @method('post')

                                                            @if($data->request_status == "0")
                                                              <button type="submit" name="status" value="1" class="btn btn-danger  ms-auto">NONAKTIF</button>
                                                             @else
                                                              <button type="submit" name="status" value="0" class="btn btn-primary ms-auto">AKTIF</button>
                                                              @endif

                                                        </form></td>
                                                        <td>{{$data->price}}</td>
                                                        <td>{{ \Carbon\Carbon::parse($data->created_at)->format('d/m/Y') }}</td>
                                                        <td>
                                                            <a href="#" class="btn btn-primary" data-toggle="modal" data-target="#modal-lg{{$data->id}}"><i class="fas fa-chart-bar"></i></a>
                                                            {{-- <a href="{{ route('tps.edit',$data->id)}}" class="btn btn-warning"><i class="fas fa-edit"></i></a> --}}

                                                        </td>
                                                    </tr>


                                                @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                        <!-- /.card-body -->
                                      </div>
                                </div>
                        </div>
                        <!-- /.row -->
                </div><!-- /.container-fluid -->
        </div>


@endsection
