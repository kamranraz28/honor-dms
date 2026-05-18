@extends('layouts.master_admin')

@section('title', 'Edit Order')

@section('content')
    <div class="content-wrapper">
        <section class="content-header">
            <div class="row">
                <div class="col-md-12">
                    <div class="box box-warning">
                        <div class="box-header with-border">
                            <h2 class="box-title">Edit Order</h2>
                        </div>
                        <div class="box-body">
                            <h4>Distributor Name: <b>{{ $distributor->firstname }}</b></h4>
                            <h4>Order Number: <b>{{ $ordersposting->orader_number }}</b></h4>

                            <form method="POST" action="{{ route('admin.orderUpdate', $ordersposting->id) }}" id="editOrderForm">
                                @csrf

                                <input type="hidden" name="orader_number" value="{{ $ordersposting->orader_number }}">
                                <input type="hidden" name="removed_ids" id="removed_ids">

                                <table class="table table-bordered" id="dynamic_field">
                                    <thead>
                                        <tr>
                                            <th>Product Name</th>
                                            <th>Quantity</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($ordersposting->OrderspostingDetails as $item)
                                            <tr>
                                                <input type="hidden" name="id[]" value="{{ $item->id }}">
                                                <td>
                                                    <select name="product[]" class="form-control select2">
                                                        <option value="">Select</option>
                                                        @foreach ($productList as $iteam)
                                                            <option value="{{ $iteam->id }}" {{ $iteam->id == $item->product_id ? 'selected' : '' }}>
                                                                {{ $iteam->name }} ({{ $iteam->model }})
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </td>
                                                <td>
                                                    <input type="number" name="quantity[]" value="{{ $item->quantity }}" class="form-control">
                                                </td>
                                                <td>
                                                    <button type="button" class="btn btn-danger remove-row">
                                                        <i class="fa fa-trash"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>

                                <button type="button" class="btn btn-primary" id="addRow">
                                    <i class="fa fa-plus"></i> Add More
                                </button>

                                <br><br>

                                <div class="box-footer mt-3">
                                    <button type="submit" class="btn btn-success btn-lg">
                                        <i class="fa fa-paper-plane"></i> Update Order
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            let removedIds = [];

            document.getElementById("addRow").addEventListener("click", function () {
                let newRow = `<tr>
                    <td>
                        <select name="product[]" class="form-control select2">
                            <option value="">Select</option>
                            @foreach ($productList as $iteam)
                                <option value="{{ $iteam->id }}">{{ $iteam->name }} ({{ $iteam->model }})</option>
                            @endforeach
                        </select>
                    </td>
                    <td>
                        <input type="number" name="quantity[]" class="form-control">
                    </td>
                    <td>
                        <button type="button" class="btn btn-danger remove-row">
                            <i class="fa fa-trash"></i>
                        </button>
                    </td>
                </tr>`;
                document.querySelector("#dynamic_field tbody").insertAdjacentHTML("beforeend", newRow);
            });

            document.querySelector("#dynamic_field").addEventListener("click", function (event) {
                if (event.target.closest(".remove-row")) {
                    let row = event.target.closest("tr");
                    let idField = row.querySelector("input[name='id[]']");

                    if (idField) {
                        removedIds.push(idField.value);
                        document.getElementById("removed_ids").value = removedIds.join(",");
                    }

                    row.remove();
                }
            });
        });
    </script>
@endsection
