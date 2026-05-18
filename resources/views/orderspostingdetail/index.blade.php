@extends('layouts.app')

@section('template_title')
    Orderspostingdetail
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row new-box">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header">
                        <div style="display: flex; justify-content: space-between; align-items: center;">

                            <span id="card_title">
                                {{ __('Orderspostingdetail') }}
                            </span>

                            <div class="float-right">
                                <a href="{{ route('orderspostingdetails.create') }}"
                                    class="btn btn-primary btn-sm float-right" data-placement="left">
                                    {{ __('Create New') }}
                                </a>
                            </div>
                        </div>
                    </div>
                    @if ($message = Session::get('success'))
                        <div class="alert alert-success">
                            <p>{{ $message }}</p>
                        </div>
                    @endif

                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table-striped table-hover table">
                                <thead class="thead">
                                    <tr>
                                        <th>No</th>

                                        <th>Orader Number</th>
                                        <th>Product Id</th>
                                        <th>Quantity</th>
                                        <th>Quantity Acc</th>
                                        <th>Price</th>
                                        <th>Price Acc</th>

                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($orderspostingdetails as $orderspostingdetail)
                                        <tr>
                                            <td>{{ ++$i }}</td>

                                            <td>{{ $orderspostingdetail->orader_number }}</td>
                                            <td>{{ $orderspostingdetail->product_id }}</td>
                                            <td>{{ $orderspostingdetail->quantity }}</td>
                                            <td>{{ $orderspostingdetail->quantity_acc }}</td>
                                            <td>{{ $orderspostingdetail->price }}</td>
                                            <td>{{ $orderspostingdetail->price_acc }}</td>

                                            <td>
                                                <form
                                                    action="{{ route('orderspostingdetails.destroy', $orderspostingdetail->id) }}"
                                                    method="POST">
                                                    <a class="btn btn-sm btn-primary"
                                                        href="{{ route('orderspostingdetails.show', $orderspostingdetail->id) }}"><i
                                                            class="fa fa-fw fa-eye"></i> {{ __('Show') }}</a>
                                                    <a class="btn btn-sm btn-success"
                                                        href="{{ route('orderspostingdetails.edit', $orderspostingdetail->id) }}"><i
                                                            class="fa fa-fw fa-edit"></i> {{ __('Edit') }}</a>
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger btn-sm"><i
                                                            class="fa fa-fw fa-trash"></i> {{ __('Delete') }}</button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                {!! $orderspostingdetails->links() !!}
            </div>
        </div>
    </div>
@endsection
