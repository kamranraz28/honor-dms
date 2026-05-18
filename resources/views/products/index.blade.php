@extends('layouts.master_admin')

@section('title')
    {{ "Sales Automation Process :: Products List" }}
@endsection

@section('content')

<div class="content-wrapper">
    <section class="content">

        <div class="row">
            <section class="col-lg-12 connectedSortable">

                <div class="box box-warning">

                    <div class="box-header with-border">
                        <h3 class="box-title text-danger">Products List</h3>
                        {{-- Create Button --}}
                        <div class="box-tools pull-right">
                            <a href="{{ route('products.create') }}"
                               class="action-btn action-sync">
                                <span class="btn-icon">
                                    <i class="fa fa-plus"></i>
                                </span>
                                <span class="btn-text">Add Product</span>
                                <span class="action-chip">Create</span>
                            </a>
                        </div>
                    </div>

                    <div class="box-body">

                        {{-- Success Message --}}
                        @if(Session::has('success'))
                            <div class="alert alert-success alert-dismissible fade in">
                                <a href="#" class="close" data-dismiss="alert">&times;</a>
                                <strong>Success!</strong> {{ Session::get('success') }}
                            </div>
                        @endif

                        <br>
                        <br>
                        <br>

                        {{-- DataTable --}}
                        <table id="example"
                               class="ui celled table"
                               cellspacing="0"
                               width="100%">

                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Action</th>
                                    <th>Product</th>
                                    <th>Model</th>
                                    <th>Product Code</th>
                                    <th>LD Price (BDT)</th>
                                    <th>Color</th>
                                    <th>Brand</th>
                                    <th>Category</th>
                                    <th>Chalan Type</th>
                                    <th>Details</th>
                                    <th>Created Date</th>
                                    <th>Image</th>
                                </tr>
                            </thead>

                            <tbody>
                                @foreach ($products as $key => $element)
                                    <tr>
                                        <td>{{ $key + 1 }}</td>

                                        {{-- Action --}}
                                        <td>
                                            <a class="btn btn-xs btn-primary"
                                                    href="{{ route('products.edit', $element['id']) }}">
                                                <i class="fa fa-pencil-square-o"></i>
                                            </a>

                                            {{-- Delete (no modal) --}}
                                            <form action="{{ route('products.destroy', $element['id']) }}"
                                                method="POST"
                                                style="display:inline;"
                                                onsubmit="return confirm('Are you sure you want to delete this product?');">

                                                {{ csrf_field() }}
                                                {{ method_field('DELETE') }}

                                                <button type="submit" class="btn btn-xs btn-danger">
                                                    <i class="fa fa-trash-o"></i>
                                                </button>
                                            </form>
                                        </td>

                                        <td>{{ $element['name'] }}</td>
                                        <td>{{ $element['model'] }}</td>
                                        <td>{{ $element['product_code'] }}</td>
                                        <td>{{ $element['dp'] }}</td>
                                        <td>{{ $element['color'] }}</td>
                                        <td>{{ $element['brand']['name'] ?? 'N/A' }}</td>
                                        <td>{{ $element['cat']['name'] ?? 'N/A' }}</td>
                                        <td>{{ $element['chalan_type'] }}</td>

                                        {{-- Details --}}
                                        <td class="text-justify"
                                            style="cursor:pointer;color:black;font-weight:bolder"
                                            data-toggle="modal"
                                            data-target="#{{ 'detailsfoModal'.$element['id'] }}">

                                            @if ($element['details'])
                                                {!! substr($element['details'], 0, 40) !!}
                                            @else
                                                N/A
                                            @endif
                                        </td>

                                        <td>
                                            {{ date_format(date_create($element['created_at']), "d-M-Y") }}
                                        </td>

                                        {{-- Image --}}
                                        <td>
                                            @if ($element['photo'])
                                                <a target="_blank"
                                                   href="{{ asset('storage/app/d/nokia/' . $element['photo']) }}">
                                                    View Photo
                                                </a>
                                            @else
                                                No Image File
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>

                        </table>

                    </div>
                </div>

            </section>
        </div>

    </section>
</div>

@endsection
