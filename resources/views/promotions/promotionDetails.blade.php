@extends('layouts.master_admin')

@section('title')
    E-Warranty System :: Promotion Details
@endsection

@section('content')

<div class="content-wrapper">
    <section class="content-header">

        {{-- Alerts --}}
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <div class="box box-warning">
            <div class="box-header">
                <h3 class="box-title">Promotion Details</h3>
            </div>

            <div class="box-body">

                {{-- ADD BUTTON --}}
                <div class="text-right" style="margin-bottom: 10px;">
                    <button class="btn btn-sm btn-success"
                        data-toggle="modal"
                        data-target="#addPromoDetailModal">
                        <i class="fa fa-plus"></i> Add Promo Detail
                    </button>
                </div>

                {{-- TABLE --}}
                <table id="example" class="display" cellspacing="0" width="100%">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Promotion</th>
                            <th>Product</th>
                            <th>Model</th>
                            <th>Amount</th>
                            <th>Quantity</th>
                            <th>Limit / Day</th>
                            <th>Details</th>
                            <th>Start</th>
                            <th>End</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse ($promos as $element)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $element->promo->name }}</td>
                                <td>{{ $element->product->name }}</td>
                                <td>{{ $element->product->model }}</td>
                                <td>{{ $element->amount }}</td>
                                <td>{{ $element->quantity }}</td>
                                <td>{{ $element->limitperday }}</td>
                                <td>{{ $element->details ?? 'N/A'}}</td>
                                <td>{{ $element->sdate }}</td>
                                <td>{{ $element->edate }}</td>
                                <td>
                                    @if ($element->status == true)
                                        <button class="btn btn-xs btn-primary" data-toggle="modal"
                                            data-target="#{{'promoStatusModal' . $element->id}}">Active</button>
                                    @else
                                        <button class="btn btn-xs btn-danger" data-toggle="modal"
                                            data-target="#{{'promoStatusModal' . $element->id}}">Inactive</button>
                                    @endif
                                </td>
                                <td>
                                    <button class="btn btn-xs btn-primary"
                                        data-toggle="modal"
                                        data-target="#updateModal{{ $element->id }}">
                                        Edit
                                    </button>

                                    <button class="btn btn-xs btn-danger"
                                        data-toggle="modal"
                                        data-target="#deleteModal{{ $element->id }}">
                                        Delete
                                    </button>
                                </td>
                            </tr>

                            {{-- Status Update MODAL --}}
                            <div class="modal fade" id="promoStatusModal{{ $element->id }}">
                                <div class="modal-dialog modal-sm">
                                    <div class="modal-content">

                                        <form method="POST"
                                            action="{{ route('admin.promo.changeActiveStatusPromoDetails') }}">
                                            @csrf

                                            <input type="hidden" name="id" value="{{ $element->id }}">
                                            <input type="hidden" name="status" value="{{ $element->status }}">

                                            <div class="modal-header">
                                                <h4 class="modal-title">Change Status</h4>
                                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                                            </div>

                                            <div class="modal-body">
                                                Are you sure you want to
                                                <strong>{{ $element->status ? 'Inactive' : 'Activate' }}</strong>
                                                this promo detail?
                                            </div>

                                            <div class="modal-footer">
                                                <button class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                                                <button class="btn btn-success">Yes</button>
                                            </div>

                                        </form>

                                    </div>
                                </div>
                            </div>


                            {{-- UPDATE MODAL --}}
                            <div class="modal fade" id="updateModal{{ $element->id }}">
                                <div class="modal-dialog modal-md">
                                    <div class="modal-content">
                                        <form method="POST" action="{{ route('admin.promo.promodetails.update') }}">
                                            @csrf
                                            @method('PUT')

                                            <div class="modal-header">
                                                <h4 class="modal-title">Update Promo Detail</h4>
                                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                                            </div>

                                            <div class="modal-body">
                                                <input type="hidden" name="id" value="{{ $element->id }}">

                                                <div class="form-group">
                                                    <label>Product</label>
                                                    <select name="product_id" class="form-control select2" required>
                                                        @foreach ($products as $product)
                                                            <option value="{{ $product->id }}"
                                                                {{ $element->product_id == $product->id ? 'selected' : '' }}>
                                                                {{ $product->name }} - {{ $product->model }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>

                                                <div class="form-group">
                                                    <label>Amount</label>
                                                    <input type="number" name="amount" class="form-control"
                                                        value="{{ $element->amount }}" required>
                                                </div>

                                                <div class="form-group">
                                                    <label>Quantity</label>
                                                    <input type="number" name="quantity" class="form-control"
                                                        value="{{ $element->quantity }}" required>
                                                </div>

                                                <div class="form-group">
                                                    <label>Limit Per Day</label>
                                                    <input type="number" name="limitperday" class="form-control"
                                                        value="{{ $element->limitperday }}" required>
                                                </div>

                                                <div class="form-group">
                                                    <label>Details</label>
                                                    <input type="text" name="details" class="form-control"
                                                        value="{{ $element->details }}" required>
                                                </div>
                                            </div>

                                            <div class="modal-footer">
                                                <button class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                                                <button class="btn btn-success">Update</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>

                            {{-- DELETE MODAL --}}
                            <div class="modal fade" id="deleteModal{{ $element->id }}">
                                <div class="modal-dialog modal-sm">
                                    <div class="modal-content">
                                        <form method="POST"
                                            action="{{ route('admin.promo.promodetails.delete', $element->id) }}">
                                            @csrf
                                            @method('DELETE')

                                            <div class="modal-header">
                                                <h4 class="modal-title">Delete</h4>
                                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                                            </div>

                                            <div class="modal-body">
                                                Are you sure you want to delete this promo detail?
                                            </div>

                                            <div class="modal-footer">
                                                <button class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                                                <button class="btn btn-danger">Delete</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>

                        @empty
                            <tr>
                                <td colspan="11" class="text-center">No promo details found</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </section>
</div>

{{-- ADD MODAL --}}
<div class="modal fade" id="addPromoDetailModal">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <form method="POST" action="{{ route('admin.promo.promodetails.add') }}">
                @csrf

                <div class="modal-header">
                    <h4 class="modal-title">Add Promo Detail</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>

                <div class="modal-body">
                    <input type="hidden" name="promo_id" value="{{ $promos->first()->promo_id ?? '' }}">

                    <div class="form-group">
                        <label>Product</label>
                        <select name="product_id" class="form-control select2" required>
                            <option value="">Select product</option>
                            @foreach ($products as $product)
                                <option value="{{ $product->id }}">
                                    {{ $product->name }} - {{ $product->model }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Amount</label>
                        <input type="number" name="amount" class="form-control" step="any" required>
                    </div>

                    <div class="form-group">
                        <label>Quantity</label>
                        <input type="number" name="quantity" class="form-control" required>
                    </div>

                    <div class="form-group">
                        <label>Limit Per Day</label>
                        <input type="number" name="limitperday" class="form-control" required>
                    </div>

                    <div class="form-group">
                        <label>Details</label>
                        <input type="text" name="details" class="form-control" required>
                    </div>
                </div>

                <div class="modal-footer">
                    <button class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button class="btn btn-success">Add</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    $('.select2').select2({
        width: '100%'
    });
</script>
@endpush
