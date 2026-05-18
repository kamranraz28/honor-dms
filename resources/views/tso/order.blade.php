@extends('layouts.master_warehouse')

@section('title')
  {{"Sales Automation Process :: Stock"}}
@endsection


@section('content')

<!-- content part================================ -->

    <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <!-- bc part================================ -->
      @include('warehouse.bc.bc')
    <!-- bc part================================ -->


  
    <!-- Main content -->
    <section class="content-header">
      <div class="row">
        <div class="">
      
      <div class="box box-warning">
            <div class="box-header with-border">
              <h3 class="box-title">Product Add</h3>
            </div>
    

    <!-- ================================== form area==================================== -->
{{-- for for displaying success and errror message --}}
  <form class="form-horizontal" method="POST" action="{{ route('warehouse.stock.store') }}" autocomplete="off" enctype="multipart/form-data">
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
                  <option value="{{ $product['id'] }}">{{ $product['product_code'] }}-{{ $product['model'] }}</option>
                @endforeach
              </select>          

            <span class="text-danger">{{ $errors->first('product_id') }}</span>

         <br>  
         <br> 
            <input type="number" name="wperiods" id="wperiod" class="form-control" style="" required  placeholder="Warranty Period" step=any min="0">
</div>
               
        </div>


        <!-- <div class="col-md-12">
          <div class="form-group {{ $errors->has('imei') ? 'has-error' : '' }}">
            <label for="imei">IMEI:</label>
            <input type="text" id="imei" name="imei" class="form-control" placeholder="Enter IMEI No" value="{{ old('imei') }}">
            <span class="text-danger">{{ $errors->first('imei') }}</span>
          </div>
        </div>
        
        
        <div class="col-md-12">
          <div class="form-group {{ $errors->has('sno') ? 'has-error' : '' }}">
            <label for="sno">Serial No:</label>
            <input type="text" id="sno" name="sno" class="form-control" placeholder="Enter Serial No No" value="{{ old('sno') }}">
            <span class="text-danger">{{ $errors->first('sno') }}</span>
          </div>
        </div>
        
        <div class="col-md-12">
          <div class="form-group {{ $errors->has('wperiod') ? 'has-error' : '' }}">
            <label for="wperiod">Warranty Period:</label>
            <input type="number" id="wperiod" name="wperiod" class="form-control" placeholder="Warranty Period" value="{{ old('wperiod') }}">
            <span class="text-danger">{{ $errors->first('wperiod') }}</span>
          </div>
        </div> -->



        <div class="form-group">
          <div class="container1">
            
            <label class="col-sm-2 control-label">Product Details</label>

            <div class="col-sm-10">
              <button  class="add_form_field btn btn-warning btn-md" style="width:49%">Add Field</button><br><br>
            </div>

          </div>
        </div>



<!-- ************************************************* -->
<script>
$(document).ready(function() {

    var max_fields      = 500;
    var wrapper         = $(".container1"); 
    var add_button      = $(".add_form_field"); 
    
    var total = 0;

    var x = 1; 
    $(add_button).click(function(e){ 
        e.preventDefault();
        if(x < max_fields){ 
          var newField = $('<div class="row" style="padding:0px 30px 8px 212px">' +
                    '<div class="col-xs-4">' +
                    '<input type="text" name="imeis[]" id="imei' + x + '" class="form-control" style="" required placeholder="IMEI" min="0">' +
                    '</div>' +
                    '<input type="text" name="qunaity[]" id="quanity' + x + '" class="form-control" style="" required placeholder="Quantity" min="0">' +
                    '</div>' +
                    '<button id="delete' + x + '"  class="delete btn btn-danger btn-round col-sm-2">Delete Field&nbsp;<span style="font-size:16px; font-weight:bold;">-</span></button>' +
                    '</div>'
                );

                $(wrapper).append(newField);

                // Set the focus on the new input field
                newField.find('input[type="text"]').focus();

        }
      else{
        alert('You Reached the limits')
      }

//========================================


});


//=========================================================
    $(wrapper).on("click",".delete", function(e){ 
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
    




 
  </div>
<!-- content part================================ -->










@endsection




