@extends('layouts.master_admin')

@section('title')
  {{"DMS ::  Secondary Sales"}}
@endsection


@section('content')

<!-- content part================================ -->

    <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Secondary Sales
        <small>Control panel</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="{{ route('admin.dashboard') }}"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="#"></i> REPORTS</a></li>
        <li class="active"><a href="{{ route('admin.distributorSales') }}">Distributor Sale</a></li>
      </ol>
    </section>

 
    <!-- Main content -->
    <section class="content-header">
      <div class="row">
        <div class="">
      <div class="box box-warning">
            <div class="box-header with-border">
              <h3 class="box-title">Search Distributors Sale</h3>
            </div>
            <!-- /.box-header -->
            <!-- form start -->
             <form class="form-horizontal" method="POST" action="{{ route('admin.distributorSales.store') }}" autocomplete="on" enctype="multipart/form-data">
<div class="box-body">
    @if(count($errors))
      <div class="alert alert-danger alert-dismissible">
        <strong>Whoops!</strong> There were some problems with your input.
        <br/>
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
    <input type="hidden" name="user_id" value="{{ Auth::id() }}">
{{-- for for displaying success and errror message --}}
                <div class="box-body">
                <div class="form-group">
                  <label class="col-sm-2 control-label">Date</label>

                  <div class="col-sm-5">
                    <div class="input-group date">
                      <div class="input-group-addon">
                        <i class="fa fa-calendar"></i>
                      </div>
                      <input name="fdate" placeholder="DD/MM/YYYY" type="text" class="form-control pull-right" id="datepicker"  required="required" autocomplete="off">
                    </div>
                  </div>

                  <div class="col-sm-5">
                    <div class="input-group date">
                      <div class="input-group-addon">
                        <i class="fa fa-calendar"></i>
                      </div>
                      <input name="todate" placeholder="DD/MM/YYYY" type="text" class="form-control pull-right" id="datepicker1"  required="required" autocomplete="off">
                    </div>
                  </div>
                </div>
                
                </div>
              <!-- /.box-body -->
              <div class="box-footer">
                <button type="submit" class="btn btn-success pull-right">Submit</button>
              </div>
              <!-- /.box-footer -->
            </form>
          </div>
        </div>
      </div>
      <div class="row">
            <div class="box box-warning">
            <div class="box-header">
              <h3 class="box-title">Stock List</h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
              <table id="example" class="display" cellspacing="0" width="100%">
                <thead>
                <tr>
                  <th colspan="7"> Date: {{$from_date}} to {{$to_date}}</th>
                </tr>


                  
                <tr>
                  <th>Region Name</th>
                  <th>Territory Name</th>
                  <th>Distributor Name</th>
                  <th>Product Name</th>
                  <th>Product SKU</th>
                  <th>Product Qantity</th>
                  <th>Date</th>
                </tr>
                </thead>
                <tbody>
@foreach ($distsales as $element)
  
  <tr>
    <td>{{$element['region']['region']}}</td>
    <td>{{$element['territory']['territory']}}</td>
    <td>{{$element['distributor']['distributor']}} </td>
    <td>{{$element['product']['product']}} </td>
    <td>{{$element['product']['sku']}} </td>
    <td>{{round($element['product_qty'], 2) }}</td>
    <td>{{$element['date']}}</td>
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
@endsection