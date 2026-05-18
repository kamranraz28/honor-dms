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
            <!-- Small boxes (Stat box) -->
            <!-- /.row -->

            <div class="new-box row" style="max-width: 1024px;" min-height="500px">
                <h1 class="orader">
                    Create new Order
                </h1>
                @if ($message = Session::get('message'))
                    <div class="alert alert-success">
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
                <div class="alert alert-danger hide errorlist"> </div>
                <form method="POST" action="{{ route('tso.store') }}" name="add_name" id="add_name">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <input type="hidden" name="user_id" value="{{ Auth::id() }}">
                    <div class="m-5">
                        <label for="" class="form-label">Select LD</label>
                        <select name="upazila_id" id="upazila_id" class="form-control select2" required="required">

                            <option>All</option>
                            @foreach ($upazilas as $key => $upazila)
                                <option value="{{ $upazila['upazila_id'] }}">
                                    {{ $upazila['name'] . '-' . $upazila['bn_name'] }}</option>
                            @endforeach
                        </select>
                    </div>
                    <br>




                    <table class="table-hover table table" id="dynamic_field">


                        <tr>
                            <!-- <td>
                                <label class="form-label">Product name</label>
                                <select name="model[]" id="model" class="form-control select2">
                                    <option value="All">Select</option>

                                    @foreach ($productList as $product)
                                        <option value="{{ $product->id }}">{{ $product->name }}
                                            ({{ $product->model }})
                                        </option>
                                    @endforeach
                                </select>
                            </td>
                            <td> <label class="form-label">Quantity</label>
                                <input type="number" name="quintity[]" class="form-control name_quintity" />
                            </td> -->


                        </tr>


                    </table>
                    <div style="display: flex; justify-content: space-between; align-items: center;">

                        <div class="float-left">

                        </div>

                        <div class="float-right">
                            <button type="button" name="add" id="add" class="btn btn-success btn-lg"> <i
                                    class="fa fa-plus-circle" aria-hidden="true"></i> Add More
                                item</button>
                        </div>
                    </div>

<br>
<div class="total-amount bg-light p-3 rounded shadow">
                        <h3 class="text-dark">Total: <span id="totalAmount" class="text-primary">(BDT) 0.00</span></h3>
                    </div>

                    <div class="form-group">
                        <label for="delivery_info">Order Remarks:</label>
                        <input type="text" class="form-control" name="remarks" placeholder="Enter Order Remarks" required>
                    </div>

                    <div class="float-left">
                            <button id="submit" class="btn btn-primary btn-lg" type="submit" role="button">
                                Order Summary</button>
                        </div>

                </form>
            </div>
    </div>

    </section>

    </div>
    <!-- /.content-wrapper -->

    <!-- content part================================ -->
@endsection

@push('scripts')
<script>
        $(document).ready(function() {
            var i = 1;
    var addAmount = 700;
    var totalAmount = 0;
    var productData = @json($productList);


    // Function to update the total price
    function updateTotal() {
        totalAmount = 0;
        $('#dynamic_field tr').each(function() {
            var $row = $(this);
            var quintity= $row.find('input[name="quintity[]"]').val();
            var price = $row.find('input[name="price[]"]').val();
            var subtotal = parseFloat(quintity) * parseFloat(price);
            totalAmount += isNaN(subtotal) ? 0 : subtotal;
            $row.find('input[name="subtotal[]"]').val(subtotal.toFixed(2));
        });
        $('#totalAmount').text('(BDT) ' + totalAmount.toFixed(2));
    }

    // Call updateTotal when the page loads
    updateTotal();


            // Add product row
            $("#add").click(function() {
                i++;
                addAmount += 700;
                $('#dynamic_field').append('<tr id="row' + i +
                    '"><td><label for="" class="form-label">Product</label><select name="model[]" class="form-control select2 product-select"><option value="All">Select</option>@foreach ($productList as $item)<option value="{{ $item->id }}">{{ $item->name }} ({{ $item->model }})</option>@endforeach</select></td><td><label for="" class="form-label">Quantity</label><input type="number" name="quintity[]" class="form-control name_quintity" /></td><td><label class="form-label">Price</label><input type="text" name="price[]" class="form-control price" readonly /></td><td><label class="form-label">Subtotal</label><input type="text" name="subtotal[]" class="form-control subtotal" readonly /></td><td><button type="button" name="remove" id="' +
                    i + '" class="btn btn-danger btn_remove">X</button></td></tr>');

                // Initialize select2 for the new product row
                $('.product-select:last').select2();

                // Handle changes in the product selection
                $('.product-select:last').change(function() {
                    var selectedProductId = $(this).val();
                    var $row = $(this).closest('tr');
                    var $priceField = $row.find('.price');

                    // Find the selected product in the productData array
                    var selectedProduct = productData.find(product => product.id == selectedProductId);

                    if (selectedProduct) {
                        $priceField.val(selectedProduct.dp);
                        updateTotal();
                    } else {
                        // Handle the case where the selected product is not found
                    }
                });

                $('.name_quintity').change(function() {
                    updateTotal();
                });
            });

            // Remove product row
            $(document).on('click', '.btn_remove', function() {
                addAmount -= 700;
                var rowIndex = $('#dynamic_field').find('tr').length;
                addAmount -= 700 * rowIndex;
                var button_id = $(this).attr("id");
                $('#row' + button_id).remove();
                updateTotal();
            });
        });
    </script>
@endpush
