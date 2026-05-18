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
        @include('tso.bc.bc')
        <!-- bc part================================ -->

        <!-- Main content -->
        <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="float-left">
                            <span class="card-title">{{ __('Show') }} Orderspostingdetailsimi</span>
                        </div>
                        <div class="float-right">
                            <a class="btn btn-primary" href="{{ route('orderspostingdetailsimis.index') }}"> {{ __('Back') }}</a>
                        </div>
                    </div>

                    <div class="card-body">
                        
                        <div class="form-group">
                            <strong>Orderspostingdetails Id:</strong>
                            {{ $orderspostingdetailsimi->orderspostingdetails_id }}
                        </div>
                        <div class="form-group">
                            <strong>IMEI:</strong>
                            {{ $orderspostingdetailsimi->IMI }}
                        </div>
                        <div class="form-group">
                            <strong>Created By:</strong>
                            {{ $orderspostingdetailsimi->created_by }}
                        </div>

                    </div>
                </div>
            </div>
        </div>
        </div>
    </section>
@endsection
