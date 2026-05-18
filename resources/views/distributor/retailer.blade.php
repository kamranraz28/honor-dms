@extends('layouts.master_distributor')

@section('title')
  {{"DMS :: Add Retailer"}}
@endsection


@section('content')

<!-- content part================================ -->

    <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <!-- bc part================================ -->
       @include('distributor.bc.bc')
    <!-- bc part================================ -->
    </section>

    


    <!-- Main content -->
    <section class="content-header">
      <div class="row">
        <div class="">
      <div class="box box-warning">
            <div class="box-header with-border">
              <h3 class="box-title">Add Retail</h3>
            </div>
            <!-- /.box-header -->
            <!-- form start -->
{{-- for for displaying success and errror message --}}
  <form class="form-horizontal" method="POST" action="{{ route('distributor.retailer.store') }}" autocomplete="off" enctype="multipart/form-data">
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


<!-- //cas cading dropdown list =============== -->







                <div class="form-group {{ $errors->has('firstname') ? 'has-error' : '' }}">
                  <label class="col-sm-2 control-label">Retail Name</label>

                  <div class="col-sm-10">
                    <input type="text" id="firstname" name="firstname" class="form-control" placeholder="Enter Fullname" value="{{ old('firstname') }}" required="required">
                    <span class="text-danger">{{ $errors->first('firstname') }}</span>
                  </div>
                </div>

                <div class="form-group {{ $errors->has('contact_name') ? 'has-error' : '' }}">
                  <label class="col-sm-2 control-label">Contact Name</label>

                  <div class="col-sm-10">
                    <input type="text" id="contact_name" name="contact_name" class="form-control" placeholder="Enter Fullname" value="{{ old('contact_name') }}" required="required">
                    <span class="text-danger">{{ $errors->first('contact_name') }}</span>
                  </div>
                </div>

                <div class="form-group {{ $errors->has('market_name') ? 'has-error' : '' }}">
                  <label class="col-sm-2 control-label">Market Name</label>

                  <div class="col-sm-10">
                    <input type="text" id="market_name" name="market_name" class="form-control" placeholder="Enter Fullname" value="{{ old('market_name') }}" required="required">
                    <span class="text-danger">{{ $errors->first('market_name') }}</span>
                  </div>
                </div>

                  <div id="store_type" class="form-group {{ $errors->has('store_type') ? 'has-error' : '' }}">
                  <label for="store_type" class="col-sm-2 control-label">Store Type</label>
                  <div class="col-sm-10">
      
      <select name="store_type" id="store_type" class="form-control" required="required">
        <option value="">Select Type</option>
        <option value="BP Retail">BP Retail</option>
        <option value="RSA Retail">RSA Retail</option>
        <option value="SIS Retail">SIS Retail</option>
         <option value="GRT Retail">GRT Retail</option>
          <option value="BS Retail">BS Retail</option>
          <option value="SP Retail">SP Retail</option>
      </select>  
                    <span class="text-danger">{{ $errors->first('district_id') }}</span>
                  </div>
                </div>


                <!-- <div class="form-group {{ $errors->has('lastname') ? 'has-error' : '' }}">
                  <label class="col-sm-2 control-label">Last Name</label>
                
                  <div class="col-sm-10">
                    <input type="text" id="lastname" name="lastname" class="form-control" placeholder="Enter Last Name" value="{{ old('lastname') }}">
                    <span class="text-danger">{{ $errors->first('lastname') }}</span>
                  </div>
                </div> -->

             {{--    <div class="form-group {{ $errors->has('email') ? 'has-error' : '' }}">
                  <label class="col-sm-2 control-label">Email</label>

                  <div class="col-sm-10">
                    <input type="text" id="email" name="email" class="form-control" placeholder="Enter Email" value="{{ old('email') }}">
                    <span class="text-danger">{{ $errors->first('email') }}</span>
                  </div>

                </div> --}}

             {{--    <div class="form-group {{ $errors->has('officeid') ? 'has-error' : '' }}">
                  <label class="col-sm-2 control-label">Retail ID</label>

                  <div class="col-sm-10">
                    <input type="text" id="officeid" name="officeid" class="form-control" placeholder="Enter Retailer ID" value="{{ old('officeid') }}" required="required">
                    <span class="text-danger">{{ $errors->first('officeid') }}</span>
                  </div>

                </div>
 --}}
                <!-- <div class="form-group {{ $errors->has('password') ? 'has-error' : '' }}">
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
                
                </div> -->

                
                <div class="form-group {{ $errors->has('contact') ? 'has-error' : '' }}">
                  <label class="col-sm-2 control-label">Contact No</label>

                  <div class="col-sm-10">
                    <input type="text" id="contact" name="contact" class="form-control" placeholder="Enter Contact No" value="{{ old('contact') }}" required="required">
                    <span class="text-danger">{{ $errors->first('contact') }}</span>
                  </div>

                </div>

                   <div class="form-group"> 
                  <label class="col-sm-2 control-label">Address</label>

                  <div class="col-sm-10">
                    <input type="text" id="address" name="address" class="form-control" placeholder="Enter Address" value="{{ old('address') }}" required="required">
                    <span class="text-danger">{{ $errors->first('address') }}</span>
                  </div>
                </div>



                <div class="form-group {{ $errors->has('image') ? 'has-error' : '' }}">
                  <label for="inputPassword3" class="col-sm-2 control-label">Upload Picture</label>
                  <div class="col-sm-10">
                    <input class="form-control" name="image" type="file">
                    <span class="text-danger">{{ $errors->first('image') }}</span>
                  </div>
                </div>



                <!-- <div class="form-group {{ $errors->has('level') ? 'has-error' : '' }}">
                  <label for="Level" class="col-sm-2 control-label">Level</label>
                  <div class="col-sm-10">
                    <select name="level" id="level" class="form-control" style="width: 100%;">
                      <option selected="selected">Select Level</option>
                      <option value="100">ASM/TO</option>
                      <option value="200">Retailer Maneger</option>
                      <option value="300">Accounts Maneger</option>
                      <option value="400">Factory Manager</option>
                    </select>
                    <span class="text-danger">{{ $errors->first('level') }}</span>
                  </div>
                </div> -->




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
              <h3 class="box-title">Retail List ( Added From LD Panel)</h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
              <table id="example" class="display" cellspacing="0" width="100%">
                <thead>
                <tr>
                  <th>#</th>
                  <th>Retail Name</th>
                  <th>Contact Name</th>
                  <th>Market Name</th>
                  <th>Email</th>
                  <th>Retail ID</th>
                  <th>Retail Type</th>
                  <th>Contact No.</th>
                  <th>Address</th>

<th>Division</th>
<th>District</th>
<th>Upazila</th>

                  
                  <th>Level</th>
                  <!-- <th>Password Status</th> -->
                  <th>Status</th>
                  
                  <th>Photo</th>
                  <!-- <th>Action</th> -->
                </tr>
                </thead>
                <tbody>
@foreach ($retailers as $key=>$element)
        <tr>
          
          <td>{{$key + 1}} </td>


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
           <td>{{$element->address}}</td>




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
    <button class="btn btn-info btn-xs">Retail</button>
  @elseif($element->level == 100)
    <button class="btn btn-info btn-xs">Distributor</button>
  @else
    <button class="btn btn-warning btn-xs">ASM/TO</button>
  @endif

</td>

<!-- <td>
  <button class="btn btn-xs btn-danger" data-toggle="modal" data-target="#{{'ratailerPasswordChangeModal'. $element->id}}">
    Change Password
  </button>
</td> -->

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
<!-- <td>

  @if ($element->level == 500)
    <button class="btn btn-xs btn-primary" data-toggle="modal" data-target="#{{'ratailerUpdateModal'. $element->id}}"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></button>
  @else
    <button class="btn btn-xs btn-primary" data-toggle="modal" data-target="#{{'ratailerUpdateModal'. $element->id}}"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></button>

  <button class="btn btn-xs btn-danger" data-toggle="modal" data-target="#{{'ratailerDeleteModal'. $element->id}}"><i class="fa fa-trash-o" aria-hidden="true"></i></button>
  @endif
  

</td> -->

        </tr>
@endforeach
                

              
                </tbody>
               
              </table>


<table>
  
  <tbody>
      <tr>
        <td colspan="9">
          {{ $retailers->links() }}
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

<form action="{{route('tso.retailer.update')}}" method="post"  autocomplete="on" enctype="multipart/form-data">
  <h3 class="text-info">Do You Want To Update This Data ?</h3>
   <br>

  <input type="hidden" name="_token" value="{{ csrf_token() }}">
  <input name="_method" type="hidden" value="put">
  <input type="hidden" name="id" value="{{ $element->id }}">


<div class="col-md-12">
  <div class="form-group {{ $errors->has('firstname') ? 'has-error' : '' }}">
    <label for="firstname">Fullname:</label>
    <input type="text" id="firstname" name="firstname" class="form-control" placeholder="Enter Fullname" value="{{$element->firstname}}">
    <span class="text-danger">{{ $errors->first('firstname') }}</span>
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




  <form action="{{route('tso.retailer.delete',$element->id)}}" method="post">
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
<!-- // jquery area ========= -->
<script type="text/javascript">

  /*$('#level').on('change', function(e){
    var level = e.target.value;
    //console.log(level);
    if (level == 300 || level == 100) {
      $('#retailerArea').css({'display':'block'});
    } else {
      //$('#user_id').empty();
      $('#retailerArea').css({'display':'none'});
      //$('#user_id').val('');
    }
  });*/


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