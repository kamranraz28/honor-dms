@extends('layouts.master_admin')

@section('title')
  {{"Sales Automation Process :: Add User"}}
@endsection


@section('content')

<!-- content part================================ -->

    <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <!-- bc part================================ -->
      @include('admin.bc.bc')
    <!-- bc part================================ -->
    </section>

    


    <!-- Main content -->
    <section class="content-header">
      <div class="row">
        <div class="">
      <div class="box box-warning">
            <div class="box-header with-border">
              <h3 class="box-title">Add User</h3>
            </div>
            <!-- /.box-header -->
            <!-- form start -->
{{-- for for displaying success and errror message --}}
  <form class="form-horizontal" method="POST" action="{{ route('admin.user.store') }}" autocomplete="on" enctype="multipart/form-data">
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



<!-- //cas cading dropdown list =============== -->
                <div id="divisionArea" class="form-group {{ $errors->has('division_id') ? 'has-error' : '' }}">
                  <label for="division" class="col-sm-2 control-label">Division</label>
                  <div class="col-sm-10">
                    <select name="division_id" id="division" class="form-control" style="width: 100%;" required="required">
                      <option value="">Select Division</option>
                      @foreach($divisions as $division )
                        <option value="{{ $division['id'] }}">{{ $division['name'] }}</option>
                      @endforeach
                    </select>
                    <span class="text-danger">{{ $errors->first('division_id') }}</span>
                  </div>
                </div>

                <div id="districtArea" class="form-group {{ $errors->has('district_id') ? 'has-error' : '' }}">
                  <label for="district" class="col-sm-2 control-label">District</label>
                  <div class="col-sm-10">
      
      <select name="district_id" id="district" class="form-control" required="required">
        <option value="">Select District</option>
      </select>  
                    <span class="text-danger">{{ $errors->first('district_id') }}</span>
                  </div>
                </div>


                <div id="upazilaArea" class="form-group {{ $errors->has('upazila_id') ? 'has-error' : '' }}">
                <label for="Zone" class="col-sm-2 control-label">Add Upazila</label>
                  <div class="col-sm-10">
                    <select name="upazila_id" id="upazila" class="form-control" required="required" data-placeholder="Select Upazila" style="width: 100%;">
                      <option value="">Select Upazila</option>
                    </select>
                    <span class="text-danger">{{ $errors->first('upazila_id') }}</span>
                  </div>
                </div>

                <div class="form-group">
                  <label class="col-sm-2 control-label">Address</label>

                  <div class="col-sm-10">
                    <input type="text" id="address" name="address" class="form-control" placeholder="Enter address" value="{{ old('address') }}">
                  </div>

                </div>


<!-- //cas cading dropdown list =============== -->













                <div class="form-group {{ $errors->has('firstname') ? 'has-error' : '' }}">
                  <label class="col-sm-2 control-label">Full Name</label>

                  <div class="col-sm-10">
                    <input type="text" id="firstname" name="firstname" class="form-control" placeholder="Enter Firstname" value="{{ old('firstname') }}">
                    <span class="text-danger">{{ $errors->first('firstname') }}</span>
                  </div>
                </div>


                <!-- <div class="form-group {{-- $errors->has('lastname') ? 'has-error' : '' --}}">
                  <label class="col-sm-2 control-label">Last Name</label>
                
                  <div class="col-sm-10">
                    <input type="text" id="lastname" name="lastname" class="form-control" placeholder="Enter Last Name" value="{{-- old('lastname') --}}">
                    <span class="text-danger">{{-- $errors->first('lastname') --}}</span>
                  </div>
                </div> -->


                <div class="form-group {{ $errors->has('email') ? 'has-error' : '' }}">
                  <label class="col-sm-2 control-label">Email</label>

                  <div class="col-sm-10">
                    <input type="text" id="email" name="email" class="form-control" placeholder="Enter Email" value="{{ old('email') }}">
                    <span class="text-danger">{{ $errors->first('email') }}</span>
                  </div>

                </div>

                <div class="form-group {{ $errors->has('officeid') ? 'has-error' : '' }}">
                  <label class="col-sm-2 control-label">Office ID</label>

                  <div class="col-sm-10">
                    <input type="text" id="officeid" name="officeid" class="form-control" placeholder="Enter Office ID" value="{{ old('officeid') }}">
                    <span class="text-danger">{{ $errors->first('officeid') }}</span>
                  </div>

                </div>

                <div class="form-group {{ $errors->has('password') ? 'has-error' : '' }}">
                  <label class="col-sm-2 control-label">Password</label>

                  <div class="col-sm-10">
                    <input type="password" id="password" name="password" class="form-control" placeholder="Enter Password" value="{{ old('password') }}">
                    <span class="text-danger">{{ $errors->first('password') }}</span>
                  </div>

                </div>

                <div class="form-group {{ $errors->has('confirm_password') ? 'has-error' : '' }}">
                  <label class="col-sm-2 control-label">Confirm Password</label>

                  <div class="col-sm-10">
                    <input type="password" id="confirm_password" name="confirm_password" class="form-control" placeholder="Enter Confirm Password" value="{{ old('confirm_password') }}">
                    <span class="text-danger">{{ $errors->first('confirm_password') }}</span>
                  </div>

                </div>

                <div class="form-group {{ $errors->has('contact_name') ? 'has-error' : '' }}">
                  <label class="col-sm-2 control-label">Contact Name</label>

                  <div class="col-sm-10">
                    <input type="text" id="contact_name" name="contact_name" class="form-control" placeholder="Enter Contact No" value="{{ old('contact_name') }}">
                    <span class="text-danger">{{ $errors->first('contact_name') }}</span>
                  </div>

                </div>

                
                <div class="form-group {{ $errors->has('contact') ? 'has-error' : '' }}">
                  <label class="col-sm-2 control-label">Contact No</label>

                  <div class="col-sm-10">
                    <input type="text" id="contact" name="contact" class="form-control" placeholder="Enter Contact No" value="{{ old('contact') }}">
                    <span class="text-danger">{{ $errors->first('contact') }}</span>
                  </div>

                </div>



                <div class="form-group {{ $errors->has('image') ? 'has-error' : '' }}">
                  <label for="inputPassword3" class="col-sm-2 control-label">Upload Picture</label>
                  <div class="col-sm-10">
                    <input class="form-control" name="image" type="file">
                    <span class="text-danger">{{ $errors->first('image') }}</span>
                  </div>
                </div>



                <div class="form-group {{ $errors->has('level') ? 'has-error' : '' }}">
                  <label for="Level" class="col-sm-2 control-label">Level</label>
                  <div class="col-sm-10">
                    <select name="level" id="level" class="form-control" style="width: 100%;">
                      <option value="" selected="selected">Select Level</option>
                      {{-- <option value="1000">Huawei</option> --}}
                      <option value="400">Top Management</option>
                      <option value="300">Mid Management</option>
                      <option value="10">TSO/TSM</option>
                      <option value="100">Distributor</option>
                      <option value="6">Warehouse</option>
                      <option value="7">Accounts</option>
                      <option value="5">Service Center</option>
                      <!-- <option value="200">Retailer</option> -->
                      <!-- <option value="50">SR</option> -->
                      
                      
                    </select>
                    <span class="text-danger">{{ $errors->first('level') }}</span>
                  </div>
                </div>

                <div class="form-group">
                  <label class="col-sm-2 control-label">Category</label>

                  <div class="col-sm-10">
                    <input type="text" id="dis_cat" name="dis_cat" class="form-control" placeholder="Enter category" value="{{ old('dis_cat') }}">
                  </div>

                </div>


               <!--    <div id="retailerArea" class="form-group {{ $errors->has('user_id') ? 'has-error' : '' }}" style="display: none">
                 <label for="user" class="col-sm-2 control-label">Retailer</label>
                 <div class="col-sm-10">
                   <select name="retailers[]" id="user_id1" class="form-control select2" multiple="multiple" style="width: 100%;">
                     
                     @foreach ($retailers as $key => $element)
                       <option value="{{$element['id']}}">{{$element['firstname']}} - {{$element['officeid']}}</option>
                     @endforeach
                   </select>
                   <span class="text-danger">{{ $errors->first('user_id') }}</span>
                 </div>
               </div> -->


                    {{-- <div id="hddistrictArea" class="form-group {{ $errors->has('district_id') ? 'has-error' : '' }}" style="display: none">
                      <label for="district" class="col-sm-2 control-label">District</label>
                        <div class="col-sm-10">
                          <select name="districts[]" id="district1" class="form-control select3" multiple="multiple" style="width: 100%;" required="required">
                            <option value="">Select District</option>
                            @foreach($districts as $district )
                              <option value="{{ $district['id'] }}">{{ $district['name'] }}</option>
                            @endforeach
                          </select>
                          <span class="text-danger">{{ $errors->first('district_id') }}</span>
                        </div>
                    </div>

                    <div id="hdupazilaArea" class="form-group {{ $errors->has('upazila_id') ? 'has-error' : '' }}" style="display: none">
                      <label for="upazila" class="col-sm-2 control-label">Upazila</label>
                        <div class="col-sm-10">
                          <select name="upazilas[]" id="upazila1" class="form-control select4" multiple="multiple" style="width: 100%;" required="required">
                            <option value="">Select Upazila</option>
                            @foreach($upazilas as $upazila )
                              <option value="{{ $upazila['id'] }}">{{ $upazila['name'] }}</option>
                            @endforeach
                          </select>
                          <span class="text-danger">{{ $errors->first('upazila_id') }}</span>
                        </div>
                    </div> --}}

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
              <h3 class="box-title">User List</h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
              <table id="example" class="ui celled table" cellspacing="0" width="100%">
                <thead>
                <tr>
                  <th>Action</th>
                  <th>Level</th>
                  <th>Category</th>
                  <th>Name</th>
                  <th>Email</th>
                  <th>Alternative Email</th>
                  <th>Office ID</th>
                  <th>Contact Name</th>
                  <th>Contact No.</th>
                  <th>Division</th>
                  <th>District</th>
                  <th>Upazila</th>
                  <th>Address</th>
                  <th>Password Status</th>
                  <th>Status</th>
                  <th>Return</th>
                  
                  <th>Photo</th>
                  <th>NID Image</th>
                  
                </tr>
                </thead>
                <tbody>
@foreach ($users as $element)
        <tr>


<td>
  <a href="{{ route('admin.singleuser',[$element->id]) }}" target="_blank" class="btn btn-xs btn-info">
    <i class="fa fa-info" aria-hidden="true" style="width: 10px"></i>
  </a>

  @if ($element->level == 500)
    <!-- <button class="btn btn-xs btn-primary" data-toggle="modal" data-target="#{{'userUpdateModal'. $element->id}}"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></button> -->
  @else
     <!-- <button class="btn btn-xs btn-primary" data-toggle="modal" data-target="#{{'userUpdateModal'. $element->id}}"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></button> -->
    
      <!-- <button class="btn btn-xs btn-danger" data-toggle="modal" data-target="#{{'userDeleteModal'. $element->id}}"><i class="fa fa-trash-o" aria-hidden="true"></i></button>  -->
  @endif
@if ($element->level == 100 || $element->level == 50)
  
  <!-- <button class="btn btn-xs btn-warning" data-toggle="modal" data-target="#{{'retailerAddModal'. $element->id}}"><i class="fa fa-plus" aria-hidden="true"></i></button> -->
  <!-- <a href="{{ route('admin.singleuser',[$element->id]) }}" target="_blank" class="btn btn-xs btn-info">
    <i class="fa fa-info" aria-hidden="true" style="width: 10px"></i>
  </a> -->
  <!-- @if (count($element->retailer) > 0 )
    
  
    <button class="btn btn-xs btn-info" data-toggle="modal" data-target="#{{'retailerInfoModal'. $element->id}}"><i class="fa fa-info" aria-hidden="true"></i></button>
  @endif -->
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
  @elseif($element->level == 5)
    <button class="btn btn-info btn-xs">Servie Center</button>
  @elseif($element->level == 6)
    <button class="btn btn-info btn-xs">Warehouse</button>
 @elseif($element->level == 7)
    <button class="btn btn-info btn-xs">Accounts</button>
  @else
    <button class="btn btn-warning btn-xs">SR</button>
  @endif

</td>


<td>@if (!empty($element['dis_cat']))
    {{ $element['dis_cat'] }}
  @else
    -
  @endif</td>


          <td>{{$element->firstname}} {{$element->lastname}}</td>
          <td>{{$element->email}}</td>
<td> 

  @if ($element['alemail'])
    {{$element['alemail']}}
  @else
    No Alternative Email
  @endif
</td> 

          <td>{{$element->officeid}}</td>
          <td>{{$element->contact_name}}</td>
          <td>{{$element->contact}}</td>


<td>
  @if (!empty($element['division']['name']))
    {{ $element['division']['name'] }}
  @else
    -
  @endif
</td>

<td>
  @if (!empty($element['district']['name']))
    {{ $element['district']['name'] }}
  @else
    -
  @endif
</td>

<td>
  @if (!empty($element['upazila']['name']))
    {{ $element['upazila']['name'] }}
  @else
    -
  @endif
</td>

<td>@if (!empty($element['address']))
    {{ $element['address'] }}
  @else
    -
  @endif</td>





<td>
  <button class="btn btn-xs btn-danger" data-toggle="modal" data-target="#{{'userPasswordChangeModal'. $element->id}}">
    Change Password
  </button>
</td>

<td>



  @if ($element->level == 500)
    <button class="btn btn-xs btn-primary" data-toggle="modal" data-target="#">Active</button>
  @else
    @if ($element->active == true)
       <button class="btn btn-xs btn-primary" data-toggle="modal" data-target="#{{'userStatusModal'. $element->id}}">Active</button>
    @else
      <button class="btn btn-xs btn-danger" data-toggle="modal" data-target="#{{'userStatusModal'. $element->id}}">Inactive</button>
    @endif
  @endif


</td>


<td>
    @if ($element->dist_return == true)
       <button class="btn btn-xs btn-primary" data-toggle="modal" data-target="#{{'userReturnStatusModal'. $element->id}}">Enable</button>
    @else
      <button class="btn btn-xs btn-danger" data-toggle="modal" data-target="#{{'userReturnStatusModal'. $element->id}}">Disable</button>
    @endif

</td>





<td> 

  @if ($element->photo)
  <a target="_blank" href="{{ asset( 'storage/app/d/nokia/' . $element->photo) }}">
    View Image
  </a>
  @else
    No Image File
  @endif
</td>  

<td> 

  @if ($element['nidimage'])
  <a target="_blank" href="{{ asset( 'storage/app/d/nokia/' . $element['nidimage']) }}">
     View Image 
  </a>
  @else
    No Image File
  @endif
</td> 



        </tr>
@endforeach
                

              
                </tbody>
               
              </table>

<table>
  
  <tbody>
      <tr>
        <td colspan="9">
          {{ $users->links() }}
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
  <!-- /.content-wrapper -->
<!-- content part================================ -->





<!--custom update modal part================================ -->


@forelse ($users as $key => $element)
  <!-- Modal -->
  <div class="modal fade" id="{{'userPasswordChangeModal'. $element->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
          
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">{{$element->firstname}}</h5>
            <button class="close" type="button" data-dismiss="modal" aria-label="Close" style="margin-top: -25px">
              <span aria-hidden="true">×</span>
            </button>
          </div>

          <div class="modal-body">
<!-- body part -->

<form action="{{route('admin.user.updatePassword')}}" method="post"  autocomplete="on" enctype="multipart/form-data">
  <h3 class="text-info">Do You Want To Update Password ?</h3>
   <br>

  <input type="hidden" name="_token" value="{{ csrf_token() }}">
  <input name="_method" type="hidden" value="put">
  <input type="hidden" name="id" value="{{ $element->id }}">





  
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
  <div class="modal fade" id="{{'userStatusModal'. $element->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
          
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">{{$element->email}}</h5>
            <button class="close" type="button" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">×</span>
            </button>
          </div>

          <div class="modal-body">
<!-- body part -->


@if ($element->active == true)
  <form action="{{route('admin.user.inactive',$element->id)}}" method="post">
   <h3 class="text-info">Do You Want To Inactive This User ?</h3>
   <br>
    <input type="hidden" name="_token" value="{{ csrf_token() }}">
    <input type="hidden" name="id" value="{{ $element->id }}">
    <!-- <input name="_method" type="hidden" value="delete"> -->
    
    <div class="form-group">
      <button class="form-control btn btn-danger">Inactive</button>
    </div>

  </form>
@else
   <form action="{{route('admin.user.active',$element->id)}}" method="post">
   <h3 class="text-info">Do You Want To Active This User ?</h3>
   <br>
    <input type="hidden" name="_token" value="{{ csrf_token() }}">
    <input type="hidden" name="id" value="{{ $element->id }}">
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




<!--custom delete modal part================================ -->

<!--custom delete modal part================================ -->





<!--custom delete modal part================================ -->


<!--custom delete modal part================================ -->







<!-- // jquery area ========= -->
<!-- // jquery area ========= -->
<script type="text/javascript">

  $('#level').on('change', function(e){
    var level = e.target.value;
    console.log(level);
    if (level == 100) {
      $('#retailerArea').css({'display':'block'});
      $('#hddistrictArea').css({'display':'none'});
      $('#hdupazilaArea').css({'display':'none'});

      $('#user_id1').prop("disabled", false);
      $('#district1').prop("disabled", true);
      $('#upazila1').prop("disabled", true);
      

    }else if (level == 300) {
      $('#hddistrictArea').css({'display':'block'});
      $('#retailerArea').css({'display':'none'});
      $('#hdupazilaArea').css({'display':'none'});

      $('#user_id1').prop("disabled", true);
      $('#district1').prop("disabled", false);
      $('#upazila1').prop("disabled", true);

    }else if (level == 10) {
      $('#hdupazilaArea').css({'display':'block'});
      $('#hddistrictArea').css({'display':'none'});
      $('#retailerArea').css({'display':'none'});

      $('#user_id1').prop("disabled", true);
      $('#district1').prop("disabled", true);
      $('#upazila1').prop("disabled", false);

    }else {
      $('#retailerArea').css({'display':'none'});
      $('#hddistrictArea').css({'display':'none'});
      $('#hdupazilaArea').css({'display':'none'});

      $('#user_id1').prop("disabled", true);
      $('#district1').prop("disabled", true);
      $('#upazila1').prop("disabled", true);

    }
  });




  $('#division').on('change', function(e){
    var division_id = e.target.value;
    console.log(division_id);
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