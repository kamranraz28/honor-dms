@extends('layouts.master_tso')

@section('title')
    {{ 'E-Warranty Ststem :: Dashboard' }}
@endsection


@section('content')
    <!-- content part================================ -->

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <!-- bc part================================ -->
        
        <!-- bc part================================ -->







        <!-- Main content -->
        <section class="content">
            <!-- Small boxes (Stat box) -->
            <div class="row">
                <div class="col-lg-4 col-xs-6">
                    <!-- small box -->
                    <div class="small-box bg-aqua">
                        <div class="inner">
                            <h3><a href="{{ route('tso.dailyPurchaseReports') }}">LD Purchase</a></h3>

                            <p><a href="{{ route('tso.dailyPurchaseReports') }}">View</a></p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-pie-graph"></i>
                        </div>
                    </div>
                </div>

                <!-- ./col -->
                <div class="col-lg-4 col-xs-6">
                    <!-- small box -->
                    <div class="small-box bg-yellow">
                        <div class="inner">
                            <h3><a href="{{ route('tso.dailySalesReports') }}">LD Sale</a></h3>

                            <p> <a href="{{ route('tso.dailySalesReports') }}">View</a></p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-pie-graph"></i>
                        </div>
                    </div>
                </div>

                <!-- ./col -->
                <div class="col-lg-4 col-xs-6">
                    <!-- small box -->
                    <div class="small-box bg-red">
                        <div class="inner">
                            <h3><a href="{{ route('tso.distributorImeiStockReport') }}">LD Stock</a></h3>

                            <p><a href="{{ route('tso.distributorImeiStockReport') }}">View</a></p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-pie-graph"></i>
                        </div>
                    </div>
                </div>
                <!-- ./col -->
            </div>
            <!-- /.row -->

            <div class="row">
                <div class="col-md-3 col-sm-6 col-12">
                    <div class="info-box custombox">
                        <span class="info-box-icon"><i class="fa fa-bar-chart" aria-hidden="true"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Total Order</span>
                            <h4 class="info-box-number">{{ $data['orader']->count() }}</h4>

                        </div>

                    </div>

                </div>

                <div class="col-md-3 col-sm-6 col-12">
                    <div class="info-box custombox">
                        <span class="info-box-icon"><i class="fa fa-bar-chart" aria-hidden="true"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Order in Draft</span>
                            <h4 class="info-box-number">{{ $data['orader']->where('status', 0)->count() }}</h4>

                        </div>

                    </div>

                </div>

                <div class="col-md-3 col-sm-6 col-12">
                    <div class="info-box custombox">
                        <span class="info-box-icon"><i class="fa fa-bar-chart" aria-hidden="true"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Order on Account</span>
                            <h4 class="info-box-number">{{ $data['orader']->where('status', 1)->count() }}</h4>

                        </div>

                    </div>

                </div>
                <div class="col-md-3 col-sm-6 col-12">
                    <div class="info-box custombox">
                        <span class="info-box-icon"><i class="fa fa-bar-chart" aria-hidden="true"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Processing</span>
                            <h4 class="info-box-number">
                                {{ $data['orader']->where('status', 2)->count() + $data['orader']->where('status', 3)->count() }}
                            </h4>

                        </div>

                    </div>

                </div>

                <div class="col-md-3 col-sm-6 col-12">
                    <div class="info-box custombox">
                        <span class="info-box-icon"><i class="fa fa-bar-chart" aria-hidden="true"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Complete !!</span>
                            <h4 class="info-box-number">{{ $data['orader']->where('status', 5)->count() }}</h4>

                        </div>

                    </div>

                </div>





            </div>


        </section>
        <!-- /.content -->

    </div>
    <!-- /.content-wrapper -->





    <!-- content part================================ -->
@endsection