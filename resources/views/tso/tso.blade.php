@extends('layouts.master_tso')

@section('title')
  {{"DMS :: Add User"}}
@endsection


@section('content')

<!-- content part================================ -->

    <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <!-- bc part================================ -->
      @include('tso.bc.bc')
    <!-- bc part================================ -->
    </section>

    


    <!-- Main content -->
    <section class="content-header">

      <div class="row">
            <div class="box box-warning">
            <div class="box-header">
              <h3 class="box-title">User List</h3>
            </div>
            <!-- /.box-header -->
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


              <table id="example" class="display" cellspacing="0" width="100%">
                <thead>
                <tr>
                  <th>Action</th>
                  <th>Name</th>
                  <th>Email</th>
                  <th>Office ID</th>
                  <th>Contact No.</th>

                  <th>Division</th>
                  <th>District</th>
                  <th>Upazila</th>     




                  <th>Level</th>
                  <th>Password Status</th>
                  <th>Status</th>
                  
                  <th>Photo</th>
                  
                </tr>
                </thead>
                <tbody>
@foreach ($users as $element)
        <tr>
<td>

    <button class="btn btn-xs btn-primary" data-toggle="modal" data-target="#{{'userUpdateModal'. $element['id']}}"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></button>
 
</td>


          <td>{{$element['firstname']}} {{$element['lastname']}}</td>
          <td>{{$element['email']}}</td>
          <td>{{$element['officeid']}}</td>
          <td>{{$element['contact']}}</td>



<td>
  @if ($element['division']['name'])
    {{$element['division']['name']}}
  @else
    -
  @endif
</td>

<td>
  @if ($element['district']['name'])
    {{$element['district']['name']}}
  @else
    -
  @endif
</td>

<td>
  @if ($element['upazila']['name'])
    {{$element['upazila']['name']}}
  @else
    -
  @endif
</td>

          
<td>
  @if ($element->level == 500)
    <button class="btn btn-danger btn-xs">Admin</button>
  @elseif($element->level == 400)
    <button class="btn btn-info btn-xs">Top Management</button>
  @elseif($element->level == 300)
    <button class="btn btn-info btn-xs">Mid Management</button>
  @elseif($element->level == 200)
    <button class="btn btn-info btn-xs">Retailer</button>
  @elseif($element->level == 100)
    <button class="btn btn-info btn-xs">Distributor</button>
  @elseif($element->level == 10)
    <button class="btn btn-info btn-xs">TSO/TSM</button>
  @elseif($element->level == 1000)
    <button class="btn btn-info btn-xs">Huawei</button>
  @else
    <button class="btn btn-warning btn-xs">SR</button>
  @endif

</td>

<td>
  <button class="btn btn-xs btn-danger" data-toggle="modal" data-target="#{{'userPasswordChangeModal'. $element['id']}}">
    Change Password
  </button>
</td>

<td>



  @if ($element['level'] == 500)
    <button class="btn btn-xs btn-primary" data-toggle="modal" data-target="#">Active</button>
  @else
    @if ($element['active'] == true)
       <button class="btn btn-xs btn-primary" data-toggle="modal" data-target="#{{'userStatusModal1'. $element['id']}}">Active</button>
    @else
      <button class="btn btn-xs btn-danger" data-toggle="modal" data-target="#{{'userStatusModal1'. $element['id']}}">Inactive</button>
    @endif
  @endif


</td>

<td> 

  @if ($element['photo'])
  <a target="_blank" href="{{ asset( 'storage/app/' . $element['photo']) }}">
    <img width="30px" height="20px" src="{{ asset( 'storage/app/' . $element['photo']) }}"> 
  </a>
  @else
    No Image File
  @endif
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
  <!-- /.content-wrapper -->
<!-- content part================================ -->








<!--custom update modal part================================ -->


@forelse ($users as $key => $element)
  <!-- Modal -->
  <div class="modal fade" id="{{'userUpdateModal'. $element['id']}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
          
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">{{$element['firstname']}}</h5>
            <button class="close" type="button" data-dismiss="modal" aria-label="Close" style="margin-top: -25px">
              <span aria-hidden="true">×</span>
            </button>
          </div>

          <div class="modal-body">
<!-- body part -->

<form action="{{route('tso.tso.update')}}" method="post"  autocomplete="on" enctype="multipart/form-data">
  <h3 class="text-info">Do You Want To Update This Data ?</h3>
   <br>

  <input type="hidden" name="_token" value="{{ csrf_token() }}">
  <input name="_method" type="hidden" value="put">
  <input type="hidden" name="id" value="{{ $element['id'] }}">


<div class="col-md-12">
  <div id="divisionArea" class="form-group {{ $errors->has('division_id') ? 'has-error' : '' }}">
    <label for="division">Division</label>
      <select name="division_id" id="division" class="form-control" style="width: 100%;" required="required">
        <option value="">Select Division</option>
        @foreach($divisions as $division )
          <option value="{{ $division['id'] }}" {{ $element['division']['id'] == $division['id'] ? ' selected="selected"' : '' }}>{{ $division['name'] }}</option>
        @endforeach
      </select>
      <span class="text-danger">{{ $errors->first('division_id') }}</span>
  </div>
</div>


<div class="col-md-12">
  <div id="districtArea" class="form-group {{ $errors->has('district_id') ? 'has-error' : '' }}">
    <label for="district">District</label>
      <select name="district_id" id="district" class="form-control" style="width: 100%;" required="required">
        <option value="">Select District</option>
        @foreach($districts as $district )
          <option value="{{ $district['id'] }}" {{ $element['district']['id'] == $district['id'] ? ' selected="selected"' : '' }}>{{ $district['name'] }}</option>
        @endforeach
      </select>
      <span class="text-danger">{{ $errors->first('district_id') }}</span>
  </div>
</div>

<div class="col-md-12">
  <div id="upazilaArea" class="form-group {{ $errors->has('upazila_id') ? 'has-error' : '' }}">
    <label for="upazila">Upazila</label>
      <select name="upazila_id" id="upazila" class="form-control" style="width: 100%;" required="required">
        <option value="">Select Upazila</option>
        @foreach($upazilas as $upazila )
          <option value="{{ $upazila['id'] }}" {{ $element['upazila']['id'] == $upazila['id'] ? ' selected="selected"' : '' }}>{{ $upazila['name'] }}</option>
        @endforeach
      </select>
      <span class="text-danger">{{ $errors->first('upazila_id') }}</span>
  </div>
</div>




<div class="col-md-12">
  <div class="form-group {{ $errors->has('firstname') ? 'has-error' : '' }}">
    <label for="firstname">Firstname:</label>
    <input type="text" id="firstname" name="firstname" class="form-control" placeholder="Enter Firstname" value="{{$element['firstname']}}">
    <span class="text-danger">{{ $errors->first('firstname') }}</span>
  </div>
</div>

<div class="col-md-12">
  <div class="form-group {{ $errors->has('lastname') ? 'has-error' : '' }}">
    <label for="lastname">Lastname:</label>
    <input type="text" id="lastname" name="lastname" class="form-control" placeholder="Enter Lastname" value="{{$element['lastname']}}">
    <span class="text-danger">{{ $errors->first('lastname') }}</span>
  </div>
</div>

<div class="col-md-12">
  <div class="form-group {{ $errors->has('officeid') ? 'has-error' : '' }}">
    <label for="officeid">Office ID:</label>
    <input type="text" id="officeid" name="officeid" class="form-control" placeholder="Enter Office ID" value="{{$element['officeid']}}" disabled="disabled">
    <span class="text-danger">{{ $errors->first('officeid') }}</span>
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






<!--custom update modal part================================ -->


@forelse ($users as $key => $element)
  <!-- Modal -->
  <div class="modal fade" id="{{'userPasswordChangeModal'. $element['id']}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
          
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">{{$element['firstname']}}</h5>
            <button class="close" type="button" data-dismiss="modal" aria-label="Close" style="margin-top: -25px">
              <span aria-hidden="true">×</span>
            </button>
          </div>

          <div class="modal-body">
<!-- body part -->

<form action="{{route('tso.tso.updatePassword')}}" method="post"  autocomplete="on" enctype="multipart/form-data">
  <h3 class="text-info">Do You Want To Update Password ?</h3>
   <br>

  <input type="hidden" name="_token" value="{{ csrf_token() }}">
  <input name="_method" type="hidden" value="put">
  <input type="hidden" name="id" value="{{ $element['id'] }}">





  
  <div class="form-group {{ $errors->has('password') ? 'has-error' : '' }}">
    <label class="col-sm-2 control-label">Password</label>

    <div class="col-sm-10">
      <input type="password" id="password" name="password" class="form-control" placeholder="Enter Password" value="{{ old('password') }}" required="required">
      <span class="text-danger">{{ $errors->first('password') }}</span>
    </div>

  </div>

<br><br>


  <div class="form-group {{ $errors->has('confirm_password') ? 'has-error' : '' }}">
    <label class="col-sm-2 control-label">Confirm Password</label>

    <div class="col-sm-10">
      <input type="password" id="confirm_password" name="confirm_password" class="form-control" placeholder="Enter Confirm Password" value="{{ old('confirm_password') }}" required="required">
      <span class="text-danger">{{ $errors->first('confirm_password') }}</span>
    </div>

  </div>


<div class="col-md-12"> 
  <div class="form-group">
    <button class="form-control btn btn-warning">Update Password</button>
  </div>
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






<!--custom hospitaldetailStatusModal modal part================================ -->


@forelse ($users as $key => $element)
  <!-- Modal -->
  <div class="modal fade" id="{{'userStatusModal'. $element['id']}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
          
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">{{$element['email']}}</h5>
            <button class="close" type="button" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">×</span>
            </button>
          </div>

          <div class="modal-body">
<!-- body part -->


@if ($element['active'] == true)
  <form action="{{route('admin.user.inactive',$element['id'])}}" method="post">
   <h3 class="text-info">Do You Want To Inactive This User ?</h3>
   <br>
    <input type="hidden" name="_token" value="{{ csrf_token() }}">
    <input type="hidden" name="id" value="{{ $element['id'] }}">
    <!-- <input name="_method" type="hidden" value="delete"> -->
    
    <div class="form-group">
      <button class="form-control btn btn-danger">Inactive</button>
    </div>

  </form>
@else
   <form action="{{route('admin.user.active',$element['id'])}}" method="post">
   <h3 class="text-info">Do You Want To Active This User ?</h3>
   <br>
    <input type="hidden" name="_token" value="{{ csrf_token() }}">
    <input type="hidden" name="id" value="{{ $element['id'] }}">
    <!-- <input name="_method" type="hidden" value="delete"> -->
    
    <div class="form-group">
      <button class="form-control btn btn-primary">Active</button>
    </div>

  </form>
@endif



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
<!--custom hospitaldetailStatusModal modal part================================ -->






<!-- // jquery area ========= -->
<script type="text/javascript">

  $('#level').on('change', function(e){
    var level = e.target.value;
    //console.log(level);
    if (level == 300 || level == 100) {
      $('#retailerArea').css({'display':'block'});
    } else {
      //$('#user_id').empty();
      $('#retailerArea').css({'display':'none'});
      //$('#user_id').val('');
    }
  });




  $('#division').on('change', function(e){
    var division_id = e.target.value;
    //console.log(division_id);
    var route = "{{route('admin.districtSelectBoxOnDivisionWithAjax')}}/"+division_id;
    $.get(route, function(data) {
      //console.log(data);
      $('#district').empty();
      $('#district').append('<option value="'+'">Select District</option>');
      $.each(data, function(index,data){
        $('#district').append('<option value="' + data.id + '">' + data.name + '</option>');
      });
    });
  });

  $('#district').on('change', function(e){
    var district_id = e.target.value;
    //console.log(district_id);
    var route = "{{route('admin.upazilaSelectBoxOnDistrictWithAjax')}}/"+district_id;
    $.get(route, function(data) {
      //console.log(data);
      $('#upazila').empty();
      
      $.each(data, function(index,data){
        $('#upazila').append('<option value="' + data.id + '">' + data.name +'</option>');
      });
    });
  });


</script>

<!-- // jquery area ========= -->

@endsection