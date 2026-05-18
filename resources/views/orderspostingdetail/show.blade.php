@extends('layouts.app')

@section('template_title')
    {{ $orderspostingdetail->name ?? "{{ __('Show') Orderspostingdetail" }}
@endsection

@section('content')
    <section class="content container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="float-left">
                            <span class="card-title">{{ __('Show') }} Orderspostingdetail</span>
                        </div>
                        <div class="float-right">
                            <a class="btn btn-primary" href="{{ route('orderspostingdetails.index') }}"> {{ __('Back') }}</a>
                        </div>
                    </div>

                    <div class="card-body">
                        
                        <div class="form-group">
                            <strong>Order Number:</strong>
                            {{ $orderspostingdetail->orader_number }}
                        </div>
                        <div class="form-group">
                            <strong>Product Id:</strong>
                            {{ $orderspostingdetail->product_id }}
                        </div>
                        <div class="form-group">
                            <strong>Quantity:</strong>
                            {{ $orderspostingdetail->quantity }}
                        </div>
                        <div class="form-group">
                            <strong>Quantity Acc:</strong>
                            {{ $orderspostingdetail->quantity_acc }}
                        </div>
                        <div class="form-group">
                            <strong>Price:</strong>
                            {{ $orderspostingdetail->price }}
                        </div>
                        <div class="form-group">
                            <strong>Price Acc:</strong>
                            {{ $orderspostingdetail->price_acc }}
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
