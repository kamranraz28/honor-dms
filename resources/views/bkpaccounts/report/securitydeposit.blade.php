@extends('layouts.master_accounts')

@section('title')
  {{"DMS :: Add Securitydeposit"}}
@endsection


@section('content')

<!-- content part================================ -->

    <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Security Deposit
        <small>Control panel</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="{{ route('accounts.dashboard') }}"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="#"></i> Settings</a></li>
        <li class="active"><a href="{{ route('accounts.securitydeposit') }}">Securitydeposit</a></li>
      </ol>
    </section>

  
    <!-- Main content -->
    <section class="content-header">
      <div class="row">
        <div class="">
      
      <div class="box box-warning">
            <div class="box-header with-border">
              <h3 class="box-title">Add Securitydeposit</h3>
            </div>
    

    <!-- ================================== form area==================================== -->
{{-- for for displaying success and errror message --}}
  <form class="form-horizontal" method="POST" action="{{ route('accounts.securitydeposit.store') }}" autocomplete="on" enctype="multipart/form-data">
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
  <label  class="col-sm-2 control-label">Deposit Amount</label>

  <div class="col-sm-10">
    <input type="number" name="securitydeposit" class="form-control"  placeholder="Deposit Amount" min="0" required="required">
  </div>
</div>

                <div class="form-group">
                  <label class="col-sm-2 control-label">Date</label>

                  <div class="col-sm-5">
                    <div class="input-group date">
                      <div class="input-group-addon">
                        <i class="fa fa-calendar"></i>
                      </div>
                      <input name="date" placeholder="MM/DD/YYYY" type="text" class="form-control pull-right" id="datepicker"  required="required" autocomplete="off">
                    </div>
                  </div>

                  <!-- <div class="col-sm-5">
                    <div class="input-group date">
                      <div class="input-group-addon">
                        <i class="fa fa-calendar"></i>
                      </div>
                      <input name="todate" placeholder="MM/DD/YYYY" type="text" class="form-control pull-right" id="datepicker1"  required="required" autocomplete="off">
                    </div>
                  </div> -->
                </div>

<div class="form-group">
  <label  class="col-sm-2 control-label">Remarks</label>

  <div class="col-sm-10">
    <input type="text" name="remarks" class="form-control"  placeholder="Remarks">
  </div>
</div>
                <hr>

              <!-- /.form group -->






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
              <h3 class="box-title">Securitydeposit List</h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
              <table id="example" class="display" cellspacing="0" width="100%">
                <thead>
                <tr>
                  <th>Region</th>
                  <th>Territory</th>
                  <th>Distributor</th>
                  <th>Securitydeposit</th>
                  <th>date</th>
                  <th>Remarks</th>
                  <th>Action</th>
                </tr>
                </thead>
                <tbody>
@foreach ($securitydeposits as $element)
     
     
                <tr>
                  
<td>{{$element['region']['region']}}</td>
<td>{{$element['territory']['territory']}}</td>
<td>{{$element['distributor']['distributor']}}</td>
<td>{{$element['securitydeposit']}}</td>
<td>{{$element['date']}}</td>
<td>
@if ($element['remarks'] != NULL)
  {{$element['remarks']}}
@else
  -
@endif

  


</td>
                  
                  <td>


  <button class="btn btn-xs btn-primary" data-toggle="modal" data-target="#{{'securitydepositUpdateModal'. $element['id']}}"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></button>

  <button class="btn btn-xs btn-danger" data-toggle="modal" data-target="#{{'securitydepositDeleteModal'. $element['id']}}"><i class="fa fa-trash-o" aria-hidden="true"></i></button>

                  </td>

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


<!--custom update modal part================================ -->


@forelse ($securitydeposits as $key => $element)
  <!-- Modal -->
  <div class="modal fade" id="{{'securitydepositUpdateModal'. $element['id']}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
          
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">{{$element['securitydeposit']}}</h5>
            <button class="close" type="button" data-dismiss="modal" aria-label="Close" style="margin-top: -25px">
              <span aria-hidden="true">×</span>
            </button>
          </div>

          <div class="modal-body">
<!-- body part -->

<form action="{{route('accounts.securitydeposit.update')}}" method="post">
  <h3 class="text-info">Do You Want To Update This Data ?</h3>
   <br>

  <input type="hidden" name="_token" value="{{ csrf_token() }}">
  <input name="_method" type="hidden" value="put">
  <input type="hidden" name="id" value="{{ $element['id'] }}">

<div class="form-group {{ $errors->has('securitydeposit') ? 'has-error' : '' }}">
  <label for="securitydeposit">Securitydeposit:</label>
  <input type="text" id="securitydeposit" name="securitydeposit" class="form-control" placeholder="Enter Product Name" value="{{ $element['securitydeposit'] }}">
  <span class="text-danger">{{ $errors->first('securitydeposit') }}</span>
</div>

<div class="form-group {{ $errors->has('remarks') ? 'has-error' : '' }}">
  <label for="remarks">Remarks:</label>
  <input type="text" id="remarks" name="remarks" class="form-control" placeholder="Enter Product Name" value="{{ $element['remarks'] }}">
  <span class="text-danger">{{ $errors->first('remarks') }}</span>
</div>


<div class="form-group {{ $errors->has('date') ? 'has-error' : '' }}">
  <label for="date">Date:</label>
  <input type="text" id="datepickerForModal{{$element['id']}}" name="date" class="form-control" placeholder="Enter Product Name" value="{{ $element['date'] }}">
  <span class="text-danger">{{ $errors->first('date') }}</span>
</div>

  <div class="form-group">
    <button class="form-control btn btn-success">Update</button>
  </div>
</form>


<script>
  $(function () {



    $('#datepickerForModal{{$element['id']}}').datepicker({
      format: 'mm/dd/yyyy',
      autoclose: true
    });

  
  })
</script>


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
<!--custom update modal part================================ -->

<!--custom delete modal part================================ -->


@forelse ($securitydeposits as $key => $element)
  <!-- Modal -->
  <div class="modal fade" id="{{'securitydepositDeleteModal'. $element['id']}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
          
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">{{$element['securitydeposit']}}</h5>
            <button class="close" type="button" data-dismiss="modal" aria-label="Close" style="margin-top: -25px">
              <span aria-hidden="true">×</span>
            </button>
          </div>

          <div class="modal-body">
<!-- body part -->




  <form action="{{route('accounts.securitydeposit.delete',$element['id'])}}" method="post">
   <h3 class="text-info">Do You Want To Delete This Data ?</h3>
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
<!-- // jquery area ========= -->



@endsection