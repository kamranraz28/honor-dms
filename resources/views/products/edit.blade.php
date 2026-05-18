@extends('layouts.master_admin')

@section('title')
    {{ 'Sales Automation Process :: Edit Product' }}
@endsection

@section('content')
    <div class="content-wrapper">
        <section class="content">

            <div class="row">
                <section class="col-lg-12 connectedSortable">

                    <div class="box box-warning">
                        <div class="box-header">
                            <h3 class="box-title text-danger">Edit Product</h3>
                        </div>

                        <div class="box-body">

                            @if (count($errors))
                                <div class="alert alert-danger">
                                    <strong>Whoops!</strong>
                                    <ul>
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            <form class="form-horizontal" method="POST"
                                action="{{ route('products.update', $product->id) }}" enctype="multipart/form-data">

                                {{ csrf_field() }}
                                {{ method_field('PUT') }}

                                <div class="box-body">

                                    {{-- Brand + Category --}}
                                    <div class="row">
                                        <div class="col-md-6">
                                            <label class="control-label">Brand</label>
                                            <input type="text"
                                                   id="brand_search"
                                                   name="brand_search"
                                                   class="form-control"
                                                   placeholder="Type to Search brand..."
                                                   list="brand_list"
                                                   autocomplete="off"
                                                   value="{{ old('brand_search', $product->brand->name ?? '') }}"
                                                   required>
                                            <datalist id="brand_list"></datalist>
                                            <input type="hidden" name="brand_id" id="brand_id"
                                                   value="{{ old('brand_id', $product->brand_id) }}">
                                            <span class="text-danger">{{ $errors->first('brand_id') }}</span>
                                        </div>

                                        <div class="col-md-6">
                                            <label class="control-label">Category</label>
                                            <input type="text"
                                                   id="category_search"
                                                   name="category_search"
                                                   class="form-control"
                                                   placeholder="Type to Search category..."
                                                   list="category_list"
                                                   autocomplete="off"
                                                   value="{{ old('category_search', $product->cat->name ?? '') }}">
                                            <datalist id="category_list"></datalist>
                                            <input type="hidden" name="cat_id" id="category_id"
                                                   value="{{ old('cat_id', $product->cat_id) }}">
                                            <span class="text-danger">{{ $errors->first('cat_id') }}</span>
                                        </div>
                                    </div>

                                    {{-- Name + Model --}}
                                    <div class="row">
                                        <div class="col-md-6">
                                            <label>Product Name</label>
                                            <input type="text" name="name" class="form-control"
                                                value="{{ $product->name }}" required>
                                        </div>

                                        <div class="col-md-6">
                                            <label>Product Model</label>
                                            <input type="text" name="model" class="form-control"
                                                value="{{ $product->model }}" required>
                                        </div>
                                    </div>

                                    {{-- Code + Color --}}
                                    <div class="row">
                                        <div class="col-md-6">
                                            <label>Product Code</label>
                                            <input type="text" name="product_code" class="form-control"
                                                value="{{ $product->product_code }}" required>
                                        </div>

                                        <div class="col-md-6">
                                            <label>Color</label>
                                            <input type="text" name="color" class="form-control"
                                                value="{{ $product->color }}">
                                        </div>
                                    </div>

                                    {{-- Price + Chalan --}}
                                    <div class="row">
                                        <div class="col-md-6">
                                            <label>Distributor Price</label>
                                            <input type="text" name="dp" class="form-control"
                                                value="{{ $product->dp }}">
                                        </div>

                                        <div class="col-md-6">
                                            <label>Chalan Type</label>
                                            <input type="text" name="chalan_type" class="form-control"
                                                value="{{ $product->chalan_type }}">
                                        </div>
                                    </div>

                                    {{-- Details --}}
                                    <div class="row">
                                        <div class="col-md-12">
                                            <label>Details</label>
                                            <textarea name="details" class="form-control" rows="2">{{ $product->details }}</textarea>
                                        </div>
                                    </div>

                                    {{-- Image --}}
                                    <div class="row">
                                        <div class="col-md-12">
                                            <label>Product Image</label>
                                            <input type="file" name="image" class="form-control">

                                            @if ($product->photo)
                                                <p style="margin-top:10px;">
                                                    <a target="_blank"
                                                        href="{{ asset('storage/app/d/nokia/' . $product->photo) }}">
                                                        View Current Image
                                                    </a>
                                                </p>
                                            @endif
                                        </div>
                                    </div>

                                </div>

                                <div class="box-footer">
                                    <button type="submit" class="action-btn action-sync">
                                        <span class="btn-icon"><i class="fa fa-save"></i></span>
                                        <span class="btn-text">Update Product</span>
                                        <span class="action-chip">Submit</span>
                                    </button>
                                </div>

                            </form>

                        </div>
                    </div>

                </section>
            </div>

        </section>
    </div>
@endsection
