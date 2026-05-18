@extends('layouts.master_warehouse')

@section('title')
    {{ 'E-Warranty Ststem :: Dashboard' }}
@endsection

@section('content')
    <!-- content part================================ -->
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <!-- bc part================================ -->
        @include('warehouse.bc.bc')
        <!-- bc part================================ -->

        <!-- Main content -->
        <div class="new-box w-600">

            @includeif('partials.errors')

            <div class="card card-default">
                @if ($message = Session::get('success'))
                    <div class="alert alert-danger">
                        <p>{{ $message }}</p>
                    </div>
                @endif
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                <div class="card-header text-center">
                    <H2 class="card-title"> {{ $orderspostingdetail->Product->name }}
                        ({{ $orderspostingdetail->Product->model }})</h2>
                    ({{ $orderspostingdetail->Product->details }})
                </div>
                <div class="card-body">
                    <div class="w-600">
                        <form method="POST" action="{{ route('orderspostingdetailsimis.store') }}" role="form"
                            enctype="multipart/form-data">

                            @csrf

                            @include('orderspostingdetailsimi.pendingForm')

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
@endsection
