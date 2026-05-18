@extends('layouts.master_admin')

@section('title')
  {{"DMS :: Add Distributor"}}
@endsection


@section('content')

<!-- content part================================ -->

    <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Distributor
        <small>Control panel</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="{{ route('admin.dashboard') }}"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="#"></i> Settings</a></li>
        <li class="active"><a href="{{ route('admin.distributor') }}">Distributor</a></li>
      </ol>
    </section>

    
 
    <!-- Main content -->
    <section class="content-header">
      <div class="row">
        <div class="">
      <div class="box box-warning">
            <div class="box-header with-border">
              <h3 class="box-title">Add Distributor</h3>
            </div>
            <!-- /.box-header -->
            <!-- form start -->
  <form class="form-horizontal" method="POST" action="{{ route('admin.distributor.store') }}" autocomplete="on" enctype="multipart/form-data">

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


<div class="col-md-12">
  <div class="form-group {{ $errors->has('region_id') ? 'has-error' : '' }}">
    <label for="region">Region:</label>

      <select name="region_id" id="region" class="form-control">
        <option value="">Select Region</option>
        @foreach($regions as $region )
          <option value="{{ $region['id'] }}">{{ $region['region'] }}</option>
        @endforeach
      </select>          

    <span class="text-danger">{{ $errors->first('region_id') }}</span>
  </div>
</div>

<div class="col-md-12">
  <div class="form-group {{ $errors->has('territory_id') ? 'has-error' : '' }}">
    <label for="territory">Territory:</label>

      <select name="territory_id" id="territory" class="form-control">
        <option value="">Select Territory</option>
      </select>          

    <span class="text-danger">{{ $errors->first('territory_id') }}</span>
  </div>
</div>



<div class="col-md-12">
  <div class="form-group {{ $errors->has('distributor') ? 'has-error' : '' }}">
    <label for="distributor">Distributor Name:</label>
    <input type="text" id="distributor" name="distributor" class="form-control" placeholder="Enter Distributor Name" value="{{ old('distributor') }}">
    <span class="text-danger">{{ $errors->first('distributor') }}</span>
  </div>
</div>

<div class="col-md-12">
  <div class="form-group {{ $errors->has('owner') ? 'has-error' : '' }}">
    <label for="owner">Owner Name:</label>
    <input type="text" id="owner" name="owner" class="form-control" placeholder="Enter Owner Name" value="{{ old('owner') }}">
    <span class="text-danger">{{ $errors->first('owner') }}</span>
  </div>
</div>
            

<div class="col-md-12">
  <div class="form-group {{ $errors->has('address') ? 'has-error' : '' }}">
    <label for="address">Address:</label>
    <input type="text" id="address" name="address" class="form-control" placeholder="Enter Address" value="{{ old('address') }}">
    <span class="text-danger">{{ $errors->first('address') }}</span>
  </div>
</div>

<div class="col-md-12">
  <div class="form-group {{ $errors->has('contact') ? 'has-error' : '' }}">
    <label for="contact">Contact:</label>
    <input type="text" id="contact" name="contact" class="form-control" placeholder="Enter Contact" value="{{ old('contact') }}">
    <span class="text-danger">{{ $errors->first('contact') }}</span>
  </div>
</div>

<div class="col-md-12">
  <div class="form-group {{ $errors->has('dob') ? 'has-error' : '' }}">
    <label for="dob">Date Of Birth:</label>
    <input type="text" id="datepicker" name="dob" class="form-control" placeholder="Enter Date Of Birth" value="{{ old('dob') }}">
    <span class="text-danger">{{ $errors->first('dob') }}</span>
  </div>
</div>

<div class="col-md-12">
  <div class="form-group {{ $errors->has('trade') ? 'has-error' : '' }}">
    <label for="trade">Trade License No:</label>
    <input type="text" id="trade" name="trade" class="form-control" placeholder="Enter Trade License No" value="{{ old('trade') }}">
    <span class="text-danger">{{ $errors->first('trade') }}</span>
  </div>
</div>

<div class="col-md-12">
  <div class="form-group {{ $errors->has('tin') ? 'has-error' : '' }}">
    <label for="tin">TIN:</label>
    <input type="text" id="tin" name="tin" class="form-control" placeholder="Enter TIN" value="{{ old('tin') }}">
    <span class="text-danger">{{ $errors->first('tin') }}</span>
  </div>
</div>

<div class="col-md-12">
  <div class="form-group {{ $errors->has('bin') ? 'has-error' : '' }}">
    <label for="bin">BIN:</label>
    <input type="text" id="bin" name="bin" class="form-control" placeholder="Enter BIN" value="{{ old('bin') }}">
    <span class="text-danger">{{ $errors->first('bin') }}</span>
  </div>
</div>

<div class="col-md-12">
  <div class="form-group {{ $errors->has('nid') ? 'has-error' : '' }}">
    <label for="nid">NID:</label>
    <input type="text" id="nid" name="nid" class="form-control" placeholder="Enter NID" value="{{ old('nid') }}">
    <span class="text-danger">{{ $errors->first('nid') }}</span>
  </div>
</div>

<div class="col-md-12">
  <div class="form-group {{ $errors->has('bname') ? 'has-error' : '' }}">
    <label for="bname">Bank Name:</label>
    <input type="text" id="bname" name="bname" class="form-control" placeholder="Enter Bank Name" value="{{ old('bname') }}">
    <span class="text-danger">{{ $errors->first('bname') }}</span>
  </div>
</div>

<div class="col-md-12">
  <div class="form-group {{ $errors->has('bname') ? 'has-error' : '' }}">
    <label for="baccount">Bank Account:</label>
    <input type="text" id="baccount" name="baccount" class="form-control" placeholder="Enter Bank Account" value="{{ old('baccount') }}">
    <span class="text-danger">{{ $errors->first('baccount') }}</span>
  </div>
</div>

<div class="col-md-12">
  
  <div class="form-group {{ $errors->has('image') ? 'has-error' : '' }}">
    <label for="image">Photo:</label>
    <input type="file" name="image">
    <span class="text-danger">{{ $errors->first('image') }}</span>
  </div>

</div>

                  
                
              <!-- /.box-body -->
              <div class="box-footer">
                <button type="submit" class="btn btn-success pull-right">Submit</button>
              </div>
              <!-- /.box-footer -->
            
</div>
            </form>
          </div>
        </div>
      </div>
      <div class="row">
            <div class="box box-warning">
            <div class="box-header">
              <h3 class="box-title">Distributor List</h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
<div class="table-responsive1" style="overflow-x: scroll;overflow-y: scroll; height: 250px;white-space:nowrap; width:100%">

              <table id="example" class="display" cellspacing="0" width="100%">
                <thead>
                <tr>
                  <th>Action</th>
                  <th>Distributor ID</th>
                  <th>Distributor Name</th>
                  <th>Owner Name</th>
                  <th>Photo</th>
                  <th>Region</th>
                  <th>Territory</th>
                  
                  <th>Address</th>
                  <th>Contact No.</th>
                  <th>Bank Name</th>
                  <th>Bank Account</th>

                  <th>DateOfBirth</th>
                  <th>TIN</th>
                  <th>BIN</th>
                  <th>NID</th>

                  
                  
                </tr>
                </thead>
                <tbody>



@foreach ($distributors as $element)
      <tr>

        <td>

          <!-- <a href="" class=" btn btn-warning btn-md">Edit</a> <a href="" class=" btn btn-danger btn-md">Delete</a> -->
  <button class="btn btn-xs btn-primary" data-toggle="modal" data-target="#{{'distributorUpdateModal'. $element['id']}}"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></button>

  <button class="btn btn-xs btn-danger" data-toggle="modal" data-target="#{{'distributorDeleteModal'. $element['id']}}"><i class="fa fa-trash-o" aria-hidden="true"></i></button>

        </td>


        <td>{{$element['duid']}}</td>
        <td>{{$element['distributor']}}</td>
        <td>{{$element['owner']}}</td>
                <td> 

@if ($element['photo'])
<a target="_blank" href="{{ asset( 'storage/app/' . $element['photo']) }}">
  <img width="30px" height="20px" src="{{ asset( 'storage/app/' . $element['photo']) }}"> 
</a>
@else
  No Image File
@endif
        </td>
        <td>{{$element['region']['region']}}</td>
        <td>{{$element['territory']['territory']}}</td>

        
        <td>{{$element['address']}}</td>
        <td>{{$element['contact']}}</td>
        <td>{{$element['bname']}}</td>
        <td>{{$element['baccount']}}</td>
        <td>{{$element['dob']}}</td>
        <td>{{$element['tin']}}</td>
        <td>{{$element['bin']}}</td>
        <td>{{$element['nid']}}</td>



      

      </tr>
@endforeach                
               
                
              
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




<!--custom update modal part================================ -->


@forelse ($distributors as $key => $element)
  <!-- Modal -->
  <div class="modal fade" id="{{'distributorUpdateModal'. $element['id']}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
          
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">{{$element['distributor']}}</h5>
            <button class="close" type="button" data-dismiss="modal" aria-label="Close" style="margin-top: -25px">
              <span aria-hidden="true">×</span>
            </button>
          </div>

          <div class="modal-body">
<!-- body part -->

<form action="{{route('admin.distributor.update')}}" method="post" autocomplete="on" enctype="multipart/form-data">
  <h5 class="text-info">Do You Want To Update This Data ?</h5>
   <br>

  <input type="hidden" name="_token" value="{{ csrf_token() }}">
  <input name="_method" type="hidden" value="put">
  <input type="hidden" name="id" value="{{ $element['id'] }}">




<!-- <div class="form-group {{-- $errors->has('distributor') ? 'has-error' : '' --}}">
  <label for="distributor">Distributor:</label>
  <input type="text" id="distributor" name="distributor" class="form-control" placeholder="Enter Distributor Name" value="{{-- $element['distributor'] --}}">
  <span class="text-danger">{{-- $errors->first('distributor') --}}</span>
</div> -->





<div class="col-md-12">
  <div class="form-group {{ $errors->has('region') ? 'has-error' : '' }}">
    <label for="region">Region:</label>

      <select name="region_id" id="region{{$element['id']}}" class="form-control">
        <option value="">Select Region</option>
        @foreach($regions as $region )
          <option value="{{ $region['id'] }}" {{ $element['region_id'] == $region['id'] ? ' selected="selected"' : '' }}>{{ $region['region'] }}</option>
        @endforeach
      </select>          

    <span class="text-danger">{{ $errors->first('region') }}</span>
  </div>
</div>



<div class="col-md-12">
  <div class="form-group {{ $errors->has('territory') ? 'has-error' : '' }}">
    <label for="territory">Territory:</label>

      <select name="territory_id" id="territory{{$element['id']}}" class="form-control">
@foreach ($territories as $territory)
  <option value="{{ $territory['id'] }}" {{ $element['territory_id'] == $territory['id'] ? ' selected="selected"' : '' }}>{{ $territory['territory'] }}</option>
@endforeach

        <option value="">Select Territory</option>
      </select>          

    <span class="text-danger">{{ $errors->first('territory') }}</span>
  </div>
</div>

<!-- // jquery area ========= -->
<script type="text/javascript">

  $('#region{{$element['id']}}').on('change', function(e){
    var region_id = e.target.value;
    console.log(region_id);
    var route = "{{route('admin.territorySelectBoxOnRegionWithAjax')}}/"+region_id;
    $.get(route, function(data) {
      console.log(data);
      $('#territory{{$element['id']}}').empty();
      
      $.each(data, function(index,data){
        $('#territory{{$element['id']}}').append('<option value="' + data.id + '">' + data.territory + '</option>');
      });
    });
  });

</script>
<!-- // jquery area ========= -->

<div class="col-md-12">
  <div class="form-group {{ $errors->has('distributor') ? 'has-error' : '' }}">
    <label for="distributor">Distributor Name:</label>
    <input type="text" id="distributor" name="distributor" class="form-control" placeholder="Enter Distributor Name" value="{{$element['distributor']}}">
    <span class="text-danger">{{ $errors->first('distributor') }}</span>
  </div>
</div>

<div class="col-md-12">
  <div class="form-group {{ $errors->has('owner') ? 'has-error' : '' }}">
    <label for="owner">Owner Name:</label>
    <input type="text" id="owner" name="owner" class="form-control" placeholder="Enter Owner Name" value="{{$element['owner']}}">
    <span class="text-danger">{{ $errors->first('owner') }}</span>
  </div>
</div>
            

<div class="col-md-12">
  <div class="form-group {{ $errors->has('address') ? 'has-error' : '' }}">
    <label for="address">Address:</label>
    <input type="text" id="address" name="address" class="form-control" placeholder="Enter Address" value="{{$element['address']}}">
    <span class="text-danger">{{ $errors->first('address') }}</span>
  </div>
</div>

<div class="col-md-12">
  <div class="form-group {{ $errors->has('contact') ? 'has-error' : '' }}">
    <label for="contact">Contact:</label>
    <input type="text" id="contact" name="contact" class="form-control" placeholder="Enter Contact" value="{{$element['contact']}}">
    <span class="text-danger">{{ $errors->first('contact') }}</span>
  </div>
</div>

<div class="col-md-12">
  <div class="form-group {{ $errors->has('dob') ? 'has-error' : '' }}">
    <label for="dob">Date Of Birth:</label>
    <input type="text" id="datepicker{{$element['id']}}" name="dob" class="form-control" placeholder="Enter Date Of Birth" value="{{$element['dob']}}">
    <span class="text-danger">{{ $errors->first('dob') }}</span>
  </div>
</div>

<!-- jquery area =========== -->

<script>
  $(function () {
    $('#datepicker{{$element['id']}}').datepicker({
      format: 'dd/mm/yyyy',
      autoclose: true
    });
  })
</script>
<!-- jquery area =========== -->



<div class="col-md-12">
  <div class="form-group {{ $errors->has('dob') ? 'has-error' : '' }}">
    <label for="trade">Trade License No:</label>
    <input type="text" id="trade" name="trade" class="form-control" placeholder="Enter Trade License No" value="{{$element['trade']}}">
    <span class="text-danger">{{ $errors->first('trade') }}</span>
  </div>
</div>

<div class="col-md-12">
  <div class="form-group {{ $errors->has('tin') ? 'has-error' : '' }}">
    <label for="tin">TIN:</label>
    <input type="text" id="tin" name="tin" class="form-control" placeholder="Enter TIN" value="{{$element['tin']}}">
    <span class="text-danger">{{ $errors->first('tin') }}</span>
  </div>
</div>

<div class="col-md-12">
  <div class="form-group {{ $errors->has('bin') ? 'has-error' : '' }}">
    <label for="bin">BIN:</label>
    <input type="text" id="bin" name="bin" class="form-control" placeholder="Enter BIN" value="{{$element['bin']}}">
    <span class="text-danger">{{ $errors->first('bin') }}</span>
  </div>
</div>

<div class="col-md-12">
  <div class="form-group {{ $errors->has('nid') ? 'has-error' : '' }}">
    <label for="nid">NID:</label>
    <input type="text" id="nid" name="nid" class="form-control" placeholder="Enter NID" value="{{$element['nid']}}">
    <span class="text-danger">{{ $errors->first('nid') }}</span>
  </div>
</div>

<div class="col-md-12">
  <div class="form-group {{ $errors->has('bname') ? 'has-error' : '' }}">
    <label for="bname">Bank Name:</label>
    <input type="text" id="bname" name="bname" class="form-control" placeholder="Enter Bank Name" value="{{$element['bname']}}">
    <span class="text-danger">{{ $errors->first('bname') }}</span>
  </div>
</div>

<div class="col-md-12">
  <div class="form-group {{ $errors->has('bname') ? 'has-error' : '' }}">
    <label for="baccount">Bank Account:</label>
    <input type="text" id="baccount" name="baccount" class="form-control" placeholder="Enter Bank Account" value="{{$element['baccount']}}">
    <span class="text-danger">{{ $errors->first('baccount') }}</span>
  </div>
</div>

<div class="col-md-12">
  
  <div class="form-group {{ $errors->has('image') ? 'has-error' : '' }}">
    <label for="image">Photo:</label>
    <input type="file" name="image">
    <span class="text-danger">{{ $errors->first('image') }}</span>
  </div>

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
@empty
  {{'Data not found'}}
@endforelse
<!--custom update modal part================================ -->

<!--custom delete modal part================================ -->


@forelse ($distributors as $key => $element)
  <!-- Modal -->
  <div class="modal fade" id="{{'distributorDeleteModal'. $element['id']}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
          
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">{{$element['distributor']}}</h5>
            <button class="close" type="button" data-dismiss="modal" aria-label="Close" style="margin-top: -25px">
              <span aria-hidden="true">×</span>
            </button>
          </div>

          <div class="modal-body">
<!-- body part -->




  <form action="{{route('admin.distributor.delete',$element['id'])}}" method="post">
   <h5 class="text-info">Do You Want To Delete This Data ?</h5>
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

  $('#region').on('change', function(e){
    var region_id = e.target.value;
    console.log(region_id);
    var route = "{{route('admin.territorySelectBoxOnRegionWithAjax')}}/"+region_id;
    $.get(route, function(data) {
      console.log(data);
      $('#territory').empty();
      
      $.each(data, function(index,data){
        $('#territory').append('<option value="' + data.id + '">' + data.territory + '</option>');
      });
    });
  });

</script>
<!-- // jquery area ========= -->


@endsection