@extends('layouts.master_retailer')

@section('title')
  {{"E-Warranty Ststem :: Return Product"}}
@endsection


@section('content')

<!-- content part================================ -->

    <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <!-- bc part================================ -->
      @include('retailer.bc.bc')
    <!-- bc part================================ -->


  
    <!-- Main content -->
    <section class="content-header">
      <div class="row">
        <div class="">
      
      <div class="box box-warning">
            <div class="box-header with-border">
              <h3 class="box-title">Return Product</h3>
            </div>
    

    <!-- ================================== form area==================================== -->
{{-- for for displaying success and errror message --}}
  <form class="form-horizontal1" method="POST" action="{{ route('retailer.dontWorry.store') }}" autocomplete="on" enctype="multipart/form-data">
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
        
        
        <div class="col-md-6">
          <div class="form-group {{ $errors->has('imei') ? 'has-error' : '' }}">
            <label for="imei">IMEI:</label>
            <input type="text" id="imei" name="imei" class="form-control" placeholder="Enter IMEI" value="{{ old('imei') }}" required>
            <span class="text-danger">{{ $errors->first('imei') }}</span>
            <span id="imeiText"></span>
          </div>
        </div>
<div style="clear: both;"></div>

        <div class="col-md-4" id="productArea" style="display: none">
          <div class="form-group {{ $errors->has('product') ? 'has-error' : '' }}">
            <label for="product">Product:</label>
            <input type="text" id="product" name="product" class="form-control" placeholder="Enter Product" value="{{ old('product') }}" disabled="disabled">
            <span class="text-danger">{{ $errors->first('product') }}</span>
          </div>
        </div>

        <div class="col-md-4" id="dwchargeArea" style="display: none">
          <div class="form-group {{ $errors->has('dwcharge') ? 'has-error' : '' }}">
            <label for="dwcharge">Charge:</label>
            <input type="text" id="dwcharge" name="dwcharge" class="form-control" placeholder="Enter Charge" value="{{ old('dwcharge') }}" disabled="disabled">
            <span class="text-danger">{{ $errors->first('dwcharge') }}</span>
          </div>
        </div>


        <div class="col-md-4" id="dwdayArea" style="display: none">
          <div class="form-group {{ $errors->has('dwday') ? 'has-error' : '' }}">
            <label for="dwday">Day:</label>
            <input type="text" id="dwday" name="dwday" class="form-control" placeholder="Enter Day" value="{{ old('dwday') }}" disabled="disabled">
            <span class="text-danger">{{ $errors->first('dwday') }}</span>
          </div>
        </div>

<div style="clear: both;"></div>
        <div class="col-md-6">
          <div class="form-group {{ $errors->has('customer') ? 'has-error' : '' }}">
            <label for="customer">Customer Name:</label>
            <input type="text" id="customer" name="customer" class="form-control" placeholder="Enter Customer" value="{{ old('customer') }}" required="required">
            <span class="text-danger">{{ $errors->first('customer') }}</span>
            <span id="customerText"></span>
          </div>
        </div>
<div style="clear: both;"></div>
        <div class="col-md-6">
          <div class="form-group {{ $errors->has('mobile') ? 'has-error' : '' }}">
            <label for="mobile">Customer Mobile:</label>
            <input type="text" id="mobile" name="mobile" class="form-control" placeholder="Enter Mobile" value="{{ old('mobile') }}" required="required">
            <span class="text-danger">{{ $errors->first('mobile') }}</span>
          </div>
        </div>



      </div>




      <div class="box-footer">
        <button type="submit" class="btn btn-success pull-right" id="submitbtn">Submit</button>
      </div>

  </form>

<!-- ================================== form area==================================== -->



          </div>


        </div>
      </div>



      <div class="row">
            <div class="box box-warning">
            <div class="box-header">
              <h3 class="box-title">Returning Prodcut List</h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
              <table id="example" class="display" cellspacing="0" width="100%">
                <thead>
                <tr>
                  <th>#</th>
                  <th>Customer </th>
                  <th>Mobile </th>
                  <th>UserID </th>
                  <th>Product </th>
                  <th>Model </th>
                  <th>Brand </th>
                  <th>IMEI </th>
                  <th>S.No </th>
                  <th>Created Date </th>
                  <th>Updated Date </th>
                </tr>
                </thead>
                <tbody>
@foreach ($dwrecords as $key => $element)
  
  <tr>
    <td>{{$key + 1}} </td>
    <td>{{$element['customer']}} </td>
    <td>{{$element['mobile']}} </td>
    

    <td>{{$element['user']['firstname']}} - {{$element['user']['officeid']}} </td>
    <td>{{$element['product']['name']}} </td>
    <td>{{$element['product']['model']}} </td>
    <td>{{$element['brand']['name']}} </td>
    <td>{{$element['imei']}} </td>
    <td>{{$element['sno']}} </td>
    <td>{{date_format(date_create($element['created_at']),"d-M-Y")}}</td>
    <td>{{date_format(date_create($element['updated_at']),"d-M-Y")}}</td>


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



<script type="text/javascript">
  

$(document).ready(function() {
  
  /*$('#level').on('change', function(e){
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


  });*/
//{dwstatus: 0, slstatus: 0, wperiod: 0, product: null, dwcharge: 0, dwday: 0}
  $("#productArea").css("display", "none");
  $("#dwchargeArea").css("display", "none");
  $("#dwdayArea").css("display", "none");

  $("#imei").on('keyup', function(e){
    var imei = e.target.value;

    var route = "{{route('admin.dontworryimeikeyup')}}/"+imei;
    //console.log(imei);
    $.get(route, function(data){
      console.log(data);
      
      if (data.dwdublicate == 1) {
//===============================
          $("#productArea").css("display", "none");
          $("#dwchargeArea").css("display", "none");
          $("#dwdayArea").css("display", "none");

          $("#customerText").text("This imei has already been activated for don't worry offer").removeClass('text-success').addClass('text-danger');

          $("#imeiText").text("").removeClass('text-success').addClass('text-danger');
//===============================
      }else{
  //======================
        if (data.dwstatus == 1 && data.slstatus == 1) {
          $("#productArea").css("display", "unset");
          $("#dwchargeArea").css("display", "unset");
          $("#dwdayArea").css("display", "unset");

          $("#product").val(data.product);
          $("#dwcharge").val(data.dwcharge);
          $("#dwday").val(data.dwday);

          console.log(parseInt(data.dwduration));
          console.log(parseInt(data.wperiod));
          console.log(parseInt(data.dwduration) - parseInt(data.wperiod));

          if (parseInt(data.wperiod) > 0 && parseInt(data.wperiod) < parseInt(data.dwduration)) {
            $("#customerText").text("Valid customer only " + (parseInt(data.dwduration) - parseInt(data.wperiod))  + " days left for don't worry offer").removeClass('text-danger').addClass('text-success');
          }else{

            $("#productArea").css("display", "none");
            $("#dwchargeArea").css("display", "none");
            $("#dwdayArea").css("display", "none");

            $("#customerText").text("Not valid customer. Don't Worry Offer is Valid before " + (parseInt(data.wperiod) - parseInt(data.dwduration))  + " Days of Purchase ").removeClass('text-success').addClass('text-danger');
          }

          $("#imeiText").text("Dont worry offer is availabel with this imei").removeClass('text-danger').addClass('text-success');

        }else if (data.dwstatus == 1 && data.slstatus == 0) {
          $("#productArea").css("display", "unset");
          $("#dwchargeArea").css("display", "unset");
          $("#dwdayArea").css("display", "unset");

          $("#product").val(data.product);
          $("#dwcharge").val(data.dwcharge);
          $("#dwday").val(data.dwday);

          if (data.slstatus == 0) {
            

            $("#customerText").text("This imei has not been sold yet to this customer but Dont worry offer is availabel").removeClass('text-danger').addClass('text-success');
          }

          $("#imeiText").text("Dont worry offer is availabel with this imei").removeClass('text-danger').addClass('text-success');

        }else{
          $("#productArea").css("display", "none");
          $("#dwchargeArea").css("display", "none");
          $("#dwdayArea").css("display", "none");

          $("#customerText").text("").removeClass('text-success').addClass('text-danger');

          $("#imeiText").text("Dont worry offer is not availabel with this imei").removeClass('text-success').addClass('text-danger');
        }
  //======================
      }

    });
  });


});

</script>

@endsection