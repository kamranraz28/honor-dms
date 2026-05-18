@extends('layouts.master_distributor')

@section('title')
  {{"Sales Automation Process :: Purchase Product"}}
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
    <section class="content-header">
      <div class="row">
        <div class="">
      
      <div class="box box-warning">
            <div class="box-header with-border">
              <h3 class="box-title">Purchase Product</h3>
            </div>
    

    <!-- ================================== form area==================================== -->
{{-- for for displaying success and errror message --}}
  <form class="form-horizontal" method="POST" action="{{ route('distributor.purchase.store') }}" autocomplete="on" enctype="multipart/form-data">
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
        <div class="form-group">
          <div class="container1">
            
            <label class="col-sm-2 control-label">Product Details</label>

            <div class="col-sm-10">
              <button  class="add_form_field btn btn-warning btn-md" style="width:50%">Add Field</button><br><br>
            </div>

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
              <h3 class="box-title">Purchase List</h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
              <table id="example" class="display" cellspacing="0" width="100%">
                <thead>
                <tr>
                  <th>#</th>
                  <th>Product Name </th>
                  <th>Product Model </th>
                  <th>Quantity </th>
                  <th>Created Date </th>
                  <!-- <th>Action</th> -->
                </tr>
                </thead>
                <tbody>
@foreach ($purchases as $key => $element)
  
  <tr>
    <td>{{$key + 1}} </td>
    <td>{{$element->product['name']}} </td>
    <td>{{$element->product['model']}} </td>
    <td>{{$element->quantity}} </td>
    <td>{{date_format(date_create($element->created_at),"d-M-Y")}}</td>
<!-- <td>
  
  <button class="btn btn-xs btn-primary" data-toggle="modal" data-target="#{{'purchaseUpdateModal'. $element->id}}"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></button>

  <button class="btn btn-xs btn-danger" data-toggle="modal" data-target="#{{'purchaseDeleteModal'. $element->id}}"><i class="fa fa-trash-o" aria-hidden="true"></i></button>

</td> -->


  </tr>


@endforeach
                </tbody>
               
              </table>
<table>
  
  <tbody>
      <tr>
        <td colspan="6">
          {{ $purchases->links() }}
        </td>
      </tr>
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
              '<div class="col-xs-4">'+
                '<select name="products[]" class="form-control select2" style="" id="product'+ x +'" required>'+
                  '<option value="" selected="selected">Select Product</option>'+
                  '@foreach ($products as $product)'+
                  '<option value="{{$product['id']}}">{{$product['name']}}-{{$product['model']}}</option>'+
                  '@endforeach'+
                '</select>'+
              '</div>'+
              '<div class="col-xs-4">'+
               '<input type="number" name="quantitites[]" id="quantitites'+ x +'" class="form-control" style="" placeholder="Quantity" min="0" required>'+
              '</div>'+
                '<button id="delete'+ x +'"  class="delete btn btn-danger btn-round col-sm-4">Delete Field &nbsp;<span style="font-size:16px; font-weight:bold;"> - </span></button>'+
            '</div>');

        }
      else{
        alert('You Reached the limits')
      }

      var productArea = $("#product"+x);

      productArea.on('mouseenter', function(e) {
          e.preventDefault();
          $('.select2').select2();
        });

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

<!--custom delete modal part================================ -->


@forelse ($purchases as $key => $element)
  <!-- Modal -->
  <div class="modal fade" id="{{'purchaseDeleteModal'. $element->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
          
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">{{$element->product['name']}}</h5>
            <button class="close" type="button" data-dismiss="modal" aria-label="Close" style="margin-top: -25px">
              <span aria-hidden="true">×</span>
            </button>
          </div>

          <div class="modal-body">
<!-- body part -->




  <form action="{{route('distributor.purchase.delete',$element->id)}}" method="post">
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

<!--custom delete modal part================================ -->


<!--custom update modal part================================ -->

  <div class="modal fade" id="{{'purchaseUpdateModal'. $element->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
          
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">{{$element->product['name']}}</h5>
            <button class="close" type="button" data-dismiss="modal" aria-label="Close" style="margin-top: -25px">
              <span aria-hidden="true">×</span>
            </button>
          </div>

          <div class="modal-body">
<!-- body part -->

<form action="{{route('distributor.purchase.update')}}" method="post">
  <p class="text-info">Do You Want To Update This Data ?</p>
   <br>

  <input type="hidden" name="_token" value="{{ csrf_token() }}">
  <input name="_method" type="hidden" value="put">
  <input type="hidden" name="id" value="{{ $element->id }}">


  <div class="form-group {{ $errors->has('product_id') ? 'has-error' : '' }}">
    <label for="product">Product:</label>

      <select name="product_id" id="product" class="form-control" required="true">
        <option value="">Select Product</option>
        @foreach($products as $product )
          <option value="{{ $product['id'] }}" {{ $element['product']['id'] == $product['id'] ? ' selected="selected"' : '' }}>{{ $product['name'] }} - {{ $product['model'] }}</option>
        @endforeach
      </select>          

    <span class="text-danger">{{ $errors->first('product_id') }}</span>
  </div>


<div class="form-group {{ $errors->has('quantity') ? 'has-error' : '' }}">
  <label for="quantity">Quantity:</label>
  <input type="number" id="quantity" name="quantity" class="form-control" placeholder="Enter Product Quantity" value="{{ $element->quantity }}">
  <span class="text-danger">{{ $errors->first('quantity') }}</span>
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



<!--custom update modal part================================ -->

@endforeach

@endsection