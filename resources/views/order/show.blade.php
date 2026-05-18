@extends('layouts.app')

@section('template_title')
    {{ $order->name ?? "{{ __('Show') Order" }}
@endsection

@section('content')
    <section class="content container-fluid">
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="float-left">
                            <span class="card-title">{{ __('Show') }} Order</span>
                        </div>
                        <div class="float-right">
                            <a class="btn btn-primary" href="{{ route('orders.index') }}"> {{ __('Back') }}</a>
                        </div>
                    </div>

                    <div class="card-body">

                        <div class="form-group">
                            <strong>Upazila Id:</strong>
                            {{ $order->upazila_id }}
                        </div>
                        <div class="form-group">
                            <strong>User Id:</strong>
                            {{ $order->user_id }}
                        </div>
                        <div class="form-group">
                            <strong>Status:</strong>
                            {{ $order->status }}
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
