@extends('layouts.master_admin')

@section('title')
    {{ "Sales Automation Process :: Create Product" }}
@endsection

@section('content')

<div class="content-wrapper">
    <section class="content">

        <div class="row">
            <section class="col-lg-12 connectedSortable">

                <div class="box box-warning">
                    <div class="box-header">
                        <h3 class="box-title text-danger">Create Product</h3>
                    </div>

                    <div class="box-body">

                        {{-- Error Messages --}}
                        @if(count($errors))
                            <div class="alert alert-danger alert-dismissible">
                                <button type="button" class="close" data-dismiss="alert">&times;</button>
                                <strong>Whoops!</strong> There were some problems with your input.
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
                                <a href="#" class="close" data-dismiss="alert">&times;</a>
                                <strong>Success!</strong> {{ Session::get('success') }}
                            </div>
                        @endif

                        {{-- Form Start --}}
                        <form class="form-horizontal"
                              method="POST"
                              action="{{ route('products.store') }}"
                              enctype="multipart/form-data"
                              autocomplete="off">

                            <input type="hidden" name="_token" value="{{ csrf_token() }}">

                            <div class="box-body">

                                {{-- Brand + Category --}}
                                <div class="row">
                                    <div class="col-md-6">
                                        <label class="control-label">Brand</label>
                                        <input type="text"
                                               id="brand_search"
                                               class="form-control"
                                               placeholder="Type to Search brand..."
                                               list="brand_list"
                                               autocomplete="off"
                                               required>
                                        <datalist id="brand_list"></datalist>
                                        <input type="hidden" name="brand_id" id="brand_id">
                                        <span class="text-danger">{{ $errors->first('brand_id') }}</span>
                                    </div>

                                    <div class="col-md-6">
                                        <label class="control-label">Category</label>
                                        <input type="text"
                                               id="category_search"
                                               class="form-control"
                                               placeholder="Type to Search category..."
                                               list="category_list"
                                               autocomplete="off">
                                        <datalist id="category_list"></datalist>
                                        <input type="hidden" name="cat_id" id="category_id">
                                        <span class="text-danger">{{ $errors->first('cat_id') }}</span>
                                    </div>
                                </div>

                                {{-- Product Name + Model --}}
                                <div class="row">
                                    <div class="col-md-6">
                                        <label class="control-label">Product Name</label>
                                        <input type="text"
                                               name="name"
                                               class="form-control"
                                               placeholder="Enter Product"
                                               value="{{ old('name') }}"
                                               required>
                                        <span class="text-danger">{{ $errors->first('name') }}</span>
                                    </div>

                                    <div class="col-md-6">
                                        <label class="control-label">Product Model</label>
                                        <input type="text"
                                               name="model"
                                               class="form-control"
                                               placeholder="Enter Product Model Name"
                                               value="{{ old('model') }}"
                                               required>
                                        <span class="text-danger">{{ $errors->first('model') }}</span>
                                    </div>
                                </div>

                                {{-- Product Code + Color --}}
                                <div class="row">
                                    <div class="col-md-6">
                                        <label class="control-label">Product Code</label>
                                        <input type="text"
                                               name="product_code"
                                               class="form-control"
                                               placeholder="Enter Product Code"
                                               value="{{ old('product_code') }}"
                                               required>
                                        <span class="text-danger">{{ $errors->first('product_code') }}</span>
                                    </div>

                                    <div class="col-md-6">
                                        <label class="control-label">Color</label>
                                        <input type="text"
                                               name="color"
                                               class="form-control"
                                               placeholder="Enter Product Color"
                                               value="{{ old('color') }}">
                                        <span class="text-danger">{{ $errors->first('color') }}</span>
                                    </div>
                                </div>

                                {{-- Distributor Price + Chalan Type --}}
                                <div class="row">
                                    <div class="col-md-6">
                                        <label class="control-label">Distributor Price</label>
                                        <input type="text"
                                               name="dp"
                                               class="form-control"
                                               placeholder="Enter Distributor Price"
                                               value="{{ old('dp') }}">
                                        <span class="text-danger">{{ $errors->first('dp') }}</span>
                                    </div>

                                    <div class="col-md-6">
                                        <label class="control-label">Chalan Type</label>
                                        <input type="text"
                                               name="chalan_type"
                                               class="form-control"
                                               placeholder="Enter Chalan Type"
                                               value="{{ old('chalan_type') }}">
                                        <span class="text-danger">{{ $errors->first('chalan_type') }}</span>
                                    </div>
                                </div>

                                {{-- Details --}}
                                <div class="row">
                                    <div class="col-md-12">
                                        <label class="control-label">Details</label>
                                        <textarea name="details"
                                                  rows="2"
                                                  class="form-control"
                                                  placeholder="Input Details">{{ old('details') }}</textarea>
                                        <span class="text-danger">{{ $errors->first('details') }}</span>
                                    </div>
                                </div>

                                {{-- Product Image --}}
                                <div class="row">
                                    <div class="col-md-12">
                                        <label class="control-label">Product Image</label>
                                        <input type="file"
                                               name="image"
                                               class="form-control">
                                        <span class="text-danger">{{ $errors->first('image') }}</span>
                                    </div>
                                </div>

                            </div>

                            <div class="box-footer">
                                <button type="submit" class="action-btn action-sync">
                                    <span class="btn-icon">
                                        <i class="fa fa-save"></i>
                                    </span>
                                    <span class="btn-text">Save Product</span>
                                    <span class="action-chip">Submit</span>
                                </button>
                            </div>

                        </form>
                        {{-- Form End --}}

                    </div>
                </div>

            </section>
        </div>

    </section>
</div>

@endsection
