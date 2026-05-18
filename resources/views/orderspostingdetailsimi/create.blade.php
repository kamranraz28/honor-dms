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

                @includeif('partials.errors')

                <div class="card card-default">
                    <div class="card-header">
                        <span class="card-title">{{ __('Create') }} Orderspostingdetailsimi</span>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('orderspostingdetailsimis.store') }}"  role="form" enctype="multipart/form-data" id="myForm">
                            @csrf

                            @include('orderspostingdetailsimi.form')

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
