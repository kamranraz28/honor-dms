@extends('layouts.master_sales1')

@section('title')
  {{"DMS :: Add Product"}}
@endsection


@section('content')

<!-- content part================================ -->

    <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Add Distributor Target
        <small>Control panel</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="{{ route('sales.dashboard') }}"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="#"></i> SALES</a></li>
        <li class="active"><a href="{{ route('sales.addDistributorTarget') }}">Add Distributor Target</a></li>
      </ol>
    </section>

  

    <!-- Main content -->
    <section class="content-header">
      <div class="row">
        <div class="">
      <div class="box box-warning">
            <div class="box-header with-border">
              <h3 class="box-title">Add target</h3>
            </div>
            <!-- /.box-header -->
            <!-- form start -->
{{-- for for displaying success and errror message --}}
  <form class="form-horizontal" method="POST" action="{{ route('sales.addDistributorTarget.store') }}" autocomplete="on" enctype="multipart/form-data">
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



                <div id="regionArea" class="form-group {{ $errors->has('region_id') ? 'has-error' : '' }}">
                  <label for="region" class="col-sm-2 control-label">Region</label>
                  <div class="col-sm-10">
                    <select name="region_id" id="region" class="form-control" style="width: 100%;" required="required">
                      <option value="">Select Region</option>
                      @foreach($regions as $region )
                        <option value="{{ $region['id'] }}">{{ $region['region'] }}</option>
                      @endforeach
                    </select>
                    <span class="text-danger">{{ $errors->first('region_id') }}</span>
                  </div>
                </div>

                <div id="territoryArea" class="form-group {{ $errors->has('territory_id') ? 'has-error' : '' }}">
                  <label for="territory" class="col-sm-2 control-label">Territory</label>
                  <div class="col-sm-10">
      
      <select name="territory_id" id="territory" class="form-control"  required="required">
        <option value="">Select Territory</option>
      </select>  
                    <span class="text-danger">{{ $errors->first('territory_id') }}</span>
                  </div>
                </div>


                <div id="distributorArea" class="form-group {{ $errors->has('distributor_id') ? 'has-error' : '' }}">
                <label for="Zone" class="col-sm-2 control-label">Add Distributor</label>
                  <div class="col-sm-10">
                    <select name="distributor_id" id="distributor" class="form-control" data-placeholder="Select Distributor" style="width: 100%;"  required="required">
                      <option value="">Select Distributor</option>
                    </select>
                    <span class="text-danger">{{ $errors->first('distributor_id') }}</span>
                  </div>
                </div>




                <div class="form-group">
                  <label class="col-sm-2 control-label">Date</label>

                  <div class="col-sm-10">
                    <div class="input-group date">
                      <div class="input-group-addon">
                        <i class="fa fa-calendar"></i>
                      </div>
                      <input name="dateMonth" placeholder="DD/YYYY" type="text" class="form-control pull-right" id="monthpicker"  required="required">
                    </div>
                  </div>
                </div>

                <hr>

              <!-- /.form group -->
     

                <div class="form-group">
                  <div class="container1">
                    
                    <label class="col-sm-2 control-label">Product Details</label>

                    <div class="col-sm-10">
                      <button  class="add_form_field btn btn-warning btn-md" style="width:50%">Add Field</button><br><br>
                    </div>

                  </div>
                </div>


                <hr>
                <div class="form-group">
                  <label for="inputPassword3" class="col-sm-3 control-label">Total( Including {{$vat}}% Vat )</label>

                  <div class="col-sm-9">
                    <input type="hidden" name="total" id="cartSum1">
                    <input type="number" name="" class="form-control" id="cartSum"  placeholder="Total" disabled="disabled">
                  </div>
                </div>
                
                <hr>
                
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
      
    </section>
    

 
  </div>
<!-- content part================================ -->





<!-- ************************************************* -->
<script>
$(document).ready(function() {

    var max_fields      = 20;
    var wrapper         = $(".container1"); 
    var add_button      = $(".add_form_field"); 
    
    var total = 0;

    var x = 1; 
    $(add_button).click(function(e){ 
        e.preventDefault();
        if(x < max_fields){ 
            x++; 
            $(wrapper).append('<div class="row "style="padding:0px 30px 8px 212px ">'+
              '<div class="col-xs-3">'+
                '<select name="products[]" class="form-control" style="width:200px;" id="product'+ x +'">'+
                  '<option selected="selected">Select Product</option>'+
                  '@foreach ($products as $product)'+
                  '<option value="{{$product['id']}}">{{$product['product']}}-{{$product['sku']}}</option>'+
                  '@endforeach'+
                '</select>'+
              '</div>'+
              '<div class="col-xs-3">'+
               '<input type="number" name="quantities[]" id="quantity'+ x +'" class="form-control" style="width:200px;" placeholder="Quantity" min="0">'+
              '</div>'+
              '<div class="col-xs-3">'+
                '<input type="number" name="prices[]" id="price'+ x +'" class="form-control priceClass" style="width:200px;"  placeholder="Price" disabled>'+
              '</div>'+
                '<button id="delete'+ x +'"  class="delete btn btn-danger btn-round col-sm-3">Delete Field &nbsp;<span style="font-size:16px; font-weight:bold;"> - </span></button>'+
            '</div>');

        }
      else{
        alert('You Reached the limits')
      }

//========================================



  var productArea1      = $("#product"+x);
  var quantityArea1      = $("#quantity" +x); 
  var priceArea1      = $("#price"+x); 
  var deleteArea1      = $("#delete"+x); 


  

//========================================

  var qty = quantityArea1.val(0);
  var totalprice = 0;
  priceArea1.val(0);
  var price = 0;

//========================================



  productArea1.on('change', function(e) {
    var product_id = e.target.value;
    //console.log(product_id);
    

    
    var route = "{{route('admin.productTableSearchByIdWithAjax')}}/"+product_id;
    $.get(route, function(data) {
      //console.log(data);

     $.each(data, function(index,data){
        price = data.price;
      });

//=======================================================
     
//======================================
  //$("#w3s").attr("href1", "https://www.w3schools.com/jquery/");
  //$("#w3s").attr("href1");


  $(quantityArea1).bind('keyup mouseup mousewheel', function (e) {
      qty = e.target.value;

        var qprice = qty*price;
        priceArea1.val(qty*price);
        totalprice += (qty*price);
        
        //deleteArea1.val(qty*price);
//===========================================
      var totalPrice = 0;
      var vatPrice = 0 ;
      var vatPrice1 = 0 ;
      var vat = "{{$vat}}";
      function getTotalCost() {
        
        $(".container1").find(".priceClass").each(function() {
          totalPrice += parseFloat($(this).val());

          vatPrice = ( totalPrice * vat) /100 ;
          vatPrice1 = ( qprice * vat) /100 ;
        });

        $("#cartSum").val(totalPrice + vatPrice );
        $("#cartSum1").val(totalPrice + vatPrice );

        deleteArea1.val(qprice + vatPrice1);

      }

      getTotalCost();

//===========================================
         
  });

    });

  });

//========================================




    });
    
    $(wrapper).on("click",".delete", function(e){ 
       var totalPrice = $("#cartSum").val();
       //var restOfPrice = $(this).val();
       var restOfPrice = e.target.value;
        totalPrice = totalPrice - restOfPrice;
       $("#cartSum").val( totalPrice );
       $("#cartSum1").val( totalPrice );
       e.preventDefault(); $(this).parent('div').remove(); x--;
        

    })
//=========================================================







});




</script>

<!-- // jquery area ========= -->
<script type="text/javascript">

  $('#level').on('change', function(e){
    var level = e.target.value;
    console.log(level);
    if (level == 100) {
      //$('#regionArea, #territoryArea, #distributorArea').css({'display':'block'});
    } else {
      //$('#regionArea, #territoryArea, #distributorArea').css({'display':'none'});
      $('#region, #territory, #distributor').val('');
    }
  });


  $('#region').on('change', function(e){
    var region_id = e.target.value;
    console.log(region_id);
    var route = "{{route('admin.territorySelectBoxOnRegionWithAjax')}}/"+region_id;
    $.get(route, function(data) {
      console.log(data);
      $('#territory').empty();
      $('#territory').append('<option value="'+'">Select Territory</option>');
      $.each(data, function(index,data){
        $('#territory').append('<option value="' + data.id + '">' + data.territory + '</option>');
      });
    });
  });

  $('#territory').on('change', function(e){
    var territory_id = e.target.value;
    console.log(territory_id);
    var route = "{{route('admin.distributorSelectBoxOnTerritoryWithAjax')}}/"+territory_id;
    $.get(route, function(data) {
      console.log(data);
      $('#distributor').empty();
      
      $.each(data, function(index,data){
        $('#distributor').append('<option value="' + data.id + '">' + data.distributor + ' - '+ data.duid+ '</option>');
      });
    });
  });


</script>
<!-- // jquery area ========= -->//


<!-- ************************************************* -->




@endsection