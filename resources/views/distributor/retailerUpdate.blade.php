@extends('layouts.master_distributor')

@section('title')
  {{"E-Warranty Ststem :: Edit Retailer"}}
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


<!-- Main row -->
<div class="row">
  <!-- Left col -->
  


<!-- ==============one section area ================= -->


  <section class="col-lg-12 connectedSortable">
          <!-- Recent Invoice -->
          <div class="box box-warning">
            <div class="box-header">
              <h3 class="box-title text-danger">Distributor Retail Information</h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
  
            <!-- form start -->
            @if(Session::has('success'))
      

      <div class="alert alert-success alert-dismissible fade in">
        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
        <strong>Success!</strong> {{Session::get('success')}}
      </div>

    @endif
             


            </div>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->


  </section>

<!-- ==============one section area ================= -->

<!-- ==============one section area ================= -->


  <section class="col-lg-12 connectedSortable">
          <!-- Recent Invoice -->
          <div class="box box-warning">
            <div class="box-header">

            </div>
            <!-- /.box-header -->
            <div class="box-body">


<table id="example" class="ui celled table" width="100%">
         

<thead>
    <tr>
        <th>Retail Name</th>
        <th>Retail Code</th>
        <th>Contact Name</th>
        <th>Contact No</th>
        <th>Market Name</th>
        <th>Address</th>
        <th>Action</th>
    </tr>
</thead>
<tbody>
    @foreach($retailerList as $retailer)
        <tr>
            <td>{{ $retailer['retailerName'] }}</td>
            <td>{{ $retailer['retailerCode'] }}</td>
            <td>{{ $retailer['contactName'] }}</td>
            <td>{{ $retailer['contact'] }}</td>
            <td>{{ $retailer['marketName'] }}</td>
            <td>{{ $retailer['address'] }}</td>
            <td>
            <button type="button" class="btn btn-md btn-warning" data-toggle="modal"
                                                data-target="#myModal_{{ $retailer['id'] }}">Edit</button>
                                                

                                            <div id="myModal_{{ $retailer['id'] }}"class="modal fade" role="dialog">
                                                <div class="modal-dialog">
                                                    <!-- Modal content-->
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <button type="button" class="close"
                                                                data-dismiss="modal">&times;</button>
                                                            <h4 class="modal-title">Update Retail Information - <b>({{ $retailer['retailerCode'] }})</b></h4>
                                                        </div>
                                                        <form action="{{ route('distributor.retailerEditUpdate') }}" method="post">
                                                            @csrf
                                                            <div class="modal-body">
                                                            <div class="form-group">
                                                                    <input type="hidden" name="user_id" value="{{ $retailer['id'] }}">
                                                                </div>
                                                                <div class="form-group">
                                                                    <label for="retailerName">Retail Name</label>
                                                                    <input type="text" class="form-control" name="retailerName" value="{{ $retailer['retailerName'] }}">
                                                                </div>
                                                                <div class="form-group">
                                                                    <label for="contactName">Contact Name</label>
                                                                    <input type="text" class="form-control" name="contactName" value="{{ $retailer['contactName'] }}">
                                                                </div>
                                                                <div class="form-group">
                                                                    <label for="contact">Contact</label>
                                                                    <input type="text" class="form-control" name="contact" value="{{ $retailer['contact'] }}">
                                                                </div>
                                                                <div class="form-group">
                                                                    <label for="marketName">Market Name</label>
                                                                    <input type="text" class="form-control" name="marketName" value="{{ $retailer['marketName'] }}">
                                                                </div>
                                                                <div class="form-group">
                                                                    <label for="address">Address</label>
                                                                    <input type="text" class="form-control" name="address" value="{{ $retailer['address'] }}">
                                                                </div>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                                                <button type="submit" class="btn btn-success" name="updateInformation">Update Information</button>
                                                        </div>
                                                    </form>
                                                    </div>
                                                </div>
                                            </div>
            </td>
        </tr>
    @endforeach

   
    
</tbody>


  </table>







            </div>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->


  </section>

<!-- ==============one section area ================= -->









</div>
<!-- /.row (main row) -->
















    </section>
    <!-- /.content -->
 
  </div>
<!-- /.content-wrapper -->

<!-- content part================================ -->
@endsection