@extends('layouts.master_admin')

@section('title')
  {{"Sales Automation Process :: Check Retailer"}}
@endsection


@section('content')


<!-- content part================================ -->

    <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <!-- bc part================================ -->
      @include('admin.bc.bc')
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
              <h3 class="box-title text-danger">Check Retailer</h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
  
            <!-- form start -->
             <form class="form-horizontal1" method="POST" action="{{ route('admin.user.CheckRetailer.store') }} " autocomplete="off" enctype="multipart/form-data">

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
          <div class="form-group {{ $errors->has('officeid') ? 'has-error' : '' }}">
            <label for="officeid">Email Or Retailer ID:</label>
            <input type="text" id="officeid" name="officeid" class="form-control" placeholder="Enter Retailer ID or Email" value="{{@$retVal = (Session::get('officeid')) ? $ssdata['officeid'] : ""  }}">
            <span class="text-danger">{{ $errors->first('officeid') }}</span>
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
              <!-- <div class="box-footer">
                <button type="submit" class="btn btn-success pull-right">Submit</button>
              </div> -->
              <!-- /.box-footer -->
            
            </form>


            </div>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->


  </section>

<!-- ==============one section area ================= -->

<!-- ==============one section area ================= -->


  <section class="col-lg-12 connectedSortable">
          <!-- Recent Invoice -->
          <div class="box box-warning">
            <div class="box-header">

            </div>
            <!-- /.box-header -->
            <div class="box-body">


              <table id="example" class="display" cellspacing="0" width="100%">
                <thead>
                <tr>
                  <th>#</th>
                  <th>Action</th>
                  <th>Created At</th>
                  <th>Last Update</th>
                  <th>IsDontWorry</th>
                  <th>Retailer Name</th>
                  <th>Contact Name</th>
                  <th>Market Name</th>
                  <th>Email</th>
                  <th>Retailer ID</th>
                  <th>Retailer Type</th>
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
@foreach ($retailers as $key=>$element)
        <tr>
          
          <td>{{$key + 1}} </td>
<td>

  @if ($element->level == 500)
    <button class="btn btn-xs btn-primary" data-toggle="modal" data-target="#{{'ratailerUpdateModal'. $element->id}}"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></button>
  @else
    <button class="btn btn-xs btn-primary" data-toggle="modal" data-target="#{{'ratailerUpdateModal'. $element->id}}"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></button>

  <button class="btn btn-xs btn-danger" data-toggle="modal" data-target="#{{'ratailerDeleteModal'. $element->id}}"><i class="fa fa-trash-o" aria-hidden="true"></i></button>
  @endif
  

</td>
<td> {{$element['created_at']}} </td>
<td> {{$element['updated_at']}} </td>


<td>
  @if ($element->isdw == true)
       <button class="btn btn-xs btn-primary" data-toggle="modal" data-target="#{{'ratailerDWStatusModal'. $element->id}}">Active</button>
  @else
    <button class="btn btn-xs btn-danger" data-toggle="modal" data-target="#{{'ratailerDWStatusModal'. $element->id}}">Inactive</button>
  @endif
</td>



          <td>{{$element->firstname}} {{$element->lastname}}</td>
          <td>{{$element->contact_name}}</td>
          <td>{{$element->market_name}}</td>
          <td>{{$element->email}}</td>
<td>
  <button class="btn btn-xs btn-warning" data-toggle="modal" data-target="#{{'ratailerIdChangeModal'. $element->id}}">
    {{$element->officeid}}
  </button>
</td>
<td>{{$element->store_type}}</td>
<td>{{$element->contact}}</td>
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
    <button class="btn btn-info btn-xs">Factory</button>
  @elseif($element->level == 300)
    <button class="btn btn-info btn-xs">Accounts</button>
  @elseif($element->level == 200)
    <button class="btn btn-info btn-xs">Retailer</button>
  @else
    <button class="btn btn-warning btn-xs">ASM/TO</button>
  @endif

</td>

<td>
  <button class="btn btn-xs btn-danger" data-toggle="modal" data-target="#{{'ratailerPasswordChangeModal'. $element->id}}">
    Change Password
  </button>
</td>

<td>



  @if ($element->level == 500)
    <button class="btn btn-xs btn-primary" data-toggle="modal" data-target="#">Active</button>
  @else
    @if ($element->active == true)
       <button class="btn btn-xs btn-primary" data-toggle="modal" data-target="#{{'ratailerStatusModal'. $element->id}}">Active</button>
    @else
      <button class="btn btn-xs btn-danger" data-toggle="modal" data-target="#{{'ratailerStatusModal'. $element->id}}">Inactive</button>
    @endif
  @endif


</td>



<td> 

  @if ($element->photo)
  <a target="_blank" href="{{ asset( 'storage/app/d/nokia/' . $element->photo) }}">
    <img width="30px" height="20px" src="{{ asset( 'storage/app/d/nokia/' . $element->photo) }}"> 
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
            <!-- /.box-body -->
          </div>
          <!-- /.box -->

  </section>
</div>
<!-- ==============one section area ================= -->






<!--custom update modal part================================ -->


@forelse ($retailers as $key => $element)
  <!-- Modal -->
  <div class="modal fade" id="{{'ratailerUpdateModal'. $element->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
          
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">{{$element->firstname}}</h5>
            <button class="close" type="button" data-dismiss="modal" aria-label="Close" style="margin-top: -25px">
              <span aria-hidden="true">×</span>
            </button>
          </div>

          <div class="modal-body">
<!-- body part -->

<form action="{{route('admin.retailer.update')}}" method="post"  autocomplete="on" enctype="multipart/form-data">
  <h3 class="text-info">Do You Want To Update This Data ?</h3>
   <br>

  <input type="hidden" name="_token" value="{{ csrf_token() }}">
  <input name="_method" type="hidden" value="put">
  <input type="hidden" name="id" value="{{ $element->id }}">




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
    <label for="firstname">Retail Name:</label>
    <input type="text" id="firstname" name="firstname" class="form-control" placeholder="Enter Fullname" value="{{$element->firstname}}">
    <span class="text-danger">{{ $errors->first('firstname') }}</span>
  </div>
</div>

<div class="col-md-12">
  <div class="form-group {{ $errors->has('contact_name') ? 'has-error' : '' }}">
    <label for="contact_name">Contact Name:</label>
    <input type="text" id="contact_name" name="contact_name" class="form-control" placeholder="Enter Contact Name" value="{{$element->contact_name}}">
    <span class="text-danger">{{ $errors->first('contact_name') }}</span>
  </div>
</div>

<div class="col-md-12">
  <div class="form-group {{ $errors->has('market_name') ? 'has-error' : '' }}">
    <label for="market_name"> Market Name:</label>
    <input type="text" id="market_name" name="market_name" class="form-control" placeholder="Enter Market Name" value="{{$element->market_name}}">
    <span class="text-danger">{{ $errors->first('market_name') }}</span>
  </div>
</div>

<div class="col-md-12">
  <div id="store_type" class="form-group {{ $errors->has('store_type') ? 'has-error' : '' }}">
    <label for="store_type">Store Type</label>
    <select name="store_type" id="store_type" class="form-control" style="width: 100%;" required="required">
      <option value="">Select Type</option>
      <option value="BP Retail" @if($element->store_type === "BP Retail") selected @endif>BP Retail</option>
      <option value="RSA Retail" @if($element->store_type === "RSA Retail") selected @endif>RSA Retail</option>
      <option value="SIS Retail" @if($element->store_type === "SIS Retail") selected @endif>SIS Retail</option>
      <option value="GRT Retail" @if($element->store_type === "GRT Retail") selected @endif>GRT Retail</option>
      <option value="BS Retail" @if($element->store_type === "BS Retail") selected @endif>BS Retail</option>
      <option value="SP Retail" @if($element->store_type === "SP Retail") selected @endif>SP Retail</option>
    </select>
    <span class="text-danger">{{ $errors->first('store_type') }}</span>
  </div>
</div>

<!-- <div class="col-md-12">
  <div class="form-group {{ $errors->has('lastname') ? 'has-error' : '' }}">
    <label for="lastname">Lastname:</label>
    <input type="text" id="lastname" name="lastname" class="form-control" placeholder="Enter Lastname" value="{{$element->lastname}}">
    <span class="text-danger">{{ $errors->first('lastname') }}</span>
  </div>
</div> -->

<div class="col-md-12">
  <div class="form-group {{ $errors->has('officeid') ? 'has-error' : '' }}">
    <label for="officeid">Retailer ID:</label>
    <input type="text" id="officeid" name="officeid" class="form-control" placeholder="Enter Retailer ID" value="{{$element->officeid}}" disabled="disabled">
    <span class="text-danger">{{ $errors->first('officeid') }}</span>
  </div>
</div>

<div class="col-md-12">
  <div class="form-group {{ $errors->has('contact') ? 'has-error' : '' }}">
    <label for="contact">Contact:</label>
    <input type="text" id="contact" name="contact" class="form-control" placeholder="Enter Contact" value="{{$element->contact}}">
    <span class="text-danger">{{ $errors->first('contact') }}</span>
  </div>
</div>

<div class="col-md-12">
  <div class="form-group {{ $errors->has('address') ? 'has-error' : '' }}">
    <label for="address">Address:</label>
    <input type="text" id="address" name="address" class="form-control" placeholder="Enter Address" value="{{$element->address}}">
    <span class="text-danger">{{ $errors->first('address') }}</span>
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


@forelse ($retailers as $key => $element)
  <!-- Modal -->
  <div class="modal fade" id="{{'ratailerDeleteModal'. $element->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
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




  <form action="{{route('admin.user.delete',$element->id)}}" method="post">
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





<!--custom update modal part================================ -->


@forelse ($retailers as $key => $element)
  <!-- Modal -->
  <div class="modal fade" id="{{'ratailerPasswordChangeModal'. $element->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
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

<form action="{{ route('admin.user.updatePassword') }}" method="post"  autocomplete="on" enctype="multipart/form-data">
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


@forelse ($retailers as $key => $element)
  <!-- Modal -->
  <div class="modal fade" id="{{'ratailerStatusModal'. $element->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
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
  <form action="{{ route('admin.user.inactive') }}" method="post">
   <h3 class="text-info">Do You Want To Inactive This Retailer ?</h3>
   <br>
    <input type="hidden" name="_token" value="{{ csrf_token() }}">
    <input type="hidden" name="id" value="{{ $element->id }}">
    <!-- <input name="_method" type="hidden" value="delete"> -->
    
    <div class="form-group">
      <button class="form-control btn btn-danger">Inactive</button>
    </div>

  </form>
@else
   <form action="{{ route('admin.user.active') }}" method="post">
   <h3 class="text-info">Do You Want To Active This Retailer ?</h3>
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




<!--custom hospitaldetailStatusModal modal part================================ -->


@forelse ($retailers as $key => $element)
  <!-- Modal -->
  <div class="modal fade" id="{{'ratailerDWStatusModal'. $element->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
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


@if ($element->isdw == true)
  <form action="{{ route('admin.user.dontworry.inactive') }}" method="post">
   <h3 class="text-info">Do You Want To Inactive This Retailer ?</h3>
   <br>
    <input type="hidden" name="_token" value="{{ csrf_token() }}">
    <input type="hidden" name="id" value="{{ $element->id }}">
    <!-- <input name="_method" type="hidden" value="delete"> -->
    
    <div class="form-group">
      <button class="form-control btn btn-danger">Inactive</button>
    </div>

  </form>
@else
   <form action="{{ route('admin.user.dontworry.active') }}" method="post">
   <h3 class="text-info">Do You Want To Active This Retailer ?</h3>
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







<!-- /.row (main row) -->











<!--custom update modal part================================ -->


@forelse ($retailers as $key => $element)
  <!-- Modal -->
  <div class="modal fade" id="{{'ratailerIdChangeModal'. $element->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
          
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">{{$element->firstname}} -  {{$element->officeid}} </h5>
            <button class="close" type="button" data-dismiss="modal" aria-label="Close" style="margin-top: -25px">
              <span aria-hidden="true">×</span>
            </button>
          </div>

          <div class="modal-body">
<!-- body part -->

<form action="{{ route('admin.user.updateOfficeid') }}" method="post"  autocomplete="on" enctype="multipart/form-data">
  <h3 class="text-info">Do You Want To Update Retailer ID ?</h3>
   <br>

  <input type="hidden" name="_token" value="{{ csrf_token() }}">
  <input name="_method" type="hidden" value="put">
  <input type="hidden" name="id" value="{{ $element->id }}">





  
  <div class="form-group {{ $errors->has('officeid') ? 'has-error' : '' }}">
    <label class="col-sm-2 control-label">Retailer ID</label>

    <div class="col-sm-10">
      <input type="text" id="officeid" name="officeid" class="form-control" placeholder="Enter Retailer ID" value="{{ old('officeid') }}" required="required">
      <span class="text-danger">{{ $errors->first('officeid') }}</span>
    </div>

  </div>

<br><br>


  <div class="form-group {{ $errors->has('confirm_officeid') ? 'has-error' : '' }}">
    <label class="col-sm-2 control-label">Confirm Retailer ID</label>

    <div class="col-sm-10">
      <input type="text" id="confirm_officeid" name="confirm_officeid" class="form-control" placeholder="Enter Confirm Retailer ID" value="{{ old('confirm_officeid') }}" required="required">
      <span class="text-danger">{{ $errors->first('confirm_officeid') }}</span>
    </div>

  </div>


<div class="col-md-12"> 
  <div class="form-group">
    <button class="form-control btn btn-warning">Update Retailer ID</button>
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
  //Session::forget(['officeid']);
@endphp
<!-- content part================================ -->


<!-- // jquery area ========= -->
<script type="text/javascript">



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