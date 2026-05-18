@extends('layouts.master_sales1')

@section('title')
  {{"DMS ::  View Distributor Target"}}
@endsection


@section('content')

<!-- content part================================ -->

    <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        View Distributor Target
        <small>Control panel</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="{{ route('sales.dashboard') }}"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="#"></i> SALES</a></li>
        <li class="active"><a href="{{ route('sales.viewDistributorTarget') }}">View Distributor Target</a></li>
      </ol>
    </section>

  
    <!-- Main content -->
    <section class="content-header">
      <div class="row">
        <div class="">
      <div class="box box-warning">
            <div class="box-header with-border">
              <h3 class="box-title">Create Invoice</h3>
            </div>
            <!-- /.box-header -->
            <!-- form start -->
  <form class="form-horizontal" method="POST" action="{{ route('sales.viewDistributorTarget.store') }}" autocomplete="on" enctype="multipart/form-data">
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

                  <div class="col-sm-5">
                    <div class="input-group date">
                      <div class="input-group-addon">
                        <i class="fa fa-calendar"></i>
                      </div>
                      <input name="dateMonth" placeholder="DD/YYYY" type="text" class="form-control pull-right" id="monthpicker"  required="required">
                    </div>
                  </div>

                  <!-- <div class="col-sm-5">
                    <div class="input-group date">
                      <div class="input-group-addon">
                        <i class="fa fa-calendar"></i>
                      </div>
                      <input name="dateMonth1" placeholder="DD/YYYY" type="text" class="form-control pull-right" id="monthpicker1"  required="required">
                    </div>
                  </div> -->
                </div>

                <hr>

              <!-- /.form group -->

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
              <h3 class="box-title">Target List</h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
              <table id="example" class="display" cellspacing="0" width="100%">
                <thead>
                <tr>
                  <th>Region1</th>
                  <th>Territory</th>
                  <th>Distributor Name</th>
                  <th>Product Name</th>
                  <th>Targeted Time</th>
                  <th>Target Quantity(Boxs)</th>
                  <th>Target Value</th>
                  <th>Target Achived(Boxs)</th>
                  <th>Target Achived Value</th>
                  <th>Target Percentage</th>
                </tr>
                </thead>
                <tbody>
@foreach ($data as $element)
  <tr>
    <td>{{$element['region']}}</td>
    <td>{{$element['territory']}}</td>
    <td>{{$element['distributor']}}</td>
    <td>{{$element['product']}}</td>
    <td>{{$element['date']}}</td>
    <td>{{$element['product_qty']}}</td>
    <td>{{$element['product_qty'] * $element['product_price']}}</td>
    <td>{{$element['product_qty1']}}</td>
    <td>{{$element['product_qty1'] * $element['product_price']}}</td>
    <td>{{ ($element['product_qty1'] * 100)/$element['product_qty'] }}%</td>
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