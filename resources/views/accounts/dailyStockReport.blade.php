@extends('layouts.master_accounts')

@section('title')
  {{"E-Warranty Ststem :: Stock Report"}}
@endsection


@section('content')


<!-- content part================================ -->

    <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <!-- bc part================================ -->
      @include('accounts.bc.bc')
    <!-- bc part================================ -->



<!-- Main row -->
<div class="row">
  <!-- Left col -->
  


<!-- ==============one section area ================= -->


  <section class="col-lg-12 connectedSortable">
          <!-- Recent Invoice -->
          <div class="box box-warning">
            <div class="box-header">
              <h3 class="box-title text-danger">Daily Stock Report</h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">


            <section class="content-header">
      <div class="row">
        <div class="">
      
      <div class="box box-warning">
            <div class="box-header with-border">
              <h3 class="box-title">Product Add</h3>
            </div>
    

    <!-- ================================== form area==================================== -->
{{-- for for displaying success and errror message --}}
  <form class="form-horizontal" method="POST" action="{{ route('accounts.dailyStockReport.store') }}" autocomplete="off" enctype="multipart/form-data">
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
{{-- for for displaying success and errror message --}}

      <div class="box-body">
        
        
                
          <div class="form-group {{ $errors->has('product_id') ? 'has-error' : '' }}">
      
              <label class="col-sm-2 control-label">Product :</label>
<div class="col-sm-5">
              <select name="product_id" id="product" class="form-control select2" required="required">
                <option value="">Select Product</option>
                @foreach($products as $key=>$product )
                  <option value="{{ $product['id'] }}">{{ $product['name'] }}-{{ $product['model'] }}</option>
                @endforeach
              </select>          

            <span class="text-danger">{{ $errors->first('product_id') }}</span>


</div>
               
        </div>






</div>




<!-- ************************************************* -->






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
         
            <!-- /.box-header -->
            <div class="box-body">
              


            </div>
            <div class="clear"></div>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->
        </div>
    </section>
  
            <!-- form start -->
     

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
        <th> # </th>
        <th> Product </th>
        <th> Model </th>
   
        <th> Stock In </th>
        <th> Stock Out </th>
        <th> Stock </th>
      </tr>

    </thead>
    <tbody>

@foreach ($dailyStockReports as $key => $element)
<tr>
          <td> {{$key + 1}} </td>
          <td> {{$element['product']}}</td>
          <td>{{$element['model']}}</td>
      
          <td> {{$element['stockin']}} </td>
          <td> {{$element['stockout']}} </td>
          <td> {{$element['stock']}} </td>
          
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




<!-- <script type="text/javascript">
  

$(document).ready(function() {
  
  $('#level').on('change', function(e){
    var level = e.target.value;


    var route = "{{--route('ajax.GetUsersOnLevelChange')--}}/"+level;
    $.get(route, function(data) {
      //console.log(data);
      $('#user_id').empty();
      $('#user_id').append('<option value="">Select User</option>');
      $.each(data, function(index,data){
        $('#user_id').append('<option value="' + data.id + '">' + data.firstname + " "+ data.lastname + " ( " +data.email +" ) " +  '</option>');
      });
    });


  });


});

</script> -->

@php
  Session::forget(['all_report','distributor_id','fdate','todate']);
@endphp
<!-- content part================================ -->
@endsection