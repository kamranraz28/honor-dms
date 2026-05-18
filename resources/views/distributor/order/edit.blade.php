@extends('layouts.master_distributor')

@section('title')
{{ 'E-Warranty System :: Dashboard' }}
@endsection

@section('content')
<!-- content part================================ -->
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <!-- bc part================================ -->
    @include('distributor.bc.bc')
    <!-- bc part================================ -->

    <!-- Main content -->
    <section class="content">
        <div class="">
            <div class="row new-box">

                @includeif('partials.errors')

                <div class="card card-default">
                    <div class="card">
                        <div class="card-header">
                            <div style="display: flex; justify-content: space-between; align-items: center;">

                                <h1 class="orader"> Order Confirmation</h1>

                                <div class="float-right">
                                    <button type="button" class="btn btn-warning btn-lg" data-toggle="modal"
                                        data-target="#myModal">
                                        <i class="fa fa-plus-circle" aria-hidden="true"></i> Add More Product
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Button trigger modal -->

                        @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                        @endif
                        <div class="card-body">
                            <form method="POST" action="{{ route('orderspostings.update', $ordersposting->id) }}"
                                role="form" enctype="multipart/form-data">
                                {{ method_field('PATCH') }}
                                @csrf

                                <div class="box-body">

                                    <div class="form-group">
                                        {{ Form::hidden('orader_number', $ordersposting->orader_number, ['class' => 'form-control' . ($errors->has('orader_number') ? ' is-invalid' : ''), 'placeholder' => 'Order Number']) }}
                                        {!! $errors->first('orader_number', '<div class="invalid-feedback">:message</div>') !!}
                                    </div>

                                    <table class="table-hover table table" id="dynamic_field_row">
                                        @foreach ($ordersposting->OrderspostingDetails as $item)
                                        <input type="hidden" name="id[]" value="{{ $item->id }}" />
                                        <input type="hidden" name="orderspostings_id" value="{{ $item->orderspostings_id }}" />
                                        <tr id="row{{ $loop->iteration }}">
                                            <td>
                                                <label class="form-label d-block">Product name</label>
                                                <select name="product[]" id="model" class="form-control select2">
                                                    <option value="All">Select</option>
                                                    @foreach ($productList as $iteam)
                                                    <option value="{{ $iteam->id }}"
                                                        {{ $iteam->id == $item->product_id ? 'selected' : '' }}>
                                                        {{ $iteam->name }} ({{ $iteam->model }})
                                                    </option>
                                                    @endforeach
                                                </select>
                                            </td>
                                            <td>
                                                <label class="form-label">Quantity</label>
                                                <input type="number" name="quintity[]" value="{{ $item->quantity }}"
                                                    class="form-control quantity" />
                                            </td>
                                            <td>
                                                <label class="form-label">Price</label>
                                                <input type="number" name="price[]" value="{{ $item->price }}"
                                                    class="form-control price" />
                                            </td>
                                          
                                            <td>
                                                <label class="form-label">Subtotal</label>
                                                <input type="text" name="subtotal[]" value="{{ $item->quantity * $item->price }}"
                                                    class="form-control subtotal" readonly />
                                            </td>
                                              <td>
                                                 <label class="form-label">
        <input type="checkbox" class="toggleDiscount"> Discount
    </label>
                                                <input type="number" name="price_acc[]" value="{{ $item->price_acc }}"
                                                    class="form-control price_acc" id="discount-field" style="display: none;" />
                                            </td>
                                            <td>
                                                <label class="form-label">Final Value</label>
                                                <input type="text" name="final_total[]"
                                                    value="{{ ($item->quantity * $item->price) - $item->price_acc }}"
                                                    class="form-control final_total" readonly />
                                            </td>
                                            <td>
                                                <label style=" width: 100%; "><br></label>
                                                <button type="button" name="remove" id="{{ $loop->iteration }}"
                                                    class="btn btn-danger btn_removes_olddata">
                                                    <i class="fa fa-trash" aria-hidden="true"></i>
                                                </button>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </table>

                                    <!-- Add Total Price Field -->
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label for="totalPrice">Total Value</label>
                                                <input type="text" name="totalPrice" id="totalPrice" class="form-control" readonly />
                                            </div>
                                        </div>
                                    </div>

                                </div>

                                <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label for="remarks">Remarks</label>
                                                <input type="text" name="remarks" id="remarks" class="form-control">
                                            </div>
                                        </div>
                                    </div>

                                <div class="box-footer mt20">
                                    <button type="submit" class="btn btn-success btn-lg">
                                        <i class="fa fa-paper-plane" aria-hidden="true"></i>
                                        {{ __('Submit') }}
                                    </button>
                                </div>
                            </form>

                            <!-- Modal -->
                            <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                                <div class="modal-dialog modal-lg" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                            <h4 class="modal-title" id="myModalLabel">Add More Product</h4>
                                        </div>
                                        <div class="modal-body">
                                            <div class="alert alert-danger hide errorlist"></div>

                                            @if ($message = Session::get('message'))
                                            <div class="alert alert-success">
                                                <p>{{ $message }}</p>
                                            </div>
                                            @endif

                                            @if ($errors->any())
                                            <div class="alert alert-danger">
                                                <ul>
                                                   
                                                </ul>
                                            </div>
                                            @endif

                                            <form method="POST" name="add_name" id="add_name">
                                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                                <input type="hidden" name="user_id" value="{{ Auth::id() }}">
                                                <input type="hidden" name="orderspostings_id" value="{{ $orderspostings_id }}">
                                                <table class="table-hover table table" id="dynamic_field">
                                                    <tr>
                                                        <td>
                                                            <label class="form-label">Product name</label>
                                                            <select name="model[]" id="model" class="form-control select2">
                                                                <option value="All">Select</option>
                                                                @foreach ($productList as $iteam)
                                                                <option value="{{ $iteam->id }}">{{ $iteam->name }}
                                                                    ({{ $iteam->model }})
                                                                </option>
                                                                @endforeach
                                                            </select>
                                                        </td>
                                                        <td>
                                                            <label class="form-label">Quantity</label>
                                                            <input type="number" name="quintity[]" class="form-control quantity" />
                                                        </td>
                                                   
                                                        <td>
                                                            <label style=" width: 100%; "><br></label>
                                                            <button type="button" name="add" id="add" class="btn btn-primary">
                                                                Add More Products
                                                            </button>
                                                        </td>
                                                    </tr>
                                                </table>
                                                <button id="submit" class="btn btn-success btn-lg" role="button">
                                                    Confirm Order
                                                </button>
                                                <button type="button" class="btn btn-danger btn-lg" data-dismiss="modal">
                                                    Close
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        // Initialize total price
        var totalPrice = 0;

        $(document).on('click', '.btn_remove_olddata', function() {
            var button_id = $(this).attr("id");
            confirm("Are you sure!");
            $('#row' + button_id + '').remove();
            updateTotalPrice();
        });

        var i = 1;

        $("#add").click(function() {
            i++;
            $('#dynamic_field').append('<tr id="row' + i +
                '"><td><label for="" class="form-label">Product</label><select name="model[]" id="model" class="form-control select2"><option value="All">Select</option>@foreach ($productList as $iteam)<option value="{{ $iteam->id }}">{{ $iteam->name }} ({{ $iteam->model }})</option>@endforeach</select></td><td><label for="" class="form-label">Quantity</label><input type="number" name="quintity[]" class="form-control quantity"/></td><td><label style=" width: 100%; "> </br></label><button type="button" name="remove" id="' +
                i +
                '" class="btn btn-danger btn_remove"><i class="fa fa-trash" aria-hidden="true"></i></button></td></tr>'
            );
        });

        $(document).on('click', '.btn_remove', function() {
    $(this).closest('tr').remove(); // This will remove the row containing the clicked "Remove" button
    updateTotalPrice();
});

    $(document).on('click', '.btn_removes_olddata', function() {
        var button_id = $(this).attr("id");
        $('#row' + button_id + '').remove();
        updateTotalPrice();
    });

    // Handle input changes for quantity, price, and discount
    $(document).on('input', '.quantity, .price, .price_acc', function() {
        updateTotalPrice();
    });

// Update the total price and subtotal values
function updateTotalPrice() {
    totalPrice = 0;
    $('.final_total').each(function(index, element) {
        var quantity = parseFloat($(element).closest('tr').find('.quantity').val()) || 0;
        var price = parseFloat($(element).closest('tr').find('.price').val()) || 0;
        var discount = parseFloat($(element).closest('tr').find('.price_acc').val()) || 0;
        var finalTotal = quantity * price - discount;
        $(element).val(finalTotal.toFixed(2)); // Update final total value with two decimal places
        totalPrice += finalTotal;

        // Update the subtotal value for this row
        var subtotal = quantity * price;
        $(element).closest('tr').find('.subtotal').val(subtotal.toFixed(2));
    });
    $('#totalPrice').val(totalPrice.toFixed(2)); // Update total price value with two decimal places
}
    // Initial calculations
    updateTotalPrice();

      // Toggle Discount Field
        $(document).on('change', '.toggleDiscount', function () {
            var discountField = $(this).closest('td').find('.price_acc');
            discountField.toggle();
            updateTotalPrice(); // Update the total price after toggling the discount field
        });

            $("#submit").on('click', function(event) {
                var formdata = $("#add_name").serialize();
                console.log(formdata);

                event.preventDefault()

                $.ajax({
                    url: "{{ Route('orderspostings.store') }}",
                    type: "POST",
                    data: formdata,
                    cache: false,
                    success: function(result) {
                        location.reload();
                        // $("#add_name")[0].reset();
                    },
                    error: function(result) {
                        if (result.responseJSON && result.responseJSON.errors) {
                            // Display validation errors to the user
                            var errors = result.responseJSON.errors;
                            for (var key in errors) {
                                if (errors.hasOwnProperty(key)) {
                                    // Display each error message to the user
                                    //console.log(errors[key][0]);
                                    $('.errorlist').removeClass('hide').append(errors[key][0]);
                                }
                            }
                        } else {
                            // Handle other types of errors here
                            console.log('An error occurred.');
                        }
                    }
                });

            });
        });
    </script>
@endpush
