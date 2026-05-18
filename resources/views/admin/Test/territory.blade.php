@extends('layouts.master_admin')

@section('title')
  {{"DMS :: Add Territory"}}
@endsection


@section('content')

<!-- content part================================ -->

    <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Territory
        <small>Control panel</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="{{ route('admin.dashboard') }}"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="#"></i> Settings</a></li>
        <li class="active"><a href="{{ route('admin.territory') }}">Territory</a></li>
      </ol>
    </section>

  
    <!-- Main content -->
    <section class="content-header">
      <div class="row">
        <div class="">
      <div class="box box-warning">
            <div class="box-header with-border">
              <h3 class="box-title">Add Territory</h3>
            </div>
            
            <!-- <form class="form-horizontal" style="padding:20px">
                <div class="box-body">
                <div class="form-group">
                  <label for="region" class="col-sm-2 control-label">Region</label>
                  <div class="col-sm-10">
                    <select name="zone" class="form-control select2" style="width: 100%;">
                      <option selected="selected">Select Region</option>
                      <option value="Dhaka">Dhaka</option>
                      <option value="Chittagong">Chittagong</option>
                      <option value="Shylet">Shylet</option>
                      <option value="Rajshahi">Rajshahi</option>
                      <option value="Khulna">Khulna</option>
                      <option value="Rangpur">Rangpur</option>
                    </select>
                  </div>
                </div>
                <div class="form-group">
                  <label   class="col-sm-2 control-label">Territory Name</label>
            
                  <div class="col-sm-10">
                    <input type="text" name="territory" class="form-control"  placeholder="Enter Region Name">
                  </div>
                </div>
                </div>
              <div class="box-footer">
                <button type="submit" class="btn btn-success pull-right">Submit</button>
              </div>
            </form> -->


    <!-- ================================== form area==================================== -->
{{-- for for displaying success and errror message --}}
  <form class="form-horizontal" method="POST" action="{{ route('admin.territory.store') }}" autocomplete="on" enctype="multipart/form-data">
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
        <div class="form-group {{ $errors->has('region') ? 'has-error' : '' }}">
          <label for="region">Region:</label>

            <select name="region_id" id="regions" class="form-control">
              <option value="">Select Region</option>
              @foreach($regions as $region )
                <option value="{{ $region['id'] }}">{{ $region['region'] }}</option>
              @endforeach
            </select>          

          <span class="text-danger">{{ $errors->first('region_id') }}</span>
        </div>
      </div>
      


      <div class="col-md-12">
        <div class="form-group {{ $errors->has('territory') ? 'has-error' : '' }}">
          <label for="territory">Territory:</label>
          <input type="text" id="territory" name="territory" class="form-control" placeholder="Enter Territory Name" value="{{ old('territory') }}">
          <span class="text-danger">{{ $errors->first('territory') }}</span>
        </div>
      </div>


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
              <h3 class="box-title">Territory List</h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
              <table id="example" class="display" cellspacing="0" width="100%">
                <thead>
                <tr>
                  <th>Region Name</th>
                  <th>Territory Name</th>
                  <th>Action</th>
                </tr>
                </thead>
                <tbody>
@foreach ($territories as $element)
  
                <tr>
                  <td>{{$element['region']['region']}}</td>
                  <td>{{$element['territory']}}</td>
                  <!-- <td><a href="" class=" btn btn-warning btn-md">Edit</a> <a href="" class=" btn btn-danger btn-md">Delete</a></td> -->
<td>
    <button class="btn btn-xs btn-primary" data-toggle="modal" data-target="#{{'territoryUpdateModal'. $element['id']}}"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></button>

  <button class="btn btn-xs btn-danger" data-toggle="modal" data-target="#{{'territoryDeleteModal'. $element['id']}}"><i class="fa fa-trash-o" aria-hidden="true"></i></button>


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


@forelse ($territories as $key => $element)
  <!-- Modal -->
  <div class="modal fade" id="{{'territoryUpdateModal'. $element['id']}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
          
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">{{$element['territory']}}</h5>
            <button class="close" type="button" data-dismiss="modal" aria-label="Close" style="margin-top: -25px">
              <span aria-hidden="true">×</span>
            </button>
          </div>

          <div class="modal-body">
<!-- body part -->

<form action="{{route('admin.territory.update')}}" method="post">
  <h3 class="text-info">Do You Want To Update This Data ?</h3>
   <br>

  <input type="hidden" name="_token" value="{{ csrf_token() }}">
  <input name="_method" type="hidden" value="put">
  <input type="hidden" name="id" value="{{ $element['id'] }}">


<div class="form-group {{ $errors->has('region') ? 'has-error' : '' }}">
  <label for="region">Region:</label>

    <select name="region_id" id="regions" class="form-control">
      <option value="">Select Region</option>
      @foreach($regions as $region )
        <option value="{{ $region['id'] }}" {{ $element['region_id'] == $region['id'] ? ' selected="selected"' : '' }}>{{ $region['region'] }}</option>
      @endforeach
    </select>          

  <span class="text-danger">{{ $errors->first('region') }}</span>
</div>


<div class="form-group {{ $errors->has('territory') ? 'has-error' : '' }}">
  <label for="territory">Territory:</label>
  <input type="text" id="territory" name="territory" class="form-control" placeholder="Enter Territory Name" value="{{ $element['territory'] }}">
  <span class="text-danger">{{ $errors->first('territory') }}</span>
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


@forelse ($territories as $key => $element)
  <!-- Modal -->
  <div class="modal fade" id="{{'territoryDeleteModal'. $element['id']}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
          
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">{{$element['territory']}}</h5>
            <button class="close" type="button" data-dismiss="modal" aria-label="Close" style="margin-top: -25px">
              <span aria-hidden="true">×</span>
            </button>
          </div>

          <div class="modal-body">
<!-- body part -->




  <form action="{{route('admin.territory.delete',$element['id'])}}" method="post">
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


@endsection