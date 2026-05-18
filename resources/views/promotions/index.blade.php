@extends('layouts.master_admin')

@section('title')
    {{"Sales Automation Process :: Promotion"}}
@endsection


@section('content')

    <!-- content part================================ -->

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->

        </section>



        <!-- Main content -->
        <section class="content-header">
            <div class="row">
                <div class="">

                    <div class="box box-warning">
                        <div class="box-header with-border">
                            <h3 class="box-title">Add Promo</h3>
                        </div>


                        <!-- ================================== form area==================================== -->
                        {{-- for for displaying success and errror message --}}
                        <form class="form-horizontal" method="POST" action="{{ route('promotions.store') }}"
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


                                <div class="form-group">
                                    <label class="col-md-2 control-label">Start Date</label>

                                    <div class="col-md-5">
                                        <div class="input-group date">
                                            <div class="input-group-addon">
                                                <i class="fa fa-calendar"></i>
                                            </div>
                                            <input name="sdate" placeholder="YYYY-MM-DD"
                                                value="{{@$retVal = ($ssdata['sdate']) ? $ssdata['sdate'] : ""  }}"
                                                type="text" class="form-control pull-right" id="datepicker3"
                                                required="required" autocomplete="off">
                                        </div>
                                    </div>
                                </div>


                                <div class="form-group">
                                    <label class="col-md-2 control-label">End Date</label>


                                    <div class="col-md-5">
                                        <div class="input-group date">
                                            <div class="input-group-addon">
                                                <i class="fa fa-calendar"></i>
                                            </div>
                                            <input name="edate" placeholder="YYYY-MM-DD"
                                                value="{{@$retVal = ($ssdata['edate']) ? $ssdata['edate'] : ""  }}"
                                                type="text" class="form-control pull-right" id="datepicker4"
                                                required="required" autocomplete="off">
                                        </div>
                                    </div>
                                </div>






                                <div class="form-group {{ $errors->has('name') ? 'has-error' : '' }}">
                                    <label class="col-md-2 control-label" for="name">Promotion Name:</label>

                                    <div class="col-md-5">
                                        <input type="text" id="name" name="name" class="form-control"
                                            placeholder="Enter Promo Name" value="{{ old('name') }}">
                                        <span class="text-danger">{{ $errors->first('name') }}</span>
                                    </div>
                                </div>





                                <div class="form-group">
                                    <div class="container1">

                                        <label class="col-md-2 control-label">Promotion Details</label>

                                        <div class="col-md-10">
                                            <button class="add_form_field btn btn-warning btn-sm" style="width:49%">Add
                                                Field</button><br><br>
                                        </div>

                                    </div>
                                </div>



                                <div class="form-group">
                                    <label class="col-md-2 control-label">Status</label>
                                    <div class="col-md-5">
                                        <label class="radio-inline">
                                            <input type="radio" name="status" value="1" checked>Active
                                        </label>
                                        <label class="radio-inline">
                                            <input type="radio" name="status" value="0">Inactive
                                        </label>
                                    </div>
                                </div>


                                <!-- ************************************************* -->
                                <script>
                                    $(document).ready(function () {

                                        var max_fields = 200;
                                        var wrapper = $(".container1");
                                        var add_button = $(".add_form_field");

                                        var total = 0;

                                        var x = 1;
                                        $(add_button).click(function (e) {
                                            e.preventDefault();
                                            if (x < max_fields) {
                                                x++;
                                                $(wrapper).append('<div class="row "style="padding:0px 30px 8px 112px ">' +
                                                    '<div class="col-md-3">' +
                                                    '<select name="products[]" required="required" class="form-control select2" id="product' + x + '">' +
                                                    '<option selected="selected" value="">Select Product</option>' +
                                                    '@foreach ($products as $product)' +
                                                        '<option value="{{$product['id']}}">{{$product['name']}}-{{$product['model']}}</option>' +
                                                    '@endforeach' +
                                                    '</select>' +
                                                    '<span class="text-warning">Select Product</span>' +
                                                    '</div>' +
                                                    '<div class="col-md-2">' +
                                                    '<input type="number" name="amounts[]" id="amount' + x + '" class="form-control" style="" required placeholder="Amount" min="0">' +
                                                    '<span class="text-warning">Amount</span>' +
                                                    '</div>' +
                                                    '<div class="col-md-2">' +
                                                    '<input type="number" name="quantites[]" id="quantity' + x + '" class="form-control" style="" required placeholder="Quantity" min="0">' +
                                                    '<span class="text-warning">Quantity</span>' +
                                                    '</div>' +
                                                    '<div class="col-md-2">' +
                                                    '<input type="number" name="limits[]" id="limit' + x + '" class="form-control" style="" required  placeholder="Limit Per Day" step=any min="0">' +
                                                    '<span class="text-warning">Limit Per Day</span>' +
                                                    '</div>' +
                                                    '<div class="col-md-2">' +
                                                    '<input type="text" name="details[]" id="details' + x + '" class="form-control" style="" required value="N/A" min="0">' +
                                                    '<span class="text-warning">Details</span>' +
                                                    '</div>' +
                                                    '<button id="delete' + x + '"  class="delete btn btn-danger btn-round col-md-1">Delete &nbsp;<span style="font-size:16px; font-weight:bold;"> - </span></button>' +
                                                    '</div>');

                                            }
                                            else {
                                                alert('You Reached the limits')
                                            }

                                            //========================================
                                            var productArea = $("#product" + x);

                                            productArea.on('mouseenter', function (e) {
                                                e.preventDefault();
                                                $('.select2').select2();
                                            });





                                        });


                                        //=========================================================
                                        $(wrapper).on("click", ".delete", function (e) {
                                            e.preventDefault(); $(this).parent('div').remove(); x--;
                                        });

                                        //=========================================================







                                    });




                                </script>




                                <!-- ************************************************* -->










                            </div>

                            <div class="box-footer">
                                <button type="submit" class="btn btn-success pull-right">Submit</button>
                            </div>
                            <div class="box-footer">
                                {{-- <lebel>For SMS: <b>Keyword IMEI Retailer_Code</b> send to 01921112244 EX: MOTO
                                    95878954878 SGB34532</lebel> --}}
                                <br>
                                <lebel>If Code is not Work please Use <a href='http://smartdms.xyz/sap/pre_sms.php'
                                        target="_blank"><b>SMS Campaign From Web</b></a> System</lebel>
                            </div>
                        </form>

                        <!-- ================================== form area==================================== -->



                    </div>


                </div>
            </div>



            <div class="row">
                <div class="box box-warning">
                    <div class="box-header">
                        <h3 class="box-title">Promo List</h3>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">
                        <table id="example" class="ui celled table" cellspacing="0" width="100%">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Details</th>
                                    <th>Promo </th>
                                    <th>Start Date </th>
                                    <th>End Date </th>
                                    <th>Created Date </th>
                                    <th>Status</th>
                                    <th>Action</th>

                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($promos as $key => $element)


                                    <tr>

                                        <td>{{$key + 1}}</td>

                                        <td>

                                            <a href="{{ route('admin.singlepromo', [$element->id]) }}" target="_blank"
                                                class="btn btn-xs btn-info">
                                                <i class="fa fa-info" aria-hidden="true" style="width: 20px"></i>
                                            </a>

                                        </td>


                                        <td>{{$element->name}}</td>
                                        <td>{{$element->sdate}}</td>
                                        <td>{{$element->edate}}</td>
                                        <td>{{date_format(date_create($element->created_at), "d-M-Y")}}</td>



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

                                            <button class="btn btn-xs btn-primary" data-toggle="modal"
                                                data-target="#{{'promoUpdateModal' . $element->id}}"><i
                                                    class="fa fa-pencil-square-o" aria-hidden="true"></i></button>

                                            <button class="btn btn-xs btn-danger" data-toggle="modal"
                                                data-target="#{{'promoDeleteModal' . $element->id}}"><i class="fa fa-trash-o"
                                                    aria-hidden="true"></i></button>

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


    @forelse ($promos as $key => $element)
        <!-- Modal -->
        <div class="modal fade" id="{{'promoUpdateModal' . $element->id}}" tabindex="-1" role="dialog"
            aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-md" role="document">
                <div class="modal-content">

                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">{{$element->name}}</h5>
                        <button class="close" type="button" data-dismiss="modal" aria-label="Close" style="margin-top: -25px">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>

                    <div class="modal-body">
                        <!-- body part -->

                        <form action="{{route('promotions.update',$element->id)}}" method="post">
                            <p class="text-info">Do You Want To Update This Data ?</p>
                            <br>

                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            <input name="_method" type="hidden" value="put">
                            <input type="hidden" name="id" value="{{ $element->id }}">

                            <div class="form-group {{ $errors->has('name') ? 'has-error' : '' }}">
                                <label for="name">Promo:</label>
                                <input type="text" id="name" name="name" class="form-control" placeholder="Enter Product Name"
                                    value="{{ $element->name }}">
                                <span class="text-danger">{{ $errors->first('name') }}</span>
                            </div>



                            <div class="form-group {{ $errors->has('sdate') ? 'has-error' : '' }}">
                                <label for="level2">Start Date:</label>
                                <div class="input-group date">
                                    <div class="input-group-addon">
                                        <i class="fa fa-calendar"></i>
                                    </div>
                                    <input name="sdate" placeholder="YYYY-MM-DD" type="text" class="form-control"
                                        id="datepickerfdf{{$element->id}}" required="required" autocomplete="off"
                                        value="{{ $element['sdate1'] }}">
                                </div>
                                <span class="text-danger">{{ $errors->first('sdate') }}</span>
                            </div>

                            <div class="form-group {{ $errors->has('edate') ? 'has-error' : '' }}">
                                <label for="level2">End Date:</label>
                                <div class="input-group date">
                                    <div class="input-group-addon">
                                        <i class="fa fa-calendar"></i>
                                    </div>
                                    <input name="edate" placeholder="YYYY-MM-DD" type="text" class="form-control"
                                        id="datepickerfde{{$element->id}}" required="required" autocomplete="off"
                                        value="{{ $element['edate1'] }}">
                                </div>
                                <span class="text-danger">{{ $errors->first('edate') }}</span>
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


        <script>
            $(function () {


                $('#datepickerfdf{{$element->id}}').datepicker({
                    format: 'yyyy-mm-dd',
                    autoclose: true
                });
                $('#datepickerfde{{$element->id}}').datepicker({
                    format: 'yyyy-mm-dd',
                    autoclose: true
                });



            })
        </script>



    @empty
        {{'Data not found'}}
    @endforelse
    <!--custom update modal part================================ -->

    <!--custom delete modal part================================ -->


    @forelse ($promos as $key => $element)
        <!-- Modal -->
        <div class="modal fade" id="{{'promoDeleteModal' . $element->id}}" tabindex="-1" role="dialog"
            aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-md" role="document">
                <div class="modal-content">

                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">{{$element->name}}</h5>
                        <button class="close" type="button" data-dismiss="modal" aria-label="Close" style="margin-top: -25px">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>

                    <div class="modal-body">
                        <!-- body part -->




                        <form action="{{route('promotions.destroy', $element->id)}}" method="post">
                            <p class="text-info">Do You Want To Delete This Data ?</p>
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



    <!--custom promoStatusModal modal part================================ -->


    @forelse ($promos as $key => $element)
        <!-- Modal -->
        <div class="modal fade" id="{{'promoStatusModal' . $element->id}}" tabindex="-1" role="dialog"
            aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-md" role="document">
                <div class="modal-content">

                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">{{$element->name}}</h5>
                        <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>

                    <div class="modal-body">
                        <!-- body part -->


                        @if ($element->status == true)
                            <form action="{{ route('admin.promo.changeActiveStatus') }}" method="post">
                                <p class="text-info">Do You Want To Inactive This Product ?</p>
                                <br>
                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                <input type="hidden" name="id" value="{{ $element->id }}">
                                <input type="hidden" name="status" value="{{ $element->status }}">

                                <div class="form-group">
                                    <button class="form-control btn btn-danger">Inactive</button>
                                </div>

                            </form>
                        @else
                            <form action="{{ route('admin.promo.changeActiveStatus') }}" method="post">
                                <p class="text-info">Do You Want To Active This Product ?</p>
                                <br>
                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                <input type="hidden" name="id" value="{{ $element->id }}">
                                <input type="hidden" name="status" value="{{ $element->status }}">
                                <!-- <input name="_method" type="hidden" value="delete"> -->

                                <div class="form-group">
                                    <button class="form-control btn btn-primary">Active</button>
                                </div>

                            </form>
                        @endif



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
    <!--custom promoStatusModal modal part================================ -->



@endsection
