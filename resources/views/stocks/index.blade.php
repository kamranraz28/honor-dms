@extends('layouts.master_admin')

@section('title')
    {{"Sales Automation Process :: Stock"}}
@endsection

@section('content')

<!-- Content Wrapper -->
<div class="content-wrapper">

    <section class="content-header">

        <div class="row">
            <div class="">

                <!-- PRODUCT ADD BOX -->
                <div class="box box-warning smart-box">
                    <div class="box-header with-border">
                        <h3 class="box-title">Product Add</h3>
                    </div>

                    <!-- FORM START -->
                    <form class="form-horizontal" method="POST" action="{{ route('stocks.store') }}" autocomplete="off" enctype="multipart/form-data">
                        @csrf

                        <div class="box-body">

                            {{-- Error Message --}}
                            @if(count($errors))
                                <div class="alert alert-danger alert-dismissible">
                                    <strong>Whoops!</strong> There were some problems with your input.
                                    <br>
                                    <ul>
                                        @foreach($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            {{-- Success Message --}}
                            @if(Session::has('success'))
                                <div class="alert alert-success alert-dismissible fade in">
                                    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                                    <strong>Success!</strong> {{ Session::get('success') }}
                                </div>
                            @endif


                            <!-- PRODUCT SELECT -->
                            <div class="form-group {{ $errors->has('product_id') ? 'has-error' : '' }}">
                                <label class="col-sm-2 control-label">Product :</label>
                                <div class="col-sm-5">
                                    <select name="product_id" id="product" class="form-control select2" required>
                                        <option value="">Select Product</option>
                                        @foreach($products as $product)
                                            <option value="{{ $product->id }}" {{ old('product_id') == $product->id ? 'selected' : '' }}>
                                                {{ $product->model }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <span class="text-danger">{{ $errors->first('product_id') }}</span>
                                </div>
                            </div>


                            <!-- ADD FIELD BUTTON -->
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Product Details</label>
                                <div class="col-sm-10">
                                    <button class="btn smart-add-btn btn-warning" style="width:50%">+ Add Field</button>
                                </div>
                            </div>

                            <!-- CONTAINER FOR IMEI FIELDS -->
                            <div class="container1"></div>

                        </div>

                        <div class="box-footer">
                            <button type="submit" class="btn btn-success pull-right">Submit</button>
                        </div>

                    </form>
                    <!-- FORM END -->

                </div>


                <!-- FILTER FORM -->
                <form class="form-horizontal" method="POST" action="{{ route('stocks.filter') }}" autocomplete="off" enctype="multipart/form-data">
                    @csrf

                    <div class="box box-warning smart-box">
                        <div class="box-body">

                            {{-- Date Filter --}}
                            <div class="col-md-8">
                                <label for="fdate" class="control-label">From Date</label>
                                <div class="input-group date">
                                    <div class="input-group-addon"><i class="fa fa-calendar"></i></div>
                                    <input name="fdate" type="text" class="form-control" id="datepicker3"
                                           value="{{ Session::get('fdate') ?? '' }}" placeholder="YYYY-MM-DD">
                                </div>
                            </div>

                            <div class="col-md-8">
                                <label for="todate" class="control-label">To Date</label>
                                <div class="input-group date">
                                    <div class="input-group-addon"><i class="fa fa-calendar"></i></div>
                                    <input name="todate" type="text" class="form-control" id="datepicker4"
                                           value="{{ Session::get('todate') ?? '' }}" placeholder="YYYY-MM-DD">
                                </div>
                            </div>

                        </div>

                        <div class="box-footer">
                            <button type="submit" class="btn btn-success pull-right">Submit</button>
                        </div>
                    </div>
                </form>

            </div>
        </div>


        {{-- STOCK TABLE --}}
        <div class="row">
            <div class="box box-warning smart-box">

                <div class="box-header">
                    <h3 class="box-title">Stock List</h3>
                    <a target="_blank" class="btn btn-sm btn-info pull-right" href="{{ route('admin.stock.excel') }}">
                        Export To Excel
                    </a>
                </div>

                <div class="box-body">

                    <table id="example" class="ui celled table" cellspacing="0" width="100%">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th> Product </th>
                                <th> Model </th>
                                <th> color </th>
                                <th> Brand </th>
                                <th> IMEI 1 </th>
                                <th> IMEI 2 </th>
                                <th> W-Period </th>
                                <th> Created Date </th>
                                <th> Action </th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach ($stocks as $key => $element)
                                <tr>
                                    <td>{{ $key + 1 }}</td>
                                    <td>{{ $element->product['name'] }}</td>
                                    <td>{{ $element->product['model'] }}</td>
                                    <td>{{ $element->product['color'] }}</td>
                                    <td>{{ $element->brand['name'] }}</td>
                                    <td>{{ $element->sno }}</td>
                                    <td>{{ $element->imei }}</td>
                                    <td>{{ $element->wperiod }}</td>
                                    <td>{{ date_format(date_create($element->created_at), "d-M-Y") }}</td>

                                    <td>
                                        <button class="btn btn-xs btn-primary" data-toggle="modal"
                                            data-target="#{{'stockUpdateModal' . $element->id}}">
                                            <i class="fa fa-pencil-square-o"></i>
                                        </button>

                                        <button class="btn btn-xs btn-danger" data-toggle="modal"
                                            data-target="#{{'stockDeleteModal' . $element->id}}">
                                            <i class="fa fa-trash-o"></i>
                                        </button>
                                    </td>

                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    <div class="text-center mt-3">
                        {{ $stocks->links() }}
                    </div>
                </div>

            </div>
        </div>

    </section>

</div>


{{-- UPDATE MODALS --}}
@forelse ($stocks as $element)
<div class="modal fade" id="{{'stockUpdateModal' . $element->id}}" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title">{{ $element->imei }}</h5>
                <button class="close" type="button" data-dismiss="modal"><span>×</span></button>
            </div>

            <div class="modal-body">

                <form action="{{route('stocks.update', $element->id)}}" method="post">
                    @csrf
                    @method('put')

                    <h4 class="text-info">Update This Data</h4>
                    <br>

                    <div class="form-group">
                        <label>IMEI 1</label>
                        <input type="text" name="sno" class="form-control" value="{{ $element->sno }}">
                    </div>

                    <div class="form-group">
                        <label>IMEI 2</label>
                        <input type="text" name="imei" class="form-control" value="{{ $element->imei }}">
                    </div>

                    <div class="form-group">
                        <label>Warranty Period</label>
                        <input type="number" name="wperiod" class="form-control" value="{{ $element->wperiod }}">
                    </div>

                    <button class="btn btn-success form-control">Update</button>

                </form>

            </div>

            <div class="modal-footer">
                <button class="btn btn-secondary" data-dismiss="modal">Cancel</button>
            </div>

        </div>
    </div>
</div>
@empty
@endforelse


{{-- DELETE MODALS --}}
@forelse ($stocks as $element)
<div class="modal fade" id="{{'stockDeleteModal' . $element->id}}" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title">{{ $element->imei }}</h5>
                <button class="close" type="button" data-dismiss="modal"><span>×</span></button>
            </div>

            <div class="modal-body">

                <form action="{{route('stocks.destroy', $element->id)}}" method="post">
                    @csrf
                    @method('delete')

                    <h4 class="text-info">Do You Want to Delete This?</h4>
                    <br>

                    <button class="btn btn-danger form-control">Delete</button>
                </form>

            </div>

            <div class="modal-footer">
                <button class="btn btn-secondary" data-dismiss="modal">Cancel</button>
            </div>

        </div>
    </div>
</div>
@empty
@endforelse


{{-- ADD FIELD CARD UI JS --}}
<style>
    .imei-card {
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        padding: 12px 15px;
        border-radius: 8px;
        margin-bottom: 10px;
        display: flex;
        align-items: center;
        gap: 12px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.04);
    }
    .imei-card .form-control {
        height: 36px;
    }
    .smart-add-btn {
        border-radius: 8px;
        padding: 8px 20px;
        font-weight: bold;
        box-shadow: 0 4px 12px rgba(0,0,0,0.12);
    }
    .delete-card-btn {
        height: 36px;
    }
</style>

<script>
$(document).ready(function () {

    var max_fields = 100;
    var wrapper = $(".container1");
    var add_button = $(".smart-add-btn");

    var x = 0;

    $(add_button).click(function (e) {
        e.preventDefault();
        if (x < max_fields) {
            x++;

            $(wrapper).append(`
                <div class="imei-card" id="imeiCard${x}">
                    <input type="text" name="imeis[]" class="form-control" placeholder="IMEI 1" required>
                    <input type="text" name="snos[]" class="form-control" placeholder="IMEI 2" required>
                    <input type="number" name="wperiods[]" class="form-control" placeholder="Warranty" required>
                    <button class="btn btn-danger delete-card-btn delete" data-id="${x}">
                        Delete
                    </button>
                </div>
            `);

        } else {
            alert('You reached the limit');
        }
    });

    $(wrapper).on("click", ".delete", function (e) {
        e.preventDefault();
        let id = $(this).data("id");
        $("#imeiCard" + id).remove();
        x--;
    });

});
</script>

@endsection
