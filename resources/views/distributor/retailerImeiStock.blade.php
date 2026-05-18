@extends('layouts.master_distributor')

@section('title')
  {{"E-Warranty Ststem :: Daily Stock Report"}}
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
        <th> Distributor </th>
        <th> Distributor Code </th>
        <th> Retailer </th>
        <th> Retailer Code </th>
        <th> Brand </th>
        <th> Product </th>
        <th> Product Model</th>
        <th> IMEI 1  </th>
        <th> IMEI 2 </th>
      </tr>

    </thead>
    <tbody>

@foreach ($queryresults as $key => $element)
<tr>
          <td> {{$key + 1}} </td>
          <td> {{$element['distributor']}} </td>
          <td> {{$element['distofficeid']}} </td>

          <td> {{$element['retailer']}} </td>
          <td> {{$element['retofficeid']}} </td>

          <td> {{$element['brand']}} </td>
          <td> {{$element['product']}} </td>
          <td> {{$element['model']}} </td>
           <td> {{$element['sno']}} </td>
<td> 
  @if ($element['imei'])
    {{$element['imei']}}
  @else
    -
  @endif
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