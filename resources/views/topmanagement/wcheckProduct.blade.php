@extends('layouts.master_topmanagement')

@section('title')
  {{"E-Warranty Ststem :: Warranty Check Product"}}
@endsection


@section('content')


<!-- content part================================ -->

    <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <!-- bc part================================ -->
      @include('topmanagement.bc.bc')
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
              <h3 class="box-title text-danger">Check Warranty</h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
  
            <!-- form start -->
             <form class="form-horizontal1" method="POST" action="{{ route('topmanagement.wcheckProduct.store') }} " autocomplete="off" enctype="multipart/form-data">

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

    <input type="hidden" name="_token" value="{{ csrf_token() }}">
    <input type="hidden" name="user_id" value="{{ Auth::id() }}">
{{-- for for displaying success and errror message --}}
                <div class="box-body">
               <br><br><br>

        <div class="col-md-8">
          <div class="form-group {{ $errors->has('imei') ? 'has-error' : '' }}">
            <label for="imei">IMEI Or Serial No:</label>
            <input type="text" id="imei" name="imei" class="form-control" placeholder="Enter IMEI Or Serial No" value="{{ old('imei') }}">
            <span class="text-danger">{{ $errors->first('imei') }}</span>
          </div>
        </div>

        <div class="col-md-4">
          <div class="form-group">
            <label for="model" style="visibility: hidden;">IMEI Or Serial No:</label>
            <button type="submit" class="btn btn-success form-control">Check...</button>
          </div>
        </div>
                
                </div>
              <!-- /.box-body -->
              <div class="box-footer">
                <!-- <button type="submit" class="btn btn-success pull-right">Submit</button> -->
              </div>
              <!-- /.box-footer -->
            
            </form>


            </div>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->


  </section>

<!-- ==============one section area ================= -->

<!-- ==============one section area ================= -->

@if (count($wcheckProducts) != 0)

@php
  //$fdate = date_format(date_create($ssdata['fdate']),"Y-m-d");
  //$todate = date_format(date_create($ssdata['todate']),"Y-m-d");
  //$user_id = $ssdata['user_id'];
@endphp


  <section class="col-lg-12 connectedSortable">
          <!-- Recent Invoice -->
          <div class="box box-warning">
            <div class="box-header">
              
            </div>
            <!-- /.box-header -->
            <div class="box-body">
<p style="text-align: center;font-size: 12px;font-weight: bold;color: black;">User Daily Report By {{$ssdata['imei']}}</p>

<table id="example5" width="100%">
         

    <thead>


  
      <tr>
        <th> Product </th>
        <th> Model </th>
        <th> IMEI </th>
        <th> SL. No </th>
        <th> Warranty Period </th>
        <th> Sale Date </th>
        <th> Warranty Start Date </th>
        <th> Warranty End Date </th>
        <th> Warranty </th>
        <th> Replace </th>
        <th> Replace Details</th>
       
      </tr>

    </thead>
    <tbody>

@foreach ($wcheckProducts as $key => $wcheckProduct)
<tr>
         
          <td> {{$wcheckProduct['product']['name']}} </td>
          <td> {{$wcheckProduct['product']['model']}} </td>
          <td> {{$wcheckProduct['imei']}} </td>
          <td> {{$wcheckProduct['sno']}} </td>
          <td> {{$wcheckProduct['wperiod']}} Days</td>
          <td> {{$wcheckProduct['saledate']}} </td>
          <td> {{$wcheckProduct['sdate']}} </td>
          <td> {{$wcheckProduct['edate']}} </td>
          
          <td> 
  @if ($wcheckProduct['wdayCount'] > $wcheckProduct['wperiod'])
    <button class="btn btn-xs btn-danger">
      Not Available
    </button>
  @else
    <button class="btn btn-xs btn-primary">
      Available
    </button>
  @endif
          </td>

            <td> 
  @if ($wcheckProduct['wdayCount'] > $wcheckProduct['wperiod'] || $wcheckProduct['iswar'] == 0)
      <button class="btn btn-xs btn-danger">
        Not Available
      </button>
  @else
    <button class="btn btn-xs btn-primary" data-toggle="modal" data-target="#{{'repalceModal'.$wcheckProduct['id']}}">
      Replace
    </button>
  @endif
          </td>      

          <td> 
  @if ($wcheckProduct['iswar'] == 0)
      
@if (count($wcheckProduct['replace']) > 0)
  <button class="btn btn-xs btn-primary" data-toggle="modal" data-target="#{{'repalceDetailsModal'.$wcheckProduct['id']}}">
    Details
  </button>
@else
  <button class="btn btn-xs btn-danger">
        Not Available
      </button>
@endif
@else
  <button class="btn btn-xs btn-danger">
      Not Available
    </button>
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
@else
@if (@$dataCount > 0)
<table width="100%"> 


    <tbody>
      <tr>
        <td rowspan="9">
          <p class="text-center text-danger">NO Data Found</p>
          
        </td>
      </tr>
    </tbody>

</table>
@endif

@endif
<!-- ==============one section area ================= -->






<!--custom update modal part================================ -->


@foreach ($wcheckProducts as $key => $wcheckProduct)
  <!-- Modal -->
  <div class="modal fade" id="{{'repalceModal'.$wcheckProduct['id']}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
          
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">{{$wcheckProduct['product']['name'] .' - '. $wcheckProduct['product']['model']}}</h5>
            <button class="close" type="button" data-dismiss="modal" aria-label="Close" style="margin-top: -25px">
              <span aria-hidden="true">×</span>
            </button>
          </div>

          <div class="modal-body">
<!-- body part -->

<form action="{{route('topmanagement.wcheckProduct.repalce')}}" method="post">
  <p class="text-info">Do You Want To Repalce ?</p>
   <br>

  <input type="hidden" name="_token" value="{{ csrf_token() }}">
  <input name="_method" type="hidden" value="put">
  <input type="hidden" name="id" value="{{ $wcheckProduct['id'] }}">

<div class="form-group {{ $errors->has('sno') ? 'has-error' : '' }}">
  <label for="sno">Serial No:</label>
  <input type="text" id="sno" name="sno" class="form-control" placeholder="Enter Serial No" value="{{ old('sno') }}" required="required">
  <span class="text-danger">{{ $errors->first('sno') }}</span>
</div>

<div class="form-group {{ $errors->has('imei') ? 'has-error' : '' }}">
  <label for="imei">IMEI No:</label>
  <input type="text" id="imei" name="imei" class="form-control" placeholder="Enter IMEI No" value="{{ old('imei') }}">
  <span class="text-danger">{{ $errors->first('imei') }}</span>
</div>

  <div class="form-group">
    <button class="form-control btn btn-success">Repalace</button>
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

@endforeach
<!--custom update modal part================================ -->





<!--custom update modal part================================ -->


@foreach ($wcheckProducts as $key => $wcheckProduct)
  <!-- Modal -->
  <div class="modal fade" id="{{'repalceDetailsModal'.$wcheckProduct['id']}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
          
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">{{$wcheckProduct['product']['name'] .' - '. $wcheckProduct['product']['model']}}</h5>
            <button class="close" type="button" data-dismiss="modal" aria-label="Close" style="margin-top: -25px">
              <span aria-hidden="true">×</span>
            </button>
          </div>

          <div class="modal-body">

@if (count($wcheckProduct['replace']) > 0)
  {{-- expr --}}

<!-- body part -->
<table class="table"> 
  
  <thead>
    <tr>
      <th>#</th>
      <th>IMEI</th>
      <th>Serial No</th>
      <th>Replace Date</th>
    </tr>
  </thead>

  <tbody>
    @foreach ($wcheckProduct['replace'] as $key => $replace)
      <tr>
        <td>{{$key + 1}}</td>
        <td>
          @if ($replace['imei'])
            {{$replace['imei']}}
          @else
            -
          @endif
        </td>


        <td>{{$replace['sno']}} </td>
        <td>{{$replace['rplsdate']}} </td>


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


<!-- modal area -->


<!-- modal area -->


@endif

@endforeach
<!--custom update modal part================================ -->






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
  Session::forget(['imei']);
@endphp
<!-- content part================================ -->
@endsection