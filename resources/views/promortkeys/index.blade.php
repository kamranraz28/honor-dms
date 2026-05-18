@extends('layouts.master_admin')

@section('title')
    {{"E-Warranty Ststem :: Promortkey OR Company"}}
@endsection


@section('content')

    <!-- content part================================ -->

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->

        <!-- bc part================================ -->
        @include('admin.bc.bc')
        <!-- bc part================================ -->



        <!-- Main content -->
        <section class="content-header">
            <div class="row">
                <div class="">

                    <div class="box box-warning">
                        <div class="box-header with-border">
                            <h3 class="box-title">Add Promortkey</h3>
                        </div>


                        <!-- ================================== form area==================================== -->
                        {{-- for for displaying success and errror message --}}
                        <form class="form-horizontal" method="POST" action="{{ route('promortkeys.store') }}"
                            autocomplete="on" enctype="multipart/form-data">
                            <div class="box-body">
                                @if(count($errors))
                                    <div class="alert alert-danger alert-dismissible">
                                        <strong>Whoops!</strong> There were some problems with your input.
                                        <br />
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
                                        <strong>Success!</strong> {{Session::get('success')}}
                                    </div>

                                @endif
                            </div>
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            {{-- for for displaying success and errror message --}}

                            <div class="box-body">
                                <div class="col-md-12">
                                    <div class="form-group {{ $errors->has('name') ? 'has-error' : '' }}">
                                        <label for="name">Promortkey:</label>
                                        <input type="text" id="name" name="name" class="form-control"
                                            placeholder="Enter Promortkey Name" value="{{ old('name') }}">
                                        <span class="text-danger">{{ $errors->first('name') }}</span>
                                    </div>
                                </div>
                            </div>

                            <div class="box-footer">
                                <button type="submit" class="btn btn-success pull-right">Submit</button>
                            </div>

                        </form>

                        <!-- ================================== form area==================================== -->



                    </div>


                </div>
            </div>



            <div class="row">
                <div class="box box-warning">
                    <div class="box-header">
                        <h3 class="box-title">Promortkey List</h3>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">
                        <table id="example" class="display" cellspacing="0" width="100%">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Promortkey </th>
                                    <th>Status </th>
                                    <th>Created Date </th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($promortkeys as $key => $element)


                                    <tr>

                                        <td>{{$key + 1}}</td>
                                        <td>{{$element['name']}}</td>

                                        <td>
                                            @if ($element->status == true)
                                                <button class="btn btn-xs btn-primary" data-toggle="modal"
                                                    data-target="#{{'promoStatusModal' . $element->id}}">Active</button>
                                            @else
                                                <button class="btn btn-xs btn-danger" data-toggle="modal"
                                                    data-target="#{{'promoStatusModal' . $element->id}}">Inactive</button>
                                            @endif
                                        </td>


                                        <td>{{date_format(date_create($element['created_at']), "d-M-Y")}}</td>

                                        <td>

                                            <button class="btn btn-xs btn-primary" data-toggle="modal"
                                                data-target="#{{'promortkeyUpdateModal' . $element['id']}}"><i
                                                    class="fa fa-pencil-square-o" aria-hidden="true"></i></button>

                                            <button class="btn btn-xs btn-danger" data-toggle="modal"
                                                data-target="#{{'promortkeyDeleteModal' . $element['id']}}"><i
                                                    class="fa fa-trash-o" aria-hidden="true"></i></button>

                                        </td>

                                    </tr>
                                @endforeach
                            </tbody>

                        </table>
                    </div>
                    <div class="clear"></div>
                    <!-- /.box-body -->
                </div>
                <!-- /.box -->
            </div>
        </section>






    </div>
    <!-- content part================================ -->


    <!--custom update modal part================================ -->


    @forelse ($promortkeys as $key => $element)
        <!-- Modal -->
        <div class="modal fade" id="{{'promortkeyUpdateModal' . $element['id']}}" tabindex="-1" role="dialog"
            aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-md" role="document">
                <div class="modal-content">

                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">{{$element['name']}}</h5>
                        <button class="close" type="button" data-dismiss="modal" aria-label="Close" style="margin-top: -25px">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>

                    <div class="modal-body">
                        <!-- body part -->

                        <form action="{{route('promortkeys.update',$element['id'])}}" method="post">
                            <h3 class="text-info">Do You Want To Update This Data ?</h3>
                            <br>

                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            <input name="_method" type="hidden" value="put">
                            <input type="hidden" name="id" value="{{ $element['id'] }}">

                            <div class="form-group {{ $errors->has('name') ? 'has-error' : '' }}">
                                <label for="name">Promortkey:</label>
                                <input type="text" id="name" name="name" class="form-control" placeholder="Enter Product Name"
                                    value="{{ $element['name'] }}">
                                <span class="text-danger">{{ $errors->first('name') }}</span>
                            </div>

                            <div class="form-group">
                                <button class="form-control btn btn-success">Update</button>
                            </div>
                        </form>

                        <!-- body part -->
                    </div>

                    <div class="modal-footer">
                        <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                    </div>
                </div>
            </div>
        </div>
    @empty
        {{'Data not found'}}
    @endforelse
    <!--custom update modal part================================ -->

    <!--custom delete modal part================================ -->


    @forelse ($promortkeys as $key => $element)
        <!-- Modal -->
        <div class="modal fade" id="{{'promortkeyDeleteModal' . $element['id']}}" tabindex="-1" role="dialog"
            aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-md" role="document">
                <div class="modal-content">

                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">{{$element['name']}}</h5>
                        <button class="close" type="button" data-dismiss="modal" aria-label="Close" style="margin-top: -25px">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>

                    <div class="modal-body">
                        <!-- body part -->




                        <form action="{{route('promortkeys.destroy', $element['id'])}}" method="post">
                            <h3 class="text-info">Do You Want To Delete This Data ?</h3>
                            <br>
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            <input name="_method" type="hidden" value="delete">

                            <div class="form-group">
                                <button class="form-control btn btn-danger">Delete</button>
                            </div>

                        </form>

                        <!-- body part -->
                    </div>

                    <div class="modal-footer">
                        <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                    </div>
                </div>
            </div>
        </div>
    @empty
        {{'Data not found'}}
    @endforelse
    <!--custom delete modal part================================ -->




    @foreach ($promortkeys as $key => $element)
    {{-- Status Update MODAL --}}
    <div class="modal fade" id="promoStatusModal{{ $element->id }}">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">

                <form method="POST" action="{{ route('admin.promortkey.statusChange') }}">
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
                        this promort key?
                    </div>

                    <div class="modal-footer">
                        <button class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                        <button class="btn btn-success">Yes</button>
                    </div>

                </form>

            </div>
        </div>
    </div>
@endforeach





@endsection
