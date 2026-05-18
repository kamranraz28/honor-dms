@extends('layouts.master_admin')

@section('title')
    {{"Sales Automation Process :: Specification"}}
@endsection

@section('content')

<div class="content-wrapper">

    <section class="content-header">
        <div class="row">
            <div class="">
                <div class="box box-warning">
                    <div class="box-header with-border">
                        <h3 class="box-title">Add Specification</h3>
                    </div>

                    <form class="form-horizontal" method="POST" action="{{ route('specifications.store') }}" autocomplete="on" enctype="multipart/form-data">
                        <div class="box-body">

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

                            @if(Session::has('success'))
                                <div class="alert alert-success alert-dismissible fade in">
                                    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                                    <strong>Success!</strong> {{ Session::get('success') }}
                                </div>
                            @endif

                        </div>

                        <input type="hidden" name="_token" value="{{ csrf_token() }}">

                        <div class="box-body">

                            <div class="col-md-12">
                                <div class="form-group {{ $errors->has('product_id') ? 'has-error' : '' }}">
                                    <label>Product</label>

                                    <input type="text" id="product_search" class="form-control"
                                        placeholder="Type to Search Product..."
                                        list="product_list"
                                        autocomplete="off">

                                    <datalist id="product_list"></datalist>

                                    <input type="hidden" name="product_id" id="product_id">

                                    <span class="text-danger">{{ $errors->first('product_id') }}</span>
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="form-group {{ $errors->has('name') ? 'has-error' : '' }}">
                                    <label for="name">Specification:</label>
                                    <input type="text" id="name" name="name" class="form-control"
                                           placeholder="Enter Specification Name" value="{{ old('name') }}">
                                    <span class="text-danger">{{ $errors->first('name') }}</span>
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="form-group {{ $errors->has('specificationdetails') ? 'has-error' : '' }}">
                                    <label for="specificationdetails">Specification Details:</label>
                                    <textarea id="specificationdetails" name="specificationdetails" rows="1"
                                              class="form-control" placeholder="Input Specification Details">{{ old('specificationdetails') }}</textarea>
                                    <span class="text-danger">{{ $errors->first('specificationdetails') }}</span>
                                </div>
                            </div>

                        </div>

                        <div class="box-footer">
                            <button type="submit" class="btn btn-success pull-right">Submit</button>
                        </div>

                    </form>

                </div>
            </div>
        </div>

        <div class="row">

            <div class="box box-warning">
                <div class="box-header">
                    <h3 class="box-title">Specification List</h3>
                </div>

                <div class="box-body">

                    <table id="example" class="ui celled table" cellspacing="0" width="100%">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Product</th>
                                <th>Model</th>
                                <th>Specification</th>
                                <th>Specification Details</th>
                                <th>Created Date</th>
                                <th>Action</th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach ($specifications as $key => $element)
                                <tr>
                                    <td>{{ $key + 1 }}</td>
                                    <td>{{ $element->product['name'] }}</td>
                                    <td>{{ $element->product['model'] }}</td>
                                    <td>{{ $element->name }}</td>

                                    <td class="text-justify"
                                        style="cursor:pointer;color: black;font-weight: bolder"
                                        data-toggle="modal"
                                        data-target="#{{ 'specificdetailsfoModal'. $element->id }}">
                                        @if ($element->specificationdetails)
                                            {!! substr($element->specificationdetails, 0, 40) !!}
                                        @else
                                            N/A
                                        @endif
                                    </td>

                                    <td>{{ date_format(date_create($element->created_at),"d-M-Y") }}</td>

                                    <td>
                                        <button class="btn btn-xs btn-primary"
                                                data-toggle="modal"
                                                data-target="#{{ 'specificationUpdateModal'. $element->id }}">
                                            <i class="fa fa-pencil-square-o"></i>
                                        </button>

                                        <button class="btn btn-xs btn-danger"
                                                data-toggle="modal"
                                                data-target="#{{ 'specificationDeleteModal'. $element->id }}">
                                            <i class="fa fa-trash-o"></i>
                                        </button>
                                    </td>

                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                </div>

                <div class="clear"></div>

            </div>

        </div>

    </section>

</div>


@foreach ($specifications as $key => $element)
<div class="modal fade" id="{{ 'specificationUpdateModal'. $element->id }}" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-md">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title">{{ $element->name }}</h5>
                <button class="close" type="button" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>

            <div class="modal-body">

                <form action="{{ route('specifications.update',$element->id) }}" method="post">
                    <h3 class="text-info">Do You Want To Update This Data?</h3>

                    <br>

                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <input name="_method" type="hidden" value="put">
                    <input type="hidden" name="id" value="{{ $element->id }}">
                    <input type="hidden" name="product_id" value="{{ $element->product_id }}">

                    <div class="form-group {{ $errors->has('name') ? 'has-error' : '' }}">
                        <label for="name">Specification:</label>
                        <input type="text" id="name" name="name" class="form-control"
                               value="{{ $element->name }}">
                        <span class="text-danger">{{ $errors->first('name') }}</span>
                    </div>

                    <div class="form-group {{ $errors->has('specificationdetails') ? 'has-error' : '' }}">
                        <label for="specificationdetails">Specification Details:</label>
                        <textarea id="details1" name="specificationdetails"
                                  class="form-control" required>{{ $element->specificationdetails }}</textarea>
                        <span class="text-danger">{{ $errors->first('specificationdetails') }}</span>
                    </div>

                    <div class="form-group">
                        <button class="form-control btn btn-success">Update</button>
                    </div>

                </form>

            </div>

            <div class="modal-footer">
                <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
            </div>

        </div>
    </div>
</div>
@endforeach


@foreach ($specifications as $key => $element)
<div class="modal fade" id="{{ 'specificationDeleteModal'. $element->id }}" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-md">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title">{{ $element->name }}</h5>
                <button class="close" type="button" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>

            <div class="modal-body">

                <form action="{{ route('specifications.destroy',$element->id) }}" method="post">
                    <h3 class="text-info">Do You Want To Delete This Data?</h3>

                    <br>

                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <input name="_method" type="hidden" value="delete">

                    <div class="form-group">
                        <button class="form-control btn btn-danger">Delete</button>
                    </div>
                </form>

            </div>

            <div class="modal-footer">
                <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
            </div>

        </div>
    </div>
</div>
@endforeach


@foreach ($specifications as $key => $element)
<div class="modal fade" id="{{ 'specificdetailsfoModal'. $element->id }}" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-md">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title">{{ $element->name }}</h5>
                <button class="close" type="button" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>

            <div class="modal-body">
                <p>{!! nl2br($element->specificationdetails) !!}</p>
            </div>

            <div class="modal-footer">
                <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
            </div>

        </div>
    </div>
</div>
@endforeach

@endsection
