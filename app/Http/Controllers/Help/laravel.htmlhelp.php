@extends('layouts.master_tso')

@section('title')
  {{"DMS :: Create Daily Reports"}}
@endsection


@section('content')

<!-- content part================================ -->

    <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Daily Reports
        <small>Control panel</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="{{ route('tso.dashboard') }}"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="#"></i> ASM/TO</a></li>
        <li class="active"><a href="{{ route('tso.dailyreport') }}">Create Daily Report</a></li>
      </ol>
    </section>

  
    <!-- Main content -->
    <section class="content-header">
      <div class="row">
        <div class="">
      <div class="box box-warning">
            <div class="box-header with-border">
              <h3 class="box-title">Create Daily Report</h3>
            </div>
            <!-- /.box-header -->
            <!-- form start -->
{{-- for for displaying success and errror message --}}
  <form class="form-horizontal" method="POST" action="{{ route('tso.dailyreport.store') }}" autocomplete="on" enctype="multipart/form-data">
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



<div class="form-group {{ $errors->has('distributor_id') ? 'has-error' : '' }}">
  <label for="distributor" class="col-sm-2 control-label">Distributor</label>
  <div class="col-sm-10">
    <select name="distributor_id" id="distributor" class="form-control select2" style="width: 100%;" required="required">
      <option value="">Select Distributor</option>
@foreach ($distusers as $element)
      @foreach($element['distuser'] as $distuser )
        <option value="{{ $distuser['id'] }}">{{ $distuser['distributor'] .' - '.  $distuser['duid'] }}</option>
      @endforeach
@endforeach

    </select>
    <span class="text-danger">{{ $errors->first('distributor_id') }}</span>
  </div>
</div>






                <div class="form-group {{ $errors->has('date') ? 'has-error' : '' }}">
                  <label class="col-sm-2 control-label">Date</label>

                  <div class="col-sm-10">
                    <div class="input-group date">
                      <div class="input-group-addon">
                        <i class="fa fa-calendar"></i>
                      </div>
                      <input name="date" placeholder="DD/MM/YYYY" type="text" class="form-control pull-right" id="datepicker" autocomplete="off" required="required"  value="{{ old('remarks') }}">
                      <span class="text-danger">{{ $errors->first('date') }}</span>
                    </div>
                  </div>
                </div>




                <div class="form-group">
                  <label for="noofso" class="col-sm-2 control-label">No Of SO</label>

                  <div class="col-sm-10">
                    <input type="number" name="so" class="form-control" id="noofso"  placeholder="No Of SO" required="required">
                  </div>
                </div>



                <div class="form-group">
                  <label for="coverage" class="col-sm-2 control-label">Outlet Coverage</label>

                  <div class="col-sm-10">
                    <input type="number" name="coverage" class="form-control" id="coverage"  placeholder="Outlet Coverage" required="required">
                  </div>
                </div>

                <div class="form-group">
                  <label for="ordered" class="col-sm-2 control-label">Outlet Ordered</label>

                  <div class="col-sm-10">
                    <input type="number" name="ordered" class="form-control" id="ordered"  placeholder="Outlet Ordered" required="required">
                  </div>
                </div>


                <div class="form-group">
                  <div class="container1">
                    
                    <label class="col-sm-2 control-label">Product Details</label>

                    <div class="col-sm-10">
                      <button  class="add_form_field btn btn-warning btn-md" style="width:50%">Add Field</button><br><br>
                    </div>

                  </div>
                </div>
              
              

              <!-- /.form group -->
   
                <hr>
                <div class="form-group {{ $errors->has('remarks') ? 'has-error' : '' }}">
                  <label  class="col-sm-2 control-label">Remarks</label>
                
                  <div class="col-sm-10">
                    <input type="text" id="remarks" name="remarks" class="form-control" placeholder="Remarks" value="{{ old('remarks') }}">
                    <span class="text-danger">{{ $errors->first('remarks') }}</span>
                  </div>
                </div>



                <!-- <div class="form-group">
                  <label for="inputPassword3" class="col-sm-2 control-label">Upload Bank Slip</label>
                
                  <div class="col-sm-10">
                    
                    <input class="form-control" name="image" type="file" id="exampleInputFile">
                  </div>
                </div> -->


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
                '<select name="products[]" class="form-control" style="" id="product'+ x +'">'+
                  '<option selected="selected">Select Product</option>'+
                  '@foreach ($products as $product)'+
                  '<option value="{{$product['id']}}">{{$product['product']}}-{{$product['sku']}}</option>'+
                  '@endforeach'+
                '</select>'+
              '</div>'+
              '<div class="col-xs-3">'+
               '<input type="number" name="orderes[]" id="orderes'+ x +'" class="form-control" style="" placeholder="Order" min="0">'+
              '</div>'+
              '<div class="col-xs-3">'+
                '<input type="number" name="deliveries[]" id="delivery'+ x +'" class="form-control" style=""  placeholder="Delivery" step=any min="0">'+
              '</div>'+
                '<button id="delete'+ x +'"  class="delete btn btn-danger btn-round col-sm-2">Delete Field &nbsp;<span style="font-size:16px; font-weight:bold;"> - </span></button>'+
            '</div>');

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
              <h3 class="box-title">Report List</h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
<div class="table-responsiv">
              <table id="example" class="display" cellspacing="0" width="100%">
                <thead>
                <tr>
                  <th>No</th>
                  
                  <th>Distributor</th>
                  <th>Date</th>
                  <th>Remarks</th>
                  <th>Details</th>
                  <th>Action</th>
                 
                </tr>
                </thead>
                <tbody>
@forelse ($dreports as $element)
  <tr>
    

    <td>{{$element->drno}}</td>








    <td>
      {{$element->distributor['distributor']}}
    </td>
    <td>
      {{date_format(date_create($element->date),"d-M-Y")}}
    </td>

    <td class="text-justify" style="cursor:pointer;color: black;font-weight: bolder" data-toggle="modal" data-target="#{{'textDetails'. $element->id}}">
      {!! substr($element->remarks, 0, 40) !!}
    </td>

    <td class="text-justify" style="cursor:pointer;color: black;font-weight: bolder" data-toggle="modal" data-target="#{{'InvoiceDetails'. $element->id}}">
      Details
    </td>


<td> 
  

  <button class="btn btn-xs btn-danger" data-toggle="modal" data-target="#{{'DeleteModal'. $element->id}}"><i class="fa fa-trash-o" aria-hidden="true"></i></button>
</td>
  </tr>
@empty
  {{"NO data Found"}}
@endforelse



                
                
              
                </tbody>
               
              </table>
<table>
  
  <tbody>
      <tr>
        <td colspan="11">
          {{ $dreports->links() }}
        </td>
      </tr>
  </tbody>

</table>

</div>
            </div>
            <div class="clear"></div>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->
        </div>
    </section>

 
  </div>
<!-- content part================================ -->


<!--custom textDetails modal part================================ -->


@forelse ($dreports as $key => $element)
  <!-- Modal -->
  <div class="modal fade" id="{{'textDetails'. $element->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
          
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">{{$element->drno}}</h5>
            <button class="close" type="button" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">×</span>
            </button>
          </div>

          <div class="modal-body">
<!-- body part -->

<p>
  {!! nl2br($element->remarks) !!}
</p>

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

<!--custom textDetails modal part================================ -->



<!--custom InvoiceDetails modal part================================ -->


@forelse ($dreports as $key => $element)
  <!-- Modal -->
  <div class="modal fade" id="{{'InvoiceDetails'. $element->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
          
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">{{$element->drno}}</h5>
            <button class="close" type="button" data-dismiss="modal" aria-label="Close" style="margin-top: -25px">
              <span aria-hidden="true">×</span>
            </button>
          </div>

          <div class="modal-body">
<!-- body part -->

  <table class="table">
    <thead>
      <tr>
        <th>Product</th>
        <th>Order</th>
        <th>Delivery</th>
      </tr>
    </thead>
    <tbody>
@foreach ($element->dreportdetail as $dreportdetail)
      <tr>
        <td>{{$dreportdetail->product}}</td>
        <td>{{$dreportdetail->pro_order}}</td>
        <td>{{$dreportdetail->pro_delivery}}</td>
      </tr>
@endforeach

    </tbody>
  </table>


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
<!--custom InvoiceDetails modal part================================ -->





<!--custom delete modal part================================ -->


@forelse ($dreports as $key => $element)
  <!-- Modal -->
  <div class="modal fade" id="{{'DeleteModal'. $element->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
          
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">{{$element->drid}}</h5>
            <button class="close" type="button" data-dismiss="modal" aria-label="Close" style="margin-top: -25px">
              <span aria-hidden="true">×</span>
            </button>
          </div>

          <div class="modal-body">
<!-- body part -->




  <form action="{{ route('tso.dailyreport.delete', [$element->id] ) }}" method="post">
   <h4 class="text-info">Do You Want To Delete This Data ?</h4>
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









@endsection