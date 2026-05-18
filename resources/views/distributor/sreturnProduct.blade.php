@extends('layouts.master_distributor')

@section('title')
  {{"Sales Automation Process :: Return Product"}}
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
              <h3 class="box-title">Return Product</h3>
            </div>
    

    <!-- ================================== form area==================================== -->
{{-- for for displaying success and errror message --}}
  <form class="form-horizontal" method="POST" action="{{ route('distributor.sreturnProduct.store') }}" autocomplete="on" enctype="multipart/form-data">
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
            
            <label class="col-sm-2 control-label">Add IMEI 1 </label>

            <div class="col-sm-10">
              <button  class="add_form_field btn btn-warning btn-md" style="width:50%">Add Field</button><br><br>
            </div>

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
              <h3 class="box-title">ST1 Returning Prodcut List</h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
              <table id="example" class="display" cellspacing="0" width="100%">
                <thead>
                <tr>
                  <th>#</th>
                  <th>Status </th>
                  <th>Retailer </th>
                  <th>Product Name </th>
                  <th>Product Model </th>
                  <th>IMEI 1 </th>
                  <th>IMEI 2 </th>
                  <th>Created Date </th>
                  <th>Updated Date </th>
                  <th>Action</th>
                </tr>
                </thead>
                <tbody>
@foreach ($preturns as $key => $element)
  
  <tr>
    <td>{{$key + 1}} </td>

    <td>

      @if ($element->status == 2)
        <button class="btn btn-xs btn-danger"> ST1 Return Apply</button> 
      @elseif ($element->status == 3)
        <button class="btn btn-xs btn-success"> ST1 Return Approved</button>
      {{-- @else
        <button class="btn btn-xs btn-primary"> ND Processed</button> --}}
      @endif
    </td>

    <td>{{$element->retailer}}</td>
    <td>{{$element->product['name']}} </td>
    <td>{{$element->product['model']}} </td>
    <td>{{$element->sno}} </td>
    <td>{{$element->imei}} </td>
    
    <td>{{date_format(date_create($element->created_at),"d-M-Y")}}</td>
    <td>{{date_format(date_create($element->updated_at),"d-M-Y")}}</td>

<td>
  
@if ($element->status == 2)
<center>
  <button class="btn btn-xs btn-danger" data-toggle="modal" data-target="#{{'saleDeleteModal'. $element->id}}"><i class="fa fa-trash-o" aria-hidden="true"></i></button>
</center>
@else
<center>
  -
</center>
@endif
</td>


  </tr>


@endforeach
                </tbody>
               
              </table>
<table>
  
  <tbody>
      <tr>
        <td colspan="6">
          {{ $preturns->links() }}
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
              '<div class="col-xs-8">'+
               '<input type="text" name="snos[]" id="snos'+ x +'" class="form-control" placeholder="IMEI 1" required autocomplete="off">'+
               '<span class="text-danger" id="snos'+ x +'text"></span>'+
              '</div>'+
                '<button id="delete'+ x +'"  class="delete btn btn-danger btn-round col-sm-4">Delete Field &nbsp;<span style="font-size:16px; font-weight:bold;"> - </span></button>'+
            '</div>');

        }
      else{
        alert('You Reached the limits')
      }


      var distributorArea = $("#distributor"+x);
      var snoArea = $("#snos"+x);
      var snotextArea = $("#snotext"+x);

      distributorArea.on('mouseenter', function(e) {
        e.preventDefault();
        $('.select2').select2();
      });

      /*snoArea.on('keyup', function(event) {
        var hid = event.target.id;
        var sno = event.target.value;
        var route = "{{--route('ajax.varifyserialnoOne')--}}/"+sno;

        $.get(route, function(data) {
          
          console.log(data);

          if (data == 0) {
            $('#'+ hid +'text').text("This s.no is not allowed for returning");
          }else if (data == 1) {
            $('#'+ hid +'text').text("This s.no has all ready been sold");
          }else if (data == 2) {
            $('#'+ hid +'text').text("This s.no does not match");
          } else {
            $('#'+ hid +'text').text("");
          }
        });


      });*/

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


@forelse ($preturns as $key => $element)
  <!-- Modal -->
  <div class="modal fade" id="{{'saleDeleteModal'. $element->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
          
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">{{$element->sno}}</h5>
            <button class="close" type="button" data-dismiss="modal" aria-label="Close" style="margin-top: -25px">
              <span aria-hidden="true">×</span>
            </button>
          </div>

          <div class="modal-body">
<!-- body part -->




  <form action="{{route('distributor.sreturnProduct.delete',$element->id)}}" method="post">
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



@endforeach

<!-- ************************************************* -->
@endsection